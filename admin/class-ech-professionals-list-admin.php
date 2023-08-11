<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/admin
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Professionals_List_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ech_Professionals_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ech_Professionals_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ech-professionals-list-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ech_Professionals_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ech_Professionals_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ech-professionals-list-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * ^^^ Add ECH-PL Admin menu
	 *
	 * @since    1.0.0
	 */
	public function ech_pl_admin_menu() {
		add_menu_page( 'ECH Professionals List Plugin Settings', 'ECH Professionals', 'manage_options', 'ech_pl_general_settings', array($this, 'ech_pl_admin_page'), 'dashicons-buddicons-activity', 110 );
	}

	// return views
	public function ech_pl_admin_page() {
		require_once ('partials/ech-professionals-list-admin-display.php');
	}


	/**
	 * ^^^ Register custom fields for plugin settings
	 *
	 * @since    1.0.0
	 */
	public function reg_ech_pl_general_settings() {
		// Register all settings for general setting page
		register_setting( 'ech_pl_gen_settings', 'ech_pl_apply_api_env');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_ppp');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_vet_pcid_dev');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_dr_pcid_dev');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_vet_pcid_live');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_dr_pcid_live');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_display_dr_type');
		register_setting( 'ech_pl_gen_settings', 'ech_pl_get_style');
	}

}
