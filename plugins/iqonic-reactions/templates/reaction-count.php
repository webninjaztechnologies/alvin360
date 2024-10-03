<?php
/**
 * The template for displaying the reaction count
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reaction-count.php
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

function reaction_count($reaction_count) {
    echo '<div class="reaction-count">';
     echo esc_html($reaction_count); 
    echo '</div>'; 
}

add_action("iq_reaction-count", "reaction_count");

?>