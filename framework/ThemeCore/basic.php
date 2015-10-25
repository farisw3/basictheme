<?php

/*
 * Function Currency
 */

function to_currency( $cur, $number, $small = false ) {

	$cur = strtoupper($cur);

	if( $number && is_numeric($number) ) {
		$number = number_format($number);
		$number = str_replace( ",", ".", $number);
		$number = $small ? "<del class=\"small\">$cur $number</del>" : $cur . ' ' . $number;
	} else {
		$number = '<i class="fa fa-phone"></i> Call Us';
	}

	return $number;
}

/*
 * Functions Discount
 */
function to_discount( $cur, $number, $discount = 0) {

	if( is_numeric($number) ) {
		if( $discount != "0" && $discount != "") {
			$diskon = $number * ($discount * 0.001);
			$harga = $number - $diskon;
			$harga = pembulatan($harga);
			$number = to_currency($cur, $number, true) . ' ' . to_currency( $cur, $harga );
		} else {
			$number = to_currency($cur, $number);
		}
	} else {
		$number = '<i class="fa fa-phone"></i> Call Us';
	}

	return $number;

}

function kecur( $cur = "", $number, $zero = 0 ) {
	$cur = strtoupper($cur);

	if( $number && is_numeric($number) ) {
		$number = number_format($number);
		$number = str_replace( ",", ".", $number);
		$number = $small ? "<del class=\"small\">$cur $number</del>" : $cur . ' ' . $number;
	} else {
		$number = $zero;
	}

	return $number;
}

function discount( $number, $cur = "", $discount = 0, $zero = 0 ) {
	if( is_numeric($number) ) {
		if( $discount != "0" && $discount != "") {
			$diskon = $number * ($discount * 0.001);
			$harga = $number - $diskon;
			$harga = pembulatan($harga);
			$number = kecur($cur, $number) . ' ' . kecur( $cur, $harga );
		} else {
			$number = kecur($cur, $number);
		}
	} else {
		$number = $zero;
	}

	return $number;
}

function total_cost($rate, $discount, $cost, $cur ) {
	$a = discount($rate, "", $discount);
	$a = str_replace(".", "", $a);

	(int) $a;

	$ret = $a * (int) $cost;
	$ret = to_currency($cur, $ret);

	return $ret;
}

/*
 * Function Pembulatan
 */
function pembulatan($uang)
{
	$ratusan = substr($uang, -3);

	$akhir = $uang + (1000 - $ratusan);

	return $akhir;
}

/*
 * Function return to kilo
 */
function to_k($number) {
	$count = strlen($number);

	switch($count){
		case 0:case 6:$nol = '';break;
		case 1:$nol = '00000';break;
		case 2:$nol = '0000';break;
		case 3:$nol = '000';break;
		case 4:$nol = '000';break;
		case 5:$nol = '0';break;
	}

	$number = (string)$number . $nol;
	$number = (int)$number;

	return $number;
}

function custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  /**
   * This first part of our function is a fallback
   * for custom pagination inside a regular loop that
   * uses the global $paged and global $wp_query variables.
   * 
   * It's good because we can now override default pagination
   * in our theme, and use this function in default quries
   * and custom queries.
   */
  	$paged = max( 1, $paged );
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }

  /** 
   * We construct the pagination arguments to enter into our paginate_links
   * function. 
   */

  $big = 999999999;

  $link = home_url( $_SERVER['REQUEST_URI'] );

  $pg = $_GET['page'];
  $link = str_replace("&page=$pg", "", $link);
  $link = str_replace("?page=$pg", "", $link);

  $link = strpos($link, "?") ? $link . "&" : $link . "?";

  $pagination_args = array(
    'base'            => $link . "page=%#%",
    'format'          => '',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => true,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  	echo "<nav class='custom-pagination'>";

	if ($paginate_links) {
	    echo $paginate_links;
	} else {
		echo '<span class="page-numbers current">1</span>';
	}

	echo "</nav>";

}

function headline_content($content, $charlength = 171) {
	$excerpt = $content;
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			return mb_substr( $subex, 0, $excut ) . "...";
		} else {
			return $subex . "...";
		}
		return '[...]';
	} else {
		return $excerpt;
	}
}

function arr_sort_by($arr, $order = SORT_ASC ) {

	$a = array();

	foreach ($arr as $key => $value) {
		$a[] = $key;
	}

	$a = sort($a, $order);

	$ret = array();

	foreach ($a as $key => $value) {
		$ret[$value] = $arr[$value];
	}

	return $ret;
	
}