<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <button type="submit" name="submit">Connexion</button>
    </form>
</body>
</html>


<?php 

require_once './user.php';
function checkForm($login, $password){
    $login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
    $requete = $bdd->prepare("SELECT * FROM user WHERE login = :login AND password = :password");
    $requete->bindParam(':login', $login);
    $requete->bindParam(':password', $password);
    $requete->execute();
    if (isset($login) && isset($password)){
        if ($requete->rowCount() === 1){
            if ($login == 'admin') {
                $_SESSION['type'] = 'admin';
            }
            return true;
        }
        else {
            return ('Login ou mot de passe incorrect');
        }
    }
    else {
        return ('Veuillez remplir tous les champs');
    }
    return false;
}

if (isset($_POST['submit'])) {
    session_start();
    if (!isset($_SESSION['login'])) {
        session_start();
        $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
        $requete = $bdd->prepare("SELECT * FROM user WHERE login = :login AND password = :password");
        $login = htmlspecialchars($_POST['login'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $requete->bindParam(':login', $login);
        $requete->bindParam(':password', $password);
        $requete->execute();
        $result = $requete->fetch();
        if ($result['login'] == $_POST['login'] && $result['password'] == $_POST['password']){
            $user = new User($result['login'], $result['firstname'], $result['lastname'], $result['password']);
            if (checkForm($user->getLogin(), $user->getPassword()) === true){
                $_SESSION['login'] = $user->getLogin();
                header('Location: index.php');
                exit();
            }
        } else {
            echo "<p class = 'error_message'>Login ou mot de passe incorrect</p>";
        }
    }
else {
    echo "<p class = 'error_message'>".$formresult."</p>";
}

}

?>