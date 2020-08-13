<?php
require_once("dao/DbConnection.class.php");

class AuthMgr
{
    // TODO : check si pseudo deja utilisé (pareil que emailExists)
    /**
     * Checks if email exists in database
     * returns true if exists
     * @param string $email
     * @return boolean
     */
    public static function emailExists(string $email): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE email=:email LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $success = false;
            if ($row) {
                $success = true;
            }
        }
        // fermeture de la connexion
        DbConnection::disconnect();
        return $success;
    }
                // TODO : a supprimer (juste là pour info, mysqli() est le remplaçant du deprecated mysql()):
                // VERSION mysqli :
                // $conn = new mysqli('db', 'root', '&aqw2ZSX', 'dsp');
                // $sql = "SELECT * FROM user WHERE email='$email' LIMIT 1";
                // $result = mysqli_query($conn, $sql);
                // if (mysqli_num_rows($result) > 0) {
                //      $errors['email'] = "Email already exists";
                // }

    /**
     * Checks if pseudo already exists in DB
     *
     * @param string $pseudo
     * @return boolean
     */
    public static function pseudoExists(string $pseudo): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE pseudo=:pseudo LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':pseudo', $pseudo);
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $success = false;
            if ($row) {
                $success = true;
            }
        }
        // fermeture de la connexion
        DbConnection::disconnect();
        return $success;
    }

    /**
     * Registers a new user
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $phone
     * @param string $pseudo
     * @param string $password
     * @param string $token
     * @return boolean
     */
    public static function signup(
        string $firstname,
        string $lastname,
        string $email,
        string $phone,
        string $pseudo,
        string $password,
        string $token
    ) {
        try {
            $dbh = DbConnection::getConnection('administrateur');
            $query = "INSERT INTO user (first_name, last_name , email, phone, pseudo, password, secure_key, date_add) 
                        VALUES (:first_name, :last_name, :email, :phone, :pseudo, :password, :secure_key, :date_add)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':first_name', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':secure_key', $token, PDO::PARAM_STR);
            date_default_timezone_set('Europe/Paris');
            $today = date("Y-m-d H:i:s");  // format mysql
            $stmt->bindParam(':date_add', $today, PDO::PARAM_STR);
            $result = $stmt->execute();

            $success = false;
            if ($result) {
                $success = true;

                $user_id = $dbh->lastInsertId();
                // storing the user's data in the session generates his connection
                $_SESSION['id'] = $user_id;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['usertype'] = 'user';
                $_SESSION['verified'] = false;
                $_SESSION['message'] = 'You are logged in!';
                $_SESSION['type'] = 'alert-success';
            } else {
                $_SESSION['message'] = "Database error. Login failed!";
                $_SESSION['type'] = "alert-danger";
            }
            // fermeture de la connexion
            DbConnection::disconnect();
            return $success;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Checks login credentials and password in DB
     * 
     * @param string $pseudo
     * @param string $password
     * @return boolean
     */
    public static function checkLogin(string $pseudo, string $password): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE pseudo=? OR email=? LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $pseudo, PDO::PARAM_STR);
        $stmt->bindParam(2, $pseudo, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            // print_r($tUser);
            $stmt->closeCursor();

            $success = false;
            // si connexion ok
            if ($tUser != false) {
                if (password_verify($password, $tUser['password'])) { // if password matches
                    $success = true;
                    // storing the user's data in the session generates his connection
                    $_SESSION['id'] = $tUser['id_user'];
                    $_SESSION['pseudo'] = $tUser['pseudo'];
                    $_SESSION['email'] = $tUser['email'];
                    $_SESSION['usertype'] = $tUser['user_type'];
                    $_SESSION['verified'] = $tUser['subscr_confirmed'];
                    $_SESSION['message'] = 'You are logged in!';
                    $_SESSION['type'] = 'alert-success';
                }
            }
        } else {
            $_SESSION['message'] = "Database error. Login failed!";
            $_SESSION['type'] = "alert-danger";
        }
        // fermeture de la connexion
        DbConnection::disconnect();
        return $success;
    }

    /**
     * Détruit la session
     *
     * @param none
     * @return none
     */
    public static function disconnectUser()
    {
        // session_start();
        unset($_SESSION['id']);  // variable utilisée pour verif connexion
        unset($_SESSION['pseudo']);
        unset($_SESSION['email']);
        unset($_SESSION['verify']);
        unset($_SESSION['usertype']);
        $_SESSION = array(); // Détruit toutes les variables de session
        session_unset(); // obsolete
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        // session_regenerate_id(true);  // redémarrer une nouvelle session
    }

    /**
     * Verify the email by comparing the sent token with the one stored in DB 
     *
     * @param string $token
     * @return boolean
     */
    public static function verifyEmail(string $token): bool
    {
        // TODO mettre un try catch ?
        try {
            $dbh = DbConnection::getConnection('administrateur');
            $query = "SELECT * FROM user WHERE secure_key=:token LIMIT 1";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':token', $token);
            $success = false;

            if ($stmt->execute()) {
                $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                if ($tUser) {
                    $query = "UPDATE user SET subscr_confirmed=1 WHERE secure_key=:token";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':token', $token);
                    if ($stmt->execute()) {
                        $_SESSION['id'] = $tUser['id_user'];
                        $_SESSION['pseudo'] = $tUser['pseudo'];
                        $_SESSION['email'] = $tUser['email'];
                        $_SESSION['verified'] = true;
                        $_SESSION['message'] = "Your email address has been verified successfully";
                        $_SESSION['type'] = 'alert-success';

                        $success = true;
                    }
                    // TODO supprimer le token de la bdd
                }
            }
            // fermeture de la connexion
            DbConnection::disconnect();
            return $success;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
