<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://dankew.me
 * @since      1.0.0
 *
 * @package    Carousel
 * @subpackage Carousel/public

 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Carousel
 * @subpackage Carousel/pub;ic
 * @author     Dan Kew <dankew@ntlworld.com>
 */
class Plugin_Name_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $carousel_v2    The ID of this plugin.
     */
    private $carousel_v2;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    private $deadGood;
    private $sourceURL = "https://www.deadgoodbooks.co.uk/wp-json/wp/v2/posts?categories=1208&per_page=25";
    
    private $booksToShow = 12;
    private $_bookContent;
    private $_booksFound = 0;
    private $_bookIndex = 0; 
    private $_coverURL, $_bookTitle, $_bookAuthor, $_bookLink, $_amazon, $_ibooks, $_kobo, $_waterstones, $_audible, $_google;
    private $_booksLinks;

    private $_theTagArray = array();

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $carousel_v2       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        $this->loadTags();

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/carousel-v2-public.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'wp-flickity-css', '//unpkg.com/flickity@2.0.4/dist/flickity.min.css', false, null );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/carousel-v2-public.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'wp-flickity-js', 'https://unpkg.com/flickity@2.0/dist/flickity.pkgd.min.js', false, null );

    }

    public function carousel_v2_output() {

        $options = get_option($this->plugin_name); 

        $tagString = "";
        foreach($this->_theTagArray as $v)
        {
            $inComingTag = ( isset( $options[$v] ) && ! empty( $options[$v] ) ) ? $options[$v] : 0;
            if ($inComingTag > 0) {
                $tagString .= "&tags[]=".$options[$v];
            }
        }

        $amazon_link = ( isset( $options['amazon_link'] ) && ! empty( $options['amazon_link'] ) ) ? 1 : 0;
        $ibooks_link = ( isset( $options['ibooks_link'] ) && ! empty( $options['ibooks_link'] ) ) ? 1 : 0;
        $kobo_link = ( isset( $options['kobo_link'] ) && ! empty( $options['kobo_link'] ) ) ? 1 : 0;
        $waterstones_link = ( isset( $options['waterstones_link'] ) && ! empty( $options['waterstones_link'] ) ) ? 1 : 0;
        $audible_link = ( isset( $options['audible_link'] ) && ! empty( $options['audible_link'] ) ) ? 1 : 0;
        $google_play_link = ( isset( $options['google_play_link'] ) && ! empty( $options['google_play_link'] ) ) ? 1 : 0;

        $this->sourceURL .= $tagString;

        $deadGood = new HandleJSON();
        $theBookData = $deadGood -> getJSON($this->sourceURL);
        $numberOfBooks = $this -> _getNumberOfBooks($theBookData);
      
        if ($numberOfBooks === 0) { 
            die("Insufficient book data returned"); 
        }
        $this->_bookContent = "
        <div><img align=left src=\"https://www.deadgoodbooks.co.uk/wp-content/themes/zuki/deadgoodbooks-logo.png\" width=100>
        <h5>Gripping reads from Dead Good Books</h5></div>";
        $this->_bookContent .= "<div class=\"carousel\" data-flickity='{ \"imagesLoaded\": true, \"pageDots\":false, \"wrapAround\": true }'>";

        while (($this->_booksFound < $this->booksToShow) && ($this->_bookIndex < $numberOfBooks)) {
            if ($deadGood -> isBook($theBookData, $this->_bookIndex)) {      
            
                $retailerOption = 0;

                // Compile the data for each book title and display ....
                // Main book data first ..
                $this->_coverURL = $deadGood -> getCoverImage($theBookData, $this->_bookIndex);
                $this->_bookTitle = $deadGood -> getTitle($theBookData, $this->_bookIndex);
                $this->_bookAuthor = $deadGood -> getAuthor($theBookData, $this->_bookIndex);
                $this->_bookLink = $deadGood -> getLink($theBookData, $this->_bookIndex);
                // Now the links to the buy options from the external web sites
                $this->_amazon = $deadGood -> getAmazonLink($theBookData, $this->_bookIndex);
                $this->_ibooks = $deadGood -> getiBooksLink($theBookData, $this->_bookIndex);
                $this->_kobo = $deadGood -> getKoboLink($theBookData, $this->_bookIndex);
                $this->_waterstones = $deadGood -> getWaterstonesLink($theBookData, $this->_bookIndex);
                $this->_audible = $deadGood -> getAudibleLink($theBookData, $this->_bookIndex);
                $this->_google = $deadGood -> getGooglePlayLink($theBookData, $this->_bookIndex);
                // Start to the build the HTML needed for the hover display
                $_booksLinks = "";
                
                if ($this->_amazon <> '') { 
                    $_booksLinks .= "<a href='".$this->_amazon."' target='_blank'>Amazon</a>"; 
                    if ($amazon_link === 1) {
                        $retailerOption = 1;
                    }
                }
                if ($this->_ibooks <> '') { 
                    $_booksLinks .= "<a href='".$this->_ibooks."' target='_blank'>iBooks</a>"; 
                    if ($ibooks_link === 1) {
                        $retailerOption = 1;
                    }    
                }
            
                if ($this->_kobo <> '') { 
                    $_booksLinks .= "<a href='".$this->_kobo."' target='_blank'>Kobo</a>";
                    if ($kobo_link === 1) {
                        $retailerOption = 1;
                    }    
                }
            
                if ($this->_waterstones <> '') { 
                    if ($waterstones_link === 1) {
                        $_booksLinks .= "<a href='".$this->_waterstones."' target='_blank'>Waterstones</a>"; 
                        $retailerOption = 1;
                    }    
                }
                if ($this->_audible <> '') { 
                    $_booksLinks .= "<a href='".$this->_audible."' target='_blank'>Audible</a>"; 
                    if ($audible_link === 1) {
                        $retailerOption = 1;
                    }    
                }
                if ($this->_google <> '') { 
                    $_booksLinks .= "<a href='".$this->_google."' target='_blank'>Google</a>"; 
                    if ($google_play_link === 1) {
                        $retailerOption = 1;
                    }    
                }
            
                if ($retailerOption === 1) {
                    // and lastly, the HTML needed for the carousel itself
                    $this->_bookContent .= "<div class=\"carousel-cell\">
                <img src=\"".$this->_coverURL."\">
                <div class=\"dropup\">
                  <a class=\"dropbtn\" href=\"".$this->_bookLink."\">".$this->_bookTitle."</a>
                  <div class=\"dropup-content\">
                  ".$_booksLinks."
                  </div>
                </div>  
                <BR><span class=\"author\">".$this->_bookAuthor."</span></div>";

                    $this->_booksFound += 1;
                }
            }  
            $this->_bookIndex += 1;
        }
        
        $this->_bookContent .= "</div>";
        
        return $this->_bookContent;
    }

    /**
     * Return the number of books (books in this instance includes extracts)
     * found in the JSON data
     * 
     * @param object $allbooks JSON decoded dataset
     * 
     * @return int
     */
    private function _getNumberOfBooks($allbooks)
    {
         return count($allbooks); 
    }

    private function loadTags()
    {
        // Get all the tags
      
        $deadGood = new HandleJSON();
        $theBookData = $deadGood -> getJSON("https://www.deadgoodbooks.co.uk/wp-json/wp/v2/posts?categories=1208&per_page=50");
        $allTags = $this -> _getAllTags($theBookData);
      
        $qs = implode(",", $allTags);
 
        $deadGoodTags = new HandleJSON();
        $theTagData = $deadGoodTags -> getJSON("https://www.deadgoodbooks.co.uk/wp-json/wp/v2/tags?&per_page=50&include=".$qs);
 
        foreach ($theTagData as $tagValue) {
            array_push($this->_theTagArray, $tagValue->slug);
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
