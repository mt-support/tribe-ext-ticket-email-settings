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

        // String to store the email headers. 
        $headers = "";

        // Set the default from name. 
        $default_from_name = $wc_email->get_from_name();

        // Set the default from email. 
        $default_from_email = $wc_email->get_from_address();

        // Set the from name with our custom value.
        $from_name = $this->get_option( 'ticketEmailsFromName', $default_from_name );

        // Set the from email with our custom value.
        $from_email = $this->get_option( 'ticketEmailsFromEmail', $default_from_email );

        // Add from.
        $headers .= sprintf( "From: %s <%s>", $this->clean_text( $from_name ), $from_email ) . "\r\n";

        // Add reply to.
        $headers .= sprintf( "Reply-To: %s", $from_email ) . "\r\n";

        // Get a list of event ids from the order id.
        $event_ids = tribe_tickets_get_event_ids( $order->get_id() );

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
