<?php
defined( 'ABSPATH' ) or die( "Access denied !" );

/**
 * Plugin's ajax routines
 *
 * @package monster-seo-pro
 * @subpackage admin
 * @since 3.4.0
 * @author Marc Moeller
 *
 */
class Cl_Ranger_Ajax {

    /**
     * register ajax methods
     *
     * @ since 1.0.0
     */
    public function setup () {
      // register ajax
      add_action( 'wp_ajax_tabledata_pages',
        array(
          $this,
          'get_json_tabledata_pages'
        ) );

      add_action( 'wp_ajax_save_meta_silo',
        array(
                $this,
                'save_silo_ajax_function'
        ) );

		add_action( 'wp_ajax_internallinks_ajax',
        array(
                $this,
                'internallinks_ajax_function'
        ) );

      add_action( 'wp_ajax_bulk_update_type',
        array(
                $this,
                'bulk_update_type_ajax_functio'
        ) );
		
	   add_action( 'wp_ajax_from_url_details_anchor',
        array(
                $this,
                'from_url_details_anchor_func'
        ) );
		
		 add_action( 'wp_ajax_backlink_details',
        array(
                $this,
                'backlink_details_func'
        ) ); 
		
		
		add_action( 'wp_ajax_import_backlinks_csv',
        array(
                $this,
                'import_backlinks_csv_func'
        ) );
		
		add_action( 'wp_ajax_clarity_setting_hide_show',
        array(
                $this,
                'clarity_setting_hide_show_func'
        ) );
		
		
		 add_action( 'wp_ajax_istarget_ready',
        array(
                $this,
                'istarget_ready_func'
        ) );
		
		 add_action( 'wp_ajax_save_filter_silo',
        array(
                $this,
                'save_filter_silo_func'
        ) );
    }
	/* Save the filter of silo to stay in the searched */
	
	function save_filter_silo_func(){
		
		if(isset($_POST["filter_key"]) && isset($_POST["filter_value"])){
			update_option( $_POST["filter_key"] , $_POST["filter_value"] );
		}
		die();
	}
	
	/* Check if tartget already applied */
	
	function istarget_ready_func(){
		global $wpdb;


		if(!isset( $_POST['meta_key']) && !isset( $_POST['meta_value']))return;
		
		$tbl = $wpdb->prefix.'postmeta';
		$metakey = $_POST['meta_key'];
		$meta_value = $_POST['meta_value'];
	 	$prepare_guery = $wpdb->prepare( "SELECT post_id FROM $tbl where meta_key ='$metakey' and meta_value like '$meta_value'");
	
	
		$get_values = $wpdb->get_col( $prepare_guery ); 
		
		
		echo  json_encode($get_values);
		
		die();
		
	}
	
	 /* clarity setting hide/show column and page title*/
    function clarity_setting_hide_show_func(){
		
		
		if(isset($_POST['key']) && isset($_POST['meta_value']))update_option($_POST['key'], $_POST['meta_value'] );
			
		die();
	}
	 /* Import the backlinks from csv*/
    function import_backlinks_csv_func(){
		// echo "fasdf";
		
		
		if(isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != "")	{
		// echo "fasdf";
	 	 $hepler = new Clranger_Helper();
		
		global $wpdb;
		
		  $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
		  
		  fgetcsv($csvFile);
		  
		  
		   $optionbacklinkimport = array();
		  
		  
		 // $line = fgetcsv($csvFile);
		  
		  $i = 0;
		  
		 
		  while(($line = fgetcsv($csvFile)) !== FALSE){
			 
			   
			   $optionbacklinkimport[$i]['dr'] = $line[0];
			   $optionbacklinkimport[$i]['referring_page_url'] = $line[1];
			   $optionbacklinkimport[$i]['link_url'] = $line[2];
			   $optionbacklinkimport[$i]['link_anchor'] = $line[3];
			   $optionbacklinkimport[$i]['path_url'] = parse_url( $line[2], PHP_URL_PATH);
			  
			   
			   $i++;
		   }
		   
		    
			$table = $wpdb->prefix.'backlink_import';
			$rows = $optionbacklinkimport;
				
			$hepler->wpdb_bulk_insert($table, $rows , array("referring_page_url" , "link_url" , "link_anchor"));
			
			wp_redirect( $_POST['redirect']);  
			
			}
			else{
				
				wp_redirect( $_POST['redirect']);  
			}
		die();
	}



