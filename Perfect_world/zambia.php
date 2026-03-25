<?php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<title>perfect planet</title>
	<link rel="icon" href="images/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<section class="zambia-header">
	<nav>
		<a href="index.php"><img src="images/logop.png"></a>
		<div class="nav-links" id="navLinks">
			<i class="fa fa-times" onclick="hideMenu()"></i>
			<ul>
				<li><a href="">Destinații</a>
					<div class="sub-menu">
						<ul>
							<li><a href="umbria.php">Umbria, Italia</a></li>
							<li><a href="fukuoka.php">Fukuoka,Japonia</a></li>
							<li><a href="australia.php">Australia de<br> Vest</a></li>
							<li><a href="zambia.php">Zambia</a></li>
							<li><a href="jamaica.php">Jamaica</a></li>
							<li><a href="dominicana.php">Dominica</a></li>
							<li><a href="albania.php">Albania</a></li>
							<li><a href="alaska.php">Alaska, SUA</a></li>
							<li><a href="marsilia.php">Marsilia,<br> Franța</a></li>
							<li><a href="mexico.php">New Mexico, SUA</a></li>
						</ul>
					</div>
				</li>
				<li><a href="about.php">Despre noi</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li>
					<?php if(isset($_SESSION['user_prenume'])): ?>
					<a href="profil.php" style="text-decoration: none;">
						<button class='loginbtn' style="width:auto; cursor:pointer;">Salut, <?= htmlspecialchars($_SESSION['user_prenume']) ?>!</button>
					</a>
					<?php else: ?>
					<button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;">Contul meu</button>
					<?php endif; ?>
				</li>
			</ul>
		</div>
		<i class="fa fa-bars" onclick="showMenu()"></i>
	</nav>

	<div id='login-form' class='login-page'>
		<div class="form-box">
			<div class='button-box'>
				<div id='btn'></div>
				<button type='button' onclick='login()' class='toggle-btn' id='btn-log-text' style='color: #fff;'>Log in</button>
				<button type='button' onclick='register()' class='toggle-btn' id='btn-reg-text' style='color: #000;'>Cont nou</button>
			</div>
			<form id='login' class='input-group-login' method='POST' action='index.php'>
				<input type='email' name='email_log' class='input-field' placeholder='Email' required>
				<input type='password' name='parola_log' class='input-field' placeholder='Introdu parola' required>
				<button type='submit' name='login_submit' class='submit-btn'>Intră în cont</button>
			</form>
			<form id='register' class='input-group-register' method='POST' action='index.php'>
				<input type='text' name='prenume' class='input-field' placeholder='Prenume' required>
				<input type='text' name='nume' class='input-field' placeholder='Nume' required>
				<input type='email' name='email_reg' class='input-field' placeholder='Email' required>
				<input type='password' name='parola_reg' class='input-field' placeholder='Introdu parola' required>
				<input type='password' class='input-field' placeholder='Confirmă parola' required>
				<button type='submit' name='register_submit' class='submit-btn'>Creează cont nou</button>
			</form>
		</div>
	</div>

	<div class="text-box">
		<h1>ZAMBIA</h1>
	</div>
</section>

<section class="zambia">
	<div class="rowt">
		<div class="square-img"></div>
		<div class="informatii">
			<h3>Zambia</h3>
			<p>Zambia este considerată „inima sălbatică” a Africii, un paradis neșlefuit pentru cei pasionați de aventură și natură neatinsă. Acasă la faimoasele Cascade Victoria (Mosi-oa-Tunya, „fumul care tună”), țara oferă unele dintre cele mai spectaculoase safariuri din lume, unde poți explora vastul Parc Național South Luangwa în căutarea leoparzilor și elefanților. Zambia este, de asemenea, renumită pentru safariurile sale pe jos, oferind o perspectivă intimă și palpitantă asupra ecosistemului african, totul însoțit de căldura inegalabilă a localnicilor săi prietenoși.</p>

			<div class="plan">
				<h4>Experiențe de neuitat</h4>
				<p>Aventurează-te pe mărețul fluviu Zambezi, simte forța cascadelor Victoria și experimentează un safari autentic într-unul dintre cele mai vibrante habitate naturale ale Africii.</p>
			</div>

			<div class="harta">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947475.9863266945!2d25.074360340798993!3d-14.346894539515082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x194285e656d0bb81%3A0xc6226ea5d487f3b!2sZambia!5e0!3m2!1sen!2sro!4v1700000000000!5m2!1sen!2sro" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
	</div>
</section>

<section class="footer">
	<div class="rowf">
		<div class="logo">
			<a href="index.php"><img src="images/logop.png"></a>
			<p>Pentru exploratorii de pretutindeni</p>
			<div class="buton">
				<?php if(isset($_SESSION['user_prenume'])): ?>
				<a href="profil.php" style="text-decoration: none;">
					<button class='loginbtn' style="width:auto; cursor:pointer;">Salut, <?= htmlspecialchars($_SESSION['user_prenume']) ?>!</button>
				</a>
				<?php else: ?>
				<button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;">Contul meu</button>
				<?php endif; ?>
			</div>
		</div>

		<div class="detalii">
			<div class="lista">
				<ul>
					<li><a href="about.php">Despre noi</a></li>
					<li><a href="contact.php">Contact</a></li>
					<li><a href="about.php">Termeni și condiții</a></li>
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
	<p style="margin-top: 10px;">
		IP: <?php echo $_SERVER['SERVER_ADDR']; ?>
	</p>
</section>

<script>
	var navLinks = document.getElementById("navLinks");
	function showMenu(){ navLinks.style.right = "0"; }
	function hideMenu(){ navLinks.style.right = "-200px"; }
</script>
<script>
	var x=document.getElementById('login');
	var y=document.getElementById('register');
	var z=document.getElementById('btn');
	var logText = document.getElementById('btn-log-text');
	var regText = document.getElementById('btn-reg-text');
	function register() {
		x.style.left='-400px'; y.style.left='50px'; z.style.left='110px';
		logText.style.color = '#000'; regText.style.color = '#fff';
	}
	function login() {
		x.style.left='50px'; y.style.left='450px'; z.style.left='0px';
		logText.style.color = '#fff'; regText.style.color = '#000';
	}
</script>
<script>
	var modal = document.getElementById('login-form');
	window.onclick = function(event) {
		if (event.target == modal) { modal.style.display = "none"; }
	}
</script>
</body>
</html>