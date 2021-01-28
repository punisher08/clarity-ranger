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
class Clranger_Helper {


    static function get_current_option($key){
      $option_group = 'clranger_common_options';
      $options = get_option( $option_group );
      if(!is_array($options)){
        $options = [];
      }
      if(array_key_exists($key, $options)){
        return $options[$key];
      }else {
        return '';
      }
    }
	// Bulk inserts records into a table using WPDB.  All rows must contain the same keys.
	// Returns number of affected (inserted) rows.
	function wpdb_bulk_insert($table, $rows , $exist = "") {
		global $wpdb;
		
		// Extract column list from first row of data
		$columns = array_keys($rows[0]);
		asort($columns);
		$columnList = '`' . implode('`, `', $columns) . '`';

		// Start building SQL, initialise data and placeholder arrays
		$sql = "INSERT INTO `$table` ($columnList) VALUES\n";
		$placeholders = array();
		$data = array();
		$dataupdate = array();


		// Build placeholders for each row, and add values to data array
		foreach ($rows as $row) {
			ksort($row);
			$rowPlaceholders = array();

			$checkexist = "SELECT COUNT(*) as count FROM $table WHERE ";
			$count = 0;
			
			
			$stackvalue = array();
			$stackvalueupdate = array();
			$wherevalueupdate = array();
			
			foreach ($row as $key => $value) {
				
					$stackvalue[] = $value;
					$stackvalueupdate[$key] = $value;
					// $data[] = $value;
				    /* echo "<pre>";
				  print_r( $exist);
				  print_r( $key);
				   
					echo "</pre>"; */
					
					if (in_array($key, $exist) && !empty($exist)) {
						
						$checkexist .= "`$key` =  " . (is_numeric($value) ? "$row[$key]  AND" : "'$row[$key] ' AND");
						
						$wherevalueupdate[$key] = (is_numeric($value) ? "$row[$key]" : "$row[$key]");
					}
				
					// $count = $wpdb->get_var("SELECT COUNT(*) as count FROM $tableIncomingList WHERE `page_id` = ".$value['id']."");
					
					$rowPlaceholders[] = is_numeric($value) ? '%d' : '%s';
				
			}
		
		
			if(!empty($exist)){
					$checkexist = preg_replace('/\W\w+\s*(\W*)$/', '$1', $checkexist);
				//echo $checkexist;
					$count = $wpdb->get_var($checkexist); 
			}
			
			
			if($count == 0){
				
				$data = array_merge($data, $stackvalue);
				$placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
				
			}
			else{
				$dataupdate = $stackvalueupdate;  
				$placeholderupdate = $rowPlaceholders;
				
				
				
					 $wpdb->update( 
						$table, 
						$dataupdate, 
						$wherevalueupdate, 
						$placeholderupdate
					);  
				
			}
			
			
			
		
		  
		  
			
		}
		

		// Stitch all rows together
		$sql .= implode(",\n", $placeholders);

/*  echo "<pre>";
		print_r($sql); 
		print_r($dataupdate); 
		 */
		
		// Run the query.  Returns number of affected rows.
		
		
		if(!empty($data))$wpdb->query($wpdb->prepare($sql, $data)); 
		
		 
	}


}
