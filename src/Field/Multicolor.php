<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-multicolor
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki;
use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Multicolor extends Field {

	/**
	 * Extra logic for the field.
	 *
	 * Adds all sub-fields.
	 *
	 * @access public
	 * @param array $args The arguments of the field.
	 */
	public function init( $args ) {

		/**
		 * Add a hidden field, the label & description.
		 */
		Kirki::add_field(
			$args['kirki_config'],
			wp_parse_args(
				[
					'type'        => 'kirki-generic',
					'default'     => '',
					'choices'     => [
						'type' => 'hidden',
					],
				],
				$args
			)
		);

		foreach ( $args['choices'] as $choice => $choice_label ) {
			Kirki::add_field(
				$args['kirki_config'],
				wp_parse_args(
					[
						'type'           => 'kirki-color',
						'settings'       => $args['settings'] . '[' . $choice . ']',
						'parent_setting' => $args['settings'],
						'label'          => '',
						'description'    => $choice_label,
						'default'        => isset( $args['default'][ $choice ] ) ? $args['default'][ $choice ] : '',
					],
					$args
				)
			);
		}
	}

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		if ( $args['settings'] !== $this->args['settings'] ) {
			return $args;
		}

		// Set the sanitize-callback if none is defined.
		if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
			$args['sanitize_callback'] = [ __CLASS__, 'sanitize' ];
		}
		return $args;
	}

	/**
	 * Sanitizes background controls
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $value The value.
	 * @return array
	 */
	public static function sanitize( $value ) {

		foreach ( $value as $key => $subvalue ) {
			$value[ $key ] = \Kirki\Field\Color::sanitize( $subvalue );
		}
		return $value;
	}

	/**
	 * Override parent method. No need to register any setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function add_setting( $wp_customize ) {}

	/**
	 * Override the parent method. No need for a control.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function add_control( $wp_customize ) {}
}
