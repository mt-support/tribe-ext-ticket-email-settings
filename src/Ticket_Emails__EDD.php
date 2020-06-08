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

        // set the from name with our custom value. fallback to the value set in EDD
        $from_name  = isset( $edd_options['from_name'] ) ? $edd_options['from_name'] : get_bloginfo( 'name' );
        $from_name = tribe_get_option( 'ticketEmailsFromName', $from_name );

        // set the from email with our custom value. fallback to the value set in WooCommerce
		$from_email = isset( $edd_options['from_email'] ) ? $edd_options['from_email'] : get_option( 'admin_email' );
        $from_email = tribe_get_option( 'ticketEmailsFromEmail', $from_email );

        // start the header. add content type.
        $headers = "Content-Type: text/html \r\n";

        // add from
        $headers .= sprintf( "From: %s <%s>\r\n", $this->clean_text( $from_name), $from_email );

        // add reply to
        $headers .= sprintf( "Reply-To: %s \r\n", $from_email );

        // get a string of emails to bcc.
        // this includes any in our setting plus organizers if enabled
        if( $bcc = $this->get_bcc_emails( $event_ids ) ) {
            $headers .= sprintf( "Bcc: %s \r\n", $bcc );
        }

        // Get a string of emails to cc.
        if( $cc = tribe_get_option( 'ticketEmailsCC' ) ) {
            $headers .= sprintf( "Cc: %s \r\n", $cc );
        }

        return $headers;
    }
}
