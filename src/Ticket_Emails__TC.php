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

use TEC\Tickets\Commerce\Settings;
use function tribe_get_option;

/**
 * Class Ticket_Emails_TPP
 *
 * @since 1.0.0
 */
class Ticket_Emails__TC extends Ticket_Emails__Abstract {
    /**
     * Add filter for the email subject.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_subject_actions() {
        add_filter( 'tribe_tickets_ticket_email_subject', [ $this, 'get_subject' ] );
    }

    /**
     * Add filter for the email headers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_header_actions() {
        add_filter( 'tribe_tickets_ticket_email_headers', [ $this, 'get_headers' ], 100, 6 );
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
    public function get_headers( $headers, $post_id, $order_id, $tickets, $provider, $args ) {
        if ( empty( $post_id ) || 'tpp' !== $provider ) {
            return $headers;
        }

        // String to store the email headers. 
        $headers = "Content-Type: text/html" . "\r\n";

        // Set the default from name. 
        $default_from_name = tribe_get_option( Settings::$option_confirmation_email_sender_name, false );

        // Set the default from email. 
        $default_from_email = tribe_get_option( Settings::$option_confirmation_email_sender_email, false );

        // Set the from name with our custom value.
        $from_name = $this->get_option( 'ticketEmailsFromName', $default_from_name );

        // Set the from email with our custom value.
        $from_email = $this->get_option( 'ticketEmailsFromEmail', $default_from_email );

        // Add from.
        $headers .= sprintf( "From: %s <%s>", $this->clean_text( $from_name ), $from_email ) . "\r\n";

        // Add reply to.
        $headers .= sprintf( "Reply-To: %s", $from_email ) . "\r\n";

        // Get a string of emails to bcc.
        if( $bcc = $this->get_bcc_emails( $post_id ) ) {
            $headers .= sprintf( "Bcc: %s", $bcc ) . "\r\n";
        }

        // Get a string of emails to cc.
        if( $cc = $this->get_option( 'ticketEmailsCC' ) ) {
            $headers .= sprintf( "Cc: %s", $cc ) . "\r\n";
        }

        return $headers;
    }
}
