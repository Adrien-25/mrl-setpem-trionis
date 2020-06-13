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
        wp_enqueue_script('septemscript', $this->plugin_url . 'assets/septemscript.js', __FILE__ , '', true);
        wp_localize_script( 'septemscript', 'my_ajax_object',
            array( 'ajax_url' => plugin_dir_url( dirname( __FILE__) ). 'Model/septemmodel.php'  ) );
        wp_localize_script( 'septemscript', 'my_ajax_object2',
            array( 'ajax_url_searchbar' => plugin_dir_url( dirname( __FILE__) ). 'Model/ajax-searchbar.php'  ) );
    }

}