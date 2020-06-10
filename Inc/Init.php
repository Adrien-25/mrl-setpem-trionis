<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Inc;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */

    public static function get_services()
    {
        return [
            
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\SettingsLink::class,
            Base\SeptemTrionisController::class,
        ];
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */

    public static function register_services()
    {
        foreach ( self::get_services() as $class) {
            $service = self::instantiate( $class );
            if ( method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }
    }

    /**
     * Initialise the classe
     * @param class $class  class from the services array
     * @return class instance new instance of the class
     */


    private static function instantiate( $class )
    {
        return new $class;
    }
}

