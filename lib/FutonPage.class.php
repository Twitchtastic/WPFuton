<?
/**
  * Type: Library
  * Package: Futon
  *	Name: Page
  *	Description: The API used for dealing with pages in WPFuton.
 */
require_once(dirname(__FILE__).'/FutonCore.class.php');
Class FutonPage {
	var $id;
	var $data;

	public function __call($method, $args) {
		if($method == 'id') {
			if(empty($this->id)) {
				$this->id = self::uuid();
			}	
		} elseif (isset($this->$method)) {
			$this->$method = $args[0];
		} elseif (isset($this->data->$method)) {
			$this->data->$method = $args[0];
		}
    }

	/*
	 * $args = list($field, $value); field being a property name and value being the corresponding value;
	 *
	*/
	function FutonPage($args = false) {
		$this->data = (object)array('route' => '', 'title' => '', 'meta' => array(), 'layout' => '', 'styles' => array(), 'javascripts' => array(), 'widgets' => array(), 'custom' => array());
		if($args !== false) {
			error_log(print_r($args,true));
			list($field, $value) = $args;
			switch($field):
				case 'id':
					$this->id = $value;
				case 'route':
					$this->data->$field = $value;
					$this->get_page();
					break;
				default:
					$this->data->$field = $value;
			endswitch;
		}
	}

	function get_page() {
		global $wpdb;
		//if(isset($this->route)) $wpdb->get_result("SELECT {$wpdb->prefix}futon_documents.* FROM {$wpdb->prefix}futon_documents doc, {$wpdb->prefix}futon_");
		//elseif(isset($this->id))
	}

	function install() {
		if(!FutonCollection::exists('futon_pages')) {
			$collection = new FutonCollection('futon_pages');
			error_log(print_r($collection,true));
			$collection->add_field('route')->add_field('title')->add_field('meta')->add_field('layout')->add_field('styles')->add_field('javascripts')->add_field('widgets')->add_validation('has_route', 'has_title')->commit();
			$view = new FutonView('pages_by_route');
			$settings = new FutonSettings();
			$view->collection('futon_pages')->add_field('route')->build();
		}
	}

	function view() {
		
	}

	function save() {
		global $wpdb;
		$id = $this->id;
		$collection = 'futon_pages';
		$data = json_encode($this->data);
		if($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}futon_documents ;")) {
			//$wpdb->update("futon_documents", , )
		} else {
			
		}
	}

	function has_route() {
		if(empty($this->route)) {
			return false;
		}
		return $this;		
	}
	
	function has_title() {
		if(empty($this->title)) {
			return false;
		}
		return $this;
	}

	function render() {
		
	}

	function get_layout() {
		
	}

	function get_styles() {
		
	}

	function place_widgets() {
		
	}

	private static function uuid() {
		return md5('FutonPage'.time());
	}
}