	/*Your Backlink Anchor Text will display details */
    function backlink_details_func(){
		
		global $wpdb;
		
		$html = "";
		
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			
			// echo $unid ;
			 $tableIncomingList = $wpdb->prefix.'backlink_import';
			
			 $arr_backlink_import = $wpdb->get_results("SELECT * FROM $tableIncomingList WHERE `id` IN (".$id.") ORDER BY `dr` DESC" , ARRAY_A);
			
			// print_r($arr_backlink_import);
			// group by `referring_page_url` , `link_anchor`
			
			
		
			
			
			
			foreach($arr_backlink_import as $key => $value){
				
			
				$html .= "<tr>";
				$html .= "<td>";
					$html .= "<b>DR </b>" . $value['dr'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "<a href='". $value['referring_page_url']."'>". $value['referring_page_url']."</a>";
					$html .= "</td>";
					
				$html .= "</tr>"; 
				
			}
			
			echo json_encode(array("html" => $html , "json" =>  json_encode($external_link_data) )); 
			
		}
		die();
	}
	 /*Your Anchor Text will find the related from of text url*/
    function from_url_details_anchor_func(){
		global $wpdb;
		
		$html = "";
		
		if(isset($_POST['url'])){
			// echo "fasdfs";
			 $url = $_POST['url'];
			 $title = $_POST['title'];
			 $postId = url_to_postid($url);
			 $tableIncomingList = $wpdb->prefix.'int_link_incoming_list';
			 
			
			 
			 
			 $incoming_list_datas = $wpdb->get_var("SELECT `incoming_list_datas` as count FROM $tableIncomingList WHERE `page_id` = ".$postId."");
			 
			
			
			$incoming_list_array = json_decode($incoming_list_datas, true);
			
			// print_r($incoming_list_array );
			
			$title_key = preg_replace("/\s+/", "_", strtolower($title));
			$title_key = preg_replace('/[^\x20-\x7E]/', '', $title_key);
			
			
			
			$key = array_search($title_key, array_column($incoming_list_array, 'key'));

			foreach($incoming_list_array[$key]['origin_url']  as $key => $value){
				
				$intlinks_count = $value['intlinks_count'];
				
			
				
				foreach($value['urls']  as $data){
					$html .= "<tr>";
					$html .= "<td style='width: 17.7554%;'>  ".$intlinks_count."</td>";
					$html .= "<td style='width: 20.938%;'> ".get_post_meta($key ,'backlinks' , true)." </td>";
					$html .= "<td style='width: 61.3066%;'> <a href='".$data."' target='_blank'> ".$this->delete_domain_url($data)." </a></td>";
					
					$html .= "</tr>";
					
				} 
			
			
			}
			
			echo json_encode(array("html" => $html , "json" =>  $incoming_list_array[$title_key ] ));
			
		}
		die();
	}
	function delete_domain_url($Url = false)
    {
    	if($Url)
    	{
    		$Url_Parts = parse_url($Url);
    		$Url = isset($Url_Parts['path']) ? $Url_Parts['path'] : '';
    		$Url .= isset($Url_Parts['query']) ? "?".$Url_Parts['query'] : '';
    	}

    	return $Url;
    }
    /*Your Internal Link Ajax Function if Needed. You can create as many as you want.*/
    function bulk_update_type_ajax_functio(){
      header('Content-Type: application/json');

      if(!isset($_POST["item"]) && !isset($_POST["input"]))die(json_encode(array("msg" => "Wrong Request!" , "success" => false)));

      $item = $_POST["item"];
      $input = $_POST["input"];
      $field = $_POST["field"];

      foreach($item as $key => $value){
      // print_r($value);
      update_post_meta( $value['value'] , $field , $input );
      }

      echo json_encode(array("msg" => "Bulk Update Success!" , "success" => true));

      die();
    }

	/* Save the meta of silo options and inputs */
	 function save_silo_ajax_function(){
		 $pid = url_to_postid($_POST['url']);
		 
		
		  if(isset($pid) && isset($_POST['name']))update_post_meta( $pid , $_POST['name'], $_POST['value'] ); 
		  
		  die();
		}


    function internallinks_ajax_function(){
      $pid = url_to_postid($_POST['url']);
      if(isset($pid) && isset($_POST['name']))update_post_meta( $pid , $_POST['name'], $_POST['value'] );
    }

    /*
    * Internal Links Page - Get all pages for tabulator
    * Since 3.4.0
    */
    function get_json_tabledata_pages(){
      $tabulator = new stdClass();
      $array = [];
      //$pages = file_get_contents( 'http://monsterseo.test/?clranger_get_tabular_one=true' );
    //  $obj = wp_remote_retrieve_body( $pages );
      //echo $pages;
    }

}
