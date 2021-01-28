<?php
defined( 'ABSPATH' ) or die( "Access denied !" );

require_once CLARITY_PATH . 'admin/class-clranger.php';

/**
 * Class to load admin modules
 *
 * @package monster-seo-pro
 * @subpackage admin
 * @since 3.4.0
 * @author Marc Moeller
 *
 */
class Clranger_Admin {

    /**
     * initializes the admin modules
     *
     * @since 3.4.0
     */
    function setup () {
        $clranger = new Cl_Ranger();
        $clranger->setup();

        $clranger_ajax = new Cl_Ranger_Ajax();
        $clranger_ajax->setup();

    }
}
