<?php
/**
 * @package MRLSeptemTrionis
 */

/*
Plugin Name: MRL Septem Trionis
Description: Gére le compteur de Septem Trionis.
Version: 1.0.0
Author: Axel
*/

//check si le plugin est utilisé par wordpress
if( ! defined('ABSPATH') ){
    die( "You're wrong" );
}

//check si l'autoload est valide pour les namespace
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


//activation et deactivation du plugin

function activate_mrl_septem(){
    Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_mrl_septem' );

function deactivate_mrl_septem(){
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_mrl_septem' );


//initialisation du Plugin
if (class_exists( 'Inc\\Init' ) ) {
    Inc\Init::register_services();
}
