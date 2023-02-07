<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Database_Query' ) ) {
	/**
	 * Used for metafields
	 */

	class Pwf_Database_Query {

		function __construct() {

		}

		/**
		 * Registered Post types
		 *
		 * @since 1.6.6
		 *
		 * @return array All registered post type
		 */
		public function get_all_registered_post_type() {
			$post_types     = array();
			$all_post_types = get_post_types( array( 'public' => true ), 'objects' );
			if ( ! empty( $all_post_types ) ) {
				foreach ( $all_post_types as $key => $post_object ) {
					$post_types[ sanitize_key( $key ) ] = array(
						'id'   => $post_object->name,
						'text' => $post_object->labels->singular_name,
					);
				}
			}

			return $post_types;
		}

		/**
		 * Return all taxonomies related to post type
		 *
		 * @since 1.6.6
		 *
		 * @return array
		 */
		public static function get_post_type_object_taxonomies( $post_type ) {
			$data = array();

			$post_taxonomies = get_object_taxonomies( sanitize_key( $post_type ), 'objects' );
			if ( ! empty( $post_taxonomies ) ) {
				foreach ( $post_taxonomies as $taxonomy ) {
					$data[] = array(
						'id'   => esc_attr( $taxonomy->name ),
						'text' => esc_attr( $taxonomy->label ),
					);
				}
			}

			return $data;
		}

		public function get_proudct_categories() {
			$data = $this->proudct_taxonomies( 'product_cat' );

			if ( ! empty( $data ) ) {
				$data = array_merge( array( self::not_selected_text() ), $data );
			}

			return $data;
		}

		public function get_proudct_tags() {
			$data = $this->proudct_taxonomies( 'product_tag' );

			if ( ! empty( $data ) ) {
				$data = array_merge( array( self::not_selected_text() ), $data );
			}

			return $data;
		}

		public function get_pages( $not_selected = true ) {

			$data = array();

			$pages = get_pages(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'sort_column' => 'post_title',
				)
			);

			if ( ! is_wp_error( $pages ) ) {
				if ( $not_selected ) {
					$data[] = self::not_selected_text();
				}

				foreach ( $pages as $page ) {
					$data[] = array(
						'id'   => absint( $page->ID ),
						'text' => esc_attr( $page->post_title ),
					);
				}
			}

			return $data;
		}

		/**
		 *
		 * return array
		 */
		public function proudct_all_taxonomies( $index_type = 'num', $no_selected_text = true ) {

			$data               = array();
			$exclude            = apply_filters( 'pwf_admin_exclude_taxonomies', array( 'product_type', 'product_visibility', 'product_shipping_class', 'product_tag', 'product_cat' ) );
			$exclude_attributes = array_column( $this->product_attributes(), 'name' );
			$exclude_attributes = apply_filters( 'pwf_admin_exclude_attributes', $exclude_attributes );
			$exclude            = array_merge( $exclude, $exclude_attributes );
			$taxonomies         = $this->get_all_taxonomies( $exclude );

			foreach ( $taxonomies as $taxonomy ) {
				$childrens = self::proudct_taxonomies( $taxonomy['name'] );
				if ( $childrens ) {
					foreach ( $childrens as $key => $child ) {
						$childrens[ $key ]['id'] = esc_attr( $taxonomy['name'] ) . '__' . absint( $child['id'] );
					}
					$childrens = array_merge( self::get_all_text( $taxonomy['name'], $taxonomy['label'] ), $childrens );

					if ( 'slug' === $index_type ) {
						$data[ $taxonomy['name'] ] = array(
							'text'     => esc_attr( $taxonomy['label'] ),
							'children' => $childrens,
						);
					} else {
						$data[] = array(
							'text'     => esc_attr( $taxonomy['label'] ),
							'children' => $childrens,
						);
					}
				}
			}

			if ( ! empty( $data ) ) {
				if ( $no_selected_text ) {
					$data = array_merge( array( self::not_selected_text() ), $data );
				}
			}

			return $data;
		}

		public function proudct_all_attributes() {

			$data       = array();
			$attributes = $this->product_attributes();
			foreach ( $attributes as $attribute ) {
				$childrens = $this->proudct_taxonomies( $attribute['name'] );
				if ( $childrens ) {
					foreach ( $childrens as $key => $child ) {
						$childrens[ $key ]['id'] = esc_attr( $attribute['name'] ) . '__' . absint( $child['id'] );
					}
					$childrens = array_merge( self::get_all_text( $attribute['name'], $attribute['label'] ), $childrens );

					$data[] = array(
						'text'     => esc_attr( $attribute['label'] ),
						'children' => $childrens,
					);
				}
			}

			if ( ! empty( $data ) ) {
				$data = array_merge( array( self::not_selected_text() ), $data );
			}
			return $data;
		}

		public function product_attributes() {

			$attributes = array();

			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attributes[] = array(
						'name'  => wc_attribute_taxonomy_name( $tax->attribute_name ),
						'label' => esc_attr( $tax->attribute_label ),
					);
				}
			}

			return $attributes;
		}

		public function get_users( $is_ajax = false, $roles = array() ) {

			$users = array();

			if ( ! empty( $roles ) ) {
				$roles = array_map( 'esc_attr', $roles );
				if ( in_array( 'all', $roles, true ) ) {
					$roles = array();
				}
			}

			$user_args = array(
				'hide_empty' => true,
				'fields'     => array( 'ID', 'display_name' ),
			);

			if ( ! empty( $roles ) ) {
				$user_args['role__in'] = $roles;
			}

			$get_users = get_users( $user_args );

			if ( ! empty( $get_users ) ) {
				foreach ( $get_users as $user ) {
					if ( $is_ajax ) {
						$users[] = array(
							'id'   => absint( $user->ID ),
							'text' => esc_attr( $user->display_name ),
						);
					} else {
						$users[] = array(
							'label' => esc_attr( $user->display_name ),
							'value' => absint( $user->ID ),
						);
					}
				}
			}

			return $users;
		}

		private function get_all_taxonomies( $exclude = array() ) {
			$taxonomies         = array();
			$woo_taxonomies     = get_object_taxonomies( 'product', 'objects' );
			$exclude_taxonomies = array( 'product_type', 'product_visibility' );
			if ( $exclude ) {
				$exclude_taxonomies = array_merge( $exclude_taxonomies, $exclude );
			}
			foreach ( $woo_taxonomies as $taxonomy ) {
				if ( ! in_array( $taxonomy->name, $exclude_taxonomies, true ) ) {
					$taxonomies[] = array(
						'label' => esc_attr( $taxonomy->label ),
						'name'  => esc_attr( $taxonomy->name ),
					);
				}
			}

			return $taxonomies;
		}

		public function proudct_taxonomies( $taxonomy_name, $parent = 0, $add_all_text = false ) {
			$data = array();
			$args = array(
				'taxonomy'   => $taxonomy_name,
				'hide_empty' => false,
			);

			// Hide WPML plugin taxonomy name translation_priority
			if ( 'translation_priority' === $taxonomy_name ) {
				return $data;
			}

			$terms = get_terms( $args );
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$data[] = array(
						'id'   => absint( $term->term_id ),
						'text' => esc_attr( $term->name ),
					);
				}
			}

			if ( ! empty( $data ) && $add_all_text ) {
				$all_text = array(
					'id'   => 'all',
					'text' => esc_html__( 'All', 'pwf-woo-filter' ),
				);

				$data = array_merge( array( $all_text ), $data );
			}

			return $data;
		}

		public function get_ajax_product_taxonomies( $taxonomy_name, $parent = '' ) {
			$data = array();
			$args = array(
				'taxonomy'   => esc_attr( $taxonomy_name ),
				'hide_empty' => false,
			);

			if ( ! empty( $parent ) && 'all' !== $parent ) {
				$args['parent'] = absint( $parent );
			} else {
				$args['parent'] = 0;
			}

			$terms = get_terms( $args );
			if ( ! is_wp_error( $terms ) ) {
				$data = self::build_hierarchy_taxonomyies( $terms, $taxonomy_name );
			}

			return $data;
		}

		// backend only
		private static function build_hierarchy_taxonomyies( $terms, $taxonomy_name ) {
			$data            = array();
			$is_hierarchical = is_taxonomy_hierarchical( $taxonomy_name );

			foreach ( $terms as $term ) {
				$term_data         = array();
				$term_data['id']   = absint( $term->term_id );
				$term_data['text'] = esc_attr( $term->name );

				if ( $is_hierarchical ) {
					$children = get_terms( self::get_default_child_taxonomy_argments( $taxonomy_name, $term->term_id ) );
					if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
						$term_data['subcat'] = self::build_hierarchy_taxonomyies( $children, $taxonomy_name );
					}
				}
				$data[] = $term_data;
			}

			return $data;
		}

		private static function get_default_child_taxonomy_argments( $taxonomy, $taxonomy_id ) {
			$data = array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'parent'     => $taxonomy_id,
			);
			return $data;
		}

		private static function get_all_text( $name, $label ) {
			$all_text = array(
				array(
					'id'   => $name . '__all',
					'text' => esc_html__( 'All', 'pwf-woo-filter' ) . ' ' . $label,
				),
			);

			return $all_text;
		}

		private static function not_selected_text() {
			return array(
				'id'   => '',
				'text' => esc_html__( 'No selected', 'pwf-woo-filter' ),
			);
		}

		/**
		 * Display all archive post types && taxonomies && pages where filter can display
		 *
		 * @since 1.6.6
		 *
		 * @return array Reutrn all available archive pages
		 */
		public function filter_query_pages( $post_type_name ) {
			$data = array(
				'none' => self::not_selected_text(),
			);

			$exclude_terms = array( 'post_format', 'product_visibility', 'product_type' );

			// Get all post types
			$registered_posts = self::get_all_registered_post_type();
			$post_types       = array();
			
			if ( isset( $registered_posts[ $post_type_name ] ) ) {
				$post_types[ $post_type_name ] = $registered_posts[ $post_type_name ];
				unset( $registered_posts[ $post_type_name ] );
			}
			$post_types['page'] = $registered_posts['page'];
			unset( $registered_posts['page'] );

			foreach ( $registered_posts as $post_key => $post_data ) {
				if ( 'attachment' !== $post_key ) {
					$post_types[ $post_key ] = $post_data;
				}
			}

			foreach ( $post_types as $post_key => $post_type ) {
				if ( 'page' === $post_key )  {
					$pages = $this->get_pages( false );
					if ( ! empty( $pages ) ) {
						foreach ( $pages as $key => $page_data ) {
							$pages[ $key ]['id']     = 'page__' . $page_data['id'];
							$pages[ $key ]['is_pro'] = true;
						}
					}

					$data[ $post_key . '__archive' ] = array(
						'text'     => $post_type['text'],
						'children' => $pages,
					);
				} else {
					$data[ $post_key . '__archive' ] = array(
						'id'   => $post_key . '__archive',
						'text' => $post_type['text'] . ' ' . esc_html__( 'Archive', 'pwf-woo-filter' ),
					);
					// check taxonomies
					$object_taxonomies = self::get_post_type_object_taxonomies( $post_key );
					if ( ! empty( $object_taxonomies ) ) {
						foreach ( $object_taxonomies as $obj_taxonomy ) {
							if ( in_array( $obj_taxonomy['id'], $exclude_terms, true ) ) {
								continue;
							}

							$children_tax = $this->proudct_taxonomies( $obj_taxonomy['id'] );
							if ( ! empty( $children_tax ) ) {
								foreach ( $children_tax as $key => $tax_data ) {
									$children_tax[ $key ]['id']     = $post_key . '__' . $obj_taxonomy['id'] . '__' . $tax_data['id'];
									$children_tax[ $key ]['is_pro'] = true;
								}

								$all_text = array(
									array(
										'id'     => $post_key . '__' . $obj_taxonomy['id'] . '__all',
										'text'   => esc_html__( 'All', 'pwf-woo-filter' ) . ' ' . $obj_taxonomy['text'],
										'is_pro' => true,
									),
								);

								$children_tax = array_merge( $all_text, $children_tax );

								$text = strtolower( $obj_taxonomy['text'] );
								if ( strpos( $text, strtolower( $post_type['text'] ) ) === false ) {
									$obj_taxonomy['text'] = $post_type['text'] . ' ' . $obj_taxonomy['text'];
								}

								$data[ $obj_taxonomy['id'] ]['text']     = $obj_taxonomy['text'];
								$data[ $obj_taxonomy['id'] ]['children'] = $children_tax;
							}
						}
					}
				}
			}

			return $data;
		}

		public function include_taxonomies_from_archive_page( $taxonomies ) {
			$taxonomies = array();
			return $taxonomies;
		}

		public function include_attributes_from_archive_page( $attributes ) {
			$attributes = array();
			return $attributes;
		}
	}
}
