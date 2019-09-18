<?php 

add_action( 'cmb2_init', 'cmb2_honoree_metaboxes' );


/**
 * Define the metabox and field configurations.
 */
function cmb2_honoree_metaboxes() {

    //BOX 1 LINK
    $cmb_honoree = new_cmb2_box( array(
        'id' => 'link_metabox',
        'title' => __('Enter Honoree Details','cmb2'),
        'object_types' => array('honoree'),
        'context' => 'normal',
        'priority' => 'high',
		'show_names' => true,
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
	 	// 'closed'     => true, // Keep the metabox closed by default
    ) );

	//Regular text field
	$cmb_honoree->add_field( array(
		'name'       => __( 'Enter First name *', 'cmb2' ),
		'desc'       => __( 'First Name of Honoree.', 'cmb2' ),
		'id'         => 'staff_first_name',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		'attributes'  => array(
			'required'    => 'required',
		),
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

    //Regular text field
	$cmb_honoree->add_field( array(
		'name'       => __( 'Enter Last name *', 'cmb2' ),
		'desc'       => __( 'Last Name of Honoree.', 'cmb2' ),
		'id'         => 'staff_last_name',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		'attributes'  => array(
			'required'    => 'required',
		),
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

   // Regular text field
   $cmb_honoree->add_field( array(
		'name'       => __( 'Position *', 'cmb2' ),
		'desc'       => __( 'Current position of honoree', 'cmb2' ),
		'id'         => 'staff_position',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		'attributes'  => array(
			'required'    => 'required',
		),
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	

	$cmb_honoree->add_field( array(
		'name' => __( 'Profile URL *', 'cmb2' ),
		'id'   => 'staff_profile_url',
		'type' => 'text_url',
		'attributes'  => array(
			'required'    => 'required',
		),
		// 'protocols' => array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' ), // Array of allowed protocols
	) );

	$cmb_honoree->add_field( array(
        'name'           => 'Select School *',
        // 'desc'           => 'Description Goes Here',
        'id'             => 'honoree_taxonomy_select',
        'taxonomy'       => 'schools', //Enter Taxonomy Slug
        'type'           => 'taxonomy_select',
        'remove_default' => 'true', // Removes the default metabox provided by WP core.
        // Optionally override the args sent to the WordPress get_terms function.
        'query_args' => array(
            // 'orderby' => 'slug',
            // 'hide_empty' => true,
		),
		'attributes'  => array(
			'required'    => 'required',
		),
    ) );
    
}

add_action( 'cmb2_init', 'cmb2_school_taxonomy_metaboxes' );

function cmb2_school_taxonomy_metaboxes() {

    //BOX 1 LINK
    $cmb_schools = new_cmb2_box( array(
        'id' => 'link_metabox',
        'title' => __('Add a Link','cmb2'),
        'object_types' => array('schools', ),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
	 	// 'closed'     => true, // Keep the metabox closed by default
    ) );


}


add_action( 'cmb2_admin_init', 'school_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function school_register_taxonomy_metabox() {
	$prefix = 'school_term_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb_term = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		// 'title'            => esc_html__( 'Category Metabox', 'cmb2' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'schools'), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );
	// $cmb_term->add_field( array(
	// 	'name'     => esc_html__( 'Extra Info', 'cmb2' ),
	// 	'desc'     => esc_html__( 'field description (optional)', 'cmb2' ),
	// 	'id'       => $prefix . 'extra_info',
	// 	'type'     => 'title',
	// 	'on_front' => false,
	// ) );
	$cmb_term->add_field( array(
		'name' => esc_html__( 'School Logo', 'cmb2' ),
		// 'desc' => esc_html__( 'Add logo for school.', 'cmb2' ),
		'id'   => $prefix . 'logo',
		'type' => 'file',
	) );
	
}
