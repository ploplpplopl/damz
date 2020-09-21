<?php

require_once("dao/DbConnection.class.php");

class AdminGestionMgr
{
    // ----------------------------------------
    // ---------------- LEVELS ----------------
    // ----------------------------------------

    /**
     * Get all price levels.
     *
     * @return array
     */
    public static function getLevels(string $db): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT * FROM ' . $db);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Format d'affichage des prix avec virgule et minimum 2 chiffres après la virgule
        foreach ($result as $k => $v) {
            $v['prix'] = strval($v['prix']); // Pas obligatoire: stocké sous forme de string en bdd
            $tDecimales = explode('.', $v['prix']);
            $nbDecimales = isset($tDecimales[1]) ? strlen($tDecimales[1]) : 0;
            $result[$k]['prix'] = $nbDecimales > 2 ? number_format($v['prix'], $nbDecimales, ',', ' ') : number_format($v['prix'], 2, ',', ' ');
        }
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get level associated to an ID
     *
     * @param string $id
     * @return array
     */
    public static function getLevelById(string $db, string $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM ' . $db . ' WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get the position max +1 (to insert a new level)
     *
     * @return array
     */
    public static function getLevelPositionMax(string $db): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM ' . $db);
        $stmt->execute();
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $max;
    }

    /**
     * Create a new level
     *
     * @param string $palier
     * @param string $prix
     * @param string $max
     * @return boolean
     */
    public static function addLevel(string $db, string $palier, string $prix, string $max): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('INSERT INTO ' . $db . ' (palier, prix, position) VALUES (:palier, :prix, :position)');
        $stmt->bindParam(':palier', $palier, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':position', $max, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Modify a level.
     *
     * @param string $palier
     * @param string $prix
     * @param string $id
     * @return boolean
     */
    public static function updateLevel(string $db, string $palier, string $prix, string $id): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE ' . $db . ' SET palier = :palier, prix = :prix WHERE id = :id');
        $stmt->bindParam(':palier', $palier, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
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
    public static function deleteLevel(string $table, int $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM ' . $table . ' WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
    }



    // ------------------------------------------
    // ----------------- COLORS -----------------
    // ------------------------------------------

    /**
     * Get all colors of the cardboard sheets.
     *
     * @return array
     */
    public static function getColors(): array
    {
        $query = 'SELECT * FROM dossier_color ORDER BY position DESC';
        $sth = DbConnection::getConnection('administrateur')->query($query);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Get id and names of all colors of the cardboard sheets.
     *
     * @return array : 'id' => 'color name'.
     */
    public static function getMappingColors(): array
    {
        $sth = DbConnection::getConnection('administrateur')->query('SELECT id_dossier_color, text FROM dossier_color');
        $sth->execute();
        $allColors = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        DbConnection::disconnect();
        return $allColors;
    }

    /**
     * Get colors related to an id.
     *
     * @param integer $id
     * @return array
     */
    public static function getColorsByID(int $id): array
    {
        $stmt = DbConnection::getConnection('administrateur')->prepare('SELECT * FROM dossier_color WHERE id_dossier_color = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Add a cardboard sheet color.
     *
     * @param string $text
     * @param string $hex
     * @param integer $printable
     * @param integer $unprintable
     * @param integer $maxPos
     * @return boolean
     */
    public static function addColor(
        string $text,
        string $hex,
        int $printable,
        int $unprintable,
        int $maxPos
    ): bool {
        $query = 'INSERT INTO dossier_color (text, hex, printable, unprintable, position) 
            VALUES (:text, :hex, :printable, :unprintable, :position)';
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        $stmt->bindParam(':hex', $hex, PDO::PARAM_STR);
        $stmt->bindParam(':printable', $printable, PDO::PARAM_INT);
        $stmt->bindParam(':unprintable', $unprintable, PDO::PARAM_INT);
        $stmt->bindParam(':position', $maxPos, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Update a cardboard sheet color.
     *
     * @param string $text
     * @param string $hex
     * @param integer $printable
     * @param integer $unprintable
     * @param integer $id
     * @return boolean
     */
    public static function updateColor(
        string $text,
        string $hex,
        int $printable,
        int $unprintable,
        int $id
    ): bool {
        $query = 'UPDATE dossier_color SET text = :text, hex = :hex, printable = :printable, unprintable = :unprintable WHERE id_dossier_color = :id';
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        $stmt->bindParam(':hex', $hex, PDO::PARAM_STR);
        $stmt->bindParam(':printable', $printable, PDO::PARAM_INT);
        $stmt->bindParam(':unprintable', $unprintable, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
        return $result;
    }

    /**
     * Delete a color.
     *
     * @param string $table
     * @param string $id
     * @return void
     */
    public static function deleteColor(int $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM dossier_color WHERE id_dossier_color = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
    }



    // ------------------------------------------
    // ----------------- ORDERS -----------------
    // ------------------------------------------

    /**
     * Get all orders.
     *
     * @param array $params
     * @param int $archive (value: 1 or 0).
     * @param string $where (the WHERE part of the request).
     * @param string $order (the first parameter of the ORDER BY part of the request).
     * @param string $way (the second parameter of the ORDER BY part of the request).
     * @param int $limitFrom (optional, the first parameter of the LIMIT part of the request).
     * @param int $limitTo (optional, the second parameter of the LIMIT part of the request).
     * @return array
     */
    public static function getOrders(array $params, int $archive, string $where, string $order, string $way, $limitFrom = FALSE, $limitTo = FALSE): array
    {
        // TODO à modifier : doit matcher les nouvelles colonnes dans la table "orders" qui vont enregistrer les infos du user à l'instant T (adresse de livraison, etc) pour que les factures restent identiques en cas de modif des infos du user.
        $query = '
			SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
			a.addr_name, a.address, a.address2, a.zip_code, a.city, c.name AS country_name
			FROM orders AS o
			INNER JOIN user AS u ON o.id_user = u.id_user
			INNER JOIN address AS a ON u.id_user = a.id_user
			INNER JOIN country AS c ON a.id_country = c.id_country
            WHERE o.id_address = a.id_address
            AND u.deleted=0
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
     * Create an order (insert an order into database).
     *
     * @param string $date_add
     * @param integer $id_user
     * @param integer $id_address
     * @param string $nom_fichier
     * @param integer $nb_page
     * @param integer $nb_page_nb
     * @param integer $nb_page_c
     * @param string $doc_type
     * @param integer $couv_ft
     * @param integer $couv_fc
     * @param string $couv_fc_type
     * @param integer $couv_fc_color
     * @param integer $dos_ft
     * @param integer $dos_fc
     * @param string $dos_fc_type
     * @param integer $dos_fc_color
     * @param string $reliure_type
     * @param string $reliure_color
     * @param integer $quantity
     * @param integer $rectoverso
     * @param string $tva
     * @param string $total
     * @return void
     */
    public static function addOrder(
        string $date_add,
        int $id_user,
        int $id_address,
        string $nom_fichier,
        int $nb_page,
        int $nb_page_nb,
        int $nb_page_c,
        string $doc_type,
        int $couv_ft,
        int $couv_fc,
        string $couv_fc_type,
        int $couv_fc_color,
        int $dos_ft,
        int $dos_fc,
        string $dos_fc_type,
        int $dos_fc_color,
        string $reliure_type,
        string $reliure_color,
        int $quantity,
        int $rectoverso,
        string $tva,
        string $total
    ) {
        $dbh = DbConnection::getConnection('administrateur');
        // TODO à modifier : doit matcher les nouvelles colonnes dans la table "orders" qui vont enregistrer les infos du user à l'instant T (adresse de livraison, etc) pour que les factures restent identiques en cas de modif des infos du user.
        $stmt = $dbh->prepare('INSERT INTO orders (date_add, id_user, id_address, nom_fichier, nb_page, nb_page_nb, nb_page_c, doc_type, couv_ft, couv_fc, couv_fc_type, couv_fc_color, dos_ft, dos_fc, dos_fc_type, dos_fc_color, reliure_type, reliure_color, quantity, rectoverso, tva, total) VALUES (:date_add, :id_user, :id_address, :nom_fichier, :nb_page, :nb_page_nb, :nb_page_c, :doc_type, :couv_ft, :couv_fc, :couv_fc_type, :couv_fc_color, :dos_ft, :dos_fc, :dos_fc_type, :dos_fc_color, :reliure_type, :reliure_color, :quantity, :rectoverso, :tva, :total)');
        $date_add = date('Y-m-d H:i:s');
        $stmt->bindParam(':date_add', $date_add, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_address', $id_address, PDO::PARAM_INT);
        $stmt->bindParam(':nom_fichier', $nom_fichier, PDO::PARAM_STR);
        $stmt->bindParam(':nb_page', $nb_page, PDO::PARAM_INT);
        $stmt->bindParam(':nb_page_nb', $nb_page_nb, PDO::PARAM_INT);
        $stmt->bindParam(':nb_page_c', $nb_page_c, PDO::PARAM_INT);
        $stmt->bindParam(':doc_type', $doc_type, PDO::PARAM_STR);
        $stmt->bindParam(':couv_ft', $couv_ft, PDO::PARAM_INT);
        $stmt->bindParam(':couv_fc', $couv_fc, PDO::PARAM_INT);
        $stmt->bindParam(':couv_fc_type', $couv_fc_type, PDO::PARAM_STR);
        $stmt->bindParam(':couv_fc_color', $couv_fc_color, PDO::PARAM_INT);
        $stmt->bindParam(':dos_ft', $dos_ft, PDO::PARAM_INT);
        $stmt->bindParam(':dos_fc', $dos_fc, PDO::PARAM_INT);
        $stmt->bindParam(':dos_fc_type', $dos_fc_type, PDO::PARAM_STR);
        $stmt->bindParam(':dos_fc_color', $dos_fc_color, PDO::PARAM_INT);
        $stmt->bindParam(':reliure_type', $reliure_type, PDO::PARAM_STR);
        $stmt->bindParam(':reliure_color', $reliure_color, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':rectoverso', $rectoverso, PDO::PARAM_INT);
        $stmt->bindParam(':tva', $tva, PDO::PARAM_STR);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
        DbConnection::disconnect();
    }

    /**
     * Archive an order (put an order into the archive tab).
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
     * @param integer $id (the id of an order).
     * @return array
     */
    public static function getSingleOrder(int $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        // TODO à modifier : doit matcher les nouvelles colonnes dans la table "orders" qui vont enregistrer les infos du user à l'instant T (adresse de livraison, etc) pour que les factures restent identiques en cas de modif des infos du user.
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
     * Getting users who have orders.
     *
     * @param array $params The request parameters.
     * @param string $where The WHERE part of the request.
     * @param string $order The first parameter of the ORDER BY part of the request.
     * @param string $way The second parameter of the ORDER BY part of the request.
     * @param int $limitFrom (optional) The first parameter of the LIMIT part of the request.
     * @param int $limitTo (optional) The second parameter of the LIMIT part of the request.
     * @return array The result set.
     */
    public static function getUsersWithOrders(array $params, string $where, string $order, string $way, $limitFrom = FALSE, $limitTo = FALSE): array
    {
        $query = '
			SELECT *, (
				SELECT COUNT(id_orders)
				FROM orders AS o
				WHERE o.id_user = u.id_user
			) AS num_orders
			FROM user AS u
			WHERE u.deleted = \'0\'
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
