<?php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');
session_start();

$host = 'db_master';
$db   = 'perfect_world_db';
$user = 'alexandra';
$pass = '1005';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

// 0. Logica pentru ACTIVARE CONT (Când dai click pe linkul din email)
if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);
    // Căutăm userul care are acest token și nu e activ încă
    $result = $conn->query("SELECT id, prenume FROM users WHERE token='$token' AND is_active=0");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        // Îl facem activ și îi ștergem token-ul (ca să nu mai poată fi folosit)
        $conn->query("UPDATE users SET is_active=1, token=NULL WHERE id=$id");
        echo "<script>alert('Contul tău a fost activat cu succes, " . $row['prenume'] . "! Acum te poți loga.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Link-ul de activare este invalid sau contul a fost deja activat.'); window.location.href='index.php';</script>";
    }
}

// 1. Logica pentru INREGISTRARE (Register) cu Email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_submit'])) {
    $nume = $conn->real_escape_string($_POST['nume']);
    $prenume = $conn->real_escape_string($_POST['prenume']);
    $email = $conn->real_escape_string($_POST['email_reg']);
    $parola = password_hash($_POST['parola_reg'], PASSWORD_DEFAULT);

    // Generăm un cod secret unic (token) format din 32 de caractere
    $token = bin2hex(random_bytes(16));

    $sql = "INSERT INTO users (nume, prenume, email, password, token, is_active) VALUES ('$nume', '$prenume', '$email', '$parola', '$token', 0)";
    if ($conn->query($sql) === TRUE) {
        // --- TRIMITEM EMAILUL ---
        $to = $email;
        $subject = "Confirmare cont Perfect Planet";
        $activation_link = "https://perfectworld.local:8443/index.php?token=" . $token;
        $message = "Salut $prenume,\n\nTe rugam sa dai click pe urmatorul link pentru a-ti activa contul pe site-ul nostru:\n$activation_link\n\nEchipa Perfect Planet";
        $headers = "From: admin@perfectworld.local";

        // Funcția magică care strigă poștașul
        mail($to, $subject, $message, $headers);

        echo "<script>alert('Cont creat! Te rugăm să îți verifici emailul (în MailHog) pentru a activa contul.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('EROARE BAZA DE DATE:\\n" . addslashes($conn->error) . "'); window.location.href='index.php';</script>";
    }
}

