<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://dankew.me
 * @since      1.0.0
 *
 * @package    Carousel
 * @subpackage Carousel/admin
*/

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Carousel
 * @subpackage Carousel/admin
 * @author     Dan Kew <dankew@ntlworld.com>
 */
class Plugin_Name_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $carousel_v2    The ID of this plugin.
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

    private $theTagArray = array();

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $carousel_v2       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */

     public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->loadTags();
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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/carousel-v2-admin.css', array(), $this->version, 'all' );

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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/carousel-v2-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_submenu_page( 'plugins.php', 'Plugin settings page title', 'Carousel Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {
         /*
         *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
         */
         $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );
         return array_merge(  $settings_link, $links );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page() {
        include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );
    }

    /**
     * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
     * @param  mixed $input as field form settings form
     * @return mixed as validated fields
     */
    public function validate($input) {

        $valid = array();

        foreach($this->theTagArray as $tagName)
        {
            $valid[$tagName[0]] = ( isset( $input[$tagName[0]] ) && ! empty( $input[$tagName[0]] ) ) ? $tagName[1] : 0;
        }
       
        $valid['amazon_link'] = ( isset( $input['amazon_link'] ) && ! empty( $input['amazon_link'] ) ) ? 1 : 0;
        $valid['ibooks_link'] = ( isset( $input['ibooks_link'] ) && ! empty( $input['ibooks_link'] ) ) ? 1 : 0;
        $valid['kobo_link'] = ( isset( $input['kobo_link'] ) && ! empty( $input['kobo_link'] ) ) ? 1 : 0;
        $valid['waterstones_link'] = ( isset( $input['waterstones_link'] ) && ! empty( $input['waterstones_link'] ) ) ? 1 : 0;
        $valid['audible_link'] = ( isset( $input['audible_link'] ) && ! empty( $input['audible_link'] ) ) ? 1 : 0;
        $valid['google_play_link'] = ( isset( $input['google_play_link'] ) && ! empty( $input['google_play_link'] ) ) ? 1 : 0;
       
        return $valid;
    }

    public function options_update() {
        register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
    }

    private function loadTags()
    {     
        $deadGood = new HandleJSON();
        $theBookData = $deadGood -> getJSON("https://www.deadgoodbooks.co.uk/wp-json/wp/v2/posts?categories=1208&per_page=50");
        $allTags = $this -> _getAllTags($theBookData);
     
        $qs = implode (",", $allTags);

        $deadGoodTags = new HandleJSON();
        $theTagData = $deadGoodTags -> getJSON("https://www.deadgoodbooks.co.uk/wp-json/wp/v2/tags?&per_page=50&include=".$qs);

        foreach ($theTagData as $tagValue) {
            $temp = array($tagValue->slug, $tagValue->id);
            array_push($this->theTagArray, $temp);
        }
    }

    private function _getAllTags($bd)
    {
        $mainTags = array();
        foreach ($bd as $book) {
            $mainTags = array_unique(array_merge($mainTags, $book->tags));
        }
        return $mainTags;
    }
}
