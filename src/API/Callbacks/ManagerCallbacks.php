<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\API\Callbacks;

use \Septem\Base\BaseController;

class ManagerCallbacks extends BaseController
{
    public function checkboxSanitize( $input ){
        //crée un array output
        $output = [];

        /*Pour chaque éléments de l'array $managers_admin,
        vérifier si la checkbox a été coché*/
        foreach ( $this->managers_admin as $key => $value ) {
        $output[$key] = isset($input[$key]) ? true : false;
        }

        /*On retourne l'array de toute les fonctionalités avec le résultat "coché" ou non
        pour chaque fonctionalités*/
        return $output;
    }

    public function adminSectionManager(){
        echo "Cochez les sections qui vous intéresse pour les activer";
    }

    public function checkboxField( $args ){
        $option_name = $args[0]['option_name'];
        $name = $args[0]['label_for'];
        $classes = $args[0]['class'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;
        //on envoi l'input en HTML correspondant à chaque field de $managers_admin
        echo '<input type="checkbox" id="'. $name . '" name="' . $option_name . '[' . $name . ']' . '" value="1" class="' . $classes . '" ' . ($checked ? 'checked' : '' ) . '>';
    }
}