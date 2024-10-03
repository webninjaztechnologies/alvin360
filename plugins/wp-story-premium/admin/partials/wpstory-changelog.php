<?php
/**
 * Plugin's changelog.
 *
 * @package WP Story Premium
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<h3>3.5.0 <small></small></h3>
<pre>- Added: Google Web Stories support.
- Added: Story modal background blur effect option.
- Improved: Video playing performance.
- Improved: Story publishing date will be automatically updated on story transitions.
- Improved: PHP 8.3 compatibility.
- Updated: Plugin core dependencies.</pre>
<hr>

<h3>3.4.3.2 <small></small></h3>
<pre>- Fixed: Server side rendering activities bug.
- Fixed: BuddyPress story insights counting bug.
- Fixed: Activity stories users ID bug.
- Fixed: Dark mode text colors.</pre>
<hr>

<h3>3.4.2 <small></small></h3>
<pre>- Added: Users' own stories moved first in activity stories.
- Fixed: Story insights counting error.
- Fixed: Story published date bug.
- Fixed: Facebook style cropping latest story.
- Improved: WordPress 6.4 compatibility.
- Updated: Plugin core dependencies.</pre>
<hr>

<h3>3.4.1 <small></small></h3>
<pre>- Fixed: Story insights users.
- Fixed: Story deleting authentication.
- Fixed: Story deleted notification style.
- Fixed: Story styling bug.</pre>
<hr>

<h3>3.4.0 <small></small></h3>
<pre>- Added: Story insights. Now you can see who viewed your story.
- Added: Story url routing. Now you can close stories by clicking browser or device back button.
- Added: Gif support for story thumbnails.
- Added: Linked stories.
- Added: Prevent cropping titles feature.
- Added: Loading animation for story circles.
- Added: Elementor 3.17 compatibility.
- Added: Timer line animations no is smooth when switching to the next story.
- Improved: Performance.
- Fixed: Video timing bug when video lagged.
- Fixed: Uploaded file type bug.
- Fixed: Story timer format conflict.</pre>
<hr>

<h3>3.2.1.1 <small>(07.01.2023)</small></h3>
<pre>- Fixed: IOS size bug.</pre>
<hr>

<h3>3.2.1 <small>(01.01.2023)</small></h3>
<pre>- Fixed: RTL languages.
- Updated: Translation .pot file.
- Updated: Plugin core dependencies.</pre>
<hr>

<h3>3.2.0 <small>(27.12.2022)</small></h3>
<pre>- Added: iOS 16.2 compatibility.
- Added: Post category stories.
- Added: Welcome page in options.
- Added: Dark mode themes compatibility.
- Added: New submit button icon.
- Improved: License notification.</pre>
<hr>

<h3>3.1.1 <small>(09.12.2022)</small></h3>
<pre>- Fixed: homeUrl undefined error.</pre>
<hr>

<h3>3.1.0 <small>(09.12.2022)</small></h3>
<pre>- Added: Story closing animation.
- Added: Feature to close stories by swiping down for touch screens.
- Added: Navigating stories with keyboard arrow right/left keys.
- Improved: When reaching the end of the stories and trying to move on to the next story, the stories will be closed.
- Improved: Performance.
- Fixed: Media loading bug.
- Fixed: Button clicking bug.</pre>
<hr>

<h3>3.0.0 <small>(24.11.2022)</small></h3>
<pre>- Added: New Facebook Style.
- Added: Reporting system.
- Added: Story deleting feature.
- Added: Story pause/unpause buttons.
- Added: Video mute/unmute buttons.
- Added: User profile links in story screen.
- Added: Image cropping feature.
- Added: New design system.
- Added: New swipe up button.
- Added: New Story Box widget.
- Improved: PeepSo and BuddyPress integrations.
- Improved: Elementor and Gutenberg integrations.
- Improved: Story submitting feature.
- Improved: IOS and Android device compatibility.
- Improved: Style customizations.
- Improved: Frontend submitting feature.
- Improved: Image rendering system.
- Removed: Web Stories feature.
- Removed: List style.
- Fixed: Styling conflicts.
- Fixed: Swiping bugs.</pre>
<hr>

<h3>2.4.0 <small>(10.09.2021)</small></h3>
<pre>- Added: PeepSo automatically placement (PeepSo v3.6.0.0).
- Added: Story creating button for activity stream.
- Added: New, activity stream widget.
- Added: New, public stories widget.
- Added: New, single stories widget.
- Added: New, users' own stories widget.
- Updated: Story Rest API.
- Updated: Options framework.
- Fixed: Public stories deleting bug.</pre>
<hr>

<h3>2.3.1 <small>(15.07.2021)</small></h3>
<pre>- Fixed: Options issue.</pre>
<hr>

<h3>2.3.0</h3>
<pre>- Added: PeepSo integration.
- Added: Timer options for public and single stories.
- Added: Image/video required alert for publishing form.
- Added: Controlling file extensions for publishing form.
- Added: Drag & drop file uploading for publishing form.
- Added: Displaying current stories option in publishing form.
- Fixed: Author stories ID error.
- Fixed: Closing publish modal after story created.
- Fixed: Author stories ordering by date.
- Fixed: Web stories RTL position error.
- Fixed: Story timer for story boxes.
- Fixed: Images parent_id problem.
- Fixed: Arrows positions on RTL websites.
- Dev: Added wpstory_allowed_image_types filter.
- Dev: Added wpstory_allowed_video_types filter.</pre>
<hr>

<h3>2.2.0</h3>
<pre>- Added: Users activities feature.
- Added: [wp-story-activities] shortcode.
- Added: Swipe up feature to open link.
- Added: Background overlay to story head for more readability.
- Added: Story options to blog posts.
- Added: Large post title to blog posts stories.
- Added: Plugin changelog history to plugin's dashboard.
- Added: Server Side and Client Side renders method.
- Improved: Gutenberg block and Elementor widget.
- Improved: Browsers compatibility.
- Removed: Script Mode option. Scripts will enqueue automatically.
- Removed: Author Archive Scripts option. Scripts will enqueue automatically.
- Fixed: Blog stories button title.
- Fixed: Mobile full screen issue.
- Dev: Added wpstory_story_categories_select_ajax filter.
- Dev: Added wpstory_story_blog_post_ids_select_ajax filter.
- Dev: Added wpstory_bp_activity_displaying_hook filter.
- Dev: Added wpstory_activity_count filter.
- Dev: Removed wpstory_public_scripts filter. Scripts will enqueue only when shortcode used.
- Dev: Removed wpstory_submitting_scripts filter. Scripts will enqueue only when shortcode used.</pre>
<hr>

<h3>2.1.0</h3>
<pre>- Added: Google Web Stories Plugin support.
- Added: Popup and carousel for Web Stories.
- Improved: Removed links from story cycles.
- Fixed: PHP syntax errors.
- Fixed: Some styling bugs.
- Dev: Added wpstory_story_ids_select_ajax filter.
- Dev: Added wpstory_public_scripts filter.</pre>
<hr>

<h3>2.0.0</h3>
<pre>- Added: Frontend/User story submission feature.
- Added: Image editor for frontend submission form.
- Added: BuddyPress integration.
- Added: bbPress integration.
- Added: Google Web Stories support.
- Added: Auto generated stories from blog posts.
- Added: Story timer.
- Added: Story item disable/enable option.
- Added: Multiple stories support in the same page.
- Added: Disabling seen story feature option.
- Added: Story cycle title color option.
- Added: Story alignment option.
- Updated: Translation files and strings.
- Fixed: Scrolling top issue when story open.</pre>
<hr>

<h3>1.2.0</h3>
<pre>- Added: Elementor widget.
- Added: Gutenberg block.
- Added: Wp Bakery Page Builder widget.
- Added: Ajax search for posts and stories.
- Added: Story circle color options.
- Added: Story background color options.
- Added: Story opener button.
- Added: Custom post type support.
- Updated: Translation files and strings.
- Improved: Video and image positions.
- Improved: Third party scripts.
- Improved: Rest api route.
- Improved: Video mute button.
- Improved: Css styles.
- Fixed: Removed unnecessary url parameters.
- Fixed: License activation issue.</pre>
<hr>

<h3>1.0.0</h3>
<pre>- Plugin released.</pre>
<style>
	[data-section-id="changelognew"] pre {
		background: #eee;
		padding: 15px;
		border-radius: 2px;
		overflow: auto;
	}

	[data-section-id="changelognew"] h3:first-child {
		margin-top: 0;
	}

	[data-section-id="changelognew"] .csf-field-content {
		max-height: 750px;
		overflow-y: auto;
	}
</style>
