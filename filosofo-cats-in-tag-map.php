<?php
/*
Plugin Name: Categories in Tag Heat Map 
Plugin URI: http://www.ilfilosofo.com/blog/2007/06/16/a-plugin-for-wordpress-23-include-categories-in-the-tag-cloud/
Description: Includes categories in your WordPress tag heat map
Version: 3.1
Author: Austin Matzko
Author URI: http://ilfilosofo.com/
*/

/*  Copyright 2009  Austin Matzko  (email : austin [at] pressedcode.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class filosofo_tag_heat_map {
	function filosofo_tag_heat_map() {
		add_filter('wp_tag_cloud',array(&$this, 'include_cats'),900,2);
	}
	
	function include_cats( $return = '', $args = array() ) {
		if ( ! $cloud = wp_cache_get('cats_and_tags_cloud','category') ) :
			$tags = get_terms(array('category','post_tag'), array_merge($args, array('orderby' => 'count', 'order' => 'DESC')));
			foreach( (array) $tags as $tag ) {
				if ( ! empty($tag->taxonomy) ) {
					$term_id = intval($tag->term_id);
					$tag->link = get_term_link($term_id, $tag->taxonomy);
					$tag->id = $tag->term_id;
				}
			}
			$cloud = wp_generate_tag_cloud( $tags, $args );
			wp_cache_set('cats_and_tags_cloud', $cloud, 'category');
		endif;
		return $cloud;
	}
} // end class
$filosofo_tag_heat_map = new filosofo_tag_heat_map();
