<?php 
						
	// if(isset($_POST))print_r($_POST);
	global $wpdb;
	  $tableIncomingList = $wpdb->prefix . "int_link_incoming_list" ;
		
	 $displayLastUpdate = $wpdb->get_row( "SELECT date FROM $tableIncomingList ORDER BY date desc LIMIT 1" , ARRAY_A );
	 
	 
	
	 
	if(!empty($displayLastUpdate) && !isset($_POST["refresh"]) ){
			$displayLastUpdate =  $displayLastUpdate["date"];
			
			
			
			$displayLastUpdate = new DateTime($displayLastUpdate);
		
			
			$later = new DateTime('now');
			
			$diff = $later->diff($displayLastUpdate)->format("%a");
			
			
			$countday = "";
			if($diff == 0)$countday = "<span class='daycountupdateclarity'>Updated</span>";
			if($diff > 0)$countday ="<span class='daycountupdateclarity'>Update : ". $diff . " day ago </span>";
			if($diff > 1)$countday ="<span class='daycountupdateclarity'>Update : ". $diff . " days ago </span>";
			
	}
	else{
		$countday = "<span class='daycountupdateclarity'>Updated</span>";
	}

?>

<div class="table_wrap">
	<div>
	    <div id="layoutSidenav_content">
	        <main>
	            <div class="container-fluid">
	            

	                <div class="card mb-4" style="max-width: 100%; padding: 0;">
	                    <div class="card-header">
	                        <div class="fitem title">

	                         <img src="<?php echo CLARITY_URL; ?>/images/ClarityRanger-Logo-darkbg.png" style="height: 15px;    width: 126.27906799316406px;    left: 532px;    top: 190px;    border-radius: 0px;"/>
							 
							
							
								<div  style="float: right;    display: inline-flex;">
									
									<form action="<?= $_SERVER['REQUEST_URI']; ?>" method="POST">
									
									  <label><?=$countday?></label>
									  <input type="submit" value="Refresh"  name="refresh" class="button button-secondary">
									   
									 
									</form> 
									<button id="clearfilters" class="button button-secondary">Clear All Filters</button> 
									<button id="Uploadbacklink" class="button button-secondary">Import Backlink</button> 
									
									<div>
										
									<a href="#" class="setting-row-size" style="background: #f3f5f6; color: #000; padding: 3px 13px 3px 14px; border-radius: 4px; display: inline-block; margin-left: 10px;"><i class="fas fa-arrows-alt-v"></i></a>
										
									<span class="dashicons dashicons-admin-generic setting-hide-show" style="    color: #0c2453;  cursor: pointer;  padding: 7px 9px;    display: inline-table;    background: #f3f5f6;position:relative;    margin-left: 7px;    border-radius: 4px;    font-size: 17px;">
									</span>
									
									<ul id="setting-tabulator" class="hide-setting-tabulator">
									<li class="url-title">
											<label ><span class="ant-checkbox ant-checkbox-url-title"><input type="checkbox" class="ant-checkbox-input" value="url-title" <?=(get_option("url-title") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>URL Title </span></label>
										</li>
									
										
									
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="il_topic" ="" <?=(get_option("il_topic") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Topic</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="il_type" ="" <?=(get_option("il_type") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Page Type</span></label>
										</li>
										
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="anchortext" ="" <?=(get_option("anchortext") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Anchor Text</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="intl_incoming" ="" <?=(get_option("intl_incoming") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Int. Link</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="backlinks_anchortext" ="" <?=(get_option("backlinks_anchortext") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Backlink Anchor Text</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="backlinks" ="" <?=(get_option("backlinks") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Backlinks</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="total_links" ="" <?=(get_option("total_links") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Total Links</span></label>
										</li>
										
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="intl_outgoing" ="" <?=(get_option("intl_outgoing") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Int. Links Out</span></label>
										</li>
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="extl_links" ="" <?=(get_option("extl_links") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Ext. Links Out</span></label>
										</li>
										
										<li>
											<label ><span class="ant-checkbox ant-checkbox-column "><input type="checkbox" class="ant-checkbox-input" value="word_count" <?=(get_option("word_count") == "false")?"":"checked"?>></span><span style="font-size: 0px;color: #b3b3b3;margin-right: 5px;margin-left: 3px;display: revert;">::</span><span>Word Count</span></label>
										</li>
										
										
										
										
									
										
										
										
										
										
									</ul>
									</div>
								</div>
	                        </div>
							
						
							
	                    </div>

						<?php
							   // print_r($this->clranger_get_tabular_one()); 
						?>

						<textarea id="clranger_tabular_data_text" class="clranger_tabular_data"><?php	echo $this->clranger_get_tabular_one();	?></textarea>

						<div id="internal-links"></div>



	                </div>
	            </div>
	        </main>

	    </div>
	</div>
</div>
