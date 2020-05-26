<?php
/**
 * Ticket Emails WooCommerce
 *
 * This file contains the class for WooCommerce ticket email actions.
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
 * Class Ticket_Emails_Woo
 *
 * @since 1.0.0
 */
class Ticket_Emails__Woo extends Ticket_Emails__Abstract {

    /**
     * Add filters for the email subject for the various providers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_subject_actions() {
        add_filter( 'wootickets_ticket_email_subject', [ $this, 'get_subject' ] );
    }

    /**
     * Add filter for email headers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_header_actions() {
        add_filter( 'woocommerce_email_headers', [ $this, 'get_headers' ], 100, 4 );
    }

    /**
     * Generate the email headers for WooCommerce ticket emails.
     *
     * @since 1.0.0
     *
     * @param string $headers - the default headers
     * @param int $type - the WooCommerce email type
     * @param object $order - the WooCommerce order instance
     * @param object $wc_email - the WooCommerce WC_Email instance
     * @return string
     */
    public function get_headers( $headers, $type, $order, $wc_email ) {

        // We only want to customize woo emails that are our ticket emails.
        if( 'wootickets' !== $type ) {
            return $headers;
        }

        // start the header. add content type.
        $headers = "Content-Type: text/html \r\n";

        // set the from name with our custom value. fallback to the value set in WooCommerce
        $from_name = tribe_get_option( 'ticketEmailsFromName', $wc_email->get_from_name() );

        // set the from email with our custom value. fallback to the value set in WooCommerce
        $from_email = tribe_get_option( 'ticketEmailsFromEmail', $wc_email->get_from_address() );

        // add from
        $headers .= sprintf( "From: %s <%s>\r\n", $this->clean_text( $from_name), $from_email );

        // add reply to
        $headers .= sprintf( "Reply-To: %s \r\n", $from_email );

        // get a list of event ids from the order id
        $event_ids = tribe_tickets_get_event_ids( $order->get_id() );

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
