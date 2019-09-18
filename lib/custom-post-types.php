<?php


function honorees(){
	$labels = array(
        'name'               => _x( 'Honorees', 'page type general name' ),
        'singular_name'      => _x( 'Honoree', 'page type singular name' ),
        'add_new'            => _x( 'Add New', 'Honoree' ),
        'add_new_item'       => __( 'Add New Honoree' ),
        'edit_item'          => __( 'Edit Honorees' ),
        'new_item'           => __( 'New Honoree' ),
        'all_items'          => __( 'All Honorees' ),
        'view_item'          => __( 'View Honoree' ),
        'search_items'       => __( 'Search Honorees' ),
        'not_found'          => __( 'No Honorees  found' ),
        'not_found_in_trash' => __( 'No Honorees found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Honorees'
      );
      
      $args = array(
        'labels'        => $labels,
        'description'   => 'Honoree',
        'hierarchical'	=> false,
        'menu_position' => 24,
        'supports'      => array( 'thumbnail','menu_order'),
        'has_archive'   => false,
        // 'rewrite'		=> array( 'slug' => 'honorees' ),
        'menu_icon'   => 'dashicons-id',
        'public' => false,  // it's not public, it shouldn't have it's own permalink
        'publicly_queryable' => false,  // you should be able to query it
        'show_ui' => true,  // you should be able to edit it in wp-admin
        'exclude_from_search' => true,  // you should exclude it from search results
        'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
      );
      register_post_type( 'honoree', $args ); 
}
add_action('init','honorees');



function honoree_custom_taxonomy(){
    $labels = array(
        'name' => _x( 'School', 'taxonomy general name' ),
        'singular_name' => _x( 'Type', 'taxonomy singular name' ),
        'search_items' =>  __( 'School Types' ),
        'all_items' => __( 'All Schools' ),
        'parent_item' => __( 'School Type' ),
        'parent_item_colon' => __( 'School Type:' ),
        'edit_item' => __( 'Edit School' ), 
        'update_item' => __( 'Update School' ),
        'add_new_item' => __( 'Add New School' ),
        'new_item_name' => __( 'New School Name' ),
        'menu_name' => __( 'Schools' ),
      ); 	

      register_taxonomy('schools',array('honoree'), array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'school/type' ),
      ));

}
add_action('init','honoree_custom_taxonomy',0);


///////////////////////
///// COLUMNS
///////////////////////


// register custom columns for the honoree CPT, reorder some columns, remove unwanted columns
add_filter('manage_honoree_posts_columns', 'honoree_posts_columns');
function honoree_posts_columns( $columns) {
  unset($columns['title']);
  $new = array();
  foreach($columns as $key => $title) {
    if ($key=='date'){
      $new['honoree_name'] = 'Name';
      $new['honoree_school'] = 'School';
      $new[$key] = $title;
    }
  }
  return $new;
}


// define custom columns for the honoree CPT 
add_action( 'manage_honoree_posts_custom_column', 'honoree_column', 10, 2);
function honoree_column( $column, $post_id ) {
  if ( 'honoree_name' === $column ) {
    $ln = get_post_meta( $post_id,'staff_last_name',true);
    $fn = get_post_meta( $post_id,'staff_first_name',true);
    echo "<a href='/wp-admin/post.php?post=$post_id&action=edit'>$ln, $fn</a>";
  }
  if ( 'honoree_school' === $column ) {
    $school = get_the_terms($post_id,'schools', true);
    $term_id = $school[0]->term_id;
    $term_name = $school[0]->name;
    echo "<a href='/wp-admin/term.php?taxonomy=schools&tag_ID=$term_id&post_type=honoree'>$term_name</a>";
  }
}


// make one of our custom columns sortable
add_filter( 'manage_edit-honoree_sortable_columns', 'honoree_sortable_columns');
function honoree_sortable_columns( $columns ) {
  $columns['honoree_name'] = 'staff_last_name';
  return $columns;
}
// tell wordpress how to sort our custom column
add_action( 'pre_get_posts', 'honoree_sortable_orderby' );
function honoree_sortable_orderby( $query ) {
    if( ! is_admin() )
        return;
    $orderby = $query->get( 'orderby');
    if( 'staff_last_name' == $orderby ) {
        $query->set('meta_key','staff_last_name');
        $query->set('orderby','meta_value');
    }
}