<?php

// This file is part of the Carrington Blueprint Theme for WordPress
//
// Copyright (c) 2008-2012 Crowd Favorite, Ltd. All rights reserved.
// http://crowdfavorite.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

$class = $avatar = $name = '';

$people_term_ids = wp_get_object_terms($post->ID, 'people', array(
	'fields' => 'ids'
));
if (!empty($people_term_ids)) {
	foreach ($people_term_ids as $people_term_id) {
		$term = cftpb_get_post($people_term_id, 'people');
		$email = cftpb_get_term_meta($people_term_id, 'people', '_cfhr_email', true);
		$term_link = get_term_link(intval($people_term_id), 'people');
		$name .= '<a href="'.esc_url($term_link).'">'.$term->post_title.'</a>';
		$avatar .= get_avatar($email, 48);
	}
}
else {
	$class = 'tax-people-null';
}

?>

<article id="post-<?php the_ID() ?>" <?php post_class('excerpt clearfix '.$class) ?>>
	<header class="entry-header">
<?php
echo $avatar.$name.'<span class="categories">'.get_the_category_list(', ').'</span>';
?>
		<time class="entry-date" datetime="<?php the_time('c'); ?>" pubdate><?php the_time(get_option('date_format')); ?></time>
		<h2 class="entry-title"><a href="<?php the_permalink() ?>"  title="<?php printf( esc_attr__( 'Permalink to %s', 'carrington-blueprint' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title() ?></a></h2>
	</header>
	<div class="entry-footer entry-meta">
<?php
the_tags(__('<p>Tags ', 'carrington-blueprint'), ', ', '</p>');
?>
	</div>
</article><!-- .post -->
