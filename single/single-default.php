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

get_header();
get_sidebar();
?>

<div id="primary" class="c6-3456">

<?php
cfct_loop();
comments_template();
?>
	<div class="pagination pagination-single">
		<span class="next"><?php next_post_link() ?></span>
		<span class="previous"><?php previous_post_link() ?></span>
	</div>
</div> <!-- #content -->
<?php 
get_footer();
