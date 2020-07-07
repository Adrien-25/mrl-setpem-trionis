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
use Septem\Base;
use Septem\Base\Activate;
use Septem\Base\Deactivate;
//check si le plugin est utilisé par wordpress
if( ! defined( 'ABSPATH' ) ){
    die;
}

//check si l'autoload est valide pour les namespace
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


//activation et deactivation du plugin

function activate_mrl_septem(){
    Activate::activate();
}
register_activation_hook( __FILE__, 'activate_mrl_septem' );

function deactivate_mrl_septem(){
    Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_mrl_septem' );


//initialisation du Plugin
if (class_exists( 'Septem\\Init' ) ) {
    \Septem\Init::register_services();
}
