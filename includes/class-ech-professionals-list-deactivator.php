<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/includes
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Professionals_List_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        self::deleteVP('healthcare-professionals/professional-profile');
        self::deleteVP('healthcare-professionals/specialty-categories');
	}

    private static function deleteVP($pageSlug){
        $slug = $pageSlug;
		$post = get_page_by_path($slug);
		if($post) {
			$pid = $post->ID;

			wp_delete_post($pid, true);

		} else {
			echo "$slug not found....!";
		}
    }


}
