<?php



class Ech_PL_Virtual_Pages_Public extends Ech_Professionals_List_Public {

	 /************************************************************************
     * To avoid the error "generated X characters of unexpected output" ocurred during plugin activation, 
     * initialize_createVP function is called in define_public_hooks, add_action('init')
     * (folder: includes/class-ech-blog.php)
     * initialize_createVP() fires after WordPress has finished loading, but before any headers are sent.
     ************************************************************************/
	public static function ECHPL_initialize_createVP() {
		// add an option to make use ECHB_setupVP is triggered once per VP. Delete this option once all VP are created.
        add_option('ECHPL_run_init_createVP', 1);
	}


	public function ECHPL_createVP() {
		if (get_option('ECHPL_run_init_createVP') == 1) {
			$this->ECHPL_setupVP('Healthcare Professional Profile', 'professional-profile', '[dr_profile_output]');
			$this->ECHPL_setupVP('Healthcare Professional Categories', 'specialty-categories', '[dr_category_list_output]');

			// Delete this option once all VP are created.
			delete_option('ECHPL_run_init_createVP');
		}
	}

	private static function ECHPL_setupVP($pageTitle, $pageSlug, $pageShortcode) {
        if ( current_user_can( 'activate_plugins' ) ) {
			// Get parent page and get its id
			$get_parent_page = get_page_by_path('healthcare-professionals');
	
			$v_page = array(
				'post_type' => 'page',
				'post_title' => $pageTitle,
				'post_name' => $pageSlug,
				'post_content' => $pageShortcode,  // shortcode from this plugin
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_parent' => $get_parent_page->ID
			);
	
			$vp_id = wp_insert_post($v_page, true);
	
		} else {
			return;
		}
    } // createVP



	/**************************************** SHORTCODE ****************************************/

