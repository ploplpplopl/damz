<?php

require_once("dao/DbConnection.class.php");

class AdminGestionMgr
{
    /**
     * récupère les paliers de prix des spirales plastiques en fonction du nombre de pages
     *
     * @return array
     */
    public static function getPaliersSpiplast(): array
    {
        $dbh = DbConnection::getConnection('administrateur');
        $query = 'SELECT palier, prix FROM paliers_spiplast';
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // fermeture de la connexion
        DbConnection::disconnect();
        return $result;
    }

}