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
			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

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
			     ->set_path( 'lib/css/common/single.css' );

			return $this;
		}
		public function enqueue_scripts(): \sv100\sv_block_woocommerce_legacy_template {
			// Product Page
			if ( function_exists( 'is_product' ) && is_product() ) {
				$this->get_script( 'single' )->set_is_enqueued();
			}

			return $this;
		}
		public function after_setup_theme() {
			add_theme_support( 'woocommerce' );
		}
	}