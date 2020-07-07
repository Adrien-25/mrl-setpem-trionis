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

    public static function septemPodium($connect){
        $podium = 'SELECT Auditeurs.idAuditeur, Auditeurs.idAuditeur, Auditeurs.nom, Auditeurs.ville, Auditeurs.age, Septem_Trionis.numSemaine, Septem_Trionis.nbAppelsSemaine, Septem_Trionis.annee FROM Auditeurs LEFT JOIN Septem_Trionis ON Auditeurs.idAuditeur = Septem_Trionis.auditeur_idAuditeur ORDER BY Septem_Trionis.nbAppelsSemaine DESC LIMIT 3';
        $req = $connect->query($podium);
        return $req->fetchAll();
    }

}

