<?php
include('../../vendor/autoload.php');

use \Septem\Model\DataBase;
//Connection à la base de donnée
$connect = Database::dbConnect();
$weekSeptem = date('W', strtotime("-1 week"));  //Renvoi la semaine actuel moins 1
$actualWeek = date('W');                        //Numéro de semaine
$year = date('Y');                              //Année actuel
$limit = 8;                                     //Nombre limite de personne à afficher par page
$page = 1;                                      //Par défaut le numéro de la page est 1
$output = "";
if($_POST['page'] > 1 ){                        //Si le numéro d'une page a été séléctionné
    $start = (($_POST['page'] - 1) * $limit);   //À partir de cette auditeurs on commence à afficher
    $page = $_POST['page'];                     //Le numéro de la page sélectionné par l'utilisateur est entré dans la vairable page
} else {
    $start = 0;                                 //Si l'utilisateur n'a pas selectionné de page, on reste sur la page 1 et on récupère les donné depuis le premier auditeur
}
$weekCheck = $connect->query("SELECT numSemaine FROM Septem_Trionis LIMIT 1")->fetch(PDO::FETCH_NUM)[0];

if($weekSeptem == $weekCheck){
    $historic = 'INSERT INTO Historique_Septem (numSemaine, nbAppelsSemaine, annee, auditeur_idAuditeur) SELECT numSemaine, nbAppelsSemaine, annee, auditeur_idAuditeur FROM Septem_Trionis';
    $connect->query($historic);
    $deleteSeptem = 'DELETE FROM Septem_Trionis';
    $connect->query($deleteSeptem);
}
//On récupère les données voulu dans la table voulu
$query = "SELECT Auditeurs.idAuditeur, Auditeurs.idAuditeur, Auditeurs.nom, Auditeurs.ville, Auditeurs.age, Septem_Trionis.numSemaine, Septem_Trionis.nbAppelsSemaine, Septem_Trionis.annee FROM Auditeurs LEFT JOIN Septem_Trionis ON Auditeurs.idAuditeur = Septem_Trionis.auditeur_idAuditeur ";

//Si on a un $_POST['query'] de l'utilisateur c'est qu'il recherche dans la searchbar
if(isset($_POST['query']) && $_POST['query'] !== ''){  
    //On récupère la valeur du $_Post et on check si les valeurs entrées correspondend à une valeur de la colonne nom, wille ou age dans la base de donnée                     
    $query .= 'WHERE (nom LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" or ville LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" or age LIKE "%'.str_replace(' ', '%', $_POST['query']).'%")'; 
}


//On vérifie si l'utilisateur à voulu ajouter ou enlever 1pts dans le septem_hebdo, puis on update sa valeur dans la base de donnée
if(isset($_POST['update_value']) && isset($_POST['update_id']) && $_POST['update_value'] !== ''){
    /*Si l'auditeur à une valeur de 0 dans sa colonne Septem et qu'on essaye d'incrémenter cette valeur
    Alors on ajoute l'auditeur dans la table septem*/
    if(($_POST['actual_value']) == 0 && $_POST['actual_value'] < $_POST['update_value']){
        $insert = 'INSERT INTO Septem_Trionis (numSemaine, nbAppelsSemaine, annee, auditeur_idAuditeur) VALUE ('.$actualWeek.', '.$_POST['update_value'].', '.$year.', '.$_POST["update_id"].');';
        $connect->query($insert);
    }else{
        /*Si l'auditeur à une valeur autre que 0 et qu'on essaye d'incrémenter ou de décrémenter
        Alors on update la valeur*/
        $update = 'UPDATE Septem_Trionis SET nbAppelsSemaine = '.$_POST["update_value"].' WHERE auditeur_idAuditeur = '.$_POST["update_id"];
        $connect->query($update);
        if($_POST['update_value'] == 0){
            /*Si la valeur qu'on a update est 0
            Alors on suprime l'utilisateur de la table septem */
            $delete = 'DELETE FROM Septem_Trionis WHERE nbAppelsSemaine = 0';
            $connect->query($delete);
        }
    }
} 


//les auditeurs sont classé par nombre de passe dans le setptem hebdo
$query .= 'ORDER BY Auditeurs.nom ASC ';
//On définit les auditeurs à afficher depuis le $_POST['page']
$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $connect->query($query);
$total_data = $statement->rowCount();
$statement = $connect->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();

//S'il y a plus d'un auditeurs de récupéré, on les affiches
if($total_data > 0){
    foreach($result as $row){
        $output .= '
        <div class="septem-auditeurs">
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["nom"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["ville"])).'</div>
                <div class="septem-item">'.htmlspecialchars(utf8_encode($row["age"])).'</div>
                <div class="septem-item septem-controls">
                    <div class="minus '.htmlspecialchars(utf8_encode($row["idAuditeur"])).' js_control">-</div>
                    <input type="text" class="septem-hebdo" id="'.htmlspecialchars(utf8_encode($row["idAuditeur"])).'" value="'.(($row["nbAppelsSemaine"]) ? htmlspecialchars(utf8_encode($row["nbAppelsSemaine"])) : 0).'" disabled>
                    <div class="plus '.htmlspecialchars(utf8_encode($row["idAuditeur"])).' js_control">+</div>
                </div>
                <div class="septem-item">'.(($row["annee"]) ? htmlspecialchars(utf8_encode($row["annee"])) : 0).'</div>
                <div class="septem-item">'.(($row["numSemaine"]) ? htmlspecialchars(utf8_encode($row["numSemaine"])) : 0).'</div>
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

//On calcule le nombre total de page qu'il doit y avoir
$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

if($total_links > 4){
    //Si la page actuelle est inferieur à 5, alors l'affichage est 1 2 3 4 5 ... dernière_page
    if($page < 5){
        for($count = 1; $count <= 5; $count++){
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    } else {
        $end_limit = $total_links - 4;

        //Si la page actuelle fais parti des 5 dernières, alors l'affichage sera 1 ... dernière_page-4 dernière_page-3 dernière_page-2 dernière_page-1 dernière_page
        if($page > $end_limit){
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++){
                $page_array[] = $count;
            }
        } else {
            //Si la page actuelle ne se trouve ni dans les 5 premières ni dans les 5 dernières alors l'affichage est 1 ... page_actuelle-1 page_actuelle page_actuelle+1 ... dernière_page
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

//Resultat en HTML
for($count = 0; $count < count($page_array); $count++){
    //On ajoute la class css active à la page actuelle pour la différencier des autres
    if($page == $page_array[$count]){
        $page_link .= '
        <li class="page-item septem-active">
            <a class="page-link js_active" data-active_page='.$page_array[$count].' href="#">'.$page_array[$count].'</a>
        </li>
        ';
        //Bouton page précédente
        $previous_id = $page_array[$count] - 1;
        if($previous_id > 0){
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Précédent</a></li>';
        }else{
            $previous_link = '';
        }

        //Bouton page suivante
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

