<?php

class DbConnection
{

    // variables statiques
    private static $connection;

    // Pas de constructeur explicite


    // fonction de connexion à la BDD
    private static function connect(string $user_type)
    {

        $tParam = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/paramDB.ini', true); // avec sections
        extract($tParam[$user_type]);

        $dsn = "mysql:host=" . $host . "; port=" . $port . "; dbname=" . $bdd . "; charset=utf8";
        try {
            self::$connection = new PDO(
                $dsn,
                $user,
                $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) // Set Errorhandling to Exception
            );
        } catch (Exception $e) {
            // en cas erreur on affiche un message et on arrete tout
            die('<h4 style="color:#FF0000"; >Erreur de connexion ! </h4>');
        }
        return self::$connection;
    }


    // fonction de 'déconnexion' de la BDD
    public static function disconnect()
    {
        self::$connection = null;
    }


    // Pattern singleton
    public static function getConnection(string $user_type)
    {
        if (self::$connection != null) {
            return self::$connection;
        } else {
            return self::connect($user_type);
        }
    }
}
