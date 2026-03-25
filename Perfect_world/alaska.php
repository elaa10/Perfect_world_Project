<?php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');
session_start();
?>

<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta charset="utf-8">
   <title>perfect planet - Alaska</title>
   <link rel = "icon"  href="images/logo.png" type="image/x-icon">
   <link rel="stylesheet" href="style.css"> <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
     <section class="alaska-header">
          <video autoplay loop muted plays-inline class="back-video">
             <source src="images/alaska.mp4" type="video/mp4">
         </video>
          <nav>
            <a href="index.php"><img src="images/logop.png"></a>
           <div class="nav-links" id="navLinks">
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
                    <?php if(isset($_SESSION['user_nume'])): ?>
                        <button class='loginbtn' style="width:auto; cursor:default;">Salut, <?= htmlspecialchars($_SESSION['user_prenume'] ?? $_SESSION['user_nume']) ?>!</button>
                    <?php else: ?>
                        <button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;">Contul meu</button>
                    <?php endif; ?>
                </li>
             </ul>
           </div>
           <i class="fa fa-bars" onclick="showMenu()"></i>
         </nav>


         <div id='login-form' class='login-page'>
             <div class="form-box" <?php if(isset($_SESSION['user_id'])) echo 'style="height: auto; padding-bottom: 20px;"'; ?>>

                 <?php if(!isset($_SESSION['user_id'])): ?>
                     <div class='button-box'>
                         <div id='btn'></div>
                         <button type='button' onclick='login()' class='toggle-btn'>Log in</button>
                         <button type='button' onclick='register()' class='toggle-btn'>Cont nou</button>
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

                 <?php else: ?>
                     <h3 style="text-align:center; padding-top:20px; font-family:'Playfair Display', serif;">Profilul tău</h3>

                     <form method='POST' action='index.php' style="padding: 10px 30px;">
                         <label style="font-size:12px; color:gray;">Prenume:</label>
                         <input type='text' name='update_prenume' class='input-field' value='<?= htmlspecialchars($_SESSION['user_prenume'] ?? '') ?>' required>
                         <label style="font-size:12px; color:gray;">Nume de familie:</label>
                         <input type='text' name='update_nume' class='input-field' value='<?= htmlspecialchars($_SESSION['user_nume'] ?? '') ?>' required>
                         <button type='submit' name='update_submit' class='submit-btn' style="margin-top:15px; background:#ff9800;">Salvează modificările</button>
                     </form>

                     <form method='POST' action='index.php' style="padding: 0 30px;" onsubmit="return confirm('Sigur vrei să ștergi contul?');">
                         <button type='submit' name='delete_submit' class='submit-btn' style="background:#dc3545; color:white;">Șterge Contul</button>
                     </form>

                     <form method='POST' action='logout.php' style="padding: 10px 30px;">
                         <button type='submit' class='submit-btn' style="background:transparent; border:1px solid gray;">Deconectare</button>
                     </form>
                 <?php endif; ?>

             </div>
         </div>


     <div class="text-box">
          <h1>ALASKA, SUA</h1>
         <p>Cele mai frumoase locuri de vizitat din toată lumea</p>
     </div>
     </section>

     <section class="alaska">
        <div class="rowt">
             <div class="square-img"></div>

             <div class="informatii">
               <h3>ALASKA, SUA</h3>
               <p>Alături de fauna sălbatică spectaculoasă, fiordurile și ghețarii superbi, Alaska este un loc minunat pentru a afla despre cultura nativă din Alaska și despre grupurile indigene care gestionează experiențe turistice autentice.
               Urși mai mari decât zimbrii, parcuri naționale de mărimea unor țări și ghețari mai mari decât unele state din SUA: cuvântul „epopee” abia poate descrie Alaska.</p>
                  <div class="plan">
                   <h4>Planifică-ți călătoria</h4>
                      <div class="harta">
                             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d16502087.168604204!2d-179.46860742397962!3d59.67602317891844!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5400df9cc0aec01b%3A0xbcdb5e27a98adb35!2sAlaska%2C%20Statele%20Unite%20ale%20Americii!5e0!3m2!1sro!2sro!4v1683540056867!5m2!1sro!2sro" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                         </div>
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
                              <?php if(!isset($_SESSION['user_nume'])): ?>
                                 <button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'"style="width:auto;">Contul meu</button>
                              <?php endif; ?>
                      </div>
                 </div>

                 <div class="detalii">
                 <div class="lista">
                  <ul>
                     <li><a href="about.html">Despre noi</a></li>
                     <li><a href="contact.html">Contact</a></li>
                    <li><a href="about.html">Termeni și condiții</a></li>
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