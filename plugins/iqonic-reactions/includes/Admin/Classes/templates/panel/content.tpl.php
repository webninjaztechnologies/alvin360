<?php
/**
 * The template for the main content of the panel.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version     4.0.0
 */
?>

<!-- Header Block -->
<?php $this->get_template('header.tpl.php'); 

// Intro Text
if (isset($this->parent->args['intro_text'])) { 
    echo '<div id="redux-intro-text">' . wp_kses_post($this->parent->args['intro_text']) . '</div>';
}

$this->get_template('menu-container.tpl.php'); 

echo '<div class="redux-main">';
// Stickybar
$this->get_template('header-stickybar.tpl.php'); 
echo '<div id="redux_ajax_overlay">&nbsp;</div>';
foreach ($this->parent->sections as $k => $section) {
    if (isset($section['customizer_only']) && true === $section['customizer_only']) { 
        continue; 
    }

    $section['class'] = isset($section['class']) ? ' ' . $section['class'] : ''; 

    $disabled = ''; 
    if (isset($section['disabled']) && $section['disabled']) { 
        $disabled = 'disabled ';
    } 

    echo '<div id="' . esc_attr($k) . '_section_group"
        class="redux-group-tab ' . esc_attr($disabled) . ' ' . esc_attr($section['class']) . '"
        data-rel="' . esc_attr($k) . '">';

    $display = true;

    if (isset($_GET['page']) && $this->parent->args['page_slug'] === $_GET['page']) { 
        if (isset($section['panel']) && false === $section['panel']) { 
            $display = false; 
        } 
    }

    if ($display) {
        /**
         * Action 'redux/page/{opt_name}/section/before'
         *
         * @param object $this ReduxFramework
         */
        do_action("redux/page/{$this->parent->args['opt_name']}/section/before", $section);

        $this->output_section($k);

        /**
         * Action 'redux/page/{opt_name}/section/after'
         *
         * @param object $this ReduxFramework
         */
        do_action("redux/page/{$this->parent->args['opt_name']}/section/after", $section);
    }

    echo '</div>'; 
}

/**
 * Action 'redux/page/{opt_name}/sections/after'
 *
 * @param object $this ReduxFramework
 */
do_action("redux/page/{$this->parent->args['opt_name']}/sections/after", $this);

echo '<div class="clear"></div>';
// Footer Block
$this->get_template('footer.tpl.php'); 
echo '<div id="redux-sticky-padder" style="display: none;">&nbsp;</div></div>';
// redux main
echo '<div class="clear"></div>';
?>
