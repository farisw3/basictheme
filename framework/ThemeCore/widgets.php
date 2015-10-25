<?php

/**
 * Setup Widget
 */

function LH_setup_widgets() {

	$widget_areas = array(
		array(
			'name'	=> __('Slider With Search', 'friedshrimp'),
			'id'	=> 'home-after-menu'
		),
		array(
			'name' 	=> __('Sidebar Right', 'friedshrimp'),
			'id'	=> 'side-widget-area'
		),
		array(
			'name'	=> __('Footer Column 1', 'friedshrimp'),
			'id'	=> 'footer-column-one'
		),
		array(
			'name'	=> __('Footer Column 2', 'friedshrimp'),
			'id'	=> 'footer-column-two'
		),
		array(
			'name'	=> __('Footer Column 3', 'friedshrimp'),
			'id'	=> 'footer-column-three'
		),
		array(
			'name'	=> __('Footer Column 4', 'friedshrimp'),
			'id'	=> 'footer-column-four'
		)
	);

	foreach( $widget_areas as $area ){
        $args = array(
            'name'          => $area['name'],
            'id'            => $area['id'],
            'before_widget' => '<div class="widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget">',
            'after_title'   => '</h2>'
        );

        register_sidebar($args);
    };

}

add_action('widgets_init', 'LH_setup_widgets');

Class ThemeSearch extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( 'friedshrimp_search_widget', 'Theme:Search', array(
				'description' => 'A search form for your site.'
			) );
	}

	function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		echo "<h4>" . $instance['title'] . "</h4>";?>
		<form class="ui icon input fluid" action="<?php echo home_url();?>" method="GET">
			<input type="text" value="" name="s" id="s" />
			<i class="search icon"></i>
		</form>
		<?php
		echo $after_widget;
		echo '<hr>';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => ''
		);
		$title = $instance['title'];
		?>
		<p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
	    </p>
	    <?php
	}

}

Class ThemeCategory extends WP_Widget {
	function __construct() {
		// Instantiate the parent object
		parent::__construct( 'friedshrimp_category_widget', 'Theme:Category', array(
				'description' => 'A list or dropdown of categories.'
			) );
	}
	function widget( $args, $instance ) {
		extract($args);
		$before_widget = '<div class="widget" id="cat_blog">';
		echo $before_widget;
		echo "<h4>" . $instance['title'] . "</h4>";

		$args = array(
			'orderby'           => 'name', 
			'order'             => 'ASC',
			'hide_empty'        => false,
			'exclude'           => array(),
			'exclude_tree'      => array(),
			'include'           => array(),
			'number'            => '',
			'fields'            => 'all',
			'slug'              => '',
			'parent'            => '0',
			'hierarchical'      => true,
			'child_of'          => 0,
			'childless'         => false,
			'get'               => '',
			'name__like'        => '',
			'description__like' => '',
			'pad_counts'        => false, 
			'offset'            => '',
			'search'            => '',
			'cache_domain'      => 'core'
		);

		$list = get_terms( array("category") , $args );

		echo "<ul>";

		foreach ($list as $key => $value) {
			echo '<li>
				<a href="'.home_url( "/".$value->slug ).'">'.$value->name.'</a>
			</li>';
		}

		echo "</ul>";?>

		<?php
		echo $after_widget;
		echo '<hr>';
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array(
			'title' => ''
		);
		$title = $instance['title'];
		?>
		<p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
	    </p>
	    <?php
	}
}

Class ThemeRecentPost extends WP_Widget {
	function __construct() {
		// Instantiate the parent object
		parent::__construct( 'friedshrimp_recent_post_widget', 'Theme:Recent Post', array(
				'description' => 'Your site’s most recent Posts.'
			) );
	}
	function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		echo "<h4>" . $instance['title'] . "</h4>";?>
		<ul class="recent-post">
			<?php
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $instance['jumlah'],
				'order_by' => 'DATE'
			);

			$list = new WP_Query($args);
			if( $list->have_posts() ) : while( $list->have_posts() ) : $list->the_post();
				echo '<li>
					<i class="gambar gambar-calendar-empty"></i>
					'.get_the_date( ).'
					<div>
						<a href="'.get_permalink().'">'.get_the_title().'</a>
					</div>
				</li>';
			endwhile;endif;
			?>
		</ul>
		<?php echo $after_widget;
		echo '<hr>';
	}
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['date'] = ( ! empty( $new_instance['recentpostdatee'] ) ) ? strip_tags( $new_instance['recentpostdate'] ) : '';
		$instance['jumlah'] = ( ! empty( $new_instance['jumlah'] ) ) ? strip_tags( $new_instance['jumlah'] ) : '';
		return $instance;
	}
	function form( $instance ) {
		$defaults = array(
			'title' => '',
			'jumlah' => 5,
			'date' => false
		);

		$title = $instance['title'];
		$checkbox = $instance['date'] !== false ? " checked=\"checked\"" : "";
		$jumlah = $instance['jumlah'];

		?>
		<p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'jumlah' ); ?>">Number of posts to show:</label>
			<input id="<?php echo $this->get_field_id( 'jumlah' ); ?>" name="<?php echo $this->get_field_name( 'jumlah' ); ?>" type="text" value="<?php echo $jumlah; ?>" size="3">
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'recentpostdate' ); ?>" name="<?php echo $this->get_field_name( 'recentpostdate' ); ?>" value="1"<?php echo $checkbox;?>>
			<label for="<?php echo $this->get_field_id( 'recentpostdate' ); ?>">Display post date?</label>
		</p>
	    <?php
	}
}

Class ThemeTagCloud extends WP_Widget {
	function __construct() {
		// Instantiate the parent object
		parent::__construct( 'friedshrimp_tags_cloud_widget', 'Theme:Tags Cloud', array(
				'description' => 'A cloud of your most used tags.'
			) );
	}
	function widget( $args, $instance ) {

	}
	function update( $new_instance, $old_instance ) {

	}
	function form( $instance ) {

	}
}

function friedshrimp_register_widgets() {
	register_widget( 'ThemeSearch' );
	register_widget( 'ThemeCategory' );
	register_widget( 'ThemeRecentPost' );
	register_widget( 'ThemeTagCloud' );
}

add_action( 'widgets_init', 'friedshrimp_register_widgets' );