<?php
include(__DIR__.'/../vendor/autoload.php');
use \Septem\Model\DataBase;
$connect = DataBase::dbConnect();
$podium = DataBase::septemPodium($connect);
?>
<div class='septem-containers'>
<h2 class="septem-title">Gestion du Septem Trionis</h2>
    <div class="septem-headers">
        <div class="septem-header">Nom</div>
        <div class="septem-header">Ville</div>
        <div class="septem-header">Age</div>
        <div class="septem-header">Septem Hebdomadaire</div>
        <div class="septem-header">Année</div>
        <div class="septem-header">Numéro de semaine</div>
    </div>
    <div id='septem-podium'>
        <?php foreach($podium as $row):?>
            <div class="septem-auditeurs">
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["nom"]))?></div>
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["ville"]))?></div>
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["age"]))?></div>
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["nbAppelsSemaine"]))?></div>
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["annee"]))?></div>
                <div class="septem-item"><?= htmlspecialchars(utf8_encode($row["numSemaine"]))?></div>
            </div>
        <?php endforeach?>
    </div>
    <input type="text" name="auditeurSearch" id="auditeurSearch" placeholder="rechercher un auditeur">
    <div id='septem-container'>
    
    </div>
</div>