<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Inc\Base;

class BaseController
{

    public $plugin_path;
    public $plugin_url;
    public $plugin;
    public $managers_admin = array();

    public function __construct(){
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( realpath(__DIR__ . '/../..') );// link settings marche pas la con de ses mort

        $this->managers_admin = [
            'septem_trionis' => 'Septem Trionis'
        ];
    }

    public function activated(  string $key )
    {
        $option = get_option('mrl_plugin_septem');
        return isset($option[$key]) ? $option[$key] : false;

    }
}
;