<?php

/**
 * Template part for displaying the header search
 *
 * @package socialv
 */

namespace SocialV\Utility;

$socialv_options = get_option('socialv-options');
?>
<form id="header-search-form" method="get" class="search-form search__form" action="<?php echo esc_url(home_url('/')); ?>" onsubmit="return false;">
    <div class="form-search">
        <input type="search" name='s' value="<?php get_search_query() ?>" class="search-input ajax_search_input" placeholder="<?php echo esc_attr(!empty($socialv_options['header_search_text']) ? $socialv_options['header_search_text'] : ''); ?>">
        <button type="button" class="search-submit ajax_search_input">
            <i class="iconly-Search icli" aria-hidden="true"></i>
        </button>
    </div>
</form>
<div class="socialv-search-result search-result-dislogbox">
    <!-- Search Content -->
    <div class="socialv-search-activity">
        <ul class="socialv-search-activity-content socialv-member-list list-inline">
            <li><span></span></li>
            <li><span class="socialv-loader"></span></li>
        </ul>
    </div>
    <div class="item-footer">
    </div>
</div>