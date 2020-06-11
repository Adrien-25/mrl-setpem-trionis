<?php 

use \Septem\Base\DataBase;
$req = DataBase::septem_query();
$count = DataBase::septem_count();
$pages = ceil($count/8);
var_dump($count);
?>
<div class="septem-container">
    <h2 class="septem-title">Gestion du Septem Trionis</h2>
    <div class="septem-headers">
        <div class="septem-header">Nom</div>
        <div class="septem-header">Ville</div>
        <div class="septem-header">Septem Hebdomadaire</div>
        <div class="septem-header">Septem Complet</div>
    </div>
    <?php while ( $data = $req->fetch() ): ?>
            <div class="septem-auditeurs">
                    <div class="septem-item"><?= htmlspecialchars(utf8_encode($data['nom'])) ?></div>
                    <div class="septem-item"><?= htmlspecialchars(utf8_encode($data['ville'])) ?></div>
                    <div class="septem-item"><?= htmlspecialchars(utf8_encode($data['septem_hebdo'])) ?></div>
                    <div class="septem-item"><?= htmlspecialchars(utf8_encode($data['septem_full'])) ?></div>
            </div>
    <?php endwhile?>
</div>