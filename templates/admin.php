<div class="wrap">
    <h1>MRL Septem Plugin</h1>
    <?php settings_errors()?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage Settings</a></li>
        <li><a href="#tab-2">ShortCodes</a></li>
        <li><a href="#tab-3">About</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <form method="POST" action="options.php">
                <?php
                    settings_fields( 'mrl_plugin_settings_septem' );
                    do_settings_sections( 'mrl_plugin_septem' );
                    submit_button();
                ?>
            </form>
        </div>
        <div id="tab-2" class="tab-pane">
            <h3>ShortCodes</h3>
            <h6>Copier/Coller le code de la section voulu pour pouvoir l'afficher sur la page</h6>
            <div>
                <p>Accueil SEPTEM TRIONIS: </br>[septemtrionis-front]</p>
            </div>
        </div>
        <div id="tab-3" class="tab-pane">
            <h3>About</h3>
        </div>
    </div>
</div>
