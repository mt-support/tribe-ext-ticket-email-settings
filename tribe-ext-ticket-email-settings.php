<?php
/**
 * Plugin Name:     Events Tickets Extension: Ticket Email Settings
 * Plugin URI:      https://theeventscalendar.com/extensions/tribe-ext-ticket-email-settings
 * GitHub Plugin URI: https://github.com/mt-support/tribe-ext-ticket-email-settings
 * Description:     An extension that adds a tab for ticket email settings in the event settings.
 * Version:         1.0.0
 * Extension Class: Tribe__Extension__Ticket_Email_Settings
 * Author:          Modern Tribe, Inc.
 * Author URI:      http://m.tri.be/1971
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     tribe-ext-ticket-email-settings
 */

// Do not load directly.
if( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

// Do not load unless Tribe Common is fully loaded.
if( ! class_exists( 'Tribe__Extension' ) ) {
    return;
}

/**
 * Class Tribe__Extension__Ticket_Email_Settings
 *
 * @since 1.0.0
 */
class Tribe__Extension__Ticket_Email_Settings extends Tribe__Extension {

    private static $version = "1.0.0";

    /**
     * Setup the Extension's properties.
     */
    public function construct() {
        $this->add_required_plugin( 'Tribe__Tickets__Main' );
    }

    /**
     * Extension initialization and hooks.
     */
    public function init() {

        // Load required files
        require_once( 'src/Main.php' );

        $plugin = new Tribe__Extension__Ticket_Email_Settings\Main;
        $plugin->init();
    }
}
