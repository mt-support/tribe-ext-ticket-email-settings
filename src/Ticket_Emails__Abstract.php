<?php
/**
 * Ticket Emails Abstract
 *
 * This file contains an abstract class for generating ticket email actions.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

use function tribe_get_option;
use function tribe_has_organizer;
use function tribe_get_organizer_ids;
use function tribe_get_organizer_email;

/**
 * Class Ticket_Emails__Abstract
 *
 * Base class for adding provider email actions.
 *
 * @since 1.0.0
 */
class Ticket_Emails__Abstract {

    /**
     * Extension initialization and hooks.
     */
    public function init() {

        // Add actions related to the email header
        $this->add_header_actions();

        // Add actions related to the email subject
        $this->add_subject_actions();
    }

    /**
     * Add filters for email headers for the various providers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_header_actions() {}

    /**
     * Add filters for the email subject for the various providers.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_subject_actions() {}

    /**
     * Filter for the email subject
     *
     * @since 1.0.0
     *
     * @param string $subject
     * @return string
     */
    public function get_subject( $subject ) {

        $subject = tribe_get_option( 'ticketEmailsSubject', $subject );

        return $this->clean_text( $subject );
    }

    /**
     * Get a comma separated list of email addresses to add to the BCC header
     *
     * @since 1.0.0
     *
     * @param mixed $event_ids - post id or array of post ids
     * @return string
     */
    public function get_bcc_emails( $event_ids ) {

        // Get the bcc field content, convert to array
        $bcc = explode(',', tribe_get_option( 'ticketEmailsBCC', '' ) );

        // If we have enabled copying in the organizers
        if( tribe_get_option( 'ticketEmailsBCCOrganizers' ) ) {

            // Get an array of organizer emails for all the posts in the event_ids array
            $organizer_emails = $this->get_organizer_emails( $event_ids );

            // Merge all of our email arrays
            $bcc = array_merge( $bcc, $organizer_emails );
        }

        // If there's any emails in the array, convert to comma separated string
        return ( ! empty( $bcc ) ) ? implode( ",", $bcc ) : false;
    }

    /**
     * Get an array of organizer emails from a post id or array of post ids.
     *
     * @since 1.0.0
     *
     * @param mixed $posts - post id or array of post ids
     * @return array
     */
    public function get_organizer_emails( $posts ) {

        // make sure $posts is an array
        if( ! is_array( $posts ) ) {
            $posts = explode( ',', $posts );
        }

        // Create a new array to store emails
        $emails = [];

        // Loop through all of the events
        foreach( $posts as $post_id ) {

            // If the event has organizers set
            if( $post_id && tribe_has_organizer( $post_id ) ) {

                // Get the organizers
                $organizers = tribe_get_organizer_ids( $post_id );

                // Loop through the organizers
                foreach( $organizers as $organizer_id ) {

                    // Get the organizer name
                    $organizer_name = $this->clean_text( tribe_get_organizer( $organizer_id ) );

                    // Get the organizer email address
                    $organizer_email = $this->clean_text( tribe_get_organizer_email( $organizer_id ) );

                    // Add the organizer email info to our emails array
                    $emails[] = $organizer_name . "<$organizer_email>";
                }
            }
        }

        return $emails;
    }

    /**
     * Process text to convert html entities and remove slashes from escaped quotes etc.
     *
     * @since 1.0.0
     *
     * @param string $text - the text to sanitize
     * @return string
     */
    public function clean_text( $text ) {
        return stripslashes_deep( html_entity_decode( $text, ENT_COMPAT, 'UTF-8' ) );
    }
}