    public function dr_profile_output($atts) {
		if (!get_option('ECHPL_run_init_createVP')) {

			$therapistid = get_query_var('therapistid', 'none');
			
			if ( $therapistid === 'none' ) { echo '<script>window.location.replace("/healthcare-professionals");</script>'; }

			$args = array(
				'therapistid' => $therapistid,
			);
			
			
			$api_link = parent::ECHPL_gen_single_dr_api_link($args);
			$get_post_json = parent::ECHPL_curl_json($api_link);
			$json_arr = json_decode($get_post_json, true);

			// check if single dr is vet
			$getApiEnv = get_option( 'ech_pl_apply_api_env' ); 
			if ( $getApiEnv == "0") {
				$vetProdCateID = '30558';
			} else {
				$vetProdCateID = '30436';
			}
			$isVet = false;
			foreach ($json_arr['personnel']['specialty'] as $specArr) {
				if($vetProdCateID == $specArr['product_category_id']) {
					$isVet = true;
				}
			}

			$en_name = [
				1 => $json_arr['personnel']['en_salutation'] ." ". $json_arr['personnel']['en_name'],
				2 => $json_arr['personnel']['en_name'].$json_arr['personnel']['en_salutation'],
			];
			$tc_name = [
				1 => $json_arr['personnel']['tc_salutation'] ." ". $json_arr['personnel']['tc_name'],
				2 => $json_arr['personnel']['tc_name'].$json_arr['personnel']['tc_salutation'],
			];
			$cn_name = [
				1 => $json_arr['personnel']['cn_salutation'] ." ". $json_arr['personnel']['cn_name'],
				2 => $json_arr['personnel']['cn_name'].$json_arr['personnel']['cn_salutation'],
			];

			$html = '';

			$html .= '<div class="all_single_dr_wrap">';

			if (get_option('ech_pl_enable_breadcrumb') == 1) {
				$html .= '<div class="sp_breadcrumb">';
					$html .= ' <div><a href="'.site_url() .'">'. parent::ECHPL_echolang(['Home', '主頁', '主页']) .'</a> > <a href="'.site_url() . '/healthcare-professionals/">'. parent::ECHPL_echolang(['Healthcare Professionals', '醫護專業人員', '医护专业人员']) .'</a> > '. parent::ECHPL_echolang([$json_arr['personnel']['en_salutation'] . ' ' . $json_arr['personnel']['en_name'], $json_arr['personnel']['tc_name'] . $json_arr['personnel']['tc_salutation'], $json_arr['personnel']['cn_name'] . $json_arr['personnel']['cn_salutation']]) .' </div>';
				$html .= '</div>'; // .sp_breadcrumb
			} // if (get_option('ech_pl_enable_breadcrumb') == 1)

			/* $backToListURLParam = '';
			if($isVet) {
				$backToListURLParam = '?dr_type=vet';
			} */

			$html .= '<div class="ECHDr_back_to_medical_list"><a href="'. site_url() .'/healthcare-professionals/"> '. parent::ECHPL_echolang(['Back to healthcare professionals', '返回醫護專業人員', '返回医护专业人员']) .'</a></div>';


			$html .= '<div class="single_dr_container">';
				$html .= '<div class="profile_container">';
					$html .= '<img src="'. $json_arr['personnel']['avatar'] .'" alt="'.parent::ECHPL_echolang([$json_arr['personnel']['en_salutation'] . ' ' . $json_arr['personnel']['en_name'], $json_arr['personnel']['tc_name'] . $json_arr['personnel']['tc_salutation'], $json_arr['personnel']['cn_name'] . $json_arr['personnel']['cn_salutation']]).'">';
					if ($json_arr['personnel']['available_to_book'] == 1) {
						$html .= '<div class="dr_booking"><a href="'. $json_arr['personnel']['doctor_whats_app_link'] .'" target="_blank">'.parent::ECHPL_echolang(['Book an Appointment', '預約醫生', '预约医生']) .'</a></div>';
					}
					$html .= '</div>'; // .profile_container
				

					$html .= '<div class="info_container">';
						// $html .= '<h1 class="dr_name">'. parent::ECHPL_echolang([$json_arr['personnel']['en_salutation'] . ' ' . $json_arr['personnel']['en_name'], $json_arr['personnel']['tc_name'] . $json_arr['personnel']['tc_salutation'], $json_arr['personnel']['cn_name'] . $json_arr['personnel']['cn_salutation']]) .'</h1>';
						$html .= '<h1 class="dr_name">'. parent::ECHPL_echolang([$en_name[$json_arr['personnel']['en_is_pre']], $tc_name[$json_arr['personnel']['tc_is_pre']], $cn_name[$json_arr['personnel']['cn_is_pre']]]) .'</h1>';
						
						$html .= '<div class="spec_container">';
							$specArrEN = array();
							$specArrZH = array();
							$specArrSC = array();
							foreach ($json_arr['personnel']['specialty'] as $specArr) {
								array_push($specArrEN, $specArr['en_name']);
								array_push($specArrZH, $specArr['tc_name']);
								array_push($specArrSC, $specArr['cn_name']);
							}
							$html .= parent::ECHPL_echolang([implode(', ', $specArrEN), implode(', ', $specArrZH), implode(', ', $specArrSC)]);
						$html .= '</div>'; // .spec_container
						

						$html .= '<div class="lang_container">';
							$drLangEN = array();
							$drLangZH = array();
							$drLangSC = array();
							foreach ($json_arr['personnel']['language'] as $langArr) {
								array_push($drLangEN, $langArr['en_name']);
								array_push($drLangZH, $langArr['tc_name']);
								array_push($drLangSC, $langArr['cn_name']);
							}

							$html .= parent::ECHPL_echolang(['Languages', '語言', '语言']) .': '. parent::ECHPL_echolang([implode(', ', $drLangEN), implode(', ', $drLangZH), implode(', ', $drLangSC)]) ;							
						$html .= '</div>'; // .lang_container


						$html .= '<div class="edu_container">';
							$html .= '<div class="sub_title">'.parent::ECHPL_echolang(['Qualifications', '學歷', '学历']) .':</div>';
							$html .= parent::ECHPL_echolang([parent::ECHPL_replace_newline($json_arr['personnel']['en_seniority']), parent::ECHPL_replace_newline($json_arr['personnel']['tc_seniority']), parent::ECHPL_replace_newline($json_arr['personnel']['cn_seniority'])]);
						$html .= '</div>'; // .edu_container


						$html .= '<div class="clinics_container">';
							$html .= '<div class="sub_title">'.parent::ECHPL_echolang(['Related Clinics', '相關診所', '相关诊所']) .'</div>';
							foreach ($json_arr['personnel']['shop'] as $shops) {
								$addressArrEN = json_decode($shops['en_address'], true);
								$addressArrZH = json_decode($shops['tc_address'], true);
								$addressArrSC = json_decode($shops['cn_address'], true);
								
								$html .= '<div class="single_clinic">';
									$html .= ' <div class="clinic_name">'.parent::ECHPL_echolang([$shops['en_name'], $shops['tc_name'], $shops['cn_name']]) .'</div>';

									$html .= '<div class="location">'. parent::ECHPL_echolang([parent::ECHPL_clinic_locations($addressArrEN), parent::ECHPL_clinic_locations($addressArrZH), parent::ECHPL_clinic_locations($addressArrSC)]) .'</div>';								
								$html .= '</div>'; // .single_clinic
							}
							
						$html .= '</div>'; // .clinics_container


					$html .= '</div>'; // .info_container

				$html .= '</div>'; // .single_dr_container
			$html .= '</div>'; // .all_single_dr_wrap

			return $html;
		} // if (!get_option('ECHPL_run_init_createVP'))

	}  //--end dr_profile_output()


