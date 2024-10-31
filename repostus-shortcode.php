<?php

/*
Plugin Name: Repost.Us Shortcode
Plugin URI: http://wordpress.org/extend/plugins/repostus-shortcode/
Description: Repost.Us makes complete articles embeddable — just like video. This plugin is for shortcodes only and does not include the full-featured Repost.Us plugin.
Version: 1.0
Author: John Pettitt
Author URI: http://freerangecontent.com/
License: MIT
*/

/*	Copyright 2012	Free Range Content Inc
	(email : support@freerangecontent.com)

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	
*/


//Shortcode support, detect and embed and convert the script to a shortcode
function repostus_sc_embed_to_short_code( $content ) {
	
	/**
	 * Repost.Us embeds are basically html with a script that expands the full embedded article.
	 * The script is filtered by WP for non-privilidged users so we need to preserve it.
	 * However the bulk of the embed code can be safely left alone and handled by wordpress
	 * as normal.  
	 *
	 * Note - doing it this way allows normal WP features like excepts to work correctly.
	 **/
	
	//Find our script and turn it into a tag.  
	$content = preg_replace('@<script src=".*//1.rp-api.com/rjs/repost-article.js.*</script>@','[repostus]',$content);
	return $content ;
}

//Shortcode support - re-expand the shortcodee into our script
function repostus_sc_shortcode( $atts ) {
	return '<script src="//1.rp-api.com/rjs/repost-article.js" type="text/javascript" data-cfasync="false"></script>';
}



/**
 * Hook a few things so wp knows what to do with us
 *
 * We check if a [repostus] short code is already defined
 * (if it is the full repostus plugin is installed so we bail)
 **/

 
//We need this to see if the full repost.us plugin is loaded
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

//Only install filters and shortcode if the full repost.us plugin is not active
if(!is_plugin_active("repostus/repostus.php")) {
	//Add a filter to create the shortcode
	add_filter('pre_kses', 'repostus_sc_embed_to_short_code');
	
	//and add the hook to expand it
	add_shortcode('repostus', 'repostus_sc_shortcode');
}

?>