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

// use \Septem\Model\DataBase;
$record_per_page = 8;
$page = '';
$output = '';

if( isset($_POST['page']) ){
    $page = $_POST["page"];
} else {
    $page = 1;
}

$start_form = ($page - 1) * $record_per_page;

$result = DataBase::septem_query2($start_form, $record_per_page);

while( $row = $result->fetch() ){
    $output .= '
        <div class="septem-auditeurs">
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["nom"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["ville"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["septem_hebdo"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["septem_full"])).'</div>
        </div>
    ';
}

$total_records = DataBase::septem_count();
$total_pages = ceil($total_records/$record_per_page);
for($i=1; $i<=$total_pages; $i++){
    $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>";
}

echo $output;