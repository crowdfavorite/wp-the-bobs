<?php

function cfhr_register_taxonomies() {
	register_taxonomy(
		'people',
		array('post'),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __('People', 'cfhr'),
				'singular_name' => __('Person', 'cfhr'),
				'search_items' => __('Search People', 'cfhr'),
				'popular_items' => __('Popular People', 'cfhr'),
				'all_items' => __('All People', 'cfhr'),
				'parent_item' => __('Parent Person', 'cfhr'),
				'parent_item_colon' => __('Parent Person:', 'cfhr'),
				'edit_item' => __('Edit Person', 'cfhr'),
				'update_item' => __('Update Person', 'cfhr'),
				'add_new_item' => __('Add New Person', 'cfhr'),
				'new_item_name' => __('New Person Name', 'cfhr'),
			),
			'sort' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array(
				'slug' => 'people',
				'with_front' => false,
				'hierarchical' => true,
			),
		)
	);
	register_taxonomy(
		'people_active_status',
		array('person'),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __('Status', 'cfhr'),
				'singular_name' => __('Status', 'cfhr'),
				'search_items' => __('Search Statuses', 'cfhr'),
				'popular_items' => __('Popular Statuses', 'cfhr'),
				'all_items' => __('All Statuses', 'cfhr'),
				'parent_item' => __('Parent Status', 'cfhr'),
				'parent_item_colon' => __('Parent Status:', 'cfhr'),
				'edit_item' => __('Edit Status', 'cfhr'),
				'update_item' => __('Update Status', 'cfhr'),
				'add_new_item' => __('Add New Status', 'cfhr'),
				'new_item_name' => __('New Status Name', 'cfhr'),
			),
			'public' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'sort' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array(
				'slug' => 'status',
				'with_front' => false,
				'hierarchical' => true,
			),
		)
	);
	// check to see if there is a term in the taxonomy...
	if (!get_term_by('slug', 'active', 'people_active_status')) {
		wp_insert_term(__('Active', 'cfhr'), 'people_active_status', array(
			'slug' => 'active'
		));
	}
}
add_action('init', 'cfhr_register_taxonomies');

function cfhr_lock_people_status_form() {
	if (!in_array('people_active_status', get_object_taxonomies('people'))) {
		return;
	}
?>
<script>
;jQuery(function($) {
// we're going to control the status taxonomy
	$('#taxonomy-people_active_status')
		.find('.category-tabs, div:not(".tabs-panel")').remove().end()
		.find('.tabs-panel').removeClass('tabs-panel');
});
</script>
<?php
}
add_action('admin_head-post.php', 'cfhr_lock_people_status_form');

// Create People post type (bound to People taxonomy) to save meta
function cfhr_tax_bindings($configs) {
	$configs[] = array(
		'taxonomy' => 'people',
		'post_type' => array(
			'person',
			array(
				'public' => false,
				'show_ui' => true,
				'label' => __('People', 'cfhr'),
				'rewrite' => false
			)
		),
		'slave_title_editable' => false,
		'slave_slug_editable' => false,
	);
	return $configs;
}
add_filter('cftpb_configs', 'cfhr_tax_bindings');

// Make people active by default when they are created
function cfhr_people_status_default($post) {
	wp_set_object_terms($post->ID, 'active', 'people_active_status');
}
add_action('cf_taxonomy_post_type_binding_created_post', 'cfhr_people_status_default');

// Post Meta config
function cfhr_meta_config($config) {
	$config[] = array(
		'title' => __('Details', 'cfhr'),	// required, Title of the Meta Box
		'type' => array('person'), 	// required, Which edit screen to add to. Use array('page','post') to add to both at the same time
		'id' => 'cfhr-meta-people', 		// required, unique id for the Meta Box
		'add_to_sortables' => true,	// optional, this is the default behavior
		'context' => 'side',		// optional, sets the location of the metabox in the edit page.  Other posibilites are 'advanced' or 'side' (this sets the meta box to apear in the rt sidebar of the edit page)
		'items' => array(
			// text input
			array(
				'name' => '_cfhr_email',			// required, this is the meta_key that will be saved by WordPress
				'label' => __('Email', 'cfhr'), 				// optional, label only printed if text is not empty
//				'label_position' => 'before',			// optional, label position in relation to the input, default: 'before'
				'type' => 'text',						// required, input type
			),
		)
	);
	return $config;
}
add_filter('cf_meta_config','cfhr_meta_config');
