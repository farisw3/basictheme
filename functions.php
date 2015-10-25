<?php

if( !class_exists('ThemeName') ) :

	Class ThemeName {

		/**
         * @var string
         */
        public $version = '0.0.0';

        /**
         * Init
         */
        public static function init() {
            $sendiri = new self();
        }

        /**
         * Constructor
         */
        public function __construct() {
            $this->define_constants();
            $this->bahan();
            $this->actions();
        }


        public function define_constants() {

        	define( 'THEME_BASE_FRAMEWORK', TEMPLATEPATH . '/framework/');

			define( 'THEME_CORE_FRAMEWORK', THEME_BASE_FRAMEWORK . 'ThemeCore/');

        }

        public function bahan() {

        	require_once( THEME_CORE_FRAMEWORK . 'functions.php' );

			require_once( THEME_CORE_FRAMEWORK . 'theme-options.php');

			require_once( THEME_CORE_FRAMEWORK . 'widgets.php');

			require_once( THEME_CORE_FRAMEWORK . 'metabox.php' );

        }

        public function actions() {

        	$this->theme_setup();

        	add_action( 'wp_enqueue_scripts', array( $this, 'theme_scripts_and_styles' ) );

        	// Register Menu
        	register_nav_menus( array(
        		'footer' => __("Footer Menu", 'yellowjacket'),
        		'primary' => __("Primary Menu", 'yellowjacket')
        	) );

        	// Change excerpt more settings
        	add_filter('excerpt_more', array( $this, 'new_excerpt_more') );
        	add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length', 999) );

        	// Filter Title
        	add_filter( 'wp_title', array( $this, 'theme_name_wp_title' ), 10, 2 );

        	// Register New Post Types
        	add_action('init', array( $this, 'new_post_types') );

        	// Register New Taxonomies/Categories
        	add_action('init', array( $this, 'new_taxonomies' ) );

        	// Remove Icon wordpress
        	add_filter('admin_footer_text', array( $this, 'remove_footer_admin') );
        	add_action('wp_before_admin_bar_render', array( $this, 'annointed_admin_bar_remove' ), 0);

        	// Flush Rules
        	add_action( 'after_switch_theme', 'flush_rewrite_rules' );

        	// Post clauses with taxes
        	add_filter('posts_clauses', array( $this, 'posts_clauses_with_tax' ), 10, 2);

        	// Setting Option Tree
        	add_filter( 'ot_options_id', array( $this, 'filter_demo_options_id' ) );
        	add_filter( 'ot_settings_id', array( $this, 'filter_demo_settings_id' ) );
        	add_filter( 'ot_layouts_id', array( $this, 'filter_demo_layouts_id' ) );
        	add_action( 'ot_header_list', array( $this, 'filter_demo_header_list' ) );

        	// Setting Mode Option Tree
        	add_filter( 'ot_theme_mode', '__return_false' );
        	add_filter( 'ot_child_theme_mode', '__return_false' );
        	add_filter( 'ot_show_pages', '__return_false' );
        	add_filter( 'ot_show_options_ui', '__return_false' );
        	add_filter( 'ot_show_settings_import', '__return_false' );
        	add_filter( 'ot_show_settings_export', '__return_false' );
        	add_filter( 'ot_show_new_layout', '__return_false' );
        	add_filter( 'ot_show_docs', '__return_false' );
        	add_filter( 'ot_use_theme_options', '__return_true' );
        	add_filter( 'ot_meta_boxes', '__return_true' );
        	add_filter( 'ot_allow_unfiltered_html', '__return_false' );
        	add_filter( 'ot_post_formats', '__return_true' );
        }

        private function theme_setup() {
        	/**
			* Add default posts and comments RSS feed links to head
			**/

			add_theme_support( 'automatic-feed-links' );

			/**
			* Enable support for Post Thumbnails
			*/

			add_theme_support( 'post-thumbnails' ); 

			/*
			* Custom image size
			*/		

			add_image_size( 'FS-homepage-thumbnail', 345, 167, true); // (cropped)
			add_image_size( 'FS-featured-thumbnail', 890, 395, true );
			add_image_size( 'FS-related-thumbnail', 320, 140, true );

			add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
        }

        public function theme_scripts_and_styles() {

        	wp_enqueue_style( 'vendor', get_bloginfo( 'stylesheet_directory' ) . '/assets/css/vendor.css' );
			wp_enqueue_style( 'main-custom', get_bloginfo( 'stylesheet_directory' ) . '/assets/css/main-custom.css' );
			wp_enqueue_style( 'theme-icons', get_bloginfo( 'stylesheet_directory' ) . '/assets/css/theme-icons.css' );

			wp_enqueue_script( 'theme-full', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/theme-full.js');

        }

        public function new_excerpt_more( $more ) {
			return '...';
		}

		public function custom_excerpt_length( $length ) {
			return 20;
		}

		public function theme_name_wp_title( $title, $sep ) {

			if( is_feed() ) {
				return $title;
			}

			global $page, $paged;

			// Add the blog name
			$title .= get_bloginfo( 'name', 'display' );

			// Add the blog description for the home/front page
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) ) {
				$title .= " $sep $site_description";
			}

			// Add a page number if necessary:
			if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
				$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
			}

			$lhpage = strtolower(get_query_var('lhpage'));

			if( $lhpage == 'hotdeals' ) {
				$title = "Promo $sep " . get_bloginfo( 'name', 'display' );
			}

			if( $lhpage == 'tickets' ) {
				$title = "Tiket $sep " . get_bloginfo( 'name', 'display' );
			}

		        if( $lhpage == "checkout" ) {
				$title = "Checkout $sep " . get_bloginfo( 'name', 'display' );
		        }
			
			return $title;
		}

		public function new_post_types() {

			$types = array(
				'tours' => array(
					'menu_title'	=> 'Paket Tour',
					'single'		=> 'Paket Tour',
					'plural'		=> 'Paket Tour',
					'supports'		=> array('title', 'thumbnail', 'category', 'editor'),
					'slug'			=> 'tours-packet',
					'public'		=> true,
					'has_archive'	=> true,
					'rewrite'		=> array('slug' => $args['slug']),

					'publicly_queriable' => true,
					'show_ui'		=> true,
					'exclude_from_search' => true,
					'show_in_nav_menus' => true,
					'menu_icon'		=> 'dashicons-index-card',
					'taxonomies' => array('post_tag'),
				),

				'hotels' => array(
					'menu_title'	=> 'Hotel',
					'single'		=> 'Hotel',
					'plural'		=> 'Hotels',
					'supports'		=> array('title', 'thumbnail', 'category', 'editor'),
					'slug'			=> 'hotel',
					'public'		=> true,
					'has_archive'	=> true,

					'publicly_queriable' => true,
					'show_ui'			 => true,
					'exclude_from_search' => true,
					'show_in_nav_menus'	 => true,
					'menu_icon'			 => 'dashicons-building',
				),

				'places' => array(
					'menu_title'	=> 'Tempat Wisata',
					'single'		=> 'Tempat Wisata',
					'plural'		=> 'Tempat Wisata',
					'supports'		=> array('title', 'thumbnail', 'category', 'editor'),
					'slug'			=> 'tempat-wisata',
					'public'		=> true,
					'has_archive'	=> true,
					'rewrite'		=> array('slug' => $args['slug']),

					'publicly_queriable' => true,
					'show_ui'			 => true,
					'exclude_from_search' => true,
					'show_in_nav_menus'	 => true,
					'menu_icon'			 => 'dashicons-location',
				)

			);

			$counter = 0;

			foreach ($types as $type => $args) {

				$tags = $args['taxonomies'] == '' ? array() : $args['taxonomies'];
				
				$labels = array(
					'name'				=> $args['menu_title'],
					'singular_name'		=> $args['singular'],
					'parent_item_colon'	=> '',
					'menu_name'			=> $args['menu_title'],
					'add_new_item'		=> 'Tambah ' . $args['menu_title'],
					'edit_item'			=> 'Edit ' . $args['menu_title']
				);

				register_post_type( $type, array(
					'labels'			=> $labels,
					'public'			=> $args['public'],
					'has_archive'		=> $args['has_archive'],

					'publicly_queriable'    => $args['publicly_queriable'],
		            'show_ui'               => $args['show_ui'],
		            'exclude_from_search'   => $args['exclude_from_search'],
		            'show_in_nav_menus'     => $args['show_in_nav_menus'],

		            'capability_type'   => 'post',
		            'supports'          => $args['supports'],
		            'rewrite'       => array('slug' => $args['slug']),
		            'menu_position'     => (5 + $counter),
		            'menu_icon'			=> $args['menu_icon'],

		            'taxonomies' => $tags

				) );

				$counter++;
			};

		}

		public function new_taxonomies() {

			$taxs = array(
				'region' => array(
					'menu_title'	=> 'Region',
					'plural'		=> 'Region',
					'single'		=> 'Region',
					'hierarchical'	=> true,
					'slug'			=> 'region',
					'post_type'		=> array('tours', 'places', 'hotels')
				),
				'categoryplace' => array(
					'menu-title'	=> 'Kategori Tempat',
					'plural'		=> 'Kategori Tempat',
					'single'		=> 'Kategori Tempat',
					'hierarchical'	=> true,
					'slug'			=> 'category-place',
					'post_type'		=> 'places'
				)
			);

			foreach( $taxs as $tax => $args ){

		        $labels = array(
		            'name'          => _x( $args['plural'], 'taxonomy general name', 'friedshrimp' ),
		            'singular_name' => _x( $args['singular'], 'taxonomy singular name', 'friedshrimp' ),
		            'search_items'  => __( 'Cari '.strtolower($args['plural']), 'friedshrimp' ),
		            'all_items'  => __( 'Semua', 'friedshrimp' ),
		            'parent_item'  => __( $args['plural'].' induk', 'friedshrimp' ),
		            'parent_item_colon'  => __( 'Cari '.$args['singular'].': ', 'friedshrimp' ),
		            'edit_item'  => __( 'Sunting '.$args['singular'], 'friedshrimp' ),
		            'update_item'  => __( 'Perbarui '.$args['singular'], 'friedshrimp' ),
		            'add_new_item'  => __( 'Tambah '.$args['singular'].' Baru', 'friedshrimp' ),
		            'new_item_name'  => __( 'Nama '.$args['singular'].' Baru', 'friedshrimp' ),
		            'menu_name'  => __( $args['menu_title'], 'friedshrimp' ),
		        );

		        $tax_args = array(
		            'hierarchical'  => $args['hierarchical'],
		            'labels'        => $labels,
		            'public'        => true,
		            'query_var'     => 'jenis',
		            'rewrite'       => array('slug' => $args['slug']),
		            // 'rewrite' => array('hierarchical' => true )
		        );

		        register_taxonomy( $tax, $args['post_type'], $tax_args );

		    }

		}

		public function remove_footer_admin () {
			echo '<span id="footer-thankyou">';
			echo '<a target="_blank" href="http://www.cirebonmedia.com"><img src="'.get_stylesheet_directory_uri() . '/assets/images/logo.png" id="wlcms-footer-logo"> </a> <span> <a target="_blank" href="http://www.cirebonmedia.com">Powered by Cirebon Media Team</a> </span>';
			echo '</span>';
		}

		public function annointed_admin_bar_remove() {

		        global $wp_admin_bar;

		        /* Remove their stuff */

		        $wp_admin_bar->remove_menu('wp-logo');

		}

		public function posts_clauses_with_tax( $clauses, $wp_query ) {
			
			global $wpdb;
			
			//array of sortable taxonomies
			$taxonomies = array('categoryplace');
			if (isset($wp_query->query['orderby']) && in_array($wp_query->query['orderby'], $taxonomies)) {
				$clauses['join'] .= "
					LEFT OUTER JOIN {$wpdb->term_relationships} AS rel2 ON {$wpdb->posts}.ID = rel2.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tax2 ON rel2.term_taxonomy_id = tax2.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
				";
				$clauses['where'] .= " AND (taxonomy = '{$wp_query->query['orderby']}' OR taxonomy IS NULL)";
				$clauses['groupby'] = "rel2.object_id";
				$clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
				$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
			}
			
			return $clauses;
		
		}

	}

endif;

add_action( 'after_setup_theme', array( 'YellowJacket', 'init' ), 10 );
