<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\Base;

Use PDO;

class DataBase
{  
    
    public static function dbConnect(){ 
        $credentials = (include('dbcredentials.php'));
        try
        {
                $db = new PDO($credentials['dsn'], $credentials['user'], $credentials['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_ERRMODE => PDO::FETCH_ASSOC
                ]);
                return $db;
        }
        catch(Exception $e)
        {
                die('Erreur : '.$e->getMessage());
        }
    }

    public static function septem_query(){
        $db = self::dbConnect();
        $req = $db-> query('SELECT nom, ville, septem_hebdo, septem_full FROM auditeurs_membres ORDER BY nom ASC LIMIT 8') ;
        return $req;
    }

    public static function septem_count(){
        $db = self::dbConnect();
        $count = (int)$db-> query('SELECT COUNT(id) FROM auditeurs_membres')->fetch(PDO::FETCH_NUM)[0];
        return $count;
    }

}