<?php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:6379');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$host = 'db_master';
$db   = 'perfect_world_db';
$user = 'alexandra';
$pass = '1005';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Conexiunea a eșuat: " . $conn->connect_error); }

$user_id = $_SESSION['user_id'];

// ==========================================
// CRUD PENTRU CONTUL UTILIZATORULUI
// ==========================================
if (isset($_POST['update_profile'])) {
    $nume = $conn->real_escape_string($_POST['nume']);
    $prenume = $conn->real_escape_string($_POST['prenume']);
    $parola_noua = $_POST['parola_noua'];

    if (!empty($parola_noua)) {
        $parola_hash = password_hash($parola_noua, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET nume='$nume', prenume='$prenume', password='$parola_hash' WHERE id=$user_id");
    } else {
        $conn->query("UPDATE users SET nume='$nume', prenume='$prenume' WHERE id=$user_id");
    }
    $_SESSION['user_nume'] = $nume;
    $_SESSION['user_prenume'] = $prenume;
    echo "<script>alert('Datele au fost actualizate cu succes!'); window.location.href='profil.php';</script>";
}

if (isset($_POST['delete_profile'])) {
    $conn->query("DELETE FROM users WHERE id=$user_id");
    session_destroy();
    echo "<script>alert('Contul tău a fost șters definitiv.'); window.location.href='index.php';</script>";
}

// ==========================================
// CRUD PENTRU WISHLIST
// ==========================================

// 1. ADAUGĂ SAU ACTUALIZEAZĂ DESTINAȚIE
if (isset($_POST['save_wishlist'])) {
    $w_id = $_POST['wishlist_id'];
    $destinatie = $conn->real_escape_string($_POST['destinatie']);
    $tara = $conn->real_escape_string($_POST['tara']);
    $perioada = $conn->real_escape_string($_POST['perioada']);

    if (empty($w_id)) {
        $conn->query("INSERT INTO wishlist (user_id, destinatie, tara, perioada, vizitat) VALUES ($user_id, '$destinatie', '$tara', '$perioada', 0)");
    } else {
        $conn->query("UPDATE wishlist SET destinatie='$destinatie', tara='$tara', perioada='$perioada' WHERE id=$w_id AND user_id=$user_id");
    }
    // NOU: Redirecționare direct la ancoră
    header("Location: profil.php#sectiune-wishlist");
    exit();
}

// 2. ȘTERGE DESTINAȚIE (Click pe inima roșie)
if (isset($_GET['del_w'])) {
    $w_id = (int)$_GET['del_w'];
    $conn->query("DELETE FROM wishlist WHERE id=$w_id AND user_id=$user_id");
    // NOU: Redirecționare direct la ancoră
    header("Location: profil.php#sectiune-wishlist");
    exit();
}

// 3. BIFEAZĂ/DEBIFEAZĂ CA "VIZITAT"
if (isset($_GET['toggle_w'])) {
    $w_id = (int)$_GET['toggle_w'];
    $stare_curenta = (int)$_GET['st'];
    $stare_noua = $stare_curenta ? 0 : 1;
    $conn->query("UPDATE wishlist SET vizitat=$stare_noua WHERE id=$w_id AND user_id=$user_id");
    // NOU: Redirecționare direct la ancoră
    header("Location: profil.php#sectiune-wishlist");
    exit();
}

// Citim datele userului
$result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta charset="utf-8">
   <title>Contul Meu - Perfect Planet</title>
   <link rel="icon" href="images/logo.png" type="image/x-icon">
   <link rel="stylesheet" href="style.css">
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
     <section class="profil-header">
          <nav>
             <a href="index.php"><img src="images/logop.png"></a>
             <div class="nav-links" id="navLinks">
             <i class="fa fa-times" onclick="hideMenu()"></i>
                <ul>
                    <li><a href="index.php">Acasă</a></li>
                    <li><a href="about.html">Despre noi</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><button class='loginbtn' style="width:auto; cursor:default;">Salut, <?= htmlspecialchars($_SESSION['user_prenume']) ?>!</button></li>
                </ul>
             </div>
             <i class="fa fa-bars" onclick="showMenu()"></i>
          </nav>
          <div class="text-box"><h1>CONTUL MEU</h1></div>
     </section>

     <section class="profil">
         <div class="rowt">
             <div class="square-img"></div>
             <div class="informatii">
                 <h3>Datele Tale</h3>
                 <form method="POST" action="profil.php" class="profile-form">
                     <label>Prenume:</label>
                     <input type="text" name="prenume" class="input-field" value="<?= htmlspecialchars($user_data['prenume']) ?>" required>
                     <label>Nume:</label>
                     <input type="text" name="nume" class="input-field" value="<?= htmlspecialchars($user_data['nume']) ?>" required>
                     <label>Email (Nu poate fi modificat):</label>
                     <input type="email" class="input-field" value="<?= htmlspecialchars($user_data['email']) ?>" readonly style="background: transparent; border-bottom: 2px solid #ccc; cursor: not-allowed; color: #999;">
                     <label>Parolă nouă (Lasă gol dacă nu vrei să o schimbi):</label>
                     <input type="password" name="parola_noua" class="input-field" placeholder="***">
                     <button type="submit" name="update_profile" class="btn-update">Actualizează Datele</button>
                 </form>

                 <div class="profile-form" style="padding-top: 10px;">
                     <div class="action-buttons">
                         <a href="logout.php" style="text-decoration: none; flex: 1;">
                             <button type="button" class="btn-logout">Deconectare</button>
                         </a>
                         <form method="POST" action="profil.php" style="flex: 1;" onsubmit="return confirm('Ești sigur că vrei să ștergi contul definitiv? Această acțiune nu poate fi anulată!');">
                             <button type="submit" name="delete_profile" class="btn-delete">Șterge Contul</button>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <section class="profil" id="sectiune-wishlist" style="padding-bottom: 50px; padding-top: 20px;">
         <div class="wishlist-container">
             <h2>Wishlist-ul Meu de Călătorii</h2>

             <form id="wishlist-form" class="wishlist-add-form" method="POST" action="profil.php">
                 <input type="hidden" name="wishlist_id" id="form_w_id" value="">

                 <input type="text" name="destinatie" id="form_dest" placeholder="Destinație Turistică" required>
                 <input type="text" name="tara" id="form_tara" placeholder="Țara" required>
                 <input type="text" name="perioada" id="form_per" placeholder="Perioada (ex: Vara 2024)">
                 <button type="submit" name="save_wishlist" id="form_btn">Adaugă</button>
             </form>

             <div id="wishlist-items-container">
                 <?php
                 // Aducem destinațiile: cele vizitate vor fi la final (vizitat ASC)
                 $w_result = $conn->query("SELECT * FROM wishlist WHERE user_id=$user_id ORDER BY vizitat ASC, id DESC");

                 if ($w_result->num_rows > 0) {
                     while ($item = $w_result->fetch_assoc()) {
                         $w_id = $item['id'];
                         $dest = htmlspecialchars($item['destinatie']);
                         $tara = htmlspecialchars($item['tara']);
                         $per = htmlspecialchars($item['perioada']);
                         $vizitat = $item['vizitat'];

                         $clasa_vizitat = $vizitat ? 'visited' : '';
                         $icon_bifa = $vizitat ? 'fa-check-square' : 'fa-square-o';
                         $culoare_bifa = $vizitat ? 'green' : '#555';
                         ?>
                         <div class="wishlist-item <?= $clasa_vizitat ?>">
                             <a href="profil.php?del_w=<?= $w_id ?>" onclick="return confirm('Sigur vrei să ștergi locația?')" style="text-decoration: none;">
                                 <i class="fa fa-heart"></i>
                             </a>

                             <p><strong><?= $dest ?></strong> - <?= $tara ?> <em>(<?= $per ?>)</em></p>

                             <i class="fa fa-pencil" title="Editează destinația"
                                onclick="editWishlist(<?= $w_id ?>, '<?= addslashes($dest) ?>', '<?= addslashes($tara) ?>', '<?= addslashes($per) ?>')"></i>

                             <a href="profil.php?toggle_w=<?= $w_id ?>&st=<?= $vizitat ?>" style="text-decoration: none;">
                                 <i class="fa <?= $icon_bifa ?> check-icon" style="color: <?= $culoare_bifa ?>;" title="Marchează ca Vizitat"></i>
                             </a>
                         </div>
                         <?php
                     }
                 } else {
                     echo "<p style='text-align: center; color: #555; font-size: 20px; font-style: italic;'>Nu ai adăugat încă nicio destinație. Începe să visezi!</p>";
                 }
                 ?>
             </div>
         </div>
     </section>

     <section class="footer">
         <div class="rowf">
              <div class="logo">
                 <a href="index.php"><img src="images/logop.png"></a>
                 <p>Pentru exploratorii de pretutindeni</p>
                 <div class="buton">
                     <button class='loginbtn' style="width:auto; cursor:default;">Salut, <?= htmlspecialchars($_SESSION['user_prenume']) ?>!</button>
                 </div>
              </div>
              <div class="detalii">
              <div class="lista">
               <ul>
                  <li><a href="about.html">Despre noi</a></li>
                  <li><a href="contact.html">Contact</a></li>
                  <li><a href="about.html">Termeni și condiții</a></li>
                  <li><p>IP: <?php echo $_SERVER['SERVER_ADDR']; ?></p></li>
                 </ul>
                </div>
              </div>
         </div>
         <div class="icons">
           <a href="#"><i class="fa fa-facebook"></i></a>
           <a href="#"><i class="fa fa-instagram"></i></a>
           <a href="#"><i class="fa fa-pinterest"></i></a>
         </div>
         <p>Made with<i class="fa fa-heart-o"></i></p>
     </section>

     <script>
          // Funcția care urcă datele din lista înapoi în formular pentru editare
          function editWishlist(id, dest, tara, per) {
              document.getElementById('form_w_id').value = id;
              document.getElementById('form_dest').value = dest;
              document.getElementById('form_tara').value = tara;
              document.getElementById('form_per').value = per;
              document.getElementById('form_btn').innerText = 'Actualizează';

              // Duce ecranul frumos înapoi la formular
              document.getElementById('wishlist-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
          }

          var navLinks = document.getElementById("navLinks");
          function showMenu(){ navLinks.style.right = "0"; }
          function hideMenu(){ navLinks.style.right = "-200px"; }
     </script>
</body>
</html>