    public function dr_category_list_output($atts) {
		if (!get_option('ECHPL_run_init_createVP')) {

			$specialtyid = get_query_var('specialtyid', 'none');
			
			if ( $specialtyid === 'none' ) { echo '<script>window.location.replace("/healthcare-professionals");</script>'; }

			$ppp = get_option('ech_pl_ppp');

			$args = array(
				'specialty_id' => $specialtyid,
				'page_size' => $ppp
			);
			$api_link = parent::ECHPL_gen_profList_api_link($args);
			$get_post_json = parent::ECHPL_curl_json($api_link);
			$json_arr = json_decode($get_post_json, true);

			$getSpName_json = parent::ECHPL_get_specialty_name($specialtyid);
			$spNameArr = json_decode($getSpName_json, true);
			$sp_name = parent::ECHPL_echolang([ $spNameArr['en'], $spNameArr['zh'], $spNameArr['sc']]);


			$html = '';

			$html .= '<div class="ech_dr_sp_list_all_wrap">';

			if (get_option('ech_pl_enable_breadcrumb') == 1) {

				$html .= '<div class="sp_breadcrumb">';
				$html .= '<div><a href="'. site_url() .'">'. parent::ECHPL_echolang(['Home', '主頁', '主页']) .'</a> > <a href="'.site_url() . '/healthcare-professionals/">'.parent::ECHPL_echolang(['Healthcare Professionals','醫護專業人員','医护专业人员']).'</a> > '. parent::ECHPL_echolang(['Specialist','專科','专科']).': '.$sp_name .' </div>';
				$html .= '</div>';
			}
			$html .= '<div class="echdr_page_anchor"></div>';
			
			$html .= '<div class="ECHDr_back_to_medical_list"><a href="'.site_url().'/healthcare-professionals/"> '. parent::ECHPL_echolang(['Back to healthcare professionals', '返回醫護專業人員', '返回医护专业人员']) .'</a></div>';
			
			$html .= '<div class="ECHDr_search_title">';
				$html .= ' <p><span>'. parent::ECHPL_echolang(['Specialist','專科','专科']).': </span>'.$sp_name.' </p>';
			$html .= '</div>'; // .ECHDr_search_title
			
			$html .= '<div class="ech_dr_container">';
				$html .= '<div class="loading_div"><p>'. parent::ECHPL_echolang(['Loading...','載入中...','载入中...']).'</p></div>';
				
				$html .= '<div class="all_drs_container" data-ppp="'.$ppp.'" data-channel="4" data-brandid="" data-region="" data-specialty="'.$specialtyid.'">';			
					foreach	($json_arr['result'] as $dr) {
						$html .= parent::ECHPL_load_card_template($dr);
					}							
			$html .= '</div>'; // .all_drs_container	
			


			/*** pagination ***/
            $total_posts = $json_arr['count'];
            $max_page = ceil($total_posts/$ppp);

			$html .= '<div class="ech_dr_pagination" data-current-page="1" data-max-page="'.$max_page.'" data-topage="" data-ajaxurl="'.get_admin_url(null, 'admin-ajax.php').'"></div>';				

			$html .= '</div>'; // .ech_dr_container
			$html .= '</div>'; // .ech_dr_sp_list_all_wrap


			return $html;
			
		} // if (!get_option('ECHPL_run_init_createVP'))

    } //dr_category_list_output

	/**************************************** (end)SHORTCODE ****************************************/

} // class