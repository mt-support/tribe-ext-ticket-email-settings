<?php
/**
 * Ticket Emails TPP
 *
 * This file contains the class for Tribe Commerce email actions.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

use function tribe_get_option;

/**
 * Class Ticket_Emails_TPP
 *
 * @since 1.0.0
 */
class Ticket_Emails__TPP extends Ticket_Emails__Abstract {

    /**
     * Add filter for the email subject.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_subject_actions() {
        add_filter( 'tribe_tpp_email_subject', [ $this, 'get_subject' ] );
    }

    /**
     * Add filter for the email headers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_header_actions() {
        add_filter( 'tribe_tpp_email_headers', [ $this, 'get_headers' ], 100, 2 );
    }

    /**
     * Generate the email headers for Tribe Commerce.
     *
     * @since 1.0.0
     *
     * @param string $headers - the default headers
     * @param int $post_id - the post id of the event.
     * @return string
     */
    public function get_headers( $headers, $post_id ) {

        // set the from name with our custom value. fallback to the value set in WooCommerce
        $from_name = tribe_get_option( 'ticketEmailsFromName' );

        // set the from email with our custom value. fallback to the value set in WooCommerce
        $from_email = apply_filters( 'tribe_tpp_email_from_email', tribe_get_option( 'ticket-paypal-confirmation-email-sender-email', false ), $post_id, $order_id );
        $from_email = tribe_get_option( 'ticketEmailsFromEmail', $from_email );

        // start the header. add content type.
        $headers = "Content-Type: text/html \r\n";

        // add from
        $headers .= sprintf( "From: %s <%s>\r\n", $this->clean_text( $from_name), $from_email );

        // add reply to
        $headers .= sprintf( "Reply-To: %s \r\n", $from_email );

        // get a string of emails to bcc.
        // this includes any in our setting plus organizers if enabled
        if( $bcc = $this->get_bcc_emails( $post_id ) ) {
            $headers .= sprintf( "Bcc: %s \r\n", $bcc );
        }

        // Get a string of emails to cc.
        if( $cc = tribe_get_option( 'ticketEmailsCC' ) ) {
            $headers .= sprintf( "Cc: %s \r\n", $cc );
        }

        return $headers;
    }
}
