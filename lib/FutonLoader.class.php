<?
/**
  * Type: Library
  * Package: Futon
  *	Name: Loader
  *	Description: The API used for loading libraries, widgets, plugins, styles, and javascript to WPFuton.
 */
Class FutonLoader {
	var $widgets = array();
	var $vendor = array();
	var $plugins = array();
	var $libs = array();
	function FutonLoader() {
		//$this->libs = self::get_libs();
	}

	function lib($name) {
		error_log("Futon{$name}.class.php");
		require_once("Futon{$name}.class.php");
	}

	function libs($names) {
		foreach($names as $name) {
			$this->lib($name);
		}
	}

	function css($name) {
		
	}

	private static function get_libs() {
		$pwd = $futon->dir_root.'libs';
		$directory_contents = scandir($pwd);
		foreach($directory_contents as $file) {
			if(is_dir($pwd.'/'.$file) && $pwd = $pwd.'/'.$file.'/'){
				if(file_exists($pwd.$file.'.class.php')) {
					
				}
				$pwd = $futon->dir_root.'libs';
			}
		}
	}

	private static function parse_class_data() {
		
	}

	private static function get_plugins() {
		
	}

	private static function get_vendors() {
		
	}

	private static function get_widgets() {
		
	}
}