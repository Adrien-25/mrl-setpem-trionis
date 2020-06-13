<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\Model;

Use PDO;
Use Exception;

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

    public static function septem_query($count){
        $db = self::dbConnect();
        $currentPage = (int)($_GET['s'] ?? 1);
        $perPage = 8;
        $pages = ceil($count/$perPage);
        if($currentPage <= 0){
            throw new Exception('Numéro de page invalide');
        }
        if($currentPage > $pages){
            throw new Exception('Cette page n\'existe pas');
        }
        $offset = $perPage * ($currentPage-1);
        $req = $db-> query("SELECT nom, ville, septem_hebdo, septem_full FROM auditeurs_membres ORDER BY nom ASC LIMIT $perPage OFFSET $offset") ;
        return [$req, $pages, $currentPage];
    }

    public static function septem_count(){
        $db = self::dbConnect();
        $count = (int)$db-> query('SELECT COUNT(id) FROM auditeurs_membres')->fetch(PDO::FETCH_NUM)[0];
        return $count;
    }

    public static function septem_query2($start_form, $record_per_page){
        $db = self::dbConnect();
        $req = $db-> query("SELECT nom, ville, septem_hebdo, septem_full FROM auditeurs_membres ORDER BY nom ASC LIMIT $start_form, $record_per_page");
        return $req;
    }

}

