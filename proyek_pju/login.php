<?php
session_start();
require_once 'config/koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Sesuaikan bagian ini dengan metode penyimpanan password Anda (hash atau teks)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role']    = $user['role'];
            
            header("Location: " . ($user['role'] == 'admin' ? "admin/dashboard.php" : "teknisi/tracking.php"));
            exit();
        } else {
            $error = "Password Salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Cahaya | Gateway Access</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            height: 100vh; display: flex; align-items: center; justify-content: center;
            background: radial-gradient(circle, #1a1a1a 0%, #000 100%);
            font-family: 'Cinzel', serif; color: #D4AF37; padding: 20px;
        }

        .login-card {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(15px);
            padding: 50px 30px;
            border: 1px solid #D4AF37;
            border-radius: 20px;
            width: 100%; max-width: 350px;
            text-align: center;
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.2);
        }

        .title { font-size: 1.5rem; letter-spacing: 4px; margin-bottom: 5px; text-shadow: 0 0 10px #D4AF37; }
        .subtitle { font-size: 0.7rem; letter-spacing: 3px; margin-bottom: 40px; color: #888; }

        input {
            width: 100%; padding: 15px; margin-bottom: 15px;
            background: rgba(0,0,0,0.5); border: 1px solid #333;
            color: #D4AF37; text-align: center; font-size: 1rem;
            border-radius: 10px; transition: 0.3s;
        }
        input:focus { outline: none; border-color: #D4AF37; box-shadow: 0 0 10px #D4AF37; }

        button {
            width: 100%; padding: 15px; border: 1px solid #D4AF37;
            background: transparent; color: #D4AF37; font-weight: bold;
            cursor: pointer; border-radius: 10px; transition: 0.4s;
            text-transform: uppercase; letter-spacing: 2px;
        }
        button:hover { background: #D4AF37; color: #000; }

        .error { color: #ff4d4d; font-size: 0.8rem; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="title">STRUKTUR CAHAYA</div>
        <div class="subtitle">SYSTEM GATEWAY ACCESS</div>
        
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="USERNAME" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <button type="submit">LOGIN</button>
        </form>
    </div>

</body>
</html>