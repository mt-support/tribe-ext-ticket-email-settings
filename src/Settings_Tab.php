<?php
/**
 * Settings Tab
 *
 * This file contains functionality for creating a settings tab.
 *
 * @package   Tribe Extension: Ticket Email Settings
 * @copyright 2020 Modern Tribe
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://github.com/mt-support/tribe-ext-ticket-email-settings
 */

namespace Tribe__Extension__Ticket_Email_Settings;

use Tribe__Settings_Tab;

/**
 * Class Settings_Tab
 *
 * @since 1.0.0
 */
class Settings_Tab {

    /**
     * Add action to create settings tab.
     */
    public function init() {

        // Create the settings panel
        add_action( 'tribe_settings_do_tabs', [ $this, 'add_settings_tabs' ] );
    }

    /**
     * Register the settings tab and fields.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_settings_tabs() {
        new Tribe__Settings_Tab( 'ticket-emails', __( 'Ticket Emails', 'tribe-ext-ticket-email-settings' ), $this->get_field_data() );
    }

    /**
     * Get array of field data
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function get_field_data() {

        $field_data = [
            'priority' => 30,
            'fields'   => [
                'info-start' => [
                    'type' => 'html',
                    'html' => '<div id="modern-tribe-info">',
                ],
                'info-box-title' => [
                    'type' => 'html',
                    'html' => '<h2>' . __( 'Ticket Emails', 'tribe-ext-ticket-email-settings' ) . '</h2>',
                ],
                'info-box-description' => [
                    'type' => 'html',
                    'html' => '<p>' . __( 'Customize the ticket emails. Supports RSVPs and Tickets from all ecommerce providers.', 'tribe-ext-ticket-email-settings' ) . '</p>',
                ],
                'info-end' => [
                    'type' => 'html',
                    'html' => '</div>',
                ],
                'tribe-form-content-start' => [
                    'type' => 'html',
                    'html' => '<div class="tribe-settings-form-wrap">',
                ],
                'eventsOnlineFieldHelperEmail' => [
                    'type' => 'html',
                    'html' => '<h3>' . __( 'Email options', 'tribe-ext-ticket-email-settings' ) . '</h3>',
                ],
                'ticketEmailsFromName' => [
                    'type' => 'text',
                    'label' => __('From name', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'html',
                    'size' => 'large',
                ],
                'ticketEmailsFromEmail' => [
                    'type' => 'text',
                    'label' => __('From email', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'email',
                    'size' => 'large',
                ],
                'ticketEmailsSubject' => [
                    'type' => 'text',
                    'label' => __('Subject', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'html',
                    'size' => 'large',
                ],
                'ticketEmailsBCC' => [
                    'type' => 'text',
                    'label' => __('BCC', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'textarea',
                    'size' => 'large',
                ],
                'ticketEmailsBCCOrganizers' => [
                    'type' => 'checkbox_bool',
                    'label' => __('BCC event organizers', 'tribe-ext-ticket-email-settings'),
					'tooltip' => esc_html__( 'Check this box to send event organizers a copy of the ticket emails.', 'tribe-ext-ticket-email-settings' ),
                    'can_be_empty' => true,
                    'validation_type' => 'boolean',
                    'size' => 'large',
                ],
                'ticketEmailsContentBefore' => [
                    'type' => 'textarea',
                    'label' => __('Before content', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'html',
                    'size' => 'large',
                ],
                'ticketEmailsContentAfter' => [
                    'type' => 'textarea',
                    'label' => __('After content', 'tribe-ext-ticket-email-settings'),
                    'can_be_empty' => true,
                    'validation_type' => 'html',
                    'size' => 'large',
                ],
            ]
        ];

        return $field_data;
    }
}
