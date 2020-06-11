<?php
/**
 * @package MRLSeptemTrionis
 */

namespace Septem\Base;

class Deactivate
{
    public static function deactivate(){
        flush_rewrite_rules();
    }
}
