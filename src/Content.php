<?php
/**
 * Custom Content
 *
 * This file contains a class to handle adding custom ticket email content.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

use function tribe_get_gcal_link;
use function tribe_get_option;
use function wp_kses_post;

/**
 * Class Main
 *
 * @since 1.0.0
 */
class Content {

    /**
     * Extension initialization and hooks.
     */
    public function init() {

        // Add custom content before the content
        add_action( 'tribe_tickets_ticket_email_top', [ $this, 'content_before' ] );

        // Add custom content after the content
        add_action( 'tribe_tickets_ticket_email_bottom', [ $this, 'content_after' ] );

        // Add gcal link for each event
        add_action( 'tribe_tickets_ticket_email_ticket_bottom', [ $this, 'gcal_link' ], 100 );

        // Control Display of attendee registration information
        add_filter( 'tribe_event_tickets_plus_email_meta_fields', [ $this, 'display_ari' ], 100 );
    }

    /**
     * Callback for outputing custom content at the very beginning of the email contents.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function content_before() {

        $content = tribe_get_option( 'ticketEmailsContentBefore' );

        if( $content ) {
            $this->content_block( $content, 'before' );
        }
    }

    /**
     * Callback for outputing custom content at the very end of the email contents.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function content_after() {

        $content = tribe_get_option( 'ticketEmailsContentAfter' );

        if( $content ) {
            $this->content_block( $content, 'after' );
        }
    }

    /**
     * Utility function for wrapping custom content and displaying in the email.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function content_block( $content, $location ) {

        $content = stripslashes( $content );
        ?>
        <table class="content-<?php echo esc_attr( $location )  ?>" align="center" width="620" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="padding: 16px 0">
            <tbody>
                <tr>
                    <td><?php echo wp_kses_post( $content ) ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /**
     * Add the Google Calendar link to the markup
     *
     * @since 1.0.0
     *
     * @param array $ticket
     * @return void
     */
    public function gcal_link( $ticket ) {

        // Check if TEC is active
        if( ! class_exists( 'Tribe__Events__Main' ) ) {
            return;
        }

        // Check if there's a post id
        if( ! isset( $ticket['event_id'] ) ) {
            return;
        }

        // Check if we've enabled display of the gcal link
        if( ! tribe_get_option( 'ticketEmailsGcal' ) ) {
            return;
        }

        // Get the gcal link
        $link = tribe_get_gcal_link( $ticket['event_id'] );

        // If no link, bail
        if( ! $link ) {
            return;
        }

        // Build the link markup
        $output = sprintf('<a href="%s">%s</a>', esc_url( $link ), esc_attr__( 'Add to Google Calendar', 'tribe-ext-ticket-email-settings' ) );

        // Build and output the content section
        $this->content_block( $output, 'gcal-link' );
    }

    /**
     * Control whether attendee registration is displayed
     *
     * @since 1.0.0
     *
     * @param array $meta_fields
     * @return array
     */
    public function display_ari( $meta_fields ) {

        // If not enabled, return false
        if( ! tribe_get_option( 'ticketEmailsARI' ) ) {
            return false;
        }

        return $meta_fields;
    }
}