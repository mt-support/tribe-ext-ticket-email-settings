<?php
/**
 * Ticket Emails EDD
 *
 * This file contains the class for Easy Digital Downloads email actions.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

use function tribe_get_option;
use function tribe_tickets_get_event_ids;

/**
 * Class Ticket_Emails_EDD
 *
 * @since 1.0.0
 */
class Ticket_Emails__EDD extends Ticket_Emails__Abstract {

    /**
     * Add filter for the email subject.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_subject_actions() {
        add_filter( 'tribe_rsvp_email_subject', [ $this, 'get_subject' ] );
    }

    /**
     * Add filter for the email headers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_header_actions() {
        add_filter( 'edd_ticket_receipt_headers', [ $this, 'get_headers' ], 100, 3 );
    }

    /**
     * Generate the email headers for Easy Digital Downloads ticket emails.
     *
     * @since 1.0.0
     *
     * @param string $headers - the default headers
     * @param int $payment_id - the EDD ...
     * @param object $payment_data - the EDD ...
     * @return string
     */
    public function get_headers( $headers, $payment_id, $payment_data ) {

        // get a list of event ids from the order id
        $event_ids = tribe_tickets_get_event_ids( $payment_id );

        // if this order doesn't have any tickets/events connected, bail
        if( ! $event_ids || empty( $event_ids ) ) {
            return $headers;
        }

        global $edd_options;

        // String to store the email headers. 
        $headers = "";

        // Set the default from name. 
        $default_from_name = isset( $edd_options['from_name'] ) ? $edd_options['from_name'] : get_bloginfo( 'name' );

        // Set the default from email. 
        $default_from_email = isset( $edd_options['from_email'] ) ? $edd_options['from_email'] : get_option( 'admin_email' );

        // Set the from name with our custom value.
        $from_name = $this->get_option( 'ticketEmailsFromName', $default_from_name );

        // Set the from email with our custom value.
        $from_email = $this->get_option( 'ticketEmailsFromEmail', $default_from_email );

        // Add from.
        $headers .= sprintf( "From: %s <%s>", $this->clean_text( $from_name ), $from_email ) . "\r\n";

        // Add reply to.
        $headers .= sprintf( "Reply-To: %s", $from_email ) . "\r\n";

        // Get a string of emails to bcc.
        if( $bcc = $this->get_bcc_emails( $event_ids ) ) {
            $headers .= sprintf( "Bcc: %s", $bcc ) . "\r\n";
        }

        // Get a string of emails to cc.
        if( $cc = $this->get_option( 'ticketEmailsCC' ) ) {
            $headers .= sprintf( "Cc: %s", $cc ) . "\r\n";
        }

        return $headers;
    }
}
