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

use function tribe_get_option;
use function wp_kses_post;

/**
 * Class Main
 *
 * @since 1.0.0
 */
class Custom_Content {

    /**
     * Extension initialization and hooks.
     */
    public function init() {

        // Add custom content before the content
        add_filter( 'tribe_tickets_ticket_email_top', [ $this, 'content_before' ] );

        // Add custom content after the content
        add_filter( 'tribe_tickets_ticket_email_bottom', [ $this, 'content_after' ] );
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
}