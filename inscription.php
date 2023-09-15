<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<header>
        <a href="index.php"><h2>Sign-in/Log-in Simulator</h2></a>
        <?php
            session_start();
            if (isset($_SESSION['login'])) {
                $username = $_SESSION['login'];
                echo "<div class='sign_out'><p class='login_check'>Bienvenue, " .strip_tags($username). "!</p><form method='post'><button type='submit' name='signout'>Sign Out</button></form></div>";
                if (isset($_POST['signout'])) {
                    session_destroy();
                    header('location: index.php');
                    exit();
                }
            } else {
                
            }
        ?>
        <div class="nav">
            <a href="connexion.php">connexion</a>
            <a href="inscription.php">inscription</a>
            <a href="profil.php">profil</a>
            <?php
            session_start();
            if ($_SESSION['type'] == 'admin') {
                echo "<a href='admin.php'>admin</a>";
            }
        ?>
        </div>
</header>
    <form class = "mainForm" method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="password2">Password</label>
            <input type="password" name="password2" id="password2">
            <p>Already registred ? Log-in <a class="link" href="connexion.php">here !</a></p>
        <button type="submit" name="submit">Inscription</button>
    </form>
</body>
</html>

<?php

require_once './user.php';
function checkForm($login, $password, $password2, $firstname, $lastname){
    $login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
    $password2 = htmlspecialchars($password2, ENT_QUOTES, 'UTF-8');
    $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
    $requete = $bdd->prepare("SELECT * FROM user WHERE login = :login");
    $requete->bindParam(':login', $login);
    $requete->execute();
    if (isset($login) && isset($password) && isset($password2) && isset($firstname) && isset($lastname)){
        if ($password == $password2){
            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}$/", $password)){
                if ($requete->rowCount() === 0){
                    return true;
                }
                else {
                    return ('Login already used');
                }
            }
            else {
                return ('Password must contain at least 8 characters, 1 uppercase, 1 lowercase, 1 number and 1 special character');
            }
        } else {
            return ('Passwords do not match');
        }
    return false;
}
}

if (isset($_POST['submit'])){
    $user = new User($_POST['login'], $_POST['firstname'], $_POST['lastname'],  $_POST['password']);
    $formresult = checkForm($user->getLogin(), $user->getPassword(), $_POST['password2'], $user->getFirstname(), $user->getLastname());
    if ($formresult === true){
        $user->addToBdd($user->getLogin(), $user->getPassword(), $user->getFirstname(), $user->getLastname());
        header('Location: connexion.php');
    } else {
        echo "<p class = 'error_message'>".$formresult."</p>";
    }
}
?>