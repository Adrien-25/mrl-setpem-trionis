<?php
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

    public static function search_bar(){
        $db = self::dbConnect();
        $req = $db-> query("SELECT nom, ville, septem_hebdo, septem_full FROM auditeurs_membres WHERE nom LIKE '%".$_POST["search"]."%'");
        return $req;
    }

}
$req = DataBase::search_bar();
$output = '';
$row = $req->fetch();
if( count($row) > 0 ){
    while($row){
        $output .= '
        <div>'.$row["nom"].'</div>
        <div>'.$row["ville"].'</div>
        <div>'.$row["septem_hebdo"].'</div>
        <div>'.$row["septem_full"].'</div>
        '
    }
    echo $output;
} else {
    echo 'Aucun auditeur trouvé';
}
