<?php

/**
 * Functions to handle the interaction with JSON data returned from the Wordpress REST API
 * call to the https://www.deadgoodbooks.co.uk web site.
 *
 * @author  Dan Kew <dankew@ntlworld.com>
 * @license GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 */

class HandleJSON
{
    /**
     * Placeholder constrctor if needed for future use
     * 
     * @return void
     */
    function __construct()
    {    
    }

    /**
     * Check the URL we are requesting does exist and doesn't return a 404
     * 
     * @param string $url the location of the source JSON data
     * 
     * @return boolean
     */
    private function _URLexists($url)
    {
        $urlHeaders = @get_headers($url);
        if (!$urlHeaders || $urlHeaders[0] == 'HTTP/1.1 404 Not Found') {
            $exists = false;
        } else {
             $exists = true;
        }      
        return $exists;
    }
    
    /**
     * Data also contains extracts, so use the ISBN value to confirm
     * it is actually a book.
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return boolean
     */
    function isBook($bookData, $position)
    {
        return $bookData[$position]->acf->isbn <> '' ? true : false;      
    }
    
    /**
     * Request the data from the provided URL and decode the JSON
     * 
     * @param string $url source of data to extract using the Wordpress REST API v2
     * 
     * @return object
     */
    function getJSON($url)
    {
        if ($this->_URLexists($url)) {
            $request = wp_remote_get($url);
            if (is_wp_error($request)) {
                die("Unable to read from data source");
            }
            $body = wp_remote_retrieve_body($request);
            $json = json_decode($body);
        } else { 
            die("Unable to locate data source"); 
        }
        return $json;
    } 
    
    /**
     * Extract the cover image URL to use from the JSON data. Assumption
     * made that this should always exist.
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for the cover image
     */
    function getCoverImage($bookData, $position)
    {
        return $bookData[$position]->acf->cover_image->sizes->large;
    }
   
    /**
     * Extract the buy from Amazon URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for Amazon link or empty string
     */
    function getAmazonLink($bookData, $position)
    {
        return isset($bookData[$position]->acf->amazon_link) ? $bookData[$position]->acf->amazon_link : '';
    }
    
    /**
     * Extract the buy from iBooks URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for iBooks link or empty string
     */
    function getiBooksLink($bookData, $position)
    {
        return isset($bookData[$position]->acf->ibooks_link) ? $bookData[$position]->acf->ibooks_link : '';       
    }
    
    /**
     * Extract the buy from Kobo URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for Kobo link or empty string
     */
    function getKoboLink($bookData, $position)
    {
        return isset($bookData[$position]->acf->kobo_link) ? $bookData[$position]->acf->kobo_link : '';       
    }
    
    /**
     * Extract the buy from Waterstones URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for Waterstones link or empty string
     */
    function getWaterstonesLink($bookData, $position)
    {
        return isset($bookData[$position]->acf->waterstones_link) ? $bookData[$position]->acf->waterstones_link : '';       
    }
    
    /**
     * Extract the buy from Audible URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for Audible link or empty string
     */
    function getAudibleLink($bookData, $position)
    {      
        return isset($bookData[$position]->acf->audible_link) ? $bookData[$position]->acf->audible_link : '';       
    }
    
    /**
     * Extract the buy from Google Play URL to use from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL for Google Play link or empty string
     */
    function getGooglePlayLink($bookData, $position)
    {
        return isset($bookData[$position]->acf->google_play_link) ? $bookData[$position]->acf->google_play_link : '';       
    }
    
    /**
     * Extract the ISBN number from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string ISBN or empty string
     */
    function getISBN($bookData, $position)
    {
        return isset($bookData[$position]->acf->cover_image->isbn) ? $bookData[$position]->acf->cover_image->isbn : '';       
    }
    
    /**
     * Extract the book title from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string book title or empty string
     */
    function getTitle($bookData, $position)
    {
        return isset($bookData[$position]->acf->title) ? $bookData[$position]->acf->title : '';       
    }
     
    /**
     * Extract the author name from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string author name or empty string
     */
    function getAuthor($bookData, $position)
    {
        return isset($bookData[$position]->acf->author) ? $bookData[$position]->acf->author : '';       
    }

    /**
     * Extract the URL to the deadgood book page from the JSON data. 
     * 
     * @param object $bookData JSON object containing all data
     * @param int    $position index onto the data of the 'book' to check
     * 
     * @return string URL to the book on the dead good web site or empty string
     */
    function getLink($bookData, $position)
    {
        return isset($bookData[$position]->link) ? $bookData[$position]->link : '';
    }    
}

?>