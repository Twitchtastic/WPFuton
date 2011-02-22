<?php
/*
Plugin Name: WPFuton
Version: 0.1
Plugin URI: http://wpfuton.minnesofa.com
Description: A simplified CMS, made to make the lives of both the developer and end user as easy as possible.
Author: sofa
Author URI: sofa@minnesofa.com

Copyright `date +%Y`  (email: )

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
require_once('lib/FutonCore.class.php');

function init(){
	error_log("==========================");
		FutonPage::install();
	error_log("==========================");
	if($_GLOBALS['already'] == false) {
		global $futon;
		$futon->settings = new FutonSettings();
		//$collection = new FutonCollection('posts');
		//$collection->add_field('name')->add_field('body')->add_field('date')->add_validation('not_empty', 'name', 'function', 'strlen')->commit();

		$document = new FutonDocument();
		$document->collection('posts')->field('body', 'Lorem ipsum dolor sit amet')->field('name', 'F41L')->commit();
		//error_log($document->validate());
		$post = $futon->find_posts_by_name('w00ts');
		//error_log(print_r($post,true));
		$view = new FutonView('recent_posts');
		$settings = new FutonSettings();
		//$view->collection('posts')->remove_field('title')->add_field('name')->build();
		$_GLOBALS['already'] = true;
		error_log('Im Awesome :: '.$futon->settings->set('the_answer', '42')->save()->get('the_answer'));

		//$page->route('/store/is/closed/')->title('My Page')->id()->save();
	}
	return false;
}
add_action('admin_init', 'init');
//$db->create_collection('pages', , $views);
/*
Class Futon {
	function Futon() {
		
	}
} */

?>
