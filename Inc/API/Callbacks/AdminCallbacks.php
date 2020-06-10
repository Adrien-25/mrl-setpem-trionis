<?php
/**
 * @package MRLSeptemTrionis
 * 
 * On récupère les différent templates correspondant aux sous-pages du menu MRL Accueil
 * dans le Dashboard
 */

namespace Inc\API\Callbacks;

use \Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    //Récupération de l'accueil
    public function adminDashboard(){
        return require_once( "$this->plugin_path/templates/admin.php");
    }

    public function adminSeptemTrionis(){
        return require_once( "$this->plugin_path/templates/septemtrionis.php");
    }

}
