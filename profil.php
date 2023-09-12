<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
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
    <?php
        require_once './user.php';
        session_start();
        if (isset($_SESSION['login'])) {
            $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
            $requete = $bdd->prepare("SELECT * FROM user WHERE login = :login");
            $requete->bindParam(':login', $_SESSION['login']);
            $requete->execute();
            $result = $requete->fetch();
            $user = new User($result['login'], $result['firstname'], $result['lastname'], $result['password']);
        }
    ?>
    <form class = "mainForm" method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $user->getLogin() ?>" onfocus="this.value='';">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo $user->getFirstname() ?>" onfocus="this.value='';">
            <label for="lastname">Name</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo $user->getLastname() ?>" onfocus="this.value='';">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="password2">Password</label>
            <input type="password" name="password2" id="password2">
        </div>
        <div class="error">
            <p></p>
        </div>
        <button type="submit" name="submit">Modifier profil</button>
    </form>


    <?php
        function checkForm( $login, $password, $password2, $firstname, $lastname){
            if (isset($login) && isset($password) && isset($password2) && isset($firstname) && isset($lastname)){
                if ($password == $password2){
                    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}$/", $password)){
                            return true;
                        }
                    }
                    else {
                        return ('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial');
                    }
                } else {
                    return ('Les mots de passe ne correspondent pas');
                }
            return false;
        }

        
        if (isset($_POST['submit'])) {
            if (!$_SESSION['login']) {
                header('location: connexion.php');
            } else {
                $formresult = checkForm($_POST['login'], $_POST['password'], $_POST['password2'], $_POST['firstname'], $_POST['lastname']);
                if ($formresult === true) {
                    $login = htmlspecialchars($_POST['login'], ENT_QUOTES, 'UTF-8');
                    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
                    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
                    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8');
                    $user->setLogin($login);
                    $user->setPassword($password);
                    $user->setFirstName($firstname);
                    $user->setLastName($lastname);
                    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
                    $requete = $bdd->prepare("SELECT id FROM user WHERE login = :login");
                    $login = $user->getLogin();
                    $requete->bindParam(':login', $login);
                    $requete->execute();
                    $result = $requete->fetch();
                    $id = $result['id'];
                    $user->changeProfile($id, $user->getLogin(), $user->getFirstname(), $user->getLastname(), $user->getPassword());
                    $_SESSION['login'] = $_POST['login'];
                    header('location: index.php');
                } else {
                    echo "<div class='error'><p>" .$formresult. "</p></div>";
                }
            }
            }
    ?>
</body>
</html>