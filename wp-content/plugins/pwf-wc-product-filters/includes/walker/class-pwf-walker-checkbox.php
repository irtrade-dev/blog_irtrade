<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Walker_Checkbox' ) ) {

	class Pwf_Walker_Checkbox extends Pwf_Walker {

		// Displays start of a level. E.g '<ul>'
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( ! empty( $output ) ) {
				$output .= '<div class="pwf-children">';
			}
		}

		// Displays end of a level. E.g '</ul>'
		function end_lvl( &$output, $depth = 0, $args = array() ) {
			if ( ! empty( $output ) ) {
				$output .= '</div>';
			}
		}

		// Displays start of an element. E.g '<li> Item Name'
		function start_el( &$output, $object, $depth = 0, $args = array(), $id = 0 ) {
			$visibilty = true;
			$checked   = '';
			$disabled  = '';
			$css_class = $this->get_item_css( $args['has_children'] );
			if ( ! empty( $this->selected_values ) ) {
				//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				if ( in_array( $object->term_id, $this->selected_values ) ) {
					$css_class .= ' checked';
					$checked    = ' checked';
				}
			}

			if ( 'hide' === $this->filter_item['action_for_empty_options'] && 1 > $object->count ) {
				$visibilty = false;
			} elseif ( 'markasdisable' === $this->filter_item['action_for_empty_options'] && 1 > $object->count ) {
				$css_class .= ' pwf-disabled';
				$disabled   = ' disabled';
			}

			if ( $visibilty ) {
				array_push( $this->items_visibilty, $object->term_id );

				$output .= '<div class="' . $css_class . '">';
				$output .= '<div class="pwf-item-inner">';
				$output .= '<div class="pwf-item-label pwf-checkbox-label">';
				$output .= '<div class="pwf-input-container pwf-checkbox-click-area"><input type="checkbox" class="pwf-input pwf-input-checkbox" data-slug="' . $object->slug . '" name="' . $this->name . '" value="' . $object->term_id . '"' . $checked . $disabled . '></div>';
				$output .= '<div class="pwf-title-container pwf-checkbox-click-area"><span class="text-title">' . $object->name . '</span>';
				if ( 'on' === $this->filter_item['display_product_counts'] && $object->count > 0 ) {
					$output .= Pwf_Render_Filter_Fields::get_html_template_item_count( $object->count );
				}
				$output .= '</div>';
				$output .= $this->get_html_toggle( $args['has_children'] );
				$output .= '</div>';
			}
		}

		// Displays end of an element. E.g '</li>'
		function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( in_array( $item->term_id, $this->items_visibilty, true ) ) {
				$output .= '</div></div>';
			}
		}
	}
}
