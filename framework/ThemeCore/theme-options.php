<?php
/**
 * Initialize the custom Theme Options.
 */
add_action( 'init', 'custom_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.3.0
 */
function custom_theme_options() {

  /* OptionTree is not loaded yet, or this is not an admin request */
  if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() )
    return false;

  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( ot_settings_id(), array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    'contextual_help' => array( 
      'content'       => array( 
        array(
          'id'        => 'option_types_help',
          'title'     => __( 'Option Types', 'option-tree-theme' ),
          'content'   => '<p>' . __( 'Help content goes here!', 'option-tree-theme' ) . '</p>'
        )
      ),
      'sidebar'       => '<p>' . __( 'Sidebar content goes here!', 'option-tree-theme' ) . '</p>'
    ),
    'sections'        => array(
      array(
      	'id'		=> 'option_general',
      	'title'		=> 'General'
      ),
      array(
      	'id'		=> 'option_booking',
      	'title'		=> 'Booking'
      ),
      array(
      	'id'		=> 'option_category',
      	'title'		=> 'Kategori'
      )
    ),
    'settings'        => array(
    	array(
    		'id'		  => 'general_logo',
    		'label'		  => 'Logo Website',
    		'desc'		  => 'This option used for change logo for the website',
    		'std'         => '',
	        'type'        => 'upload',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
    		'id'		  => 'general_phonenumber',
    		'label'		  => 'Nomor Telepon Official',
    		'desc'		  => 'Used for tell visitor your official phone number.',
    		'std'         => '',
	        'type'        => 'text',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
    		'id'		  => 'general_email1',
    		'label'		  => 'Email Official #1',
    		'desc'		  => 'Used for tell visitor your official email.',
    		'std'         => '',
	        'type'        => 'text',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
    		'id'		  => 'general_email2',
    		'label'		  => 'Email Official #2',
    		'desc'		  => 'Used for tell visitor your official email.',
    		'std'         => '',
	        'type'        => 'text',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
    		'id'		  => 'general_address',
    		'label'		  => 'Official Address',
    		'desc'		  => 'Used for tell visitor your official address.',
    		'std'         => '',
	        'type'        => 'textarea-simple',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
	        'id'          => 'general_social_links',
	        'label'       => __( 'Social Links', 'option-tree-theme' ),
	        'desc'        => '<p>The social media you have for.</p>',
	        'std'         => '',
	        'type'        => 'social-links',
	        'section'     => 'option_general',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
	    array(
    		'id'		  => 'booking_email',
    		'label'		  => 'Email Destination for Booking',
    		'desc'		  => 'Used for sending you email if customer booking a packet.',
    		'std'         => '',
	        'type'        => 'text',
	        'section'     => 'option_booking',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => '',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
    	),
    	array(
	        'id'          => 'kategori_tour_indonesia',
	        'label'       => 'Kategori Tour Indonesia',
	        'desc'        => 'Digunakan untuk menyeting kategori manakah yang termasuk kategori Indonesia',
	        'std'         => '',
	        'type'        => 'taxonomy-select',
	        'section'     => 'option_category',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => 'region',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
	    array(
	        'id'          => 'kategori_tour_international',
	        'label'       => 'Kategori Tour International',
	        'desc'        => 'Digunakan untuk menyeting kategori manakah yang termasuk kategori International',
	        'std'         => '',
	        'type'        => 'taxonomy-select',
	        'section'     => 'option_category',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => 'region',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
	    array(
	        'id'          => 'kategori_place_tourism',
	        'label'       => 'Kategori Objek Wisata',
	        'desc'        => 'Digunakan untuk menyeting kategori tempat manakah yang termasuk kategori Objek Wisata',
	        'std'         => '',
	        'type'        => 'taxonomy-select',
	        'section'     => 'option_category',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => 'categoryplace',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
	    array(
	        'id'          => 'kategori_place_food',
	        'label'       => 'Kategori Wisata Kuliner',
	        'desc'        => 'Digunakan untuk menyeting kategori tempat manakah yang termasuk kategori Wisata Kuliner',
	        'std'         => '',
	        'type'        => 'taxonomy-select',
	        'section'     => 'option_category',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => 'categoryplace',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
	    array(
	        'id'          => 'kategori_place_religion',
	        'label'       => 'Kategori Wisata Religi',
	        'desc'        => 'Digunakan untuk menyeting kategori tempat manakah yang termasuk kategori Wisata Religi',
	        'std'         => '',
	        'type'        => 'taxonomy-select',
	        'section'     => 'option_category',
	        'rows'        => '',
	        'post_type'   => '',
	        'taxonomy'    => 'categoryplace',
	        'min_max_step'=> '',
	        'class'       => '',
	        'condition'   => '',
	        'operator'    => 'and'
	    ),
      array(
          'id'          => 'kategori_place_shopping',
          'label'       => 'Kategori Wisata Shopping',
          'desc'        => 'Digunakan untuk menyeting kategori tempat manakah yang termasuk kategori Wisata Belanja',
          'std'         => '',
          'type'        => 'taxonomy-select',
          'section'     => 'option_category',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => 'categoryplace',
          'min_max_step'=> '',
          'class'       => '',
          'condition'   => '',
          'operator'    => 'and'
      )
    )
  );
  
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings ); 
  }
  
  /* Lets OptionTree know the UI Builder is being overridden */
  global $ot_has_custom_theme_options;
  $ot_has_custom_theme_options = true;
  
}