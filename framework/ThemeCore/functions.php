<?php

require 'basic.php';

$facilty_feature = array('facilty_fullday_tour','facilty_refreshment', 'facilty_breakfest', 'facilty_lunch', 'facilty_dinner', 'facilty_tourguide', 'facilty_hotel', 'facilty_private_transport', 'facilty_ticket');
$facilty_icon = array('child', 'puzzle', '59', '8', '14', '16', '6', '26', '51');
$facilty_name = array('Fullday Tour', 'Refreshment', 'Sarapan', 'Makan Siang', 'Makan Malam', 'Guide', 'Hotel', 'Private Transport', 'Tiket Objek Wisata');

class Menu_Atas extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth ) {

    	$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
    	$display_depth = ( $depth + 1); // because it counts the first submenu as 0
    	$classes = array(
        	'list',
        	( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
        	( $display_depth >=2 ? 'sub-sub-menu' : '' ),
        	'menu-depth-' . $display_depth
        );
    
    	$class_names = implode( ' ', $classes );
 
    	// build html
    	$output .= "\n" . $indent . '<div class="' . $class_names . '">' . "\n";
	}

	function end_lvl( &$output, $depth) {
		$output .= "</div></div>";
	}

	/**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {

        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

        $class_names = join(
            ' item '
        ,   apply_filters(
                'nav_menu_css_class'
            ,   array_filter( $classes ), $item
            )
        );

        ! empty ( $class_names )
            and $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= "";

        $attributes  = '';

        $attributes .= "id='menu-item-$item->ID'";

        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

        $attributes .= " $class_names";

        // insert description for top level elements only
        // you may change this
        $description = ( ! empty ( $item->description ) and 0 == $depth )
            ? '<small class="nav_desc">' . esc_attr( $item->description ) . '</small>' : '';

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        if( $args->walker->has_children ) {
        	$anchor = "<div class='item-sub'>\t<a $attributes>"
            . $args->link_before
            . $title
            . '<i class="gambar gambar-down-open-mini"></i></a>';
        } else {
        	$anchor = "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> ' . "\n";
        }

        $item_output = $args->before
            . $anchor
            . $args->link_after
            . $description
            . $args->after;

        // Since $output is called by reference we don't need to return anything.
        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
    	if( $depth > 0 ) {
    		$output .= "";
    	}
    }

}

function get_one_image( $id, $key, $size = "thumbnail" ) {
	$image = get_post_meta( $id, $key, true );
	$image = is_array($image) ? wp_get_attachment_image( $image[0], $size ) : "";
	return $image;
}

function print_harga_dropdown($selected = '', $class = array(), $name = 'price' ) {

	$class = "ui dropdown fluid " . implode(" ", $class);

	echo '<select class="'.$class.'" name="'.$name.'">
		<option value="">Semua Harga</option>
		<option value="0-500000">IDR 0 to IDR 500.000</option>
		<option value="500000-1000000">IDR 500.000 to IDR 1.000.000</option>
		<option value="1000000-1500000">IDR 1.000.000 to IDR 1.500.000</option>
		<option value="1500000-2000000">IDR 1.500.000 to IDR 2.000.000</option>
		<option value="2000000-2500000">IDR 2.000.000 to IDR 2.500.000</option>
		<option value="2500000-999999">IDR 2.500.000 or more</option>  
	</select>';

}

function print_destination_dropdown($multiple = false, $selected = "", $name = 'region') {

	$name = $multiple ? $name . "[]" : $name;

	$multiple = $multiple ? " multiple" : "";
	
	echo '<select class="ui dropdown fluid" name="'.$name.'" '.$multiple.'>
	<option value="">Semua Destinasi</option>';
	
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
	
	$region = get_terms( array("region") , $args );

	$reg = $_GET['region'] == "" ? array() : $_GET['region'];
	
	foreach ($region as $k) {

		$children = get_term_children( $k->term_id, "region" );

		echo '<optgroup label="' . $k->name . '">';
	
		foreach ($children as $child) {

			$term = get_term_by( 'id', $child, 'region' );

			if( in_array($child, $reg) || ($selected != "" && $child == $selected ) ) {
				echo '<option value="'.$child.'" selected>'.$term->name.'</option>';
			} else {
				echo '<option value="'.$child.'">'.$term->name.'</option>';
			}
	
		}
	
		echo '</optgroup>';
	
	}

	echo '</select>';

}

function get_category_dropdown($terms = '', $multiple = false, $name = 'category') {

	if( $terms == "" )
		return '';

	$m = $multiple ? " multiple" : "";

	$ret = '<select class="ui dropdown fluid" name="'.$name.'" '.$m.'>
	<option value="">Semua Kategori</option>';
											
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
																
	$region = get_terms( array($terms) , $args );

	$name = str_replace("[]", "", $name);

	$v = $_GET[$name] == "" ? array() : $_GET[$name];
																
	foreach ($region as $k) {
		if( in_array($k->term_id, $v) ) {
			$ret .= '<option value="'.$k->term_id.'" selected>'.$k->name.'</option>';
		} else {
			$ret .= '<option value="'.$k->term_id.'">'.$k->name.'</option>';
		}																
	}

	$ret .= '</select>';

	return $ret;
}

function print_category_dropdown($terms = '', $name = 'category') {
	echo get_category_dropdown($terms,$name);
}

function get_categoryplace_dropdown() {
	return get_category_dropdown('categoryplace', true, 'categoryplace[]');
}

function the_categoryplace_dropdown() {
	echo get_categoryplace_dropdown();
}

function cm_pagetitle() {

	$post_type = strtolower(get_query_var('post_type'));

	$region = strtolower($_GET['region']);
	$price = strtolower($_GET['price']);

	if( $post_type != "" ) {

		if( isset($_GET['region']) || isset($_GET['price'])) {
			return 'Hasil Pencarian Tour:';
		} else {
			$obj = get_post_type_object( $post_type );
			return $obj->label;
		}

	}

	if( is_single( 'grouptours' ) ) {
		return get_the_title( get_the_ID() );
	}

	return false;
}

function breadcrumbs() {

	$li = '<li><a href="'.home_url().'">Home</a></li>';

	$post_type = strtolower(get_query_var('post_type'));

	if( $post_type != "" ) {

		if( is_single( $post_type ) ) {

		} else {
			if( isset($_GET['region']) || isset($_GET['price']) ) {
				$li .= '<li class="active">Hasil Pencarian</li>';
			} else {
				$obj = get_post_type_object( $post_type );
				$li .= '<li class="active">'. $obj->label.'</li>';
			}			
		}
	}

	echo $li;

}

function arr_region() {

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
	
	$region = get_terms( array("region") , $args );

	$arr = array();

	foreach ($region as $k) {

		$name = 'region_' . $k->term_id;

		$arr[$name] = array(
			'name' => $name,
			'title' => $k->name
		);

	}

	return $arr;

}

function get_place_icon($id, $inline = true) {

	$objek_wisata = ot_get_option( 'kategori_place_tourism' );
	$kuliner = ot_get_option( 'kategori_place_food' );
	$religi = ot_get_option( 'kategori_place_religion' );
	$shop = ot_get_option( 'kategori_place_shopping' );

	$cl = $inline ? " icon big" : "";

	if( $objek_wisata == $id )
		return '<i class="icon_set_1_icon-4'.$cl.'"></i>';

	if( $kuliner == $id )
		return '<i class="icon_set_1_icon-58'.$cl.'"></i>';

	if( $religi == $id )
		return '<i class="icon_set_1_icon-1'.$cl.'"></i>';

	if( $shop == $id )
		return '<i class="icon_set_1_icon-50'.$cl.'"></i>';


}

function split_title($title, $tag = "span") {
	$judul = explode(" ", $title);
	$jmlh = sizeof($judul);

	$j = $jmlh % 2 == 0 ? $jmlh / 2 : ( ( $jmlh - ($jmlh%2) ) / 2 ) + ($jmlh%2);
	
	$r = "<$tag>";

	for ($i = 0; $i < $j; $i++) { 
		$r .= $judul[$i] ." ";
	}

	$r .= "</$tag>";

	for ($i=$j; $i < $jmlh; $i++) { 
		$r .= $judul[$i]." ";
	}

	return $r;
}

function breadcrumb( $link, $active ) {

	echo '<div id="position">
		<div class="ui container">
			<i class="gambar gambar-location-outline gambar-icon"></i>
			<ul>
                <li><a href="'.home_url().'">Home</a></li>';

    foreach ($link as $key => $value) {

    	if( $key == $active ) {
    		echo '<li><i class="gambar gambar-angle-right"></i> '.$key.'</li>';
    	} else {
    		echo '<li><i class="gambar gambar-angle-right"></i> <a href="'.$value.'">'.$key.'</a></li>';
    	}
    }

    echo '</ul>
		</div>
	</div>';

}

function sortby($sortby) {

	$view = $_GET['view'] == "" ? "grid" : $_GET['view'];

	$link = viewlink($view);

	$sb = strtolower($_GET['order_by']);

	$s = strtolower($_GET['order']);

	if( strpos($link, "order_by=") !== false ) {

		$link = str_replace("order_by=$sb", "order_by=$sortby", $link);
	} else {
		$link .= "&order_by=$sortby";
	}

	$sort =  $sb != $sortby ? "asc" : $sb == $sortby && $s == "desc" ? "asc" : "desc";

	if( strpos($link, "order=") !== false ) {
		$link = str_replace("order=$s", "order=$sort", $link);
	} else {
		$link .= "&order=$sort";
	}

	echo $link;

}

function viewlink($view) {
	$link = $_SERVER['QUERY_STRING'];

	$v = $_GET['view'];

	if( strpos($link, "view") !== false ) {
		$link = str_replace("view=$v", "view=$view", $link);
	} else {
		$link .= "&view=$view";
	}

	$link = "?$link";
	return $link;
}

function viewby($view) {
	echo viewlink($view);
}

function get_rating($rating = 0) {
	$ret = '<div class="rating">';

		for ($i=1; $i <= 5 ; $i++) { 
			if( $i <= $rating ) {
				$ret .= '<i class="gambar gambar-star voted"></i>';
			} else {
				$ret .= '<i class="gambar gambar-star-empty"></i>';
			}
		}

	$ret .= '</div>';

	return $ret;
}

function print_rating($rating = 0) {
	echo get_rating($rating);
}

function qs_input() {

	$view = strtolower($_GET['view']);
	$orderby = strtolower($_GET['order_by']);
	$order = strtolower($_GET['order']);

	echo '<input type="hidden" name="order_by" value='.$orderby.' />';

	echo '<input type="hidden" name="order" value='.$order.' />';

	echo '<input type="hidden" name="view" value='.$view.' />';

}

$default = array(
		'wifi' => 'Wifi di kamar',
		'tv' => 'Plasma TV',
		'pool' => 'Swimming Pool',
		'fitness' => 'Fitness Center',
		'restaurant' => 'Restaurant'
	);

function get_adds_information($key) {
	
	switch($key) {
		case 'wifi':return '<i class="icon_set_1_icon-86"></i>';break;
		case 'tv':return '<i class="icon_set_2_icon-116"></i>';break;
		case 'pool':return '<i class="icon_set_2_icon-110"></i>';break;
		case 'fitness':return '<i class="icon_set_2_icon-117"></i>';break;
		case 'restaurant':return '<i class="icon_set_1_icon-58"></i>';break;
	}
}

function get_adds($adds = "", $title = false) {
	global $default;

	if( $adds == "")
		return;

	$ret = '<ul class="add-info">';

	foreach ($default as $key => $value) {
		if( in_array($key, $adds) ) {
			if( $title ) {
				$ret .= '<li><span>'.get_adds_information($key).' '.$value.'</span></li>';
			} else {
				$ret .= '<li data-content="'.$value.'" data-variation="inverted" class="dipopup"><span>'.get_adds_information($key).'</span></li>';
			}
		}
	}

	$ret .= '</ul>';

	return $ret;
}
function get_feature( $list, $ads = "", $title = false ) {

	if(!is_array($list))
		return false;

	$ret = '<ul class="add-info">';

	foreach ($list as $key => $value) {
		if( $title ) {
			$ret .= '<li><span>'.get_place_icon($value->term_id, false).' '.$value->name.'</span></li>';
		} else {
			$ret .= '<li data-content="'.$value->name.'" data-variation="inverted" class="dipopup"><span>'.get_place_icon($value->term_id, false).'</span></li>';
		}
	}

	$ret .= $ads;

	$ret .= '</ul>';

	return $ret;

}

function sort_value() {
	$order = strtolower($_GET['order_by']);

	switch($order) {
		default:return 'date';break;
		case 'name':return 'title';break;
		case 'price':return 'meta_value meta_value_num date';break;
		case 'category': return 'categoryplace title';break;
	}

	return;
}
function list_facilty($list, $name) {
	if( is_string($list) )
		return;
	$count = sizeof($list);
	$ret = array();
	$a = "";
	$b = "";
	if( $count > 1 ) {

		if( $count > 6 ) {
			if( $count % 2 == 0 ){
				$one = $count/2;
				$two = $one;
			}else {
				$one = $count / 2;
				$two = $count % 2;
			}
			for ($i = 0; $i < $one; $i++) { 
				$item = $list[$i];
				$a .= "<li>$item[$name]</li>";
			}
			for ($i = $one; $i < $two; $i++) { 
				$item = $list[$i];
				$b .= "<li>$item[$name]</li>";
			}
		} else {
			for ($i = 0; $i < $count; $i++) { 
				$item = $list[$i];
				$a .= "<li>$item[$name]</li>";
			}
		}

	} else {
		for ($i = 0; $i < $count; $i++) { 
			$item = $list[$i];
			$a .= "<li>$item[$name]</li>";
		}
	}

	$ret = array($a, $b);

	return $ret;
}

function get_icon_name($id) {
	$objek_wisata = ot_get_option( 'kategori_place_tourism' );
	$kuliner = ot_get_option( 'kategori_place_food' );
	$religi = ot_get_option( 'kategori_place_religion' );
	$shop = ot_get_option( 'kategori_place_shopping' );

	$cl = $inline ? " icon big" : "";

	if( $objek_wisata == $id )
		return 'wisata';

	if( $kuliner == $id )
		return 'food';

	if( $religi == $id )
		return 'religi';

	if( $shop == $id )
		return 'shopping';
}

function get_tour_icon($id) {
	global $facilty_feature, $facilty_icon;

	foreach ($facilty_feature as $key => $f) {
		if( $f == $id)
			$index = $key;
	}

	$icon = $facilty_icon[$index];

	return $icon;
}

function get_tour_facilty_value($id) {
	global $facilty_feature, $facilty_name;

	foreach ($facilty_feature as $key => $f) {
		if( $f == $id)
			$index = $key;
	}

	$icon = $facilty_name[$index];

	return $icon;
}

function get_tour_facilty($list, $title = false) {
	if(!is_array($list))
		return false;

	$ret = '<ul class="add-info">';

	foreach ($list as $key => $value) {
		if( $title ) {
			$ret .= '<li><span><i class="icon_set_1_icon-'.get_tour_icon($value).'"></i> '.get_tour_facilty_value($value).'</span></li>';
		} else {
			$ret .= '<li data-content="'.get_tour_facilty_value($value).'" data-variation="inverted" class="dipopup"><span><i class="icon_set_1_icon-'.get_tour_icon($value).'"></i></span></li>';
		}
	}

	$ret .= $ads;

	$ret .= '</ul>';

	return $ret;
}

function get_minimum_price() {
	$args = array(
		'post_type' => 'grouptours',
		'posts_per_page' => 1,
		'order' => 'DESC',
		'orderby' => 'meta_value meta_value_num',
		'meta_key' => 'tour_price',
		'meta_query' => array(
			'relation' => "AND",
			array(
				'key' => 'tour_price',
				'value' => ' ',
				'compare' => '!=',
			),
			array(
				'key' => 'tour_price',
				'value' => array(0,9999999),
				'type' => 'numeric',
				'compare' => 'BETWEEN',
			)
		)
	);

	$list = new WP_Query($args);

	if( $list->have_posts() ) : while( $list->have_posts() ) : $list->the_post();

		$cur = get_post_meta( get_the_ID(), 'tour_price_currency', true );
		$rate = get_post_meta(get_the_id(), 'tour_price', true );
		$disc = get_post_meta ( get_the_ID(), 'tour_price_discount', true);
		$disc = $disc == "" ? 0 : $disc;

		$rate = to_discount( "<span class=\"currency\">$cur</span>", $rate, $disc);

	endwhile;endif;

	return $rate;
}

function the_minimum_price() {
	echo get_minimum_price();
}

function get_count_index($nomer) {

	if( $nomer % 2 == 0 ) {
		$index = ($nomer/2) - 1;
	} else {
		$index = ( ($nomer + ($nomer%2) ) / 2 ) - 1;
	}

	return $index;
}
function set_html_content_type() {
return 'text/html';
}