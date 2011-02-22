<?
/**
  * Type: Library
  * Package: Futon
  *	Name: Collection
  *	Description: The API used for setting up collections, Futons database tables.
 */
Class FutonCollection {
	var $name;
	var $fields;
	var $views;
	var $validations;
	var $saved = false;
	function FutonCollection($name) {
		global $wpdb;
		$this->name = $name;
		if($collection = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}futon_collections WHERE name = '{$name}'", OBJECT)) {
			$this->fields		= json_decode($collection->fields);
			$this->validations	= json_decode($collection->validations);
			$this->table		= $wpdb->prefix.'futon_collections';
			$this->saved		= true;
		}
		return $this;
	}

	function add_field($field_name) {
		$this->fields->$field_name = true;
		return $this;
	}

	function remove_field($field_name) {
		return $this->fields->$field_name = false;
		return $this;
	}
	// $validation = array('strtoupper')
	// $validation = array('rtrim', array('::document::', "\n"))
	function add_validation($name, $field, $type, $validation) {
		if(!isset($this->validation))
		switch($type):
			case 'function':
				$this->validations->$name = self::create_validation_function($field, $validation);
				break;
			case 'compare':
				$this->validations->$name = self::create_validation_comparison($field, $validation);
				break;
		endswitch;
		return $this;
	}

	function commit() {
		global $wpdb;
		if($this->saved) {
			$wpdb->update( $wpdb->prefix.'futon_collections', array('validations' => json_encode($this->validations), 'fields' => json_encode($this->fields)), array('name' => $this->name) );
		} else {
			$wpdb->insert( $wpdb->prefix.'futon_collections', array('name' => $this->name, 'validations' => json_encode($this->validations), 'fields' => json_encode($this->fields)) );
		}
	}

	function field_names() {
		return array_keys((Array)$this->fields);
	}

	static function exists($name) {
		global $wpdb;
		return ($wpdb->query("SELECT COUNT(id) FROM {$wpdb->prefix}futon_collections WHERE name = '{$name}'") > 0);
	}

	private static function create_validation_function($field, $validation) {
		if(is_array($validation)) {
			$has_params = (count($validation) > 1);
			$function = $validation[0];
		} else {
			$function = $validation;
		}
		$futon_validation = array('type' => 'function', 'field' => $field, 'function' => $function);

		if($has_params) {
			$params = $validation[1];
			$futon_validation['params'] = $params;
		} else {
			$futon_validation['params'] = "::self::";
		}
		return $futon_validation;
	}

	private static function create_validation_comparison($field, $validation) {
		
	}

	function validate($document) {
		foreach((Array)$this->collection->validations as $name => $validation) {
			switch($validation->type):
				case 'function':
					$this->validate_function($document, $validation);
					break;
				case 'compare':
					$this->validate_comparison($document, $validation);
					break;
			endswitch;
		}
		return $document;
	}


	private function validate_function($document, $validation) {
		$function = $validation['function'];
		$args = $validation['args'];
		$field = $validation['field'];
		if($key = array_search('::self::', $args) && $key !== false) {
			$args[$key] = $document->$field;
		}
		$valid = $function(extract($args));
		error_log("=======================================\n VALID :: ".$valid."\n=======================================");
		return $valid;
	}

	private function validate_comparison($document, $validation) {
		
	}



}
