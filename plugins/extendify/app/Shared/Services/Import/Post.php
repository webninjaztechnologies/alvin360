<?php
/**
 * Posts class
 */

namespace Extendify\Shared\Services\Import;

defined('ABSPATH') || die('No direct access.');

/**
 * This class responsible for querying and updating the posts.
 */
class Post
{
    /**
     * Returns all the posts needed for the update.
     *
     * @return mixed
     */
    public static function all()
    {
        $wpdb = $GLOBALS['wpdb'];

	      // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->posts} WHERE post_status != 'trash'
                  AND (INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0)
                  AND post_type NOT IN ('revision', 'attachment')",
                'extendify-image-import',
                ' ext-import"',
                ' ext-import ',
                '"ext-import '
            )
        );
    }

    /**
     * Returns all the posts needed for the update.
     *
     * @return mixed
     */
    public static function countPostsNeedingUpdate()
    {
        $wpdb = $GLOBALS['wpdb'];

	      // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT count(ID) as posts_count FROM {$wpdb->posts} WHERE post_status != 'trash'
                  AND (INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0 OR INSTR(post_content, %s) > 0)
                  AND post_type NOT IN ('revision', 'attachment')",
                'extendify-image-import',
                ' ext-import"',
                ' ext-import ',
                '"ext-import '
            )
        );
    }

    /**
     * Check if the post is not being edited at the moment.
     *
     * @param int $postId the WordPress post id.
     * @return int
     */
    public static function isLocked($postId)
    {
        require_once ABSPATH . '/wp-admin/includes/post.php';
        return \wp_check_post_lock($postId);
    }

    /**
     * Update the post with the new content
     *
     * @param \WP_Post $post    the WordPress post.
     * @param string   $content the new content of the post.
     * @return int|\WP_Error The post ID on success. The value 0 or WP_Error on failure.
     */
    public static function update($post, $content)
    {
        /**
         * Compare the saved modification time in the database
         * with the one we have for the post, if they are not identical
         * we abort the update completely.
         */
        $currentUpdatedAt = get_post_field('post_modified', $post->ID);
        // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        if (strtotime($currentUpdatedAt) !== strtotime($post->post_modified)) {
            return new \WP_Error(1006, 'Post has been modified.');
        }

        // Recheck the database to ensure that the post is not being edited at the moment.
        if (self::isLocked($post->ID)) {
            return new \WP_Error(1005, 'Post is locked.');
        }

        // Update the post.
        $updatedPost = [
            'ID' => $post->ID,
            'post_content' => $content,
        ];

        return wp_update_post($updatedPost);
    }
}
