<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Meta_Data' ) ) {

	class Pwf_Meta_Data {

		public function __construct() {
		}

		public function query_type() {
			$data = array(
				array(
					'id'   => 'and',
					'text' => esc_html__( 'AND', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'or',
					'text' => esc_html__( 'OR', 'pwf-woo-filter' ),
				),
			);

			return $data;
		}

		public function action_button() {
			$data = array(
				array(
					'id'   => 'reset',
					'text' => esc_html__( 'Reset', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'filter',
					'text' => esc_html__( 'Filter', 'pwf-woo-filter' ),
				),
			);

			return $data;
		}

		public function source_of_options() {

			$source_of_options = array(
				array(
					'id'     => 'attribute',
					'text'   => esc_html__( 'Attribute', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'category',
					'text'   => esc_html__( 'Category', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'tag',
					'text'   => esc_html__( 'Tag', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'taxonomy',
					'text'   => esc_html__( 'Taxonomy', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'stock_status',
					'text'   => esc_html__( 'Stock status', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'orderby',
					'text'   => esc_html__( 'Order by', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'author',
					'text'   => esc_html__( 'Author', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'meta',
					'text'   => esc_html__( 'Custom field (Meta)', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'on_sale',
					'text'   => esc_html__( 'On sale', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'featured',
					'text'   => esc_html__( 'Featured', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
			);

			return $source_of_options;
		}

		public function rules_parameter() {

			$param = array(
				array(
					'id'   => 'attribute',
					'text' => esc_html__( 'Attribute', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'category',
					'text' => esc_html__( 'Category', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'tag',
					'text' => esc_html__( 'Tag', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'taxonomy',
					'text' => esc_html__( 'Taxonomy', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'page',
					'text' => esc_html__( 'Page', 'pwf-woo-filter' ),
				),
			);

			return $param;
		}

		public function rule_equal() {

			$equal = array(
				array(
					'id'   => 'equalto',
					'text' => esc_html__( 'Equal to', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'notequalto',
					'text' => esc_html__( 'Not Equal to', 'pwf-woo-filter' ),
				),
			);

			return $equal;
		}


		public function proudct_categories() {
			$data         = array();
			$product_cats = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				)
			);

			if ( ! empty( $product_cats ) ) {
				$data[] = array(
					'id'   => 'all',
					'text' => esc_html__( 'All', 'pwf-woo-filter' ),
				);
				foreach ( $product_cats as $cat ) {
					$data[] = array(
						'id'   => absint( $cat->term_id ),
						'text' => esc_attr( $cat->name ),
					);
				}
			}

			return $data;
		}

		public function proudct_attributes() {
			$data = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes = wc_get_attribute_taxonomies();
				if ( ! empty( $attributes ) ) {
					foreach ( $attributes as $attribute ) {
						$term_name = wc_attribute_taxonomy_name( $attribute->attribute_name );

						$data[] = array(
							'id'   => esc_attr( $term_name ),
							'text' => esc_attr( $attribute->attribute_label ),
						);
					}
				}
			}

			return $data;
		}

		public function item_display() {
			$display = array(
				array(
					'id'   => 'all',
					'text' => esc_html__( 'All', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'parent',
					'text' => esc_html__( 'Only Parent', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'selected',
					'text' => esc_html__( 'Only Selected', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'except',
					'text' => esc_html__( 'Except Selected', 'pwf-woo-filter' ),
				),
			);

			return $display;
		}

		public function products_orderby() {

			$orderby = array(
				array(
					'id'   => 'menu_order',
					'text' => esc_html__( 'Default sorting', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'popularity',
					'text' => esc_html__( 'Sort by popularity', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'rating',
					'text' => esc_html__( 'Sort by average rating', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'date',
					'text' => esc_html__( 'Sort by latest', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'price',
					'text' => esc_html__( 'Sort by price: low to high', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'price-desc',
					'text' => esc_html__( 'Sort by price: high to low', 'pwf-woo-filter' ),
				),
			);

			return $orderby;
		}

		public function order_by() {
			$order_by = array(
				array(
					'id'   => 'name',
					'text' => esc_html__( 'Name', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'order',
					'text' => esc_html__( 'Menu Order', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'count',
					'text' => esc_html__( 'Count', 'pwf-woo-filter' ),
				),
			);

			return $order_by;
		}

		public function default_toggle_state() {
			$toggle_state = array(
				array(
					'id'     => 'show',
					'text'   => esc_html__( 'Show content', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'hide',
					'text'   => esc_html__( 'Hide content', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
			);

			return $toggle_state;
		}

		public function action_for_empty_options() {
			$action = array(
				array(
					'id'   => 'showall',
					'text' => esc_html__( 'Show all', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'hide',
					'text' => esc_html__( 'Hide', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'markasdisable',
					'text' => esc_html__( 'Mark as disabled', 'pwf-woo-filter' ),
				),
			);

			return $action;
		}

		public function more_options_by() {

			$data = array(
				array(
					'id'   => 'disabled',
					'text' => esc_html__( 'Disabled', 'pwf-woo-filter' ),
				),
				array(
					'id'     => 'scrollbar',
					'text'   => esc_html__( 'Scrollbar', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
				array(
					'id'     => 'morebutton',
					'text'   => esc_html__( 'More button', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
			);

			return $data;
		}

		public function dropdown_style() {
			$data = array(
				array(
					'id'     => 'default',
					'text'   => esc_html__( 'Default', 'pwf-woo-filter' ),
					'is_pro' => 'false',
				),
				array(
					'id'     => 'plugin',
					'text'   => esc_html__( 'Select 2', 'pwf-woo-filter' ),
					'is_pro' => 'true',
				),
			);

			return $data;
		}

		public function price_url_format() {
			$data = array(
				array(
					'id'   => 'dash',
					'text' => esc_html__( 'Parameters through a dash', 'pwf-woo-filter' ),
				),
				array(
					'id'   => 'two',
					'text' => esc_html__( 'Two parameters', 'pwf-woo-filter' ),
				),
			);

			return $data;
		}

		public function meta_key_compare_data() {

			$data = array(
				array(
					'text' => '',
					'id'   => '',
				),
			);

			return $data;
		}

		public function meta_key_type_data() {

			$data = array(
				array(
					'text' => '',
					'id'   => '',
				),
			);

			return $data;
		}

		public function user_roles() {
			$data[] = array(
				'id'   => '',
				'text' => '',
			);

			return $data;
		}
	}
}
