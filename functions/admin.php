<?php

function cfhr_manage_post_posts_columns($posts_columns) {
	$_columns = array();
	foreach ($posts_columns as $k => $v) {
		if ($k == 'author') {
			$_columns['people'] = __('People', 'cfhr');
		}
		else {
			$_columns[$k] = $v;
		}
	}
	return $_columns;
}
add_filter('manage_post_posts_columns', 'cfhr_manage_post_posts_columns');

function cfhr_manage_post_posts_custom_column($column_name, $post_id) {
	if ($column_name != 'people') {
		return;
	}
	$links = array();
	$people = wp_get_object_terms($post_id, 'people');
	foreach ($people as $person) {
		$links[] = '<a href="'.esc_url(admin_url('edit.php?people='.$person->slug)).'">'.esc_html($person->name).'</a>';
	}
	echo implode(', ', $links);
}
add_action('manage_post_posts_custom_column', 'cfhr_manage_post_posts_custom_column', 10, 2);

function cfhr_manage_person_posts_columns($posts_columns) {
	$_columns = array();
	foreach ($posts_columns as $k => $v) {
		if ($k == 'title') {
			$_columns['avatar'] = __('&nbsp;', 'cfhr');
		}
		$_columns[$k] = $v;
	}
	return $_columns;
}
add_filter('manage_person_posts_columns', 'cfhr_manage_person_posts_columns');

function cfhr_manage_person_posts_custom_column($column_name, $post_id) {
	if ($column_name != 'avatar') {
		return;
	}
	echo get_avatar(get_post_meta($post_id, '_cfhr_email', true), 36);
}
add_action('manage_person_posts_custom_column', 'cfhr_manage_person_posts_custom_column', 10, 2);

function cfhr_admin_head_edit() {
?>
<style>
.fixed .column-people {
	width: 15%;
}
.fixed .column-avatar {
	text-align: center;
	width: 36px;
}
</style>
<?php
}
add_action('admin_head-edit.php', 'cfhr_admin_head_edit');
