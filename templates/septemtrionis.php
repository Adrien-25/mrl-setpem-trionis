<?php
include(__DIR__.'/../vendor/autoload.php');
use \Septem\Model\DataBase;
$connect = DataBase::dbConnect();
$podium = DataBase::septemPodium($connect);
$columnNames = ['Nom', 'Ville', 'Âge', 'Septem', 'Année', 'Numéro de semaine'];
?>
<div class='septem-containers'>
<h2 class="septem-title">Podium Septem Trionis</h2>
    <div class="septem-headers">
        <?php foreach($columnNames as $columnName):?>
            <div class="septem-header"><?= $columnName?></div>
        <?php endforeach?>
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
    <h2 class="septem-title">Liste des auditeurs</h2>
    <input type="text" name="auditeurSearch" id="auditeurSearch" placeholder="rechercher un auditeur">
    <div class="septem-headers">
        <?php foreach($columnNames as $columnName):?>
            <div class="septem-header"><?= $columnName?></div>
        <?php endforeach?>
    </div>
    <div id='septem-container'>
    
    </div>
</div>