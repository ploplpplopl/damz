<?php

require_once("dao/DbConnection.class.php");

class AdminGestionMgr
{
    // -----------------------------------------------------
    // ---------------- SPIRALES PLASTIQUES ----------------
    // -----------------------------------------------------
    /**
     * récupère les paliers de prix des spirales plastiques en fonction du nombre de pages
     *
     * @return array
     */
    public static function getPaliersSpiplast(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_spiplast');
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
    public static function getPalierSpiplastById(string $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_spiplast WHERE id = :id');
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
    public static function getPalierSpiplastPositionMax(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM paliers_spiplast');
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
    public static function setNewPalierSpiplast(string $palier, string $prix, string $max): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('INSERT INTO paliers_spiplast (palier, prix, position) VALUES (:palier, :prix, :position)');
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
    public static function updatePalierSpiplast(string $palier, string $prix, string $id): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE paliers_spiplast SET palier = :palier, prix = :prix WHERE id = :id');
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
    public static function delPalierSpiplast(string $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM paliers_spiplast WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
    }

    // ------------------------------------------------------
    // ---------------- SPIRALES METALLIQUES ----------------
    // ------------------------------------------------------
    /**
     * récupère les paliers de prix des spirales metalliques en fonction du nombre de pages
     *
     * @return array
     */
    public static function getPaliersSpimetal(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_spimetal');
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
    public static function getPalierSpimetalById(string $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_spimetal WHERE id = :id');
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
    public static function getPalierSpimetalPositionMax(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM paliers_spimetal');
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
    public static function setNewPalierSpimetal(string $palier, string $prix, string $max): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('INSERT INTO paliers_spimetal (palier, prix, position) VALUES (:palier, :prix, :position)');
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
    public static function updatePalierSpimetal(string $palier, string $prix, string $id): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE paliers_spimetal SET palier = :palier, prix = :prix WHERE id = :id');
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
    public static function delPalierSpimetal(string $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM paliers_spimetal WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
    }

        // --------------------------------------------------------
    // ---------------- RELIURES THERMOCOLLEES ----------------
    // --------------------------------------------------------
    /**
     * récupère les paliers de prix des spirales plastiques en fonction du nombre de pages
     *
     * @return array
     */
    public static function getPaliersThermo(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_thermo');
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
    public static function getPalierThermoById(string $id): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('SELECT * FROM paliers_thermo WHERE id = :id');
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
    public static function getPalierThermoPositionMax(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM paliers_thermo');
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
    public static function setNewPalierThermo(string $palier, string $prix, string $max): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('INSERT INTO paliers_thermo (palier, prix, position) VALUES (:palier, :prix, :position)');
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
    public static function updatePalierThermo(string $palier, string $prix, string $id): bool
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('UPDATE paliers_thermo SET palier = :palier, prix = :prix WHERE id = :id');
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
    public static function delPalierThermo(string $id)
    {
        $dbh = DbConnection::getConnection('administrateur');
        $stmt = $dbh->prepare('DELETE FROM paliers_thermo WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        // fermeture de la connexion
        DbConnection::disconnect();
    }
}
