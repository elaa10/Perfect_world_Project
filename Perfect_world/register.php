<?php
// Datele de conectare la baza de date
$host = 'db';
$db   = 'perfect_world_db';
$user = 'alexandra';
$pass = '1005';

// Ne conectăm la baza de date
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Partea de CREATE: Dacă formularul a fost trimis, salvăm datele
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    // Parola trebuie criptată pentru securitate
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Folosim tabela `users` și coloanele în engleză
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='color: green; text-align:center;'>User registered successfully!</div>";
    } else {
        echo "<div style='color: red; text-align:center;'>Error: " . $conn->error . "</div>";
    }
}

// Partea de DELETE: Dacă s-a apăsat pe butonul de ștergere
if (isset($_GET['delete_id'])) {
    $id_to_delete = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $id_to_delete");
    echo "<div style='color: orange; text-align:center;'>User deleted!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register / CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 8px; }
        input { width: 95%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add User (Create)</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Secure Password" required>
        <button type="submit" name="submit">Register</button>
    </form>

    <h2>Existing Users (Read & Delete)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        // Partea de READ: Citim din tabela `users`
        $result = $conn->query("SELECT id, name, email FROM users");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td><a href='register.php?delete_id=" . $row["id"] . "' style='color: red;'>Delete</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>