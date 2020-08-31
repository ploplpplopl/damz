<?php

require_once("dao/DbConnection.class.php");

class AdminGestionMgr
{
    // ------------------------------------------
    // ---------------- RELIURES ----------------
    // ------------------------------------------
    /**
     * récupère les paliers de prix des reliures en fonction du nombre de pages
     *
     * @return array
     */
    public static function getPaliers(string $db): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT * FROM ' . $db);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // fermeture de la connexion
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get palier associated to an ID
     *
     * @param string $id
     * @return array
     */
    public static function getPalierById(string $db, string $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM ' . $db . ' WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get the position max +1 (to insert a new palier)
     *
     * @return array
     */
    public static function getPalierPositionMax(string $db): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM ' . $db);
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
        return $max;
    }

    /**
     * Create a new palier
     *
     * @param string $palier
     * @param string $prix
     * @param string $max
     * @return boolean
     */
    public static function setNewPalier(string $db, string $palier, string $prix, string $max): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('INSERT INTO ' . $db . ' (palier, prix, position) VALUES (:palier, :prix, :position)');
        $stmt->bindParam(':palier', $palier, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':position', $max, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Modify un palier
     *
     * @param string $palier
     * @param string $prix
     * @param string $id
     * @return boolean
     */
    public static function updatePalier(string $db, string $palier, string $prix, string $id): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE ' . $db . ' SET palier = :palier, prix = :prix WHERE id = :id');
        $stmt->bindParam(':palier', $palier, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Delete a level.
     *
     * @param string $table
     * @param string $id
     * @return void
     */
    public static function delPalier(string $table, int $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM ' . $table . ' WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
    }

    /**
     * Getting orders.
     *
     * @param array $params The request parameters.
     * @param int $archive The archive field: 1 or 0.
     * @param string $where The WHERE part of the request.
     * @param string $order The first parameter of the ORDER BY part of the request.
     * @param string $way The second parameter of the ORDER BY part of the request.
     * @param int $limitFrom (optional) The first parameter of the LIMIT part of the request.
     * @param int $limitTo (optional) The second parameter of the LIMIT part of the request.
     * @return array The result set.
     */
    public static function getOrders(array $params, int $archive, string $where, string $order, string $way, $limitFrom = FALSE, $limitTo = FALSE): array
    {
        $query = '
			SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
			a.address, a.address2, a.zip_code, a.city, c.name AS country_name
			FROM orders AS o
			INNER JOIN user AS u ON o.id_user = u.id_user
			INNER JOIN address AS a ON u.id_user = a.id_user
			INNER JOIN country AS c ON a.id_country = c.id_country
			WHERE o.id_address = a.id_address
			AND o.archive = \'' . $archive . '\'
			' . $where . '
			ORDER BY ' . $order . ' ' . $way . '
		';
        if ((!empty($limitFrom) || 0 === $limitFrom) && isset($limitTo)) {
            $query .= ' LIMIT ' . (int) $limitFrom . ', ' . (int) $limitTo;
        }
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Archive an order.
     *
     * @param string $id
     */
    public static function archiveOrder(int $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE orders SET archive = \'1\' WHERE id_orders = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
    }

    /**
     * Get an order, with some related user information.
     *
     * @param integer $id
     * @return void
     */
    public static function getSingleOrder(int $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('
            SELECT o.*, u.first_name, u.last_name, u.date_add
            FROM orders AS o
            INNER JOIN user AS u ON o.id_user = u.id_user
            WHERE id_orders = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Getting users.
     *
     * @param array $params The request parameters.
     * @param string $where The WHERE part of the request.
     * @param string $order The first parameter of the ORDER BY part of the request.
     * @param string $way The second parameter of the ORDER BY part of the request.
     * @param int $limitFrom (optional) The first parameter of the LIMIT part of the request.
     * @param int $limitTo (optional) The second parameter of the LIMIT part of the request.
     * @return array The result set.
     */
    public static function getUsers(array $params, string $where, string $order, string $way, $limitFrom = FALSE, $limitTo = FALSE): array
    {
        $query = '
			SELECT *, (
				SELECT COUNT(id_orders)
				FROM orders AS o
				WHERE o.id_user = u.id_user
			) AS num_orders
			FROM user AS u
			WHERE 1
			' . $where . '
			ORDER BY ' . $order . ' ' . $way . '
		';
        if ((!empty($limitFrom) || 0 === $limitFrom) && isset($limitTo)) {
            $query .= ' LIMIT ' . (int) $limitFrom . ', ' . (int) $limitTo;
        }
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

}
