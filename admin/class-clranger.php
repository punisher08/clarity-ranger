<?php
defined( 'ABSPATH' ) or die( "Access denied !" );

/**
 * adds custom post type sos to wordpress
 *
 * @package monster-seo-pro
 * @subpackage admin
 * @since 3.4.0
 * @author Marc Moeller
 *
 */
class Cl_Ranger {
    /**
     * add actions and filters
     *
     * @ since 3.4.0
     */
    public function setup () {
        add_action( 'admin_enqueue_scripts',
                array(
                        $this,
                        'add_clranger_scripts'
                ) );

        add_action( 'admin_notices',
                array(
                        $this,
                        'clranger_admin_notice'
                ) );

        add_action( 'admin_menu',
                array(
                        $this,
                        'add_admin_pages'
                ) );

    }

    /**
     * display invalid/duplicate locker message when locked added/updated
     *
     * @ since 3.4.0
     */
    function clranger_admin_notice ($message = false,$state = '') {
        if(!$message){return;}
        $html = <<<EOD
    <div class="$state dismiss"><p>$message</p></div>
EOD;
        echo $html;
    }

    /**
     * register and enqueue scripts
     *
     * @ since 3.4.0
     */
    function add_admin_pages () {
        define( "CLRANGER_PATHS", ['clarity-ranger'] );
        add_menu_page( 'Marc Moeller Clarity Ranger', 'Clarity Ranger', 'manage_options', 'clarity-ranger', array($this, 'clranger_internal_links_page') , CLARITY_URL . '/images/icon2.png' , 6 );
		
		add_submenu_page( 'clarity-ranger', 'Reverse Silo', 'Reverse Silo','manage_options', 'clarity-reverse-silo' ,array($this, 'clranger_reverse_silo_page') );
    }

	 function clranger_reverse_silo_page() {
  		 ob_start();
  		include('views/reverse-silo.php');
  		$output = ob_get_clean();
  		echo  $output; 
    }


    function clranger_internal_links_page() {
  		ob_start();
  		include('views/internallinks.php');
  		$output = ob_get_clean();
  		echo  $output;
    }

