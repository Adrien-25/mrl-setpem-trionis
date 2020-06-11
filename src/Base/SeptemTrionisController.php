<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\Base;

use \Septem\API\SettingsApi;
use \Septem\Base\BaseController;
use \Septem\API\Callbacks\AdminCallbacks;

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