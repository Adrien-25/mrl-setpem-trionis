<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\API\SettingsApi;
use \Inc\API\Callbacks\AdminCallbacks;
use \Inc\API\Callbacks\ManagerCallbacks;

class Dashboard extends BaseController
{
    public $settings;

    public $pages = array();

    public $callbacks;
    public $callbacks_mngr;

    public function register(){
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks;
        $this->callbacks_mngr = new ManagerCallbacks;

        //Création de la page principal du menu MRL Accueil
        $this->setPages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        // SettingsApi
        $this->settings->addPages( $this->pages )->subPage('Gestion Septem')->register();
    }

    public function setPages(){
        $this->pages = [
            [
                'page_title' => 'MRL Plugin Septem',
                'menu_title' => 'MRL Septem Trionis',
                'capability' => 'manage_options',
                'menu_slug' => 'mrl_plugin_septem',
                'callback' => [$this->callbacks, 'adminDashboard'],
                'icon_url' => 'dashicons-format-image',
                'position' => 5
            ]
        ];

    }

    public function setSettings(){

            $args = [
                [ 'option_group' => 'mrl_plugin_settings_septem',
                'option_name' => 'mrl_plugin_septem',
                'callback' => [$this->callbacks_mngr, 'checkboxSanitize']
                ]
            ];

            $this->settings->setSettings($args);
    }

    public function setSections(){
        //affichage du titre de la page suivi de la pharase decripive
        $args = [
            [
                'id' => 'mrl_admin_index_septem',
                'title' => 'Settings',
                'callback' => [$this->callbacks_mngr, 'adminSectionManager'],
                'page' => 'mrl_plugin_septem'
            ]
            ];

            $this->settings->setSections($args);
    }

    public function setFields(){

        $args = [];

        foreach ( $this->managers_admin as $key => $value) {
            $args[] = [
                'id' => $key,
                'title' => $value,
                'callback' => [$this->callbacks_mngr, 'checkboxField'],
                'page' => 'mrl_plugin_septem',
                'section' => 'mrl_admin_index_septem',// ID du setSection()
                'args' => [
                    [
                        'option_name' => 'mrl_plugin_septem',
                        'label_for' => $key,
                        'class' => 'example-classe'
                    ]
                ]
            ];
        }

            $this->settings->setFields($args);
    }

}