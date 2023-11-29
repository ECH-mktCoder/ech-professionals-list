<?php

/**
 * Fired during plugin activation
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/includes
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Professionals_List_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// connect to LIVE API when first activate
		$getApiEnv = get_option( 'ech_pl_apply_api_env' );
		if(empty($getApiEnv) || !$getApiEnv ) {
			add_option( 'ech_pl_apply_api_env', 1 );
		}


		// Set vet and doctor product_category_id value
		$getVetPcID_live = get_option( 'ech_pl_vet_pcid_live' );
		$getDrPcID_live = get_option( 'ech_pl_dr_pcid_live' );
		$getDentistPcID_live = get_option( 'ech_pl_dentist_pcid_live' );

		if(empty($getVetPcID_live) || !$getVetPcID_live ) {
			add_option( 'ech_pl_vet_pcid_live', '30436' );
		}
		if(empty($getDrPcID_live) || !$getDrPcID_live ) {
			add_option( 'ech_pl_dr_pcid_live', '30001' );
		}
		if(empty($getDentistPcID_live) || !$getDentistPcID_live ) {
			add_option( 'ech_pl_dentist_pcid_live', '30565' );
		}

		$getVetPcID_dev = get_option( 'ech_pl_vet_pcid_dev' );
		$getDrPcID_dev = get_option( 'ech_pl_dr_pcid_dev' );
		$getDentistPcID_dev = get_option( 'ech_pl_dentist_pcid_dev' );
		if(empty($getVetPcID_dev) || !$getVetPcID_dev ) {
			add_option( 'ech_pl_vet_pcid_dev', '30558' );
		}
		if(empty($getDrPcID_dev) || !$getDrPcID_dev ) {
			add_option( 'ech_pl_dr_pcid_dev', '30001' );
		}
		if(empty($getDentistPcID_dev) || !$getDentistPcID_dev ) {
			add_option( 'ech_pl_dentist_pcid_dev', '30005' );
		}

		// controller of display all types of dr / only dr / only vet / dentist vet
		$getDisplayDrType = get_option( 'ech_pl_display_dr_type' );
		if(empty($getDisplayDrType) || !$getDisplayDrType ) {
      add_option( 'ech_pl_display_dr_type', 'all' );
    }


    // set post per page to 12
    $getPPP = get_option( 'ech_pl_ppp' );
    if(empty($getPPP) || !$getPPP ) {
        add_option( 'ech_pl_ppp', 12 );
    }

		// apply which css files, default is ECH website style
		$getStyle = get_option('ech_pl_get_style');
		if(empty($getStyle) || !$getStyle ) {
            add_option( 'ech_pl_get_style', 'ech_web' );
        }


		// controller of enable / disable breadcrumb
		$getBreadcrumb = get_option('ech_pl_enable_breadcrumb');
		if(empty($getBreadcrumb) || !$getBreadcrumb ) {
            add_option( 'ech_pl_enable_breadcrumb', 0 );
        }

	} //activate


    






}