// 2. Logica pentru AUTENTIFICARE (Login)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $email = $conn->real_escape_string($_POST['email_log']);
    $parola_introdusa = $_POST['parola_log'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // VERIFICARE NOUĂ: Contul este activat?
        if ($row['is_active'] == 0) {
            echo "<script>alert('Contul tău nu este activat! Te rugăm să dai click pe link-ul din email.');</script>";
        } else {
            // Dacă e activ, verificăm parola
            if (password_verify($parola_introdusa, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_nume'] = $row['nume'];
                $_SESSION['user_prenume'] = $row['prenume'];
                echo "<script>alert('Te-ai logat cu succes, " . $row['prenume'] . "!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Parola este incorectă!'); window.location.href='index.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Nu am găsit niciun cont cu acest email!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--adapteaza formatul site ului la orice device-->
   <meta charset="utf-8">
   <title>perfect planet</title>
   <link rel = "icon"  href="images/logo.png" type="image/x-icon">
   <link rel="stylesheet" href="style.css"> <!--face leg cu fisierul css-->
   <!-- Fondul luat de pe google fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--butonul pt meniu telefon -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
     <section class="header">
	      <nav>
		     <a href="index.html"><img src="images/logop.png"></a>
			 <div class="nav-links" id="navLinks">
			 <!-- buton pentru inchis meniul pe telefon -javascirt-->
			 <i class="fa fa-times" onclick="hideMenu()"></i>
			    <ul>
				    <li><a href="">Destinații</a>
					     <div class="sub-menu">
						     <ul>
							      <li><a href="umbria.html">Umbria, Italia</a></li>
					              <li><a href="fukuoka.html">Fukuoka,Japonia</a></li>
								  <li><a href="australia.html">Australia de<br> Vest</a></li>
					              <li><a href="zambia.html">Zambia</a></li>
					              <li><a href="jamaica.html">Jamaica</a></li>
					              <li><a href="dominicana.html">Dominica</a></li>
					              <li><a href="albania.html">Albania</a></li>
					              <li><a href="alaska.php">Alaska, SUA</a></li>
					              <li><a href="marsilia.html">Marsilia,<br> Franța</a></li>
					              <li><a href="mexico.html">New Mexico, SUA</a></li>
							 </ul>
						 </div>
					</li>
					<li><a href="about.html">Despre noi</a></li>
					<li><a href="contact.html">Contact</a></li>
					<li>
                        <?php if(isset($_SESSION['user_prenume'])): ?>
                            <button class='loginbtn' style="width:auto; cursor:default;">Salut, <?= htmlspecialchars($_SESSION['user_prenume']) ?>!</button>
                            <a href="logout.php" style="text-decoration: none;">
                                <button class='loginbtn' style="width:auto; background:#dc3545; margin-left: 10px;">Ieșire</button>
                            </a>
                        <?php else: ?>
                            <button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;">Contul meu</button>
                        <?php endif; ?>
                    </li>
				</ul>
			 </div>
			 <!-- buton pentru deschis meniul pe telefon -javascirt-->
			 <i class="fa fa-bars" onclick="showMenu()"></i>
		  </nav>
		  
		  
		  <div id='login-form'class='login-page'>
                      <div class="form-box">
                          <div class='button-box'>
                              <div id='btn'></div>
                              <button type='button'onclick='login()'class='toggle-btn'>Log in</button>
                              <button type='button'onclick='register()'class='toggle-btn'>Cont nou</button>
                          </div>

                          <form id='login' class='input-group-login' method='POST' action='index.php'>
                              <input type='email' name='email_log' class='input-field' placeholder='Email' required >
          		            <input type='password' name='parola_log' class='input-field' placeholder='Introdu parola' required>
          		            <input type='checkbox' class='check-box'><span>Memorează parola</span>
          		            <button type='submit' name='login_submit' class='submit-btn'>Intră în cont</button>
          		        </form>

          		        <form id='register' class='input-group-register' method='POST' action='index.php'>
          		            <input type='text' name='prenume' class='input-field' placeholder='Prenume' required>
          		            <input type='text' name='nume' class='input-field' placeholder='Nume' required>
          		            <input type='email' name='email_reg' class='input-field' placeholder='Email' required>
          		            <input type='password' name='parola_reg' class='input-field' placeholder='Introdu parola ' required>
          		            <input type='password' class='input-field' placeholder='Confirmă parola' required>
          		            <input type='checkbox' class='check-box' required><span>Sunt de acord cu termenii și condițiile</span>
                              <button type='submit' name='register_submit' class='submit-btn'>Creează cont nou</button>
          	            </form>

                      </div>
                  </div>
	
	
	 <div class="text-box">
	      <h1>CĂLĂTORII ÎN JURUL LUMII</h1>
		  <p>Cele mai frumoase locuri de vizitat din toată lumea</p>
	 </div>
	 </section>
	 
	 <section class="text">
	 <h2>10 LOCURI SPECIALE</h2>
	 <p>Itinerariul fiecărei destinții este creat pentru a te ajuta să visezi, să plănuiești, să te aventurezi și să descoperi secretele acestei planete perfecte. Descoperă 10 destinații special alese pentru a-ți face visurile mai colorate.</p>
	 </section>
	 
	 
	 <section class="content">
	     <h1>CE TE INSPIRĂ?</h1>
		 <p>RECOMANDĂRI PENTRU A URMĂRI CEEA CE IUBEȘTI</p>
		 
		 <div class="row">
		        <div class="content-col">
				<div class="oval-image1"></div>
				<h3>Gastronomie</h3>
				<ul>
				    <li><a href="umbria.html">Umbria, Italia</a></li>
					<li><a href="fukuoka.html">Fukuoka,Japonia</a></li>
				</ul>
				</div>
				<div class="content-col">
				<div class="oval-image2"></div>
				<h3>Aventură</h3>
				<ul>
				    <li><a href="australia.html">Australia de Vest</a></li>
					<li><a href="zambia.html">Zambia</a></li>
				</ul>
				</div>
				<div class="content-col">
				<div class="oval-image3"></div>
				<h3>Natură</h3>
				<ul>
				    <li><a href="jamaica.html">Jamaica</a></li>
					<li><a href="dominicana.html">Dominica</a></li>
				</ul>
				</div>
				<div class="content-col">
				<div class="oval-image4"></div>
				<h3>Oameni</h3>
				<ul>
				    <li><a href="albania.html">Albania</a></li>
					<li><a href="alaska.php">Alaska, SUA</a></li>
				</ul>
				</div>
				<div class="content-col">
				<div class="oval-image5"></div>
				<h3>Cultură</h3>
				<ul>
				    <li><a href="marsilia.html">Marsilia, Franța</a></li>
					<li><a href="mexico.html">New Mexico, USA</a></li>
				</ul>
				</div>
		 </div>
	 </section>
	
	 
	 <section class="footer">
	    	   <div class="rowf">
			         <div class="logo">
					      <a href="index.html"><img src="images/logop.png"></a>
					      <p>Pentru exploratorii de pretutindeni</p>	
                          <div class="buton">
		                  <button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'"style="width:auto;">Contul meu</button>
					      </div>
					 </div>
					 
					 <div class="detalii">
					 <div class="lista">
					  <ul>
					     <li><a href="about.html">Despre noi</a></li>
					     <li><a href="contact.html">Contact</a></li>
						 <li><a href="about.html">Termeni și condiții</a></li>
					     <li><button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'"style="width:auto;">Contul meu</button></li>
				       </ul>
					   </div>
					 </div>
			   
			   </div>
			   <div class="icons">
			     <a href="https://www.facebook.com/ionela.trifan.733"><i class="fa fa-facebook"></i></a>
				 <a href="https://www.instagram.com/trifanalexandra10/"><i class="fa fa-instagram"></i></a>
				 <a href="https://ro.pinterest.com/alexandraisabela04/"><i class="fa fa-pinterest"></i></a>
				 </div>
				 <p>Made with<i class="fa fa-heart-o"></i></p>
	 
	 </section>
	 
	 <!--JavaScript pt meniu telefon-->
	 <script>
	      var navLinks = document.getElementById("navLinks");
		  function showMenu(){
		      navLinks.style.right = "0";
		  }
		  function hideMenu(){
		      navLinks.style.right = "-200px";
		  }
	 </script>
	 
	 
	 
	   <script>
        var x=document.getElementById('login');
		var y=document.getElementById('register');
		var z=document.getElementById('btn');
		function register()
		{
			x.style.left='-400px';
			y.style.left='50px';
			z.style.left='110px';
		}
		function login()
		{
			x.style.left='50px';
			y.style.left='450px';
			z.style.left='0px';
		}
	</script>
	<script>
        var modal = document.getElementById('login-form');
        window.onclick = function(event) 
        {
            if (event.target == modal) 
            {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>