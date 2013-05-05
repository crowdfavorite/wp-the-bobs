<?php

function cfhr_admin_bar($wp_admin_bar) {
	if (current_user_can('edit_posts') && defined('CFHR_TRIAGE_CAT_ID')) {
		if ($count = cfhr_triage_count()) {
			$title = sprintf(__('Triage (%d)', 'cfhr'), $count);
		}
		else {
			$title = __('Triage', 'cfhr');
		}
		$wp_admin_bar->add_menu(array(
			'id' => 'cfhr_triage',
			'title' => $title,
			'href' => admin_url('edit.php?s&post_status=all&post_type=post&action=-1&m=0&cat='.CFHR_TRIAGE_CAT_ID.'&paged=1&mode=list&action2=-1'),
		));
		$wp_admin_bar->add_menu(array(
			'id' => 'cfhr_check_mail',
			'title' => __('Check Mail', 'cfhr'),
			'href' => site_url('wp-mail.php'),
		));
		$wp_admin_bar->add_menu(array(
			'id' => 'cfhr_filter',
			'title' => __('Filters', 'cfhr'),
			'href' => '#',
		));
	}
}
add_action('admin_bar_menu', 'cfhr_admin_bar', 75);

function cfhr_triage_count() {
	$count = new WP_Query(array(
		'posts_per_page' => -1,
		'cat' => CFHR_TRIAGE_CAT_ID,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	));
	return $count->post_count;
}

function cfhr_admin_bar_js() {
?>
<script type="text/javascript">
jQuery(function($) {
	$(document).on('click', '#wp-admin-bar-cfhr_filter a', function(e) {
		e.preventDefault();
		$('.filter').slideToggle();
// 	}).on('click', '#wp-admin-bar-cfhr_check_mail a', function(e) {
// 		e.preventDefault();
// 		$.get(
// 			$(this).attr('href'),
// 			function(response) {
// console.log(response);
// 			}
// 		);
	});
});
</script>
<?php
}
add_action('wp_footer', 'cfhr_admin_bar_js');