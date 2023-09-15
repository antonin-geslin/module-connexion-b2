<?php 
    class User {
        private string $login;
        private string $firstname;
        private string $lastname;
        private string $password;


        function __construct(string $login, string $firstname, string $lastname, string $password) {
            $this->login = $login;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->password = $password;
        }

        function getLogin() {
            return $this->login;
        }

        function getFirstname() {
            return $this->firstname;
        }

        function getLastname() {
            return $this->lastname;
        }

        function getPassword() {
            return $this->password;
        }

        function setLogin($login){
            $this->login = $login;
        }

        function setFirstName($firstname){
            $this->firstname = $firstname;
        }

        function setLastName($lastname){
            $this->lastname = $lastname;
        }

        function setPassword($password){
            $this->password = $password;
        }


        function addToBdd ($login, $password, $firstname, $lastname){
            $login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
            $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
            $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
            $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
            $requete = $bdd->prepare("INSERT INTO user (login, firstname, lastname, password) VALUES (:login, :firstname, :lastname, :password)");
            $requete->bindParam(':login', $login);
            $requete->bindParam(':password', $password);
            $requete->bindParam(':firstname', $firstname);
            $requete->bindParam(':lastname', $lastname);
            $requete->execute();
        }

        function changeProfile($id,$login, $firstname, $lastname, $password) {
            $login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
            $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
            $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
            $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexionb2;charset=utf8', 'admiN1337$', 'admiN1337$');
            $requete = $bdd->prepare("UPDATE user SET login = :login, firstname = :firstname, lastname = :lastname, password = :password WHERE id = :id");
            $requete->bindParam(':login', $login);
            $requete->bindParam(':id', $id);
            $requete->bindParam(':firstname', $firstname);
            $requete->bindParam(':lastname', $lastname);
            $requete->bindParam(':password', $password);
            $requete->execute();
        }
    }

?>