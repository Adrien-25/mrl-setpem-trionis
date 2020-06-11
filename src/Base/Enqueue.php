<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\Base;

use \Septem\Base\BaseController;

class Enqueue extends BaseController
{

    public function register(){
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );//send the js and css
    }

    function enqueue(){
        wp_enqueue_style('septemstyle', $this->plugin_url . 'assets/septemstyle.css', __FILE__ );
        wp_enqueue_script('septemscript', $this->plugin_url . 'assets/septemscript.js', __FILE__ );
    }

}