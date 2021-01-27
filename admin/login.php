<?php
session_start();
include_once('../library/db_func.php');

if (!empty($_SESSION['name'])) header('Location: index.php');
$data = 'not empty';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitizeInput($_POST['username']);
    $password = MD5($_POST['password']);
    $data = select("user", "username='$username' AND password='$password'");

    if (!empty($data)) {
        $_SESSION['name'] = $data[0]['name'];
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wehaus!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body class="body-login">
    <form action="" method="POST">
        <div class="field">
            <label class="label">Username</label>
            <div class="control">
                <input class="input" name="username" type="text" placeholder="">
            </div>
        </div>
        <div class="field">
            <label class="label">Password</label>
            <div class="control">
                <input class="input" name="password" type="password" placeholder="">
            </div>
            <?= empty($data) ? '<p class="help is-danger">Gagal login! username/password salah</p>' : '' ?>
        </div>
        <button class="button is-warning has-text-white">Login</button>
    </form>
</body>

</html>