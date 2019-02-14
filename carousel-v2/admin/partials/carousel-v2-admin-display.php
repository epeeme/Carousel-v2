<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://dankew.me
 * @since      1.0.0
 *
 * @package    Carousel
 * @subpackage Carousel/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
<h2>Carousel v2 <?php _e('Options', $this->plugin_name); ?></h2>
<P>A custom Wordpress plugin that displays 12 featured books from the <a href="https://www.deadgoodbooks.co.uk/">Dead Good Books</a> web site, our community site for lovers of crime fiction.</P>
The plugin displays the featured books in a carousel. Each item has a featured image, a title that links to the relevant 
book post on the <a href="https://www.deadgoodbooks.co.uk/">Dead Good site</A>, and links to buy the book from available 
retailers when the user hovers over the book title.</p>

<h4>Useage</h4>
    <p>shortcode - <code>[carousel-v2]</code></p>
    <hr>
    <form method="post" name="cleanup_options" action="options.php">
    <?php
    //Grab all options
    $options = get_option($this->plugin_name); 

    foreach ($this->theTagArray as $tagName) {
        ${$tagName[0]} = ( isset( $options[$tagName[0]] ) && ! empty( $options[$tagName[0]] ) ) ? $tagName[1] : 0;
    }
        
    $amazon_link = ( isset( $options['amazon_link'] ) && ! empty( $options['amazon_link'] ) ) ? 1 : 0;
    $ibooks_link = ( isset( $options['ibooks_link'] ) && ! empty( $options['ibooks_link'] ) ) ? 1 : 0;
    $kobo_link = ( isset( $options['kobo_link'] ) && ! empty( $options['kobo_link'] ) ) ? 1 : 0;
    $waterstones_link = ( isset( $options['waterstones_link'] ) && ! empty( $options['waterstones_link'] ) ) ? 1 : 0;
    $audible_link = ( isset( $options['audible_link'] ) && ! empty( $options['audible_link'] ) ) ? 1 : 0;
    $google_play_link = ( isset( $options['google_play_link'] ) && ! empty( $options['google_play_link'] ) ) ? 1 : 0;
        
    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
    ?>
    
    <table>
    <tr valign=top>
    <td width=33%>

    <fieldset>
        <p><b><?php _e( 'Display Tags', $this->plugin_name ); ?></b></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Display Tags', $this->plugin_name ); ?></span>
        </legend>
    
    <?php
    foreach ($this->theTagArray as $tagName) {            
        print "<label for=\"".$this->plugin_name.'-'.$tagName[0]."\">\n";
        print "<input type=\"checkbox\" id=\"".$this->plugin_name.'-'.$tagName[0]."\" name=\"".$this->plugin_name.'['.$tagName[0]."]\" value=\"".$tagName[1]."\"";
        checked( ${$tagName[0]}, $tagName[1] );
        print ">";         
        print "<span>".esc_attr_e('#'.$tagName[0], $this->plugin_name)."</span><BR>";
        print "</label>";
    }
    ?>

    </fieldset>

    </td>
    <td width=33%>
    <fieldset>
        <p><b><?php _e( 'Display Retailer Links', $this->plugin_name ); ?></b></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Display Retailer', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name.'-amazon_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-amazon_link'; ?>" name="<?php echo $this->plugin_name.'[amazon_link]'; ?>" value="1" <?php checked( $amazon_link, 1 ); ?> >
          <span><?php esc_attr_e('Amazon', $this->plugin_name); ?></span><BR>
        </label>

        <label for="<?php echo $this->plugin_name.'-ibooks_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-ibooks_link'; ?>" name="<?php echo $this->plugin_name.'[ibooks_link]'; ?>" value="1" <?php checked( $ibooks_link, 1 ); ?> >
          <span><?php esc_attr_e('ibooks', $this->plugin_name); ?></span><BR>
        </label>

        <label for="<?php echo $this->plugin_name.'-kobo_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-kobo_link'; ?>" name="<?php echo $this->plugin_name.'[kobo_link]'; ?>" value="1" <?php checked( $kobo_link, 1 ); ?> >
          <span><?php esc_attr_e('Kobo', $this->plugin_name); ?></span><BR>
        </label>

        <label for="<?php echo $this->plugin_name.'-waterstones_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-waterstones_link'; ?>" name="<?php echo $this->plugin_name.'[waterstones_link]'; ?>" value="1" <?php checked( $waterstones_link, 1 ); ?> >
          <span><?php esc_attr_e('Waterstones', $this->plugin_name); ?></span><BR>
        </label>

        <label for="<?php echo $this->plugin_name.'-audible_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-audible_link'; ?>" name="<?php echo $this->plugin_name.'[audible_link]'; ?>" value="1" <?php checked( $audible_link, 1 ); ?> >
          <span><?php esc_attr_e('Audible', $this->plugin_name); ?></span><BR>
        </label>

        <label for="<?php echo $this->plugin_name.'-google_play_link'; ?>">
          <input type="checkbox" id="<?php echo $this->plugin_name.'-google_play_link'; ?>" name="<?php echo $this->plugin_name.'[google_play_link]'; ?>" value="1" <?php checked( $google_play_link, 1 ); ?> >
          <span><?php esc_attr_e('Google Play', $this->plugin_name); ?></span><BR>
        </label>

    </td>
    </tr>
    </table>   
    <hr>
    <?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>
</div>
