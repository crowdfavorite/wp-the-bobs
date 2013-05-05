<?php

function cfhr_manage_post_posts_columns($posts_columns) {
	$_columns = array();
	foreach ($posts_columns as $k => $v) {
		if ($k == 'title') {
			$_columns['widget'] = '&nbsp;';
		}
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
	if (!in_array($column_name, array('widget', 'people'))) {
		return;
	}
	switch ($column_name) {
		case 'people' :
			$links = array();
			$people = wp_get_object_terms($post_id, 'people');
			foreach ($people as $person) {
				$links[] = '<a href="'.esc_url(admin_url('edit.php?people='.$person->slug)).'">'.esc_html($person->name).'</a>';
			}
			echo implode(', ', $links);
			break;
		case 'widget':
			echo '<a class="cfhr-widget-icon" href="#cfhr-tax-popover" data-post-id="'.$post_id.'">'.__('Manage Taxonomies', 'cfhr').'</a>';
			break;
	}
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
	wp_enqueue_script('jquery-ui-position');
	wp_enqueue_script(
		'jquery-popover',
		trailingslashit(get_stylesheet_directory_uri()).'assets/js/jquery-popover/jquery.cf.popover.js',
		array('jquery', 'jquery-ui-position')
	);
?>
<style>
.fixed .column-widget {
	width: 2.2em;
}
.fixed .column-widget a {
	background-image: url("images/menu.png");
	background-position: -336px -35px;
	background-repeat: no-repeat;
	display: block;
	height: 20px;
	overflow: hidden;
	text-indent: -9999px;
	width: 16px;
}
.fixed .column-people {
	width: 15%;
}
.fixed .column-avatar {
	text-align: center;
	width: 36px;
}
.fixed tr.dim td {
	opacity: 0.5;
}
#cfhr-tax-popover {
	background: #fff;
	border: 1px solid #fff;
	-moz-box-shadow: 0 2px 3px 2px #ccc;
	-webkit-box-shadow: 0 2px 3px 2px #ccc;
	box-shadow: 0 2px 3px 2px #ccc;
	display: none;
	height: 300px;
	position: absolute;
	width: 600px;
}
#cfhr-tax-popover .before {
	position: absolute;
	top: 13px;
	left: -11px;
	border-right: 10px solid #fff;
	border-right-color: inherit; 
	border-bottom: 10px solid transparent;
	border-top: 10px solid transparent; 
}
#cfhr-tax-popover.flopped-y .before {
	bottom: 9px;
	top: auto;
}
#cfhr-tax-popover .loading {
	background: #fff url(<?php echo admin_url('images/loading.gif'); ?>) center center no-repeat;
	height: 300px;
	left: 0;
	position: absolute;
	top: 0;
	width: 220px;
}
#cfhr-tax-popover .tax {
	float: left;
	margin: 10px;
	width: 180px;
}
#cfhr-tax-popover .tax li {
	list-style: none;
}
#cfhr-tax-popover .tax p {
	bottom: 0px;
	position: absolute;
	text-align: center;
	width: 180px;
}
#cfhr-tax-popover .content {
	clear: right;
	float: left;
	height: 290px;
	overflow: auto;
	padding: 0 0 10px;
	width: 400px;
}
#cfhr-tax-popover .content h2.title {
	font-size: 1.2em;
	line-height: 140%;
}
</style>
<script type="text/javascript">
jQuery(function($) {
	$('.fixed .column-widget a').popover({
		my: 'left top',
		at: 'right top',
		offset: '12px -12px',
		collision: 'none flop'
	}).on('popover-show', function() {
		$el = $($(this).attr('href'));
		$el.html('<span role="presentation" class="before"/><div class="loading"></div>');
		$.get(
			'<?php echo admin_url('admin-ajax.php'); ?>',
			{
				action: 'cfhr-tax-popover',
				post_id: $(this).data('post-id')
			},
			function(response) {
				$el.find('.loading').replaceWith(response.html).end()
					.find('#category-<?php echo CFHR_TRIAGE_CAT_ID; ?>').remove();
			}
		);
	});
	$(document).on('click', '#cfhr-tax-popover .button-primary', function() {
		var $popover = $('#cfhr-tax-popover'),
			$people = $popover.find('#parent option:selected'),
			$cats = $popover.find('input:checkbox:checked'),
			cats = [],
			postId = $(this).data('post-id'),
			$tr = $('#post-' + postId);
		$cats.each(function() {
			cats.push($(this).val());
		});
		$.post(
			'<?php echo admin_url('admin-ajax.php'); ?>',
			{
				action: 'cfhr-tax-save',
				post_id: postId,
				people: $people.val(),
				cats: cats,
			},
			function(response) {
				$tr.replaceWith(response);
			}
		);
		$tr.addClass('dim');
		$('body').click();
	});
});
</script>
<?php
}
add_action('admin_head-edit.php', 'cfhr_admin_head_edit');

function cfhr_tax_popover_shell() {
?>
<div id="cfhr-tax-popover"><div class="loading"></div></div>
<?php
}
add_action('admin_footer-edit.php', 'cfhr_tax_popover_shell');

function cfhr_tax_popover($post_id = 0) {
	$post = get_post($post_id);
?>
<div class="tax">
<?php
	$people = wp_get_object_terms($post_id, 'people');
	$args = array(
		'hide_empty' => 0,
		'hide_if_empty' => false,
		'taxonomy' => 'people',
		'name' => 'parent',
		'orderby' => 'name',
		'hierarchical' => true,
		'show_option_none' => __('(None)', 'cfhr')
	);
	if (!empty($people)) {
		$args['selected'] = $people[0]->term_id;
	}
	wp_dropdown_categories($args);
?>
	<ul>
<?php
	wp_terms_checklist(
		$post_id,
		array(
			'taxonomy' => 'category'
		)
	);
?>
	</ul>
	<p><a href="#" class="submit button-primary" data-post-id="<?php echo $post_id; ?>"><?php _e('Save', 'cfhr'); ?></a></p>
</div>
<div class="content">
	<h2 class="title"><?php echo esc_html($post->post_title); ?></h2>
	<?php echo wpautop($post->post_content); ?>
</div>
<?php
}

function cfhr_admin_ajax_tax_popover() {
// get post id
	$post_id = (isset($_GET['post_id']) ? intval($_GET['post_id']) : 0);
// return HTML
	ob_start();
	cfhr_tax_popover($post_id);
	$html = ob_get_clean();
	header('Content-type: application/json');
	echo json_encode(compact('post_id', 'html'));
	die();
}
add_action('wp_ajax_cfhr-tax-popover', 'cfhr_admin_ajax_tax_popover');

function cfhr_admin_ajax_tax_save() {
// get post id
	$post_id = (isset($_POST['post_id']) ? intval($_POST['post_id']) : 0);
	if (empty($post_id)) {
		die();
	}
// save People, -1 = no people
	$people = ($_POST['people'] == -1 ? array() : intval($_POST['people']));
	wp_set_object_terms($post_id, $people, 'people');
// save Cats, set to Triage cat if no cats
	$cats = array_map('intval', $_POST['cats']);
	wp_set_object_terms($post_id, $cats, 'category');
// return HTML for table row
	global $wp_query;
	$wp_query->query(array(
		'p' => $post_id
	));
	set_current_screen('edit.php');
	$wp_list_table = _get_list_table('WP_Posts_List_Table', array(
		'screen' => 'edit.php'
	));
	$wp_list_table->display_rows();
	die();
}
add_action('wp_ajax_cfhr-tax-save', 'cfhr_admin_ajax_tax_save');
