<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/public
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Professionals_List_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public $vp_body;
	public $vp_title;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		switch(get_option('ech_pl_get_style')) {
			case 'ec_vet': 
				// vet list style
				wp_enqueue_style( $this->plugin_name.'_vet', plugin_dir_url( __FILE__ ) . 'css/ech-vet-list.css', array(), $this->version, 'all' );

				// single vet style
				if( strpos($_SERVER['REQUEST_URI'], "healthcare-professionals/professional-profile") !== false)  {
					wp_enqueue_style( $this->plugin_name.'_vet_profile', plugin_dir_url( __FILE__ ) . 'css/ech-vet-profile.css', array(), $this->version, 'all' );
				}

				// vet cates & tags style
				if( strpos($_SERVER['REQUEST_URI'], "healthcare-professionals/specialty-categories") !== false)  {
					wp_enqueue_style( $this->plugin_name.'_vet_cate_tag_list', plugin_dir_url( __FILE__ ) . 'css/ech-vet-category-list.css', array(), $this->version, 'all' );
				}

				break;

			default: // all and dr only
				// all list style
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ech-professionals-list-public.css', array(), $this->version, 'all' );

				// single dr style
				if( strpos($_SERVER['REQUEST_URI'], "healthcare-professionals/professional-profile") !== false)  {
					wp_enqueue_style( $this->plugin_name.'_dr_profile', plugin_dir_url( __FILE__ ) . 'css/ech-pl-profile.css', array(), $this->version, 'all' );
				}

				// dr cates & tags style
				if( strpos($_SERVER['REQUEST_URI'], "healthcare-professionals/specialty-categories") !== false)  {
					wp_enqueue_style( $this->plugin_name.'_dr_cate_tag_list', plugin_dir_url( __FILE__ ) . 'css/ech-pl-category-list.css', array(), $this->version, 'all' );
				}


		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ech-professionals-list-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-pagination', plugin_dir_url( __FILE__ ) . 'js/ech-pl-pagination.js', array( 'jquery' ), $this->version, false );

	}


	
	/******************************** SHORTCODE FUNCTIONS ********************************/

	public function echpl_display_profess_list($atts) {
		$paraArr = shortcode_atts(array(
			'ppp' => get_option('ech_pl_ppp'),
			'channel_id' => 4,
			'dr_type' => null,
			'spec_id' => null,
			'brand_id' => null
		), $atts);

		$ppp = (int)$paraArr['ppp'];
		$channel_id = (int)$paraArr['channel_id'];
		$dr_type = strtolower(htmlspecialchars(str_replace(' ', '', $paraArr['dr_type'])));
		$spec_id = (int)$paraArr['spec_id'];
		$brand_id = (int)$paraArr['brand_id'];
		
		
		if ( $dr_type != null ) {
			$getDisplayDrType = $dr_type;
		} else {
			$getDisplayDrType = get_option( 'ech_pl_display_dr_type' );
		}

		$currDrType = '';

		switch($getDisplayDrType) {
			case 'vet':
				$currDrType = $this->ECHPL_get_dr_type_id('Vet');
				break;
			case 'dentist':
				$currDrType = $this->ECHPL_get_dr_type_id('Dentist');
				break;

			default: // display all
				$currDrType = $this->ECHPL_get_dr_type_id('Doctor');
		}
		
		

	
		$api_args = array(
			'page_size'=>$ppp,
			'channel_id' => $channel_id,
			'product_category_id' => $currDrType,
			'specialty_id' => $spec_id,
			'brand_id' => $brand_id
		);

	
		$api_link = $this->ECHPL_gen_profList_api_link($api_args);

		$output = '';
		
		$output .= '<div class="ech_dr_big_wrap">'; 
		$output .= '<div class="echdr_page_anchor"></div>'; // anchor
		
		
		/*********** FITLER ************/
		$output .= '<div class="ech_dr_filter_container">';		

		switch($getDisplayDrType) {
			case 'vet':
				$output .= $this->ECPL_get_dr_type($getDisplayDrType);
				$output .= $this->ECHPL_get_vet_regions();
				break;

			case 'dentist':
				$output .= $this->ECPL_get_dr_type($getDisplayDrType);
				$output .= $this->ECHPL_get_spec($getDisplayDrType);
				break;
			
			case 'dr':
				$output .= $this->ECPL_get_dr_type($getDisplayDrType);
				$output .= $this->ECHPL_get_spec($getDisplayDrType);
				break;
			
			default: 
				$output .= $this->ECPL_get_dr_type($getDisplayDrType);
				$output .= $this->ECHPL_get_vet_regions(); // this will only shown in "Vet"
				$output .= $this->ECHPL_get_spec($getDisplayDrType);
		}

		$output .= '<div class="dr_filter_btn_container"><div class="dr_filter_btn">'.$this->ECHPL_echolang(['Submit', '提交', '提交']).'</div></div>';
		$output .= '</div>'; //ech_dr_filter_container
		/*********** (END) FITLER ************/
		
		
		/*********** POST LIST ************/
		$output .= '<div class="ech_dr_container" >';
		$get_drList_json = $this->ECHPL_curl_json($api_link);
		$json_arr = json_decode($get_drList_json, true);
		/*** loading div ***/
		$output .= '<div class="loading_div"><p>'. $this->ECHPL_echolang(['Loading...','載入中...','载入中...']).'</p></div>';
		/*** (end) loading div ***/

		$output .= '<div class="all_drs_container" data-ppp="'.$ppp.'" data-channel="'.$channel_id .'" data-brandid="" data-region="" data-specialty="" data-drtype="'.$currDrType.'">';
			foreach ($json_arr['result'] as $dr) {
				$output .= $this->ECHPL_load_card_template($dr);
			}
		$output .= '</div>'; //all_posts_container


		/*** pagination ***/
		$total_posts = $json_arr['count'];
		$max_page = ceil($total_posts/$ppp);
		
		
		$output .= '<div class="ech_dr_pagination" data-current-page="1" data-max-page="'.$max_page.'" data-topage="" data-ajaxurl="'. get_admin_url(null, 'admin-ajax.php') .'"></div>';

		$output .= '</div>'; //ech_dr_container

		/*********** (END) POST LIST ************/

		$output .= '</div>'; //ech_dr_big_wrap


		return $output;
	} //echpl_display_profess_list

	


	/**************************************
	 * Display dr list by spec by brand
	 **************************************/
	public function ECHLP_display_profess_list_by_spec_by_brand($atts) {
		$paraArr = shortcode_atts(array(
			'ppp' => get_option('ech_pl_ppp'),
			'channel_id' => 4,
			'dr_type' => null,
			'spec_id' => null,
			'brand_id' => null
		), $atts);

		$ppp = (int)$paraArr['ppp'];
		$channel_id = (int)$paraArr['channel_id'];
		$dr_type = strtolower(htmlspecialchars(str_replace(' ', '', $paraArr['dr_type'])));
		$spec_id = (int)$paraArr['spec_id'];
		$brand_id = (int)$paraArr['brand_id'];


		if ($dr_type == null) {
			return '<div class="code_error">shortcode error - dr_type not specified</div>';
		}
		if ($spec_id == null && $brand_id == null) {
			return '<div class="code_error">shortcode error - spec_id / brand_id not specified</div>';
		}

		
		switch($dr_type) {
			case 'vet':
				$currDrType = $this->ECHPL_get_dr_type_id('Vet');
				break;
			case 'dentist':
				$currDrType = $this->ECHPL_get_dr_type_id('Dentist');
				break;

			default: // display all
				$currDrType = $this->ECHPL_get_dr_type_id('Doctor');
		}

		$api_args = array(
			'page_size'=>$ppp,
			'channel_id' => $channel_id,
			'product_category_id' => $currDrType,
			'specialty_id' => $spec_id,
			'brand_id' => $brand_id
		);

		$api_link = $this->ECHPL_gen_profList_api_link($api_args);
		$get_drList_json = $this->ECHPL_curl_json($api_link);
		$json_arr = json_decode($get_drList_json, true);


		$output = '';
		$output .= '<div class="echpl_by_spec_container">';
			foreach ($json_arr['result'] as $dr) {
				$output .= $this->ECHPL_load_card_template($dr);
			}
		$output .= '</div>'; // echpl_by_spec_container


		$total_posts = $json_arr['count'];
		$max_page = ceil($total_posts/$ppp);

		if ($max_page > 1) {
			$output .= '<div class="echlp_load_more_container">';
				$output .= '<div class="loading_text">'.$this->ECHPL_echolang(['Loading...','請稍等...','请稍等...']).'</div>';
				$output .= '<div class="echlp_load_more_btn" data-maxpage="'.$max_page.'" data-ppp="'.$ppp.'" data-currpage="1" data-channel="'.$channel_id.'" data-drtype="'.$currDrType.'" data-specid="'.$spec_id.'" onclick="ECHDrBySpec_load_more_dr()">'.$this->ECHPL_echolang(['Load More', '更多', '更多']).'</div>';
			$output .= '</div>'; // echlp_load_more_container
		}

		return $output;
	} // ECHLP_display_profess_list_by_spec


	/******************************** (end)SHORTCODE FUNCTIONS ********************************/




	/************************************
	 * Load more posts function
	 ************************************/
	public function ECHPL_load_more_dr() {
		$ppp = $_POST['ppp'];
		$toPage = $_POST['toPage'];
		$channel_id = $_POST['filterChannel'];
		$brand_id = $_POST['filterBrand'];
		$filterRegion = $_POST['filterRegion'];
		$filterSp = $_POST['filterSp'];
		$filterDrType = $_POST['filterDrType'];
	
		$api_args = array(
			'page_size'=>$ppp,
			'page' => $toPage,
			'channel_id' => $channel_id,
			'brand_id' => $brand_id,
			'region' => $filterRegion,
			'specialty_id' => $filterSp,
			'product_category_id' => $filterDrType
		);
		$api_link = $this->ECHPL_gen_profList_api_link($api_args); 
	
		$get_dr_json = $this->ECHPL_curl_json($api_link); 
		$json_arr = json_decode($get_dr_json, true);
		
		$html = '';
		$max_page = '';
	
		if(isset($json_arr['result']) && $json_arr['count'] != 0 ) {
			$total_posts = $json_arr['count'];
			$max_page = round($total_posts/$ppp, 0);
	
			foreach ($json_arr['result'] as $dr) {
				$html .= $this->ECHPL_load_card_template($dr);
			}
		} else {
			$html .= $this->ECHPL_echolang(['No result ...' , '沒有結果', '没有结果']);
		}

		echo json_encode(array('html'=>$html, 'max_page' => $max_page), JSON_UNESCAPED_SLASHES);
	
		wp_die();
	}



	/***=========================== FILTERS ===========================***/
	public function ECHPL_get_vet_regions() {
		$vetTypeID = $this->ECHPL_get_dr_type_id('Vet');

		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_therapist_location_list?region_key=香港特别行政区&channel_id=4&product_category_id=' . $vetTypeID;
		$get_regions_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_regions_json, true);


		$html = '';

		$html .= '<div class="filter_regions_container" data-drtype="vet">';
			$html .= '<div class="echdr_filter_dropdown_checkbox">';
				$html .= '<lable class="anchor">'.$this->ECHPL_echolang([ 'Select Region', '選擇地區', '选择地区']).'</lable>';
				$html .= '<ul class="echdr_dropdown_checkbox_list">';
					$html .= '';
					foreach($json_arr['result'] as $region) {
						$html .= '<li><label class="echdr_dropdown_checkbox_item"><input type="checkbox" name="region" class="region" value="'.$region['id'].'" data-region-value="" /> '.$this->ECHPL_echolang([ $region['en_atitle'], $region['tc_atitle'], $region['atitle']]).'</lable></li>';
					}
				$html .= '</ul>';
			$html .= '</div>';
		$html .= '</div>'; //filter_regions_container

		return $html;
	}

	public function ECHPL_get_spec($getDisplayDrType) {
		$drTypeID = $this->ECHPL_get_dr_type_id('Doctor');
		$vetTypeID = $this->ECHPL_get_dr_type_id('Vet');
		$dentistTypeID = $this->ECHPL_get_dr_type_id('Dentist');


		switch($getDisplayDrType) {
			case 'vet':
				$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$vetTypeID;
				break;
			case 'dentist':
				$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$dentistTypeID;
				break;

			default: // display all
				$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$drTypeID;
		}
		

		$get_sp_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_sp_json, true);

		$html = '';

		$html .= '<div class="filter_spec_container">';
			$html .= '<select name="spec" class="filter_spec">';
			$html .= '<option value="">'.$this->ECHPL_echolang(['All Specialty', '全部範圍', '全部范围']).'</option>';
			foreach($json_arr['result']['result'] as $sp) {
				$html .= '<option value="'.$sp['forever_specialty_id'].'">'.$this->ECHPL_echolang([ $sp['en_name'], $sp['tc_name'], $sp['cn_name'] ]).'</option>';
			}
			$html .= '</select>';
		$html .= '</div>'; //filter_spec_container

		return $html;
	}


	public function ECPL_get_dr_type($getDisplayDrType){
		$drTypeID = $this->ECHPL_get_dr_type_id('Doctor');
		$vetTypeID = $this->ECHPL_get_dr_type_id('Vet');
		$dentistTypeID = $this->ECHPL_get_dr_type_id('Dentist');

		
		$html = '';
		$html .= '<div class="filter_drType_container">';

		switch($getDisplayDrType) {
			case 'vet':
				$html .= '<input type="hidden" value="'.$vetTypeID.'" class="filter_drType" />';
				break;
			case 'dentist':
				$html .= '<input type="hidden" value="'.$dentistTypeID.'" class="filter_drType" />';
				break;
			case 'dr':
				$html .= '<input type="hidden" value="'.$drTypeID.'" class="filter_drType" />';
				break;

			default: // display all
				$selectedDr = false;
				$selectedVet = false;
				$selectedDentist = false;
				switch($getDisplayDrType) {
					case 'vet':
						$selectedVet = true;
						break;
					case 'dentist':
						$selectedDentist = true;
						break;
					default: 
						$selectedDr = true;
				}
				
				$html .= '<select name="dr_type" class="filter_drType">';
					$html .= '<option data-drtype="dr" value="'.$drTypeID.'" '. ($selectedDr ? 'selected': '') .'>'.$this->ECHPL_echolang(['Doctor', '醫生', '医生']).'</option>';
					$html .= '<option data-drtype="dentist" value="'.$dentistTypeID.'" '. ($selectedDentist ? 'selected': '') .'>'.$this->ECHPL_echolang(['Dentist', '牙醫', '牙医']).'</option>';			
					$html .= '<option data-drtype="vet" value="'.$vetTypeID.'" '. ($selectedVet ? 'selected': '') .'>'.$this->ECHPL_echolang(['Vet', '獸醫', '兽医']).'</option>';			
				$html .= '</select>';
				
		}

		$html .= '</div>'; //filter_spec_container

		return $html;
	}



	public function ECHPL_filter_dr_list() {
		$ppp = $_POST['ppp'];
		$filter_spec = $_POST['filter_spec'];
		$filter_region = $_POST['filter_region'];
		$filter_drType = $_POST['filter_drType'];
	
		$api_args = array(
			'page_size'=>$ppp,
			'specialty_id' => $filter_spec,
			'region' => $filter_region,
			'product_category_id' => $filter_drType
		);
		$api_link = $this->ECHPL_gen_profList_api_link($api_args);
		
	
		$get_dr_json = $this->ECHPL_curl_json($api_link);
		$json_arr = json_decode($get_dr_json, true);
		$html = '';
	
		
		$max_page = '';
		if(isset($json_arr['result']) && $json_arr['count'] != 0 ) {
			$total_posts = $json_arr['count'];
			//$max_page = round($total_posts/$ppp, 0);
			$max_page = ceil($total_posts/$ppp);
			foreach ($json_arr['result'] as $post) {
				$html .= $this->ECHPL_load_card_template($post);
			}
		} else {
			$html .= $this->ECHPL_echolang(['No result ...' , '沒有結果', '没有结果']);
		}
		
		echo json_encode(array('html'=>$html, 'max_page' => $max_page, 'api' => $api_link), JSON_UNESCAPED_SLASHES);
	
		wp_die();
	}


	public function ECHPL_update_spec_options() {
		$typeID = $_POST['filter_drType'];

		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$typeID;

		$get_sp_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_sp_json, true);

		$html = '';
		$html .= '<option value="">'.$this->ECHPL_echolang(['All Specialty', '全部範圍', '全部范围']).'</option>';
		foreach($json_arr['result']['result'] as $sp) {
			$html .= '<option value="'.$sp['forever_specialty_id'].'">'.$this->ECHPL_echolang([ $sp['en_name'], $sp['tc_name'], $sp['cn_name'] ]).'</option>';
		}

		echo $html;
	}


	/***=========================== (END)FILTERS ===========================***/




	/***=========================== API LINKS ===========================***/
	
	/*********************************************
	 * Based on the dashboard settings, get api domain
	 *********************************************/
	public function ECHPL_get_api_domain() {
		$getApiEnv = get_option( 'ech_pl_apply_api_env' );
		if ( $getApiEnv == "0") {
			$api_domain = 'https://globalcms-api-uat.umhgp.com';
		} else {
			$api_domain = 'https://globalcms-api.umhgp.com';
		}

		return $api_domain;
	}


	/********************************************************************************
	 * Get platform ID API parameter. Used in basic_doctor and basic_doctor_list API
	 ********************************************************************************/
	public function ECHPL_get_API_platformID() {
		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/publishPlatformListByName?platform_name=BLOG';
		$get_platformID_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_platformID_json, true);
	
		$platformID = $json_arr['result']['result'][0]['platform_id'];
		return $platformID;
	}

	/****************************************
	 * Filter and merge value and return a full API Doctor List link. 
	 * Array key: page, page_size, channel_id, specialty_id, brand_id, region
	 ****************************************/
	public function ECHPL_gen_profList_api_link(array $args) {
		

		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/basic_doctor_list?platform_id='.$this->ECHPL_get_API_platformID();

		if(!empty($args['page'])) {
			$full_api .= '&';
			$full_api .= 'page=' . $args['page'];
		} else {
			$full_api .= '&';
			$full_api .= 'page=1';
		}


		if(!empty($args['blog_status'])) {
			$full_api .= '&';
			$full_api .= 'blog_status=' . $args['blog_status'];
		} else {
			$full_api .= '&';
			$full_api .= 'blog_status=1';
		}
	
	
		if(!empty($args['page_size'])) {
			$full_api .= '&';
			$full_api .= 'page_size=' . $args['page_size'];
		} else {
			$full_api .= '&';
			$full_api .= 'page_size=12';
		}
	
	
		if(!empty($args['product_category_id'])) {
			$full_api .= '&';
			$full_api .= 'product_category_id=' . $args['product_category_id'];
		} 
		
		
		if(!empty($args['channel_id'])) {
			$full_api .= '&';
			$full_api .= 'channel_id=' . $args['channel_id'];
		} else {
			$full_api .= '&';
			$full_api .= 'channel_id=4';
		}
	
	
		if(!empty($args['specialty_id'])) {
			$full_api .= '&';
			$full_api .= 'specialty_id=' . $args['specialty_id'];
		}
	
		if(!empty($args['brand_id'])) {
			$full_api .= '&';
			$full_api .= 'brand_id=' . $args['brand_id'];
		}
	
		if(!empty($args['region'])) {
			$full_api .= '&';
			$full_api .= 'region=' . $args['region'];
		}

		

		return $full_api;
	}

	public function ECHPL_gen_single_dr_api_link(array $args){
		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/basic_doctor?platform_id='.$this->ECHPL_get_API_platformID();
	
		if(!empty($args['therapistid'])) {
			$full_api .= '&';
			$full_api .= 'therapistid=' . $args['therapistid'];
		} 
	
		if(!empty($args['personnel_id'])) {
			$full_api .= '&';
			$full_api .= 'personnel_id=' . $args['personnel_id'];
		}
	
		if(!empty($args['version'])) {
			$full_api .= '&';
			$full_api .= 'version=' . $args['version'];
		} 
	
		return $full_api;
	} 


	public function ECHPL_get_specialty_name($sp_id) {
		$full_api = $this->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4';
		$get_spList_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_spList_json, true);
	
		$spListArr = $json_arr['result']['result'];
		// search sp id and get its array key
		$key = array_search($sp_id, array_column($spListArr, 'forever_specialty_id'));
		
		$en_name = $spListArr[$key]['en_name'];
		$zh_name = $spListArr[$key]['tc_name'];
		$sc_name = $spListArr[$key]['cn_name'];
		
		return json_encode(array('en'=>$en_name, 'zh' => $zh_name, 'sc'=>$sc_name), JSON_UNESCAPED_SLASHES);
	}


	public function ECHPL_get_dr_type_id($type_name) {
		/* $full_api = $this->ECHPL_get_api_domain() . '/v1/api/basic_product_category_list?get_type=4&channel_id=4&level=1';
		$get_pc_json = $this->ECHPL_curl_json($full_api);
		$json_arr = json_decode($get_pc_json, true);

		$pcArr = $json_arr['result'];
		// search product categroy name and get its array key
		$key = array_search($type_name, array_column($pcArr, 'en_name'));

		$prodCateID = $pcArr[$key]['product_category_id']; */

		$getApiEnv = get_option( 'ech_pl_apply_api_env' ); 

		$getVetPcID_live = get_option( 'ech_pl_vet_pcid_live' );
		$getDrPcID_live = get_option( 'ech_pl_dr_pcid_live' );
		$getDentistPcID_live = get_option( 'ech_pl_dentist_pcid_live' );

		$getVetPcID_dev = get_option( 'ech_pl_vet_pcid_dev' );
		$getDrPcID_dev = get_option( 'ech_pl_dr_pcid_dev' );
		$getDentistPcID_dev = get_option( 'ech_pl_dentist_pcid_dev' );


		if ( $getApiEnv == "0") {
			// UAT
			switch(strtolower($type_name)) {
				case 'vet':
					$prodCateID = $getVetPcID_dev;
					break;
				case 'dentist':
					$prodCateID = $getDentistPcID_dev;
					break;
				default: // doctor
					$prodCateID = $getDrPcID_dev;
			}
		} else {
			// LIVE
			switch(strtolower($type_name)) {
				case 'vet':
					$prodCateID = $getVetPcID_live;
					break;
				case 'dentist':
					$prodCateID = $getDentistPcID_live;
					break;
				default: // doctor
					$prodCateID = $getDrPcID_live;
			}
		}

		return $prodCateID;
	}
	
	/***===========================(END) API LINKS ===========================***/

	
	/****************************************
	 * Load Single Post Template
	 ****************************************/
	public function ECHPL_load_card_template($dr) {
		$html = '';

		$avatar = $dr['avatar'];
		if($avatar == "") { $avatar = "../assets/img/medical-team.png"; }

		/***** SPECIALTY *****/
		$spArrEn = array();
		$spArrZH = array();
		$spArrSC = array();
		foreach($dr['specialty_list'] as $sp) {
			array_push($spArrEn, array('type'=>'sp', 'sp_id'=>$sp['specialty_id'], 'sp_name'=> $sp['en_name']) );
			array_push($spArrZH, array('type'=>'sp', 'sp_id'=>$sp['specialty_id'], 'sp_name'=> $sp['tc_name']));
			array_push($spArrSC, array('type'=>'sp', 'sp_id'=>$sp['specialty_id'], 'sp_name'=> $sp['cn_name']));
		}
		/***** (END) SPECIALTY *****/

		/***** Check Name  *****/
		$en_name = [
			1 => $dr['en_salutation'] ." ". $dr['en_name'],
			2 => $dr['en_name'].$dr['en_salutation'],
		];
		$tc_name = [
			1 => $dr['tc_salutation'] ." ". $dr['tc_name'],
			2 => $dr['tc_name'].$dr['tc_salutation'],
		];
		$cn_name = [
			1 => $dr['cn_salutation'] ." ". $dr['cn_name'],
			2 => $dr['cn_name'].$dr['cn_salutation'],
		];
		/***** (END)Check Name  *****/

		$html .= '<div class="single_dr_card">';
		
			$html .= '<div class="dr_avatar"><a href="'.site_url().'/healthcare-professionals/professional-profile/'.$dr['therapistid'].'"><img src="'.$avatar.'" /></a></div>';
			// $html .= '<div class="dr_name"><a href="'.site_url().'/healthcare-professionals/professional-profile/'.$dr['therapistid'].'">'.$this->ECHPL_echolang([ $dr['en_salutation'].' '.$dr['en_name'], $dr['tc_name'].$dr['tc_salutation'],  $dr['cn_name'].$dr['cn_salutation']]).'</a></div>';
			$html .= '<div class="dr_name"><a href="'.site_url().'/healthcare-professionals/professional-profile/'.$dr['therapistid'].'">'.$this->ECHPL_echolang([$en_name[$dr['en_is_pre']], $tc_name[$dr['tc_is_pre']], $cn_name[$dr['cn_is_pre']]]).'</a></div>';
			$html .= '<div class="specialty"><strong>'.$this->ECHPL_echolang(['Specialist','專科','专科']).': </strong>'.$this->ECHPL_echolang([$this->ECHPL_apply_comma_from_array($spArrEn), $this->ECHPL_apply_comma_from_array($spArrZH), $this->ECHPL_apply_comma_from_array($spArrSC)]).'</div>';
			
		$html .= '</div>'; //single_dr_card

		return $html;
	}


	



	/**************************************
	 * Translation function
	 **************************************/
	public function ECHPL_echolang($stringArr) {
		global $TRP_LANGUAGE;
	
		switch ($TRP_LANGUAGE) {
			case 'zh_HK':
				$langString = $stringArr[1];
				break;
			case 'zh_CN':
				$langString = $stringArr[2];
				break;
			default:
				$langString = $stringArr[0];
		}
	
		if(empty($langString) || $langString == '' || $langString == null) {
			$langString = $stringArr[1]; //zh_HK
		}
	
		return $langString;
	
	}

	/****************************************
	 * This function is used to create a comma sparated list from an array for list categories / tags display
	 ****************************************/
	public function ECHPL_apply_comma_from_array($langArr) {
		$prefix = $commaList = '';

		foreach($langArr as $itemArr) {
			if($itemArr['type'] == 'sp') {
				$commaList .= $prefix . '<a href="'.site_url().'/healthcare-professionals/specialty-categories/'.$itemArr['sp_id'].'">' . $itemArr['sp_name']. '</a>';
			} else {
				$type = 'cate_id=';
			}

			$prefix = ", ";
		}

		return $commaList;
	}



    public function ECHPL_replace_newline($s) {
        $s = preg_replace("/\r\n|\r|\n/", '<br/>', $s);
	    return $s;
    }


    public function ECHPL_clinic_locations($addressArr) {
        $location = '';
        for($i = 0; $i < count($addressArr); $i++) {
            $location .= $addressArr[$i];
        }
        
        return $location;
    }



	



	/**************************************
	 * Curl function
	 **************************************/
	public function ECHPL_curl_json($api_link) {
		$ch = curl_init();
	
		$api_headers = array(
			'accept: application/json',
			'version: v1',
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $api_headers);
		curl_setopt($ch, CURLOPT_URL, $api_link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	
		return $result;
	}


}

