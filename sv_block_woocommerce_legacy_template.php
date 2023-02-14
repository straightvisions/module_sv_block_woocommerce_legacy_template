<?php
	namespace sv100;

	class sv_block_woocommerce_legacy_template extends init {
		public function init() {
			$this->set_module_title( __( 'Block: WooCommerce Legacy Template', 'sv100' ) )
				->set_module_desc( __( 'Settings for Gutenberg Block', 'sv100' ) )
				->register_scripts();

			// override templates
			add_filter( 'wc_get_template', array( $this, 'wc_get_template' ), 10, 5 );

            // override template parts
            add_filter( 'wc_get_template_part', array( $this, 'wc_get_template_part' ), 10, 4 );

			// add theme support flag
			add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );

			// remove theme support flag
			add_action( 'wp', array( $this, 'remove_theme_support' ) );

			return $this;
		}
		public function wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
			if ( is_file( $this->get_path( 'lib/tpl/frontend/' . $template_name ) ) ){
				return $this->get_path( 'lib/tpl/frontend/' . $template_name );
			} else {
				return $located;
			}
		}
        public function wc_get_template_part( $located, $template, $slug ) {
            if ( is_file( $this->get_path( 'lib/tpl/frontend/' . $template . '-' . $slug . ".php" ) ) ){
                return $this->get_path( 'lib/tpl/frontend/' . $template . '-' . $slug . ".php" );
            } else {
                return $located;
            }
        }

		public function register_scripts(): \sv100\sv_block_woocommerce_legacy_template {
			$this->get_script( 'single' )
			     ->set_path( 'lib/css/common/common.css' );

			return $this;
		}
		public function enqueue_scripts(): \sv100\sv_block_woocommerce_legacy_template {
			if ( function_exists( 'is_product' ) && is_product() ) {
				foreach($this->get_scripts() as $script){
					$script->set_is_enqueued();
				}
			}

			return $this;
		}
		public function add_theme_support() {
			add_theme_support( 'woocommerce', array(
				'thumbnail_image_width' => get_option( 'thumbnail_size_w'),
				'gallery_thumbnail_image_width' => get_option( 'thumbnail_size_w'),
				'single_image_width' => get_option( 'medium_size_w'),
			) );
		}
		public function remove_theme_support() {
			remove_theme_support( 'wc-product-gallery-zoom' );
			remove_theme_support( 'wc-product-gallery-lightbox' );
			remove_theme_support( 'wc-product-gallery-slider' );
		}
	}