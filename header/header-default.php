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

$blog_desc = get_bloginfo('description');
$title_description = (is_home() && !empty($blog_desc) ? ' - '.$blog_desc : '');

?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes() ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset') ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title( '-', true, 'right' ); echo esc_html( get_bloginfo('name'), 1 ).$title_description; ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="container grid">

	<div id="main" class="clearfix">
	
		<div class="filter">
<?php

$args = array(
	'taxonomies' => array(
		'people' => array(
		),
		'category' => array(
		),
	),
	'date' => true
);

CF_Taxonomy_Filter::start_form();

echo '<div class="cftf-options"><span class="label">'.__('Options', 'capsule').'</span>';

foreach ($args['taxonomies'] as $taxonomy => $tax_args) {
	if (is_array($args)) {
		CF_Taxonomy_Filter::tax_filter($taxonomy, $tax_args);
	}
	// Just passed in taxonomy name with no options
	else {
		CF_Taxonomy_Filter::tax_filter($args);
	}
}

CF_Taxonomy_Filter::author_select();

echo '</div>';
echo '<div class="cftf-dates"><span class="label">'.__('Date Range', 'capsule').'</span>';

CF_Taxonomy_Filter::date_filter();

echo '</div>';
echo '<div class="cftf-submit">';

CF_Taxonomy_Filter::submit_button();

echo '</div>';

CF_Taxonomy_Filter::end_form();

?>
		</div>
