<?php

function tourtravel_detail_fields($post) {

	$akomodasi = get_post_meta( $post->ID, 'akomodasi', true );
	?>
	<div class="ui top attached tabular menu">
		<a class="active item" data-tab="first">Akomodasi</a>
		<a class="item" data-tab="second">Fasilitas</a>
		<a class="item" data-tab="third">Objek Wisata</a>
		<a class="item" data-tab="fourth">Tour Plan</a>
	</div>
	<div class="ui bottom attached active tab segment" data-tab="first">
		<?php akomodasi_field($akomodasi);?>
	</div>
	<div class="ui bottom attached tab segment" data-tab="second">
		<?php facility_field($post->ID);?>
	</div>
	<div class="ui bottom attached tab segment" data-tab="third">
		<?php objekwisata_field($post->ID);?>
	</div>
	<div class="ui bottom attached tab segment" data-tab="fourth"></div>
<?php }

function akomodasi_field($content = "") {
	wp_editor( $content, "akomodasi" );
}

function facility_field($id) {
	global $facilty_feature, $facilty_icon, $facilty_name;

	$item = '<div class="inline fields">';

	foreach ($facilty_feature as $key => $value) {
		$$value = get_post_meta( $id, $value, true );

		$checked = $$value == "1" ? " checked" : "";

		$item .= '<div class="field"><div class="ui checkbox">
			<input id="fac-'.$value.'" type="checkbox" name="'.$value.'" value="1"'.$checked.'>
			<label for="fac-'.$value.'">
				<i class="icon '.$facilty_icon[$key].'"></i>
				'.$facilty_name[$key].'
			</label>
		</div></div>';
	}

	$item .= "</div>";

	echo $item;

}
function objekwisata_field($id) {
	
	$args = array(
		'post_type' => 'places',
		'orderby' => 'name',
		'order' => 'asc'
	);

	$list = new WP_Query($args);

	$place = '<select id="tourplace" class="ui dropdown">';

	if( $list->have_posts() ) : while( $list->have_posts() ) : $list->the_post();

		$place .= '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
		
	endwhile;endif;
	wp_reset_postdata();

	$place .= '</select>';

	echo $place;?>

	<button type="button" class="button">Tambah</button>

<?php }