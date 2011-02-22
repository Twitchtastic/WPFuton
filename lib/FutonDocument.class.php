<?
/**
  * Type: Library
  * Package: Futon
  *	Name: Document
  *	Description: The API used for working with documents, mostly for backend.
 */
Class FutonDocument {
	var $id;
	var $revision_of;
	var $collection;
	var $data;
	var $table;
	var $saved = false;
	function FutonDocument($id = false) {
		global $wpdb;
		if($id) {
			$document = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}futon_documents WHERE id = '{$id}'", OBJECT);
			$this->collection($document->collection);
			foreach($document->data as $field_name => $value) {
				$this->add_field($field_name, $value);
			}
		} else {
			$this->id = self::create_uuid();
		}
		$this->table = $wpdb->prefix.'futon_documents';
		return $this;
	}

	function add_field($field_name, $value = null) {
		$this->create_method($field_name);
		$this->create_property($field_name, $value);
	}

	function collection($collection_name) {
		$this->collection = new FutonCollection($collection_name);
		foreach($this->collection->field_names() as $field) {
			$this->add_field($field);
		}
		return $this;
	}

	function commit() {
		global $wpdb;
		if($this->saved) {
			$wpdb->update( $this->table, array('collection' => $this->collection->name, 'data' => json_encode($this->data)), array('id' => $this->id) );
		} else {
			$wpdb->insert( $this->table, array('id' => $this->id, 'collection' => $this->collection->name, 'data' => json_encode($this->data)) );
		}
		$this->saved = true;
	}

	function field($name, $value = null) {
		if($value != null) {
			$this->data->$name = $value;
			$this->$name = $value;
			return $this;
		} else {
			return $this->$name;
		}
	}

	function validate() {
		$this->collection->validate($this);
		return $this;
	}

	private function create_method($field_name) {
		$params = "$value = ''";
		$code = 'if(!empty($value)) $this->'.$field_name.' = $value; return $this->'.$field_name.';';
		//error_log(print_r($this->runtime_functions,true));
	//	$this->runtime_functions[$field_name] = create_function($params,$code);
	}

	private function create_property($field_name, $value) {
		$this->$field_name = $value;
		$this->data->$field_name = $value;
	}

	private static function create_uuid() {
		return md5('FutonDocument'.time());
	}
}