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
        $stmt = $dbh->query('SELECT * FROM '.$db);
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
        $stmt = $dbh->prepare('SELECT * FROM '.$db.' WHERE id = :id');
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
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM '.$db);
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
        $stmt = $dbh->prepare('INSERT INTO '.$db.' (palier, prix, position) VALUES (:palier, :prix, :position)');
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
        $stmt = $dbh->prepare('UPDATE '.$db.' SET palier = :palier, prix = :prix WHERE id = :id');
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
     * Delete un palier
     *
     * @param string $id
     * @return void
     */
    public static function delPalier(string $db, string $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM '.$db.' WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
    }

}
