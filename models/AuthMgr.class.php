<?php

require_once("dao/DbConnection.class.php");

class AuthMgr
{

    // not tested
    public static function addUser_test(array $params): bool
    {
        // Check mandatory fields.
        $requiredFields = [
            'email',
            'pseudo',
            'password',
        ];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                throw new Exception('Undefined field ' . $field);
            }
        }

        // Add/rewrite some fields.
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        if (!array_key_exists('date_add', $params)) {
            $params['date_add'] = date('Y-m-d H:i:s');
        }

        // Execute query.
        $query = 'INSERT INTO user (%s) VALUES (%s)';
        $keys = implode(', ', array_keys($params));
        $values = trim(str_repeat('?,', count($params)), ',');

        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare(sprintf($query, $keys, $values));
        $result = $stmt->execute(array_values($params));
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }
    // Call sample: addUser_test(['email' => 'test@example.com', 'pseudo' => 'azerty', 'password' => 'P@ss-w0rd']);


    /**
     * Get all user's data.
     *
     * @return array|null
     */
    public static function getAllUsers(): ?array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT * FROM user WHERE deleted = \'0\'');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result ?: NULL;
    }

    /**
     * Get any user's data.
     *
     * @param int $id The user id.
     * @return array|null
     */
    public static function getUserByID(int $id): ?array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM user WHERE id_user = :id AND deleted=0');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result ?: NULL;
    }

    /**
     * Get user addresses.
     *
     * @param integer $id
     * @return array
     */
    public static function getUserAddresses(int $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT a.*, c.name AS country_name
		FROM address AS a
		INNER JOIN country AS c
		ON a.id_country = c.id_country
        WHERE a.id_user = :id
        AND a.deleted = 0');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get an address
     *
     * @param integer $id_address
     * @param integer $id_user
     * @return array
     */
    public static function getAddress(int $id_address, int $id_user): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM address WHERE id_address = :id_address AND id_user = :id_user');
        $stmt->bindParam(':id_address', $id_address);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get countries
     *
     * @return array
     */
    public static function getCountries(): array
    {
        $stmt = DbConnection::getConnection('administrateur')->query('SELECT * FROM country ORDER BY name ASC');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Update an address.
     *
     * @param array $params
     * @param integer $id_address
     * @param string $condition
     * @return boolean
     */
    public static function updateAddress(array $params, int $id_address, string $condition = ''): bool
    {
        // Check mandatory fields.
        $requiredFields = [
            'id_user',
            'id_country',
        ];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                throw new Exception('Undefined field ' . $field);
            }
        }

        // Execute query.
        $query = 'UPDATE address SET %s WHERE id_address = %d %s';
        $values = '';
        foreach (array_keys($params) as $key) {
            $values .= $key . ' = ?, ';
        }
        $values = trim($values, ', ');

        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare(sprintf($query, $values, $id_address, $condition));
        $result = $stmt->execute(array_values($params));

        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    public static function addAddress(array $params): bool
    {
        // Check mandatory fields.
        $requiredFields = [
            'id_user',
            'id_country',
        ];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                throw new Exception('Undefined field ' . $field);
            }
        }

        // Execute query.
        $query = 'INSERT INTO address (%s) VALUES (%s)';
        $keys = implode(', ', array_keys($params));
        $values = trim(str_repeat('?,', count($params)), ',');

        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare(sprintf($query, $keys, $values));
        $result = $stmt->execute(array_values($params));
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Checks if email exists in database
     * returns true if exists
     * 
     * @param string $email
     * @return boolean
     */
    public static function emailExists(string $email, int $id_user = NULL): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'SELECT * FROM user WHERE email = :email AND deleted=0';
        if (!empty($id_user)) {
            $query .= ' AND id_user != :id_user';
        }
        $query .= ' LIMIT 1';
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email);
        if (!empty($id_user)) {
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        }
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
    public static function pseudoExists(string $pseudo, int $id_user = NULL): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'SELECT * FROM user WHERE pseudo = :pseudo AND deleted=0';
        if (!empty($id_user)) {
            $query .= ' AND id_user != :id_user';
        }
        $query .= ' LIMIT 1';
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':pseudo', $pseudo);
        if (!empty($id_user)) {
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        }
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
     * Create a user.
     *
     * @param string $user_email
     * @param string $user_pseudo
     * @param string $user_password
     * @param string $user_user_type
     * @param string $token
     * @return array
     */
    public static function setUser(
        string $user_email,
        string $user_pseudo,
        string $user_password,
        string $token,
        string $user_user_type = ''
    ): bool {
        try {
            if (empty($user_user_type)) {
                $user_user_type = 'user';
            }
            $dbh = DbConnection::getConnection('administrateur');
            $query = 'INSERT INTO user (email, pseudo, password, user_type, secure_key, date_add) VALUES (:email, :pseudo, :password, :user_type, :secure_key, :date_add)';
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
            $stmt->bindParam(':pseudo', $user_pseudo, PDO::PARAM_STR);
            $user_password = password_hash($user_password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $user_password, PDO::PARAM_STR);
            $stmt->bindParam(':user_type', $user_user_type, PDO::PARAM_STR);
            $stmt->bindParam(':secure_key', $token, PDO::PARAM_STR);
            // date_default_timezone_set('Europe/Paris');
            $dateAdd = date("Y-m-d H:i:s");
            $stmt->bindParam(':date_add', $dateAdd, PDO::PARAM_STR);
            $result = $stmt->execute();
            $stmt->closeCursor();
            DbConnection::disconnect();
            return $result;
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
        $query = "SELECT * FROM user WHERE (pseudo=? OR email=?) AND deleted=0 LIMIT 1";
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
            } elseif ($tUser && password_verify($password, $tUser['password'])) {
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
     * @return array|FALSE
     */
    public static function getUserByEmail(string $email)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE email=? AND deleted=0 LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);

        $success = FALSE;
        if ($stmt->execute()) {
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if (!empty($tUser)) {
                $success = $tUser;
            }
        }

        DbConnection::disconnect();
        return $success;
    }

    /**
     * Reset a new password
     * 
     * @param string $password
     * @return string
     */
    public static function resetPassword(string $password, string $token, string $email): string
    {
        // Récupération de l'utilisateur.
        $dbh = DbConnection::getConnection('administrateur');
        $query = "SELECT * FROM user WHERE email=? AND secure_key=? AND deleted=0 LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $token, PDO::PARAM_STR);

        $output = 'db_connection_failed';
        if ($stmt->execute()) {
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if (empty($tUser)) {
                $output = 'user_not_found';
            } else {
                // Modification du mot de passe.
                $dbh = DbConnection::getConnection('administrateur');
                $query = "UPDATE user SET password = :password WHERE email = :email AND secure_key = :token AND deleted=0";
                $stmt = $dbh->prepare($query);
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $stmt->closeCursor();
                    $output = 'password_updated';
                }
            }
        }
        DbConnection::disconnect();
        return $output;
    }

    /**
     * Modify a user.
     *
     * @param string $user_user_type
     * @param string $user_first_name
     * @param string $user_last_name
     * @param string $user_phone
     * @param integer $id
     * @return boolean
     */
    public static function updateUserByID(
        string $user_user_type,
        string $user_first_name,
        string $user_last_name,
        string $user_phone,
        int $id
    ): bool {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'UPDATE user SET user_type = :user_type, first_name = :first_name, last_name = :last_name, phone = :phone WHERE id_user = :id_user AND deleted=0';
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':user_type', $user_user_type, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $user_first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $user_last_name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $user_phone, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Update a user
     *
     * @param array $params
     * @param integer $id
     * @param string $condition
     * @return boolean
     */
    public static function updateUser(array $params, int $id, string $condition = ''): bool
    {
        // Add/rewrite some fields.
        if (array_key_exists('password', $params)) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        }

        // Execute query.
        $query = 'UPDATE user SET %s WHERE id_user = %d AND deleted=0 %s';
        $values = '';
        foreach (array_keys($params) as $key) {
            $values .= $key . ' = ?, ';
        }
        $values = trim($values, ', ');

        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare(sprintf($query, $values, $id, $condition));
        $result = $stmt->execute(array_values($params));

        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }
    // AuthMgr::updateUser(['email' => 'test@example.com', 'pseudo' => 'azerty', 'password' => 'P@ss-w0rd'], $id, ' AND token="ojzneojzen"');

    /**
     * Destroy the session
     *
     * @param none
     * @return none
     */
    public static function disconnectUser()
    {
        unset($_SESSION['user'], $_SESSION['file_to_print'], $_SESSION['tunnel']);
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
            $query = "SELECT * FROM user WHERE secure_key=:token AND deleted=0 LIMIT 1";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $tUser = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $success = 'error';
            if ('1' === $tUser['subscr_confirmed']) {
                $success = 'already_confirmed';
            } else {
                $query = 'UPDATE user SET subscr_confirmed=1 WHERE secure_key=:token AND deleted=0';
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
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

    /**
     * User deletion.
     *
     * @param int $id_user
     */
    public static function deleteUser(int $id_user)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = "UPDATE user SET deleted=1 WHERE id_user = :id_user";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    public static function setPwdExpirationDate(string $email, string $expDate = 'NOW()'): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'UPDATE user SET reset_pwd_expiration = :expDate WHERE email = :email AND deleted=0';
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }
}
