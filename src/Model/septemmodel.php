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

//Connection à la base de donnée
$connect = Database::dbConnect();

$limit = 8;                                     //Nombre limite de personne à afficher par page
$page = 1;                                      //Par défaut le numéro de la page est 1
$output = "";
if($_POST['page'] > 1 ){                        //Si le numéro d'une page a été séléctionné
    $start = (($_POST['page'] - 1) * $limit);   //À partir de cette auditeurs on commence à afficher
    $page = $_POST['page'];                     //Le numéro de la page sélectionné par l'utilisateur est entré dans la vairable page
} else {
    $start = 0;                                 //Si l'utilisateur n'a pas selectionné de page, on reste sur la page 1 et on récupère les donné depuis le premier auditeur
}

//On récupère les données voulu dans la table voulu
$query = "SELECT nom, ville, septem_hebdo, septem_full FROM auditeurs_membres ";

if(isset($_POST['query'])){                      //Si on a une query de l'utilisateur c'est qu'il a séléctionné une page ou recherche dan la searchbar
    $query .= 'WHERE nom LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
}

$query .= 'ORDER BY nom ASC ';
$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $connect->query($query);
$total_data = $statement->rowCount();
$statement = $connect->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
if($total_data > 0){
    foreach($result as $row){
        $output .= '
        <div class="septem-auditeurs">
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["nom"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["ville"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["septem_hebdo"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["septem_full"])).'</div>
        </div>
        ';
    }
} else {
    $output = '
    <p>Aucun auditeur avec ce nom</p>
    ';
}

$output .= '
<br/>
<div>
    <ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

if($total_links > 4){
    if($page < 5){
        for($count = 1; $count <= 5; $count++){
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    } else {
        $end_limit = $total_links - 4;

        if($page > $end_limit){
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++){
                $page_array[] = $count;
            }
        } else {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $page - 1; $count <= $page +1; $count++){
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
} else {
    for($count = 1; $count <= $total_links; $count++){
        $page_array[] = $count;
    }
}
for($count = 0; $count < count($page_array); $count++){
    if($page == $page_array[$count]){
        $page_link .= '
        <li class="page-item active">
            <a class="page-link active" href="#">'.$page_array[$count].'</a>
        </li>
        ';
        $previous_id = $page_array[$count] - 1;
        if($previous_id > 0){
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Précédent</a></li>';
        }else{
            $previous_link = '';
        }

        $next_id = $page_array[$count] + 1;
        if($next_id >= $total_links)
        {
            $next_link = '';
        }
        else
        {
            $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Suivant</a></li>';
        }
    }else{
        if($page_array[$count] == '...'){
            $page_link .= '
            <li>
                <p class="page-link dot">...</p>
            </li>
            ';
        }
        else{
            $page_link .= '
            <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
            ';
        }
    }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
    </ul>

</div>
';

echo $output;