    /**
     * register and enqueue scripts
     *
     * @ since 3.4.0
     */
    function add_clranger_scripts () {
		
		$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
		$url = $base_url . $_SERVER["REQUEST_URI"];
		
        wp_enqueue_style( 'seo-bot-font', 'https://fonts.googleapis.com/css2?family=Oswald&display=swap' );


        if(isset($_GET['page'])){
        $page = $_GET['page'];
        if(in_array($page,CLRANGER_PATHS)){
      
        }
        }

        if(isset($_GET['page'])){
          $page = $_GET['page'];
          if($page == 'clarity-ranger'){
            wp_enqueue_script('script-name', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.0/sweetalert2.min.js' , array(), '10.12.0', true);
            wp_enqueue_style( 'style-sweetalert2', "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.0/sweetalert2.css" );
            wp_enqueue_style( 'font-awesome-5', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css' );

            wp_enqueue_style( 'clranger_ilink_style', CLARITY_URL . 'css/clranger-ilinks.css',
                  array(),
                  date("h:i:s")  );

            wp_register_script( 'clranger_ilink_script', CLARITY_URL . 'js/internallinks.js',
                    array(),
                     date("h:i:s"), true );

            wp_localize_script( 'clranger_ilink_script', 'myobject',
                    array(
                            'myajaxurl' => admin_url( 'admin-ajax.php' ),
							'import_backlinks_action' => admin_url('admin-ajax.php?action=import_backlinks_csv'),
							'current_url' => $url,
							'setting_hide_show' => array(
								
								'word_count' 	=> (get_option("word_count") == "false")? 0 : 1 ,
								'il_topic' 		=> (get_option("il_topic") == "false")? 0 : 1 ,
								'il_type' 		=> (get_option("il_type") == "false")? 0 : 1 ,
								'anchortext' 	=> (get_option("anchortext") == "false")? 0 : 1 ,
								'intl_incoming' => (get_option("intl_incoming") == "false")? 0 : 1 ,
								'backlinks_anchortext' 	=> (get_option("backlinks_anchortext") == "false")? 0 : 1 ,
								'backlinks' 	=> (get_option("backlinks") == "false")? 0 : 1 ,
								'total_links' 	=> (get_option("total_links") == "false")? 0 : 1 ,
								'intl_outgoing' => (get_option("intl_outgoing") == "false")? 0 : 1 ,
								'extl_links' 	=> (get_option("extl_links") == "false")? 0 : 1 
							)
                    ) );
            wp_enqueue_script('clranger_ilink_script');

            wp_enqueue_style( 'clranger_tabulator_style', CLARITY_URL . 'css/tabulator.min.css',
                  array(),
                 "4.8.4"  );
            wp_enqueue_script( 'clranger_tabulator_script', CLARITY_URL . 'js/tabulator.min.js',
                  array(),
                  "4.8.4"  );
          }
		  
		  
        }
		
	  if(isset($_GET['page'])){
		  $page = $_GET['page'];
		  if($page == 'clarity-reverse-silo'){
			  
			  
			    wp_enqueue_style( 'clranger_reverse_silo_style', CLARITY_URL . 'css/clranger-reverse-silo.css',array(),"4.8.4"  );
			  
				 wp_enqueue_script('script-name', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.0/sweetalert2.min.js' , array(), '10.12.0', true);
            wp_enqueue_style( 'style-sweetalert2', "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.0/sweetalert2.css" );
            wp_enqueue_style( 'font-awesome-5', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css' );
			  
				  wp_register_script( 'clranger_ilink_script', CLARITY_URL . 'js/reverse-silo.js',
						array(),
						 date("h:i:s"), true );

				wp_localize_script( 'clranger_ilink_script', 'objectsilo',
						array(
								'myajaxurl' => admin_url( 'admin-ajax.php' ),
								'tabulator_data' => $this->clranger_tabulator_silo(),
								'filter_rs_topic' => get_option( 'rs_topic' ),
								
						) );
				wp_enqueue_script('clranger_ilink_script');
				
				
				 wp_enqueue_style( 'clranger_tabulator_style', CLARITY_URL . 'css/tabulator.min.css',
                  array(),
                 "4.8.4"  );
				
            wp_enqueue_script( 'clranger_tabulator_script', CLARITY_URL . 'js/tabulator.min.js',
                  array(),
                  "4.8.4"  );
				
		  }
	  }
    }
	function clranger_tabulator_silo(){
		 $array = array();



	

		  $args = array(
			"post_type" => array('page' , 'post'),
			"post_status" => "publish" ,
			"posts_per_page" => -1
			);

			// Custom query.
			$query = new WP_Query( $args );
			  $countpage = 0;


		  if ( $query->have_posts() ) {
			  $topic = '';
			  while ( $query->have_posts() ) {
				$query->the_post();
				
				 $url = get_the_permalink();
				 $id = get_the_id();
				$rs_topic = get_post_meta(get_the_ID() ,'rs_topic' , true);
				$rs_type = get_post_meta(get_the_ID() ,'rs_type' , true);
				$rs_number = get_post_meta(get_the_ID() ,'rs_number' , true);
				
				
				$pUrl = parse_url($url);
				$post_content = get_the_content();

				if(defined('KC_VERSION') && defined('KC_VERSION') ){
					// echo "fasdf";
					  $post_content = get_post_meta(get_the_ID() ,'kc_raw_content' , true);
				}
				
				$doc = new DOMDocument;
      			@$doc->loadHTML($post_content);

				$xpath = new DOMXPath($doc);
				$links = $xpath->query('//a');

				
			  

				

				foreach ($links as $link) {
					$href = $link->getAttribute('href');
					$class = $link->getAttribute('class');
					$text =  trim($link->textContent);
					$postId = url_to_postid($href);
					
					
					// echo $class;
					preg_match_all('/\S+/', strtolower($link->getAttribute('rel')), $rel);

					$link_host = parse_url($href, PHP_URL_HOST);
					$orurl = parse_url($url, PHP_URL_PATH);

					$path = "";

					if($link_host == ""){
						$link_host =  $pUrl['host'];
						$path = parse_url($href, PHP_URL_PATH);
					}
					else
					{
						$path = parse_url($href, PHP_URL_PATH);
					}

					$current_host = $pUrl['host'];

					if ($link->hasAttribute('href') && !in_array('nofollow', $rel[0]) && $path != $orurl  && $class == "" && $link_host == $current_host && $href != "#" && $postId != 0)
					{
						
						$title_key = preg_replace("/\s+/", "_", strtolower($text));
						$title_key = preg_replace('/[^\x20-\x7E]/', '', $title_key);
						$currentcount = isset($linksoutgoing[$postId][$title_key]) ? $linksoutgoing[$postId][$title_key]['count'] : 0 ;
						$postId = url_to_postid($href);
						
						
						$postId_rs_number = get_post_meta($postId ,'rs_number' , true);
						$check_rs_topic = get_post_meta(get_the_ID() ,'rs_topic' , true);
						$postId_rs_type = get_post_meta($postId ,'rs_type' , true);
						
						//if()
							
						if($check_rs_topic != ""){
							$linksoutgoing[$rs_type][get_the_ID()]['rs_number'] = $rs_number;
							$linksoutgoing[$rs_type][get_the_ID()]['id'] = get_the_ID();
							$linksoutgoing[$rs_type][get_the_ID()]['urls'][] = array(
								'count' 	=>  ($currentcount + 1) , 
								'title'		=>  $text ,
								'rs_number' 	=>  $postId_rs_number , 
								'rs_type' 	=>  $postId_rs_type , 
								'url'		=>  $href ,
								'key'		=>  $title_key ,
								'origin_url'=>    $url
							); 
						} 
					 	/* $linksoutgoing[get_the_ID()][$title_key]['count'] =  ($currentcount + 1) ;
						$linksoutgoing[get_the_ID()][$title_key]['title'] =  $text ;
						$linksoutgoing[get_the_ID()][$title_key]['url'] =  $href ;
						$linksoutgoing[get_the_ID()][$title_key]['key'] =  $title_key ;	
						$linksoutgoing[get_the_ID()][$title_key]['origin_url'][get_the_ID()]['urls'][] =  $url ;
						$linksoutgoing[get_the_ID()][$title_key]['refer_page_url'] =  "" ;
						$linksoutgoing[get_the_ID()][$title_key]['refer_domain'] =   "" ;
						$linksoutgoing[get_the_ID()][$title_key]['domain_rate'] =   "" ; 
					 */

					
					}
					else{
						if($check_rs_topic != ""){
							
							$title_key = preg_replace("/\s+/", "_", strtolower($text));
							$title_key = preg_replace('/[^\x20-\x7E]/', '', $title_key);
							$currentcount = isset($linksoutgoing[$postId][$title_key]) ? $linksoutgoing[$postId][$title_key]['count'] : 0 ;
							$postId = url_to_postid($href);
							$postId_rs_number = get_post_meta($postId ,'rs_number' , true);
							$postId_rs_type = get_post_meta($postId ,'rs_type' , true);
							
								$linksoutgoing[$rs_type][get_the_ID()]['rs_number'] = $rs_number;
								$linksoutgoing[$rs_type][get_the_ID()]['id'] = get_the_ID();
								$linksoutgoing[$rs_type][get_the_ID()]['urls'][] = array(
									'count' 	=>  ($currentcount + 1) , 
									'rs_number' 	=>  $postId_rs_number , 
									'rs_type' 	=>  $postId_rs_type , 
									'title'		=>  $text ,
									'url'		=>  $href ,
									'key'		=>  $title_key ,
									'origin_url'=>    $url
								);
							}
					}
					
				}
				
			
				
				if(count($links) == 0  && $rs_topic != "" ){
					$linksoutgoing[$rs_type][get_the_ID()]['rs_number'] = $rs_number;
					$linksoutgoing[$rs_type][get_the_ID()]['id'] = get_the_ID();
					$linksoutgoing[$rs_type][get_the_ID()]['urls'] = array();
				}
				
				 $obj = [
				  "selectionid"=>"<input type='checkbox' name='id' style='border: 1px solid #7e89935c;border-radius: 2px!important;height: 16px!important;min-height: 16px;' value='".get_the_ID()."'>",
				  "url"=>$this->delete_domain_url($url),
				  "id"=>$id,
				  "rs_topic"=>$rs_topic,
				  "rs_number"=> $rs_number,
				  "rs_type"=> $rs_type,
				  "outl_counter"=>"",
				  "outl_outgoing"=> "",
				  "comment"=> "",
				];
				
				
				

					array_push($array, $obj);
			  }
			 
		  }
		  
		  foreach($linksoutgoing as $key => $value){
			   usort($linksoutgoing[$key], function($a, $b) {
				
					return  $a['rs_number']<=> $b['rs_number'];
				});	 
		  }
		  
		  
		  
				
			
		
		
	/* 	 echo "<pre>";
					print_r($linksoutgoing);
					 
					  echo "</pre>";    */
		  
		   foreach ($array as $key=>$value) {
			
			  $lkoutcount = 0;
			  $countsupport = 3;
			 $findkey = "";
			 $rs_type = $value['rs_type'];
			 $rs_topic = $value['rs_topic'];
			  // $postId = url_to_postid($value['url']);
			  $id = $value['id'];
			  
			  
			  if($rs_type != "")$findkey = array_search($id, array_column($linksoutgoing[$rs_type], 'id'));
			  
			  	
				 
			 	// echo $findkey."<br>";
				// echo $value['id']."<br>";
				
				 
				 // if($findkey < -1)echo "wala"."<br>";
				  // echo "----------<br>";
			// if(182 == $value['id'])die();	 
				 
				 
		
		    	if(isset($linksoutgoing[$rs_type][$findkey]) && $findkey > -1 ){
					 
					$countallsupport = count($linksoutgoing[$rs_type]);
					
					
					
					
					if( $findkey == 0){
						$countsupport = 2;
						
					}
					
					// echo $findkey ."!=". ($countallsupport - 1)."<br>";
					if($findkey == ($countallsupport - 1) ){
						$countsupport = 2;
					} 
					
					if($countallsupport == 1 ){
						$countsupport = 1;
					} 
				
					
					
					
					
					if(isset($linksoutgoing[$rs_type][$findkey]['urls'])){
				  
						$arr_correcturl = array();
						
						$prevkey = $findkey == 0 ? -1 : ($findkey - 1);
						$nextkey = $findkey + 1;
						
						
						if(isset($linksoutgoing[$rs_type][$prevkey])){
							$prevoutgoing =  $linksoutgoing[$rs_type][$prevkey];
						
							$prevoutgoingid = $prevoutgoing['id'];
							
							$arr_correcturl[] = $this->delete_domain_url(get_the_permalink($prevoutgoingid));
							
						}
						if(isset($linksoutgoing[$rs_type][$nextkey])){
							$nextoutgoing =  $linksoutgoing[$rs_type][$nextkey];
						
						
						
						
							$nextoutgoingid = $nextoutgoing['id'];
							
							$arr_correcturl[] = $this->delete_domain_url(get_the_permalink($nextoutgoingid));
							
						}
						if($rs_type == "money"){
							$firstsupportoutgoing =  $linksoutgoing['support'][0];
							$firstsupportoutgoingid = $firstsupportoutgoing['id'];
							
							$arr_correcturl[] = $this->delete_domain_url(get_the_permalink($firstsupportoutgoingid));
							
						}
						
						
						foreach($linksoutgoing['money'] as $moneykey => $moneyvalue){
							$moneyid = $moneyvalue['id'];
							
							$arr_correcturl[] = $this->delete_domain_url(get_the_permalink($moneyid));
						}
				
						 $lkoutcount = 0;
						 
						 
						
						 $arrayrowserror = array();
						 $activeurls = array();
						foreach($linksoutgoing[$rs_type][$findkey]['urls'] as $keydata => $valuedata) {
							   if(in_array($this->delete_domain_url($valuedata['url']), $arr_correcturl)){
								   
								   $lkoutcount++;
								   
								   
								   
								   $activeurls[] =$this->delete_domain_url($valuedata['url']);
								
								   
							   }
							   else{
								   
								   
								   $arrayrowserror[] = $keydata; 
								   
								   $activeurls[] =$this->delete_domain_url($valuedata['url']);
								   
							   }
							  
							   

						 }
						 
						 
						 $missingurl = array_diff($arr_correcturl,$activeurls);
						
							
							
							$tablecomment = "<ul>";
							
							
							
							
							
								foreach($linksoutgoing[$rs_type][$findkey]['urls'] as $keydata => $valuedata) {
									
									
								
									$firstChar = mb_substr($valuedata['rs_type'], 0, 1, "UTF-8");
									
									$firstChar = $firstChar != ""  ?"(".$firstChar.")" : "";
										
									$symbolsilo = $valuedata['rs_number'] !== "" && $firstChar != ""?  "(".$valuedata['rs_number'].")": $firstChar;
									
									
									 if(in_array($this->delete_domain_url($valuedata['url']), $arr_correcturl)){
										 
										 
										 
										 $tablecomment .= "<li> Correct  </li>";
										 
										 $array[$key]['outl_outgoing'] = $array[$key]['outl_outgoing'] .""."<li data-anchor-url='".$valuedata['url']."' data-anchor-text='".$valuedata['title']."' class='anchor-text-class'> <span style='display: block;font-weight: 400;' ><i class='fas fa-check check-green'></i> ".$this->delete_domain_url($valuedata['url'])." <b style='text-transform: capitalize;'>$symbolsilo</b> <b style='color: #000000;font-weight: 700;'> </b>  </span></li>" ;
										 
									 }
									else{
										
										$firstKey = array_key_first($missingurl);
										
										if($firstKey !== null && $value['rs_type'] != "money"){
											
											 $postId = url_to_postid($missingurl[$firstKey]);
											$rs_number = get_post_meta($postId ,'rs_number' , true);

												
											$missingsymbolsilo = $rs_number !== "" ? $rs_number: "M";
											
											
											
											$array[$key]['outl_outgoing'] = $array[$key]['outl_outgoing'] .""."<li  data-anchor-url='".$valuedata['url']."' data-anchor-text='".$valuedata['title']."' class='anchor-text-class'><span style='display: block;font-weight: 400;color:red;' ><i class='fas fa-circle dot-alert'></i>".$this->delete_domain_url($valuedata['url'])." <b style='text-transform: capitalize;'>$symbolsilo</b>  <b style='color: #000000;font-weight: 700;'> </b>  </span> </li>" ;
									
									
											
									
											$tablecomment .= "<li>   Should link to <a  style='color: #00b8ff;' href='$missingurl[$firstKey]'> $missingurl[$firstKey]  </a><b>($missingsymbolsilo)</b></li>";
												
											
											  unset($missingurl[$firstKey] );
											  
										}
										else{
											
											$urlvalue = $valuedata['url'];
											$link_host = parse_url($valuedata['url'], PHP_URL_HOST);
											$orurl = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
									
											if($link_host == ""){
												$link_host =  $pUrl['host'];
												
											}
											else
											{
												
											} 

											 
											
											if($_SERVER['HTTP_HOST'] == $link_host) $urlvalue = $this->delete_domain_url($urlvalue); 
											
											
											$array[$key]['outl_outgoing'] = $array[$key]['outl_outgoing'] .""."<li  data-anchor-url='".$valuedata['url']."' data-anchor-text='".$valuedata['title']."' class='anchor-text-class'><span style='display: block;font-weight: 400;color:red;' ><i class='far fa-times-circle' style='color:red;font-size: 14px;'></i>".$urlvalue ." <b style='text-transform: capitalize;'>$symbolsilo</b>  <b style='color: #000000;font-weight: 700;'> </b>  </span> </li>" ;
											
											$tablecomment .= "<li>No links to external websites from supporting pages  </li>";
										}
									
									}
								}
							
							
							
							
							foreach($missingurl as $missingdata){
								
								if($value['url'] == $missingdata)break;
									$postId = url_to_postid($missingdata);
									$rs_number = get_post_meta($postId ,'rs_number' , true);

										
									$missingsymbolsilo = $rs_number !== "" ? $rs_number: "M";
								
								
									$tablecomment .= "<li>   Should have a link to <a  style='color: #00b8ff;' href='$missingdata'> $missingdata  </a><b>($missingsymbolsilo)</b></li>"; 
							}
							
							$tablecomment .= "</ul>";
							
							if($value['rs_topic'] != "" && $value['rs_type'] != "")$array[$key]['comment'] =  $tablecomment; 
							
							
							
							if($value['rs_topic'] != "" && $value['rs_type'] != "")$array[$key]['outl_counter'] = $lkoutcount."/". $countsupport; 
							
							if($lkoutcount == $countsupport){
								$array[$key]['outl_counter'] = "<i class='fas fa-check check-green'></i>".$array[$key]['outl_counter'];
							}
							if($lkoutcount < $countsupport){
								$array[$key]['outl_counter'] = "<i class='fas fa-circle dot-alert'></i>".$array[$key]['outl_counter'];
							}
						  
						   $array[$key]['outl_outgoing'] = " <div ><div><ul>". $array[$key]['outl_outgoing']."</ul> </div> </div>";
					  }
					
					
				}  
				else{
					
					if($rs_type != "" && $rs_topic != ""){
						$countallsupport = count($linksoutgoing[$rs_type]);
						
						$lkoutcount = 0;
						
						
						
						if($value['rs_topic'] != "" && $value['rs_type'] != "")$array[$key]['outl_counter'] = $lkoutcount ."/". $countsupport; 
					
					}
				}
				
				
			  
			  
			 $array[$key]['url'] = "<a href='".$value['url']."' target='_blank'>".$value['url']."</a>";
			  /*  
			   */
			 
		  } 
		
			

      return json_encode($array);
    

    }//END clranger_get_tabular_one function

	

    function last_date_page_modified(){
		
			 $post_list = array(
				'post_type'      => array('page' , 'post'),
				'posts_per_page'    => 1,
				'orderby'        => 'modified',
				"post_status" => "publish" 
				) ;
				// Custom query.
				$query = new WP_Query( $post_list );
				 
				 
			  $last_update = null; 
				 
			  if ( $query->have_posts() ) {
				  while ( $query->have_posts() ) {
						$query->the_post();
						$last_update = get_post_modified_time("F d, Y g:i a");
				  }
				}
			wp_reset_postdata();
		
		return $last_update;	
			
	}
    function clranger_get_tabular_one(){
		
	  global $wpdb;
		
	  $getLastDatePageModified = $this->last_date_page_modified();
	  
	  
	  $tableIncomingList = $wpdb->prefix . "int_link_incoming_list" ;
	 $getLastUpdateIncomingList = $wpdb->get_row( "SELECT date FROM $tableIncomingList ORDER BY date desc LIMIT 1" , ARRAY_A );
		
		
		 
		
		if(!empty($getLastUpdateIncomingList) ){
			$getLastUpdateIncomingList =  $getLastUpdateIncomingList["date"];
		}
		else{
			$getLastUpdateIncomingList =  '2000-01-01 01:01:01';
		}
		//print_r($getLastUpdateIncomingList);
		
	/* 	echo  $datePageUpdated;
		echo  $dateIncomingListUpdated; */
		
		$dateIncomingListUpdated = new DateTime($getLastUpdateIncomingList);
		$datePageUpdated = new DateTime($getLastDatePageModified);
		
		
		
		
		
		
      $array = [];

  		$intlinksincomming = array();
		$extlinksoutgoing =  array();
		$extlinksoutgoinglisturl =  array();

	
	
	
	

      $args = array(
  		"post_type" => array('page' , 'post'),
  		"post_status" => "publish" ,
  		"posts_per_page" => -1
  		);

    	// Custom query.
    	$query = new WP_Query( $args );
		  $countpage = 0;


      if ( $query->have_posts() ) {
          $topic = '';
          while ( $query->have_posts() ) {
            $query->the_post();

            $topic = get_post_meta(get_the_ID() ,'il_topic' , true);
            $metaType = get_post_meta(get_the_ID() ,'il_type' , true);
            $backlinks = get_post_meta(get_the_ID() ,'backlinks' , true);

            $url = get_the_permalink();

      			$pUrl = parse_url($url);


      			$post_content = get_the_content();

				if(defined('KC_VERSION') && defined('KC_VERSION') ){
					// echo "fasdf";
					  $post_content = get_post_meta(get_the_ID() ,'kc_raw_content' , true);
				}
			


      			$doc = new DOMDocument;
      			@$doc->loadHTML($post_content);

  		      $xpath = new DOMXPath($doc);
            $links = $xpath->query('//a');

            $numIntLinksIncomming = 0;
            $numIntLinksOutgoing = 0;
            $numExtLinksCounter = 0;
          



            foreach ($links as $link) {
            	$href = $link->getAttribute('href');
            	$class = $link->getAttribute('class');
            	$text =  trim($link->textContent);
            	$postId = url_to_postid($href);

            	// echo $class;
            	preg_match_all('/\S+/', strtolower($link->getAttribute('rel')), $rel);

            	$link_host = parse_url($href, PHP_URL_HOST);
            	$orurl = parse_url($url, PHP_URL_PATH);

            	$path = "";

            	if($link_host == ""){
            		$link_host =  $pUrl['host'];
            		$path = parse_url($href, PHP_URL_PATH);
            	}
            	else
            	{
            		$path = parse_url($href, PHP_URL_PATH);
            	}

            	$current_host = $pUrl['host'];

            	if ($link->hasAttribute('href') && !in_array('nofollow', $rel[0]) && $path != $orurl  && $class == "" && $link_host == $current_host && $href != "#" && $postId != 0)
              {
            		// $numIntLinksIncomming++;
            		$title_key = preg_replace("/\s+/", "_", strtolower($text));
            		$title_key = preg_replace('/[^\x20-\x7E]/', '', $title_key);
            		$currentcount = isset($intlinksincomming[$postId][$title_key]) ? $intlinksincomming[$postId][$title_key]['count'] : 0 ;
            		//echo $currentcount; 2064

					
				
            		$intlinksincomming[$postId][$title_key]['count'] =  ($currentcount + 1) ;
            		$intlinksincomming[$postId][$title_key]['title'] =  $text ;
            		$intlinksincomming[$postId][$title_key]['url'] =  $href ;
					$intlinksincomming[$postId][$title_key]['key'] =  $title_key ;	
            		$intlinksincomming[$postId][$title_key]['origin_url'][get_the_ID()]['urls'][] =  $url ;
            		$intlinksincomming[$postId][$title_key]['refer_page_url'] =  "" ;
            		$intlinksincomming[$postId][$title_key]['refer_domain'] =   "" ;
            		$intlinksincomming[$postId][$title_key]['domain_rate'] =   "" ;

            		$numIntLinksOutgoing++;
					
					/* echo $title_key."<br><pre>";
					
					print_r($intlinksincomming);
					
					 if($text == "body_fat_transfer_breast_augmentation_cost��"){
						print_r($intlinksincomming);
						echo "<pre>";
						echo $href;
						echo "sample";
					}  */
					
						
            	}
              else
              {
				  
				/*   if()
				  
				  $extlinksoutgoing[$postId]["backlinks"]["title"] =   $text ;
						$extlinksoutgoing[$postId]["backlinks"]['href'] =  $href ;
						$extlinksoutgoing[$postId]["backlinks"]['url'] =  $url ;
						 */
            		if ($link->hasAttribute('href') && $link_host != $current_host && $href != "#" ){
						$numExtLinksCounter++;
						
						$number = rand();
						
						
						$hosthref = parse_url($href);
						
						
						//print_r( $hosthref['host'] ); 
						
					 	$removewww = preg_replace("/\www./", "", strtolower($hosthref['host']));
					 	$keybacklink = preg_replace("/\./", "_", strtolower($removewww));
					/* 	
						print_r( $keybacklink ); 
						echo "<br>"; */
						
						$extlinksoutgoing[get_the_ID()][$numExtLinksCounter]["backlinks"]["title"] =   $text ;
						$extlinksoutgoing[get_the_ID()][$numExtLinksCounter]["backlinks"]['href'] =  $href ;
						$extlinksoutgoing[get_the_ID()][$numExtLinksCounter]["backlinks"]['url'] =  $url ;
						$extlinksoutgoing[get_the_ID()][$numExtLinksCounter]["backlinks"]['key'] =  $keybacklink ;
						
						
						$extlinksoutgoinglisturl[$keybacklink][] = array(
							"title"  =>   $text ,
							'href' =>  $href ,
							'url' =>  $url ,
						);
						
						
					}

            		if ($link->hasAttribute('href') && !in_array('nofollow', $rel[0])  && $class == "" && $link_host == $current_host && $href != "#" && $postId != 0)$numIntLinksOutgoing++;
					
					
					
					
            	}

            }
			

			
			
			$classGreenType = "whitetype";
			if (substr($metaType, 0, 1) === 'M' || substr($metaType, 0, 1) === 'm') { 
					$classGreenType = "greentype";
			}
			if (substr($metaType, 0, 1) === 'S' || substr($metaType, 0, 1) === 's') { 
					$classGreenType = "orangetype";
			}
			if (substr($metaType, 0, 1) === 'P' || substr($metaType, 0, 1) === 'p') { 
					$classGreenType = "navybluetype";
			}
            if($metaType != "")$metaType  = "<span class='page-type ".$classGreenType ."' >".$metaType."</span>";




			

			/* echo get_the_title();
			echo "<br>"; */

            $obj = [
              "selectionid"=>"<input type='checkbox' name='id' style='border: 1px solid #7e89935c;border-radius: 2px!important;height: 16px!important;min-height: 16px;' value='".get_the_ID()."'>",
              "id"=>get_the_ID(),
              "url"=>$this->delete_domain_url($url),
              "il_topic"=>$topic,
              "word_count"=>str_word_count(strip_tags($post_content)), 
              "il_type"=> $metaType,
              "anchortext"=>"",
              "intl_incoming"=> "",
              "backlinks_anchortext"=> "",
              "backlinks"=>$backlinks,
              "total_links"=> 0 ,
              "intl_outgoing"=>$numIntLinksOutgoing,
              "extl_links"=> $numExtLinksCounter
            ];

            array_push($array, $obj);

          }//END WHILE
		
		//$intlinksincomming = $this->array_orderby($intlinksincomming, 'count', SORT_DESC);
		 /* echo "<pre>";
		
		 print_r($extlinksoutgoing);
		
		echo "</pre>";  */  
		// 30918899
		
  		foreach ($array as $key=>$value) {
			 $postId = url_to_postid($value['url']);
				
				
			/* Backlink List */
			if(!empty($value['url'])){
				/* 	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   {
						 $url = "https://"; 
					}				 
					else  {
						 $url = "http://";   
					// Append the host(domain name, ip) to the URL.  
					} */
					// $url = rtrim($_SERVER['HTTP_HOST'].$value['url'] , "/");   	
					$url =$value['url'];   	
					
					
					
					$tablebacklinkimport = $wpdb->prefix . "backlink_import" ;
					
					
				
					 $query = "SELECT * FROM $tablebacklinkimport WHERE `path_url` = '".$url."'";
					 /* print_r($query);
				echo "<br>"; */
					$backlink_import = $wpdb->get_results( $query, ARRAY_A );
					
				/* 	echo "<pre>";
						 print_r($query);
						 print_r($backlink_import);
						echo "</pre>";  */
					// echo count($backlink_import);
					
					
					
					
					$backlinkarray = array();
					
					
				/* 	$found = array_filter($backlink_import,function($v,$k) use ($backlinkarray){
						
						 print_r($v);
						 
						 echo "<br>fasdf<br>"; 
						 print_r($backlinkarray);
						 echo "<br>fasdf<br>"; 
						 echo empty($backlinkarray); 
						 echo "<br>fasdf<br>"; 
						if(!empty($backlinkarray) ){
							 $backlinkarray[]  = $v;
							 
							 
							 return true;
						}
						else{
							 return false;
						}
							echo "<pre>"; 
						print_r($backlinkarray);
							echo "</pre>"; 
					  
					},ARRAY_FILTER_USE_BOTH); */
					
					
					
					//echo "Start";
					 foreach($backlink_import as $keydata => $valuedata) {
						$isdublicate = true;
						foreach($backlinkarray as $backkey => $backvalue ){
							if( $backvalue['link_anchor'] ==  $valuedata['link_anchor']	 ){
								/* 	echo "<pre>"; 
						print_r($backvalue);
							echo "</pre>"; */
									$backlinkarray[$backvalue['id']]["count"]  = ($backlinkarray[$backvalue['id']]["count"] + 1);
									
									
									// echo $backlinkarray[$backvalue['id']]["id"];
									
									$backlinkarray[$backvalue['id']]['ids'][]  = $valuedata['id']; 
									
									/* echo "---------------------------<pre>";
									print_r($backlinkarray[$backvalue['id']]);
									echo "</pre>";   */  
									$isdublicate = false;
									break;
							} 
							else{
								    $isdublicate = true;
							}
						}
						if(empty($backlinkarray) || $isdublicate){
							$backlinkarray[$valuedata['id']]  = $valuedata;
							$backlinkarray[$valuedata['id']]["count"]  = 1;
							$backlinkarray[$valuedata['id']]['ids'][]  = $valuedata['id']; 
						}
						// $array[$key]['backlinks_anchortext'] = $array[$key]['backlinks_anchortext'] .""."<li class='backlink-anchor-text-class' data-backlink-id='".$valuedata['id']."' ><span style='display: block;font-weight: 400;' ><i class='fas fa-plus open-anchor-other-details'></i>".$valuedata['link_anchor']." <b style='color: #000000;font-weight: 700;'> </b> </span> <table class='backlinks-table hide-backlinks-table' style='width: 100%;'><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;
					
					}
					  
					  /* 	echo "---------------------------<pre>";
					print_r($backlinkarray);
					echo "</pre>";      */
					
					
					usort($backlinkarray, function($a, $b) {
						/* 	
							echo "intlinksincomming <pre>";	 
							print_r($a);
								echo "</pre>";   */
							return $b['count'] <=> $a['count'];
						});	
				
					$show_others = "";
					if(count($backlinkarray) > 1){
						  $show_others =  "<input type='checkbox' id='show-others' class='fas fa-angle-down'>";
					  }
					
					 foreach($backlinkarray as $keydata => $valuedata) {
						 $array[$key]['backlinks_anchortext'] = $array[$key]['backlinks_anchortext'] .""."<li class='backlink-anchor-text-class' data-backlink-id='".implode(",", $valuedata['ids'])."' ><span style='display: block;font-weight: 400;' ><i class='fas fa-plus open-anchor-other-details'></i>".$valuedata['link_anchor']." <b style='color: #000000;font-weight: 700;'>". $valuedata['count'] ."</b> </span> <table class='backlinks-table hide-backlinks-table' style='width: 100%;'><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;
					
					 }
					
					
					$array[$key]['backlinks_anchortext'] = " <div class='limit-items'> $show_others<div class='items'><ul>". $array[$key]['backlinks_anchortext']."</ul> </div>  </div>";
						 
					if(!empty($array[$key]['backlinks'] ) && $array[$key]['backlinks']  != ""){
						// $array[$key]['backlinks'] = ($array[$key]['backlinks'] + count($backlink_import));
						$array[$key]['backlinks'] = count($backlink_import);
					}
					else{
						$array[$key]['backlinks'] = count($backlink_import);
					}
			}	
				
		
			
			
			
			/*  if(isset($extlinksoutgoing[$value['id']])){
				 
				 	 	//
						
						 if(count($extlinksoutgoing[$value['id']]) > 1){
							  $show_others =  "<input type='checkbox' id='show-others' class='fas fa-angle-down'>";
						  }
					
						 foreach($extlinksoutgoing[$value['id']] as $keydata => $valuedata) {
							
							 $array[$key]['backlinks_anchortext'] = $array[$key]['backlinks_anchortext'] .""."<li class='backlink-anchor-text-class' data-backlink-id='".$value['id']."' data-backlink-unid='".$keydata."' ><span style='display: block;font-weight: 400;' ><i class='fas fa-plus open-anchor-other-details'></i>".$valuedata['backlinks']['title']." <b style='color: #000000;font-weight: 700;'> </b> </span> <table class='backlinks-table hide-backlinks-table' style='width: 100%;'><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;
						 }
						
						 
						  $array[$key]['backlinks_anchortext'] = " <div class='limit-items'>$show_others<div class='items'><ul>". $array[$key]['backlinks_anchortext']."</ul> </div>  </div>";
						 
			 } */
			
			
			
			
			 $array[$key]['url'] = "<a href='".$value['url']."' target='_blank'>".$value['url']."</a>";
				
			 if(isset($intlinksincomming[$postId])){
    			 $sum = array_sum(array_column($intlinksincomming[$postId], 'count'));
    			 // echo $sum;
				
				
				/* echo "<pre>";
		
				 print_r($intlinksincomming[$postId]);
				
				echo "</pre>";  */
				
    			
    			 $array[$key]['intl_incoming'] = $sum;

    			 $array[$key]['total_links'] = intval($array[$key]['backlinks']) + $sum;

    			 // echo  $array[$key]['total_links'];


				usort($intlinksincomming[$postId], function($a, $b) {
				/* 	
					echo "intlinksincomming <pre>";	 
					print_r($a);
						echo "</pre>";   */
					return $b['count'] <=> $a['count'];
				});				
				//echo "intlinksincomming <pre>";	 
				/* print_r($intlinksincomming[$postId]);
				
				
					
					
				echo "</pre>";   */
					

    			 foreach($intlinksincomming[$postId] as $keydata => $valuedata) {
					  $percentage = "";

					  $percentage = round($valuedata['count']  / ($sum / 100),2);
					
					
						
					//  $intlinksincomming[$postId][$title_key]['origin_url'][get_the_ID()]['intlinks_count'] =   $title_key ;
						
						
				
						
					if ( $datePageUpdated > $dateIncomingListUpdated || isset($_POST["refresh"])) {	
						if(isset($valuedata['origin_url'])){		
							 foreach($valuedata['origin_url'] as $origin_urlkey => $origin_urlvalue){
							
									
									
									 if(isset($intlinksincomming[$origin_urlkey])){
											$internallinksum = array_sum(array_column($intlinksincomming[$origin_urlkey], 'count'));
									
											$intlinksincomming[$postId][$keydata]['origin_url'][$origin_urlkey]['intlinks_count'] =   $internallinksum;
									 
									 }
									
									
							} 
						
						}
					}	
						
						
						
					  if( $percentage >= 80   ){
						$percentage = "(<b style='color:#fb0000;font-weight: 400;'>".$percentage."%</b>)";
						// $percentage = "<b style='color:#84ab0d'>".$percentage."%</b>";
					  }

					  if($percentage >= 40 && $percentage < 79){
						$percentage = "(<b style='color:#fba200;font-weight: 400;'>".$percentage."%</b>)";
						// $percentage = "<b style='color:#fba200'>".$percentage."%</b>";
					  }
					  if($percentage > 0 && $percentage < 39){
						$percentage = "(<b style='color:#84ab0d;font-weight: 400;'>".$percentage."%</b>)";
						// $percentage = "<b style='color:#fb0000'>".$percentage."%</b>";
					  }

					  if($valuedata['title'] != "" && $valuedata['title'] != null) {
						
						
						
						$array[$key]['anchortext'] = $array[$key]['anchortext'] .""."<li data-anchor-url='".$valuedata['url']."' data-anchor-text='".$valuedata['title']."' class='anchor-text-class'><span style='display: block;font-weight: 400;' ><i class='fas fa-plus open-anchor-other-details'></i>".$valuedata['title']." <b style='color: #000000;font-weight: 700;'>".$valuedata['count']." </b> ". $percentage ."  </span> <table class='int-list-from hide-anchor-list-from' style='width: 100%;'><thead><tr> <th>Int. L</th> <th>BL</th> <th>From</th> </tr></thead><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;
						
					


					  }else{

						$array[$key]['anchortext'] = $array[$key]['anchortext'] .""."<li data-anchor-url='".$valuedata['url']."' data-anchor-text='".$valuedata['title']."' class='anchor-text-class'><span style='display: block;font-weight: 400;' ><i class='fas fa-plus open-anchor-other-details'></i> ( No Text ) <b style='color: #000000;font-weight: 700;'>".$valuedata['count']." </b> ". $percentage ."  </span> <table class='int-list-from hide-anchor-list-from' style='width: 100%;'><thead><tr> <th>Int. L</th> <th>BL</th> <th>From</th> </tr></thead><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;
						
						

						//$array[$key]['anchortext'] = $array[$key]['anchortext'] .""."<li  data-anchor-url='".$valuedata['url']."' data-anchor-text='' class='anchor-text-class'><span style='display: block;font-weight: 100;color:red;' ><i class='fas fa-plus open-anchor-other-details'> Home "." <b style='color:#6e6893;font-weight: 700;'>".$valuedata['count']." ". $percentage ."  </b></span><table class='int-list-from hide-anchor-list-from' style='width: 100%;'><thead><tr> <th>Int. L</th> <th>BL</th> <th>From</th> </tr></thead><tbody style='border: #cececebf solid 1px;'> </tbody></table></li>" ;

					  }
							// if($keydata == "title") $array[$key]['anchortext'] =  $array[$key]['anchortext'] . "<br>". $data["title"] ;


    			 }

  				$show_others = "";
  				$show_all = " ";


				//echo count($intlinksincomming[$postId]);
				/* echo "<pre>";
				print_r($intlinksincomming);
				echo "</pre>"; */
				  if(count($intlinksincomming[$postId]) > 1){
				  $show_others =  "<input type='checkbox' id='show-others' class='fas fa-angle-down'>";
				  }
				  if(count($intlinksincomming[$postId]) > 3){
				   $show_all = " <i class='fas fa-file-alt hide-all-icon show-icon'><input type='checkbox' id='show-all'></i> <i class='fas fa-download hide-all-icon show-icon'><span style='font-weight: 100;font-family: sans-serif;font-size: 12px;font-style: normal;'>csv</span></i>";
				  }

				  $array[$key]['anchortext'] = " <div class='limit-items'>".$show_others."<div class='items'><ul>". $array[$key]['anchortext']."</ul> </div> ".$show_all ." </div>";

			}//END IF($intlinksincomming)
			
		
			/* 
		 */
			/* ////////////////////////////////////////////////
			////////////Update the table IncomingList//////////
			////////////////////////////////////////////////*/
			
			
		  /* 	echo "<pre>";
			print_r($extlinksoutgoinglisturl);
			echo "<br>";   */
			
			/* extlinksoutgoing
			extlinksoutgoinglisturl */
			
			
			/* 	echo "<pre>";
				print_r($extlinksoutgoinglisturl);
			print_r($extlinksoutgoing);
					 echo "</pre>"; */
			
			if ( $datePageUpdated > $dateIncomingListUpdated || isset($_POST["refresh"])) {
				
				
				    update_option( 'extlinksoutgoinglisturl', json_encode($extlinksoutgoinglisturl) );
				    
					
					//echo get_option( 'extlinksoutgoinglisturl' );
					
					$count = $wpdb->get_var("SELECT COUNT(*) as count FROM $tableIncomingList WHERE `page_id` = ".$value['id']."");
					
				
					
				
					
					
					if($count < 1  && isset($intlinksincomming[$postId])){
						
						$table = $wpdb->prefix.'int_link_incoming_list';
						$data = array('page_id' => $value['id'] , 'incoming_list_datas' => json_encode($intlinksincomming[$postId]) , 'date' => current_time( 'mysql' ));
						$format = array('%s','%s' , '%s' );
						$wpdb->insert($table,$data,$format);
					
					}
					else{
						if(isset($intlinksincomming[$postId])){
							$table = $wpdb->prefix.'int_link_incoming_list';
							$data = array( 'incoming_list_datas' => json_encode($intlinksincomming[$postId]) , 'date' => current_time( 'mysql' ));
							$wpdb->update($table, $data , array('page_id'=> $value['id'] ));
						}
						
					}
					
					
					
					
					
					
					
					/* $count = $wpdb->get_var("SELECT COUNT(*) as count FROM $tableIncomingList WHERE `page_id` = ".$value['id']."");
					
					if($count == 0  && isset($extlinksoutgoing[$value['id']])){
						
						$table = $wpdb->prefix.'int_link_incoming_list';
						$data = array('page_id' => $value['id'] , 'external_link_datas' => json_encode($extlinksoutgoing[$value['id']]) , 'date' => current_time( 'mysql' ));
						$format = array('%s','%s' , '%s' );
						$wpdb->insert($table,$data,$format);
					
					}
					else{
						if(isset($extlinksoutgoing[$value['id']])){
							$table = $wpdb->prefix.'int_link_incoming_list';
							$data = array( 'external_link_datas' => json_encode($extlinksoutgoing[$value['id']]) , 'date' => current_time( 'mysql' ));
							$wpdb->update($table, $data , array('page_id'=> $value['id'] ));
						}
					} */
					
					
					 
					
			}
			else {
				
					
					
			}
			 
			
			////////////////////////////////////////////////
			/////////End update the IncomingList////////////
			////////////////////////////////////////////////
			
			
			$array[$key]['url'] = "<span class='url-title-inside' style='color: black;font-size: 12px;".((get_option("url-title") != "false")?"display: block;":"display:none;")."'> " . get_the_title($value["id"]) .  "</span>"  . $array[$key]['url'];
			
			
      }
		  // angle-down
		
		

      echo json_encode($array);
      }//END if ( $query->have_posts() )

    }//END clranger_get_tabular_one function

/* 	function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	} */
     /**
       * Delete the domain from url string var
       *
       * @ since 3.4.0
       */
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





}
