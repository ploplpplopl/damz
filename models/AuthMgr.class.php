<?php

require_once("dao/DbConnection.class.php");

class AuthMgr
{
    // TODO : check si pseudo deja utilisé (pareil que emailExists)
    /**
     * Checks if email exists in database
     * returns true if exists
	 * 
     * @param string $email
     * @return boolean
     */
    public static function emailExists(string $email): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'SELECT * FROM user WHERE email=:email LIMIT 1';
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

    /**
     * Checks if pseudo already exists in DB
     *
     * @param string $pseudo
     * @return boolean
     */
    public static function pseudoExists(string $pseudo): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'SELECT * FROM user WHERE pseudo=:pseudo LIMIT 1';
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
            $query = 'INSERT INTO user (first_name, last_name , email, phone, pseudo, password, secure_key, date_add) VALUES (:first_name, :last_name, :email, :phone, :pseudo, :password, :secure_key, :date_add)';
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':first_name', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
			$password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':secure_key', $token, PDO::PARAM_STR);
            date_default_timezone_set('Europe/Paris');
			$dateAdd = date("Y-m-d H:i:s");
            $stmt->bindParam(':date_add', $dateAdd, PDO::PARAM_STR);
            $result = $stmt->execute();

            if (!$result) {
				$success = false;
            }
			else {
                $success = true;
            }
            // fermeture de la connexion
            DbConnection::disconnect();
            return $success;
        } catch (Exception $e) {
			// TODO log error in DB rather than display an ugly message.
            echo $e->getMessage();
        }
    }

    /**
     * Checks login credentials and password in DB
     * 
     * @param string $pseudo
     * @param string $password
     * @return array
     */
    public static function checkLogin(string $pseudo, string $password): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE pseudo=? OR email=? LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $pseudo, PDO::PARAM_STR);
        $stmt->bindParam(2, $pseudo, PDO::PARAM_STR);

		$output = [
			'status' => 'error',
			'user' => NULL,
		];
        if ($stmt->execute()) {
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
			
			$output['user'] = $tUser;
			unset($output['user']['password']);
			
            if ('0' === $tUser['subscr_confirmed']) {
				$output['status'] = 'not_confirmed';
            }
			elseif ($tUser && password_verify($password, $tUser['password'])) {
				$output['status'] = 'ok';
            }
        }
		
        DbConnection::disconnect();
        return $output;
    }
	
    /**
     * Send an email to set a new password
     * 
     * @param string $email
     * @return bool
     */
    public static function forgotPassword(string $email): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE email=? LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);

		$success = FALSE;
        if ($stmt->execute()) {
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
			
			if (!empty($tUser)) {
				$success = TRUE;
			}
        }
		
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
        unset($_SESSION['user']);
        /*
		$_SESSION = array(); // Détruit toutes les variables de session
        session_unset(); // obsolete
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        // session_regenerate_id(true);  // redémarrer une nouvelle session
		*/
    }

    /**
     * Verify the email by comparing the sent token with the one stored in DB 
     *
     * @param string $token
     * @return string
     */
    public static function verifyEmail(string $token): string
    {
        try {
            $dbh = DbConnection::getConnection('administrateur');
            $query = "SELECT * FROM user WHERE secure_key=:token LIMIT 1";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':token', $token);
			$stmt->execute();
			$tUser = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
            $success = 'error';
			if ('1' === $tUser['subscr_confirmed']) {
				$success = 'already_confirmed';
			}
			else {
				$query = 'UPDATE user SET subscr_confirmed=1 WHERE secure_key=:token';
				$stmt = $dbh->prepare($query);
				$stmt->bindParam(':token', $token);
				if ($stmt->execute()) {
					$success = 'confirmed';
				}
			}
            DbConnection::disconnect();
            return $success;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
