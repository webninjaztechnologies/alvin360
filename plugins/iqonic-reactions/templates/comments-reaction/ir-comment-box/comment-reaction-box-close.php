<?php
defined('ABSPATH') || exit;

function ir_comment_box_close()
{
?>
    <div class="popup_close-button">
        <i class="icon-close-2"></i>
    </div>
<?php
}

add_action("ir-comment-box-close", "ir_comment_box_close");
