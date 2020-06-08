<?php
/**
 * Main
 *
 * This is the main class file for the extension.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

/**
 * Class Main
 *
 * @since 1.0.0
 */
class Main {

    /**
     * Extension initialization and hooks.
     */
    public function init() {

        // Load required files
        require_once( 'Ticket_Emails__Abstract.php' );

        // Load and boot our modules
        array_map( function( $module ) {

            // Load the file
            require_once( $module . ".php" );

            // Add namespace
            $module = sprintf( '%s\%s', __NAMESPACE__, $module );

            // Create new instance
            $instance = new $module;

            // Initiate the modules
            $instance->init();
        }, [
            'Settings_Tab',
            'Content',
            'Ticket_Emails__RSVP',
            'Ticket_Emails__TPP',
            'Ticket_Emails__Woo',
            'Ticket_Emails__EDD'
        ]);
    }
}