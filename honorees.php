<?php
/**
 * Plugin Name:     Honorees
 * Plugin URI:      https://github.com/esalcido
 * Description:     Display Faculty and Staff members who are being recognized for their significant contributions to their respective fields.
 * Author:          Edward Salcido
 * Author URI:      www.edwardsalcido.com
 * Version:         1.0.0
 *
 * @package         honorees
 *
 * Reference:       https://gist.github.com/thefuxia/3804204
 */

require_once( __DIR__ . '/lib/custom-post-types.php');
require_once( __DIR__ . '/lib/custom-fields.php');

//add styles
function add_stylesheets(){
	wp_register_style('honoree_styles',plugins_url('/css/style.css',__FILE__));
	wp_enqueue_style('honoree_styles');
}
add_action('wp_enqueue_scripts','add_stylesheets');

add_shortcode('honorees','honoree_list');

function honoree_list($atts=[], $content=null){

	$output='';
	//$term_name='';
	
	$output.="<div class='main-container'>";
	
	//get taxonomies
	$terms = get_terms(array(
		'taxonomy'=>'schools',
		'hide_empty'=>true,
		'meta_query'=>array(
		'order_by'=>'ASC',	
			array(
				'key' =>"school_term_logo",
			),
		),
	));

	//get taxonomy terms
	foreach($terms as $term){
	
	$output.="<div>";
	$output.="<div class='university'>";

	//get taxonomy meta term for the logo
	$term_name = get_term_meta($term->term_id,'school_term_logo');
	foreach($term_name as $key=>$val){
		//echo $key.' '.$val;
		$output.=	"<img src='$val' alt='' />";
	}

	$output.="</div><!--end university -->";
	$output.="<hr>";
	//print out all the honorees that pertain to the current taxonmy logo

	$output.="<div class='profiles'>";

	//query honoree post types
	$args = array(
        'post_type' => 'honoree',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'last_name',
		'order' => 'ASC',
		 'tax_query'=>array(
		 	array(
				'taxonomy'         => 'schools',
		 	 	'terms'            => $term->term_id,
		 	 	 'operator'         => 'IN',
		 	),
		 ),
		'meta_query'=>array(

			'last_name'=>array(
				'key' =>"staff_last_name",
			),
		),
	);
	
	// $tax_query = array(
	// 	'relation' => 'AND',
	// 	array(
	// 		'taxonomy'         => 'honoree_taxonomy_select',
	// 		// 'terms'            => 'schools',
	// 		 'field'            => 'term_id',
	// 		// 'operator'         => 'IN',
	// 		'value'=>$term->name,
			
	// 	),
	// );

    $loop= new WP_Query($args);
	//loop through each honoree
	while($loop->have_posts() ): $loop->the_post();
		
		$name = 	  get_the_title();
		$first_name = get_post_meta( get_the_ID(),'staff_first_name',true);
		$last_name = get_post_meta( get_the_ID(),'staff_last_name',true);

		$position =   get_post_meta( get_the_ID(),'staff_position',true);
		$university = get_post_meta( get_the_ID(),'staff_university',true);
		$profile_url = get_post_meta( get_the_ID(),'staff_profile_url',true);

		//$image = wp_get_attachment_image_src( get_the_ID() );
		$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true); 
		$thumb_url = $thumb_url_array[0];
		$image_alt = get_post_meta($thumb_id,'_wp_attachment_image_alt',true);

		//print profile
		
		$output .= "<div id='profile-".get_the_ID()."' class='profile-container'>";
		$output .=	"<div class='profile-image'><img class='wp-image-6971 size-full alignnone' src='$thumb_url'  alt='$image_alt' width='162' height='163' /></div>";
		$output .=	"<div class='profile-details'>";
		$output .=		"<a href='$profile_url' ><div class='profile-name'>$first_name $last_name</div></a>";
		$output .=		"<div class='profile-position'><p>$position</p></div>";
		$output .=		"<div class='profile-school'><p>$university</p></div>";
		$output .=	"</div>";
		$output .="</div>";
		

	endwhile;
	wp_reset_postdata();
	
	$output.="</div><!--.profiles -->";
	$output.="</div>";
	}
	
	$output.="</div><!--.main container -->";
	
	
	return $output;
	
}



