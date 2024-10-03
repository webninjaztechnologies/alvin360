<?php
function imt_get_plugin_directory()
{
	return IQONIC_MODERATION_TOOL_PATH;
}
function imt_get_template_directory()
{
	return get_template_directory() . '/iqonic-moderation-tool';
}
/**
 * Get a Iqonin Moderation Tools template part for display in a theme.
 *
 * @since 1.7.0
 * @since 7.0.0 Added $args parameter.
 *
 * @param string      $slug Template part slug. Used to generate filenames,
 *                          eg 'block' for 'block.php'.
 * @param string|null $name Optional. Template part name. Used to generate
 *                          secondary filenames, eg 'personal' for 'block-personal.php'.
 * @param array       $args Optional. Extra args to pass to locate_template().
 * @return false|string Path to located template. See {@link imt_locate_template()}.
 */
function imt_get_template_part($slug, $name = null, $args = array())
{

	/**
	 * Fires at the start of imt_get_template_part().
	 *
	 * This is a variable hook that is dependent on the slug passed in.
	 *
	 * @since 1.7.0
	 * @since 7.0.0 Added $args parameter.
	 *
	 * @param string $slug Template part slug requested.
	 * @param string $name Template part name requested.
	 * @param array  $args Extra args to pass to locate_template().
	 */
	do_action('get_template_part_' . $slug, $slug, $name, $args);

	// Setup possible parts.
	$templates = array();
	if (isset($name)) {
		$templates[] = $slug . '-' . $name . '.php';
	}
	$templates[] = $slug . '.php';
	/**
	 * Filters the template parts to be loaded.
	 *
	 * @since 1.7.0
	 * @since 7.0.0 Added $args parameter.
	 *
	 * @param array  $templates Array of templates located.
	 * @param string $slug      Template part slug requested.
	 * @param string $name      Template part name requested.
	 * @param array  $args      Extra args to pass to locate_template().
	 */
	$templates = apply_filters('imt_get_template_part', $templates, $slug, $name, $args);

	// Return the part that is found.
	return imt_locate_template($templates, true, false, $args);
}

function imt_locate_template($template_names, $load = false, $require_once = true, $args = array())
{

	// Bail when there are no templates to locate.
	if (empty($template_names)) {
		return false;
	}

	// No file found yet.
	$located            = false;
	$template_locations = imt_get_template_stack();

	// Try to find a template file.
	foreach ((array) $template_names as $template_name) {

		// Continue if template is empty.
		if (empty($template_name)) {
			continue;
		}

		// Trim off any slashes from the template name.
		$template_name  = ltrim($template_name, '/');

		// Loop through template stack.
		foreach ((array) $template_locations as $template_location) {
			// Continue if $template_location is empty.
			if (empty($template_location)) {
				continue;
			}

			// Check child theme first.
			if (file_exists(trailingslashit($template_location) . $template_name)) {
				$located = trailingslashit($template_location) . $template_name;
				break 2;
			}
		}
	}

	/**
	 * This action exists only to follow the standard Iqonic Moderation Tool coding convention,
	 * and should not be used to short-circuit any part of the template locater.
	 *
	 * If you want to override a specific template part, please either filter
	 * 'imt_get_template_part' or add a new location to the template stack.
	 */
	do_action('imt_locate_template', $located, $template_name, $template_names, $template_locations, $load, $require_once, $args);

	/**
	 * Filter here to allow/disallow template loading.
	 *
	 * @since 2.5.0
	 *
	 * @param bool $value True to load the template, false otherwise.
	 */
	$load_template = (bool) apply_filters('imt_locate_template_and_load', true);

	if ($load_template && $load && !empty($located)) {
		load_template($located, $require_once, $args);
	}

	return $located;
}


function imt_register_template_stack($location_callback = '', $priority = 10)
{

	// Bail if no location, or function/method is not callable.
	if (empty($location_callback) || !is_callable($location_callback)) {
		return false;
	}

	// Add location callback to template stack.
	return add_filter('imt_template_stack', $location_callback, (int) $priority);
}

/**
 * Deregister a previously registered template stack location.
 *
 * @since 1.7.0
 *
 * @see imt_register_template_stack()
 *
 * @param string $location_callback Callback function that returns the stack location.
 * @param int    $priority          Optional. The priority parameter passed to
 *                                  {@link imt_register_template_stack()}. Default: 10.
 * @return bool See {@link remove_filter()}.
 */
function imt_deregister_template_stack($location_callback = '', $priority = 10)
{

	// Bail if no location, or function/method is not callable.
	if (empty($location_callback) || !is_callable($location_callback)) {
		return false;
	}

	// Add location callback to template stack.
	return remove_filter('imt_template_stack', $location_callback, (int) $priority);
}

/**
 * Get the "template stack", a list of registered directories where templates can be found.
 *
 * Calls the functions added to the 'imt_template_stack' filter hook, and return
 * an array of the template locations.
 *
 * @since 1.7.0
 *
 * @see imt_register_template_stack()
 *
 * @global array $wp_filter         Stores all of the filters.
 * @global array $merged_filters    Merges the filter hooks using this function.
 * @global array $wp_current_filter Stores the list of current filters with
 *                                  the current one last.
 * @return array The filtered value after all hooked functions are applied to it.
 */
function imt_get_template_stack()
{
	global $wp_filter, $merged_filters, $wp_current_filter;

	// Setup some default variables.
	$tag  = 'imt_template_stack';
	$args = $stack = array();

	// Add 'imt_template_stack' to the current filter array.
	$wp_current_filter[] = $tag;

	// Sort.
	if (class_exists('WP_Hook')) {
		$filter = $wp_filter[$tag]->callbacks;
	} else {
		$filter = &$wp_filter[$tag];

		if (!isset($merged_filters[$tag])) {
			ksort($filter);
			$merged_filters[$tag] = true;
		}
	}

	// Ensure we're always at the beginning of the filter array.
	reset($filter);

	// Loop through 'imt_template_stack' filters, and call callback functions.
	do {
		foreach ((array) current($filter) as $the_) {
			if (!is_null($the_['function'])) {
				$args[1] = $stack;
				$stack[] = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
			}
		}
	} while (next($filter) !== false);

	// Remove 'imt_template_stack' from the current filter array.
	array_pop($wp_current_filter);

	// Remove empties and duplicates.
	$stack = array_unique(array_filter($stack));

	/**
	 * Filters the "template stack" list of registered directories where templates can be found.
	 *
	 * @since 1.7.0
	 *
	 * @param array $stack Array of registered directories for template locations.
	 */
	return (array) apply_filters('imt_get_template_stack', $stack);
}
