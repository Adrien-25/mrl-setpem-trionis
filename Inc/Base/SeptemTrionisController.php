<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Inc\Base;

use \Inc\API\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\API\Callbacks\AdminCallbacks;

class SeptemTrionisController extends BaseController
{
    public $settings;
    public $callbacks;
    public $subpages = array();

    public $custom_post_types = [];

    public function register()
    {
        
        if ( ! $this->activated( 'septem_trionis' ) ) return;

        $this->callbacks = new AdminCallbacks;
        $this->settings = new SettingsApi;
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();

        add_shortcode( 'septemtrionis-front', [$this, 'septemtrionis_front'] );
    }

    public function septemtrionis_front()
    {
        ob_start();

        echo "<link rel=\"stylesheet\"  href=\"$this->plugin_url/assets/mrlseptemtrionis.css\"></link>";

        require_once( "$this->plugin_path/templates/septemtrionis-front.php" );

        return ob_get_clean();

    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' => 'mrl_plugin_septem',
                'page_title' => 'SeptemTrionis',
                'menu_title' => 'SeptemTrionis',
                'capability' => 'manage_options',
                'menu_slug' => 'septem_trionis',
                'callback' => [$this->callbacks, 'adminSeptemTrionis']
            ]
        ];
    }
}