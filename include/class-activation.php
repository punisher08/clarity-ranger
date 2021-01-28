<?php
defined( 'ABSPATH' ) or die( "Access denied !" );

/**
 * Helper and Util functions
 *
 * @package monster-seo-pro
 * @subpackage include
 * @since 3.4.0
 * @author Marc Moeller
 *
 */
class Clranger_activation {


    static function int_link_incoming_list(){
		global $wpdb;
		 $table_name = $wpdb->prefix . 'int_link_incoming_list';
		 $sql = "CREATE TABLE $table_name (
		 id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
		 page_id mediumint(9) NOT NULL,
		 incoming_list_datas varchar(10000) NOT NULL,
		 external_link_datas varchar(100000) NOT NULL,
		 date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		 PRIMARY KEY  (id)
		 );";
		 
		 
		  $table_name = $wpdb->prefix . 'backlink_import';
		  $sql .= "CREATE TABLE $table_name (
		 id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
		 dr mediumint(9) NOT NULL,
		 referring_page_url varchar(1000) NOT NULL,
		 link_url varchar(1000) NOT NULL,
		 path_url varchar(1000) NOT NULL,
		 link_anchor varchar(1000) NOT NULL,
		 date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		 PRIMARY KEY  (id)
		 );";

		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );
    }



}
