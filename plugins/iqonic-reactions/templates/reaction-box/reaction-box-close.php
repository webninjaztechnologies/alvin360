<?php

/**
 * The template for displaying the close icon for reaction box
 *
 * This file can be overridden by copying it to yourtheme/iqonic-reactions/templates/reaction-box/reaction-box-close.php
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

function ir_box_close()
{ 
   echo '<div class="popup_close-button">
        <i class="icon-close-2"></i>
    </div>';

}

add_action("ir-box-close", "ir_box_close");
