<?
/**
  * Type: Library
  * Package: Futon
  *	Name: View
  *	Description: The API used for accessing database data within page context.
 */
Class FutonView {
	var $name;
	var $collection;
	var $function;
	var $fields;

	function FutonView($name) {
		global $wpdb;
		$this->name = $name;
		if($view = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}futon_views WHERE name = '{$name}'", OBJECT)) {
			$this->fields = (object)json_decode($view->fields);
			$this->collection = $view->collection;
			error_log(print_r($fields,true));
			$this->table = $wpdb->prefix.'futon_view_'.$this->collection.'_by_'.implode("_and_", $this->field_names());
			$this->function = 'find_'.$this->collection.'_by_'.implode("_", $this->field_names());
			$this->saved = true;
		}
		return $this;
	}

	function collection($collection_name = false) {
		if($collection_name !== false){
			$this->collection = $collection_name;
		}
		return $this;
	}

	function add_field($field_name) {
		$this->fields->$field_name = true;
		return $this;
	}

	function remove_field($field_name) {
		if(isset($this->fields->$field_name)) unset($this->fields->$field_name);
		return $this;
	}

	static function create_getters() {
		foreach(self::get_all_views() as $key => $value) {
			error_log($key.'::'.print_r($value,true));
		}
	}

	function build() {
		global $wpdb;
		$json_fields = json_encode($this->fields);
		if($this->saved) {
			$wpdb->update( $wpdb->prefix.'futon_views', array('collection' => $this->collection, 'fields' => json_encode($this->fields), 'function' => $this->function), array('name' => $this->name) );
			$this->create_table();
			$this->compile();
		} else {
			$wpdb->insert( $wpdb->prefix.'futon_views', array('name' => $this->name, 'collection' => $this->collection, 'fields' => json_encode($this->fields), 'function' => $this->function) );
			$this->create_table();
			$this->compile();
		}
		return $this;
	}

	private static function get_all_views() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}futon_views");
	}

	function field_names() {
		return array_keys((Array)$this->fields);
	}

	function create_table() {
		global $wpdb;
		$this->table = $wpdb->prefix.'futon_view_'.$this->collection.'_by_'.implode("_", $this->field_names());
		$wpdb->query("DROP TABLE IF EXISTS {$this->table}");
		foreach(array_keys((array)$this->fields) as $field) {
				$create .= "\n$field varchar(128) NOT NULL, ";
		}
		$sql = "CREATE TABLE " . $this->table . " (
			  id varchar(256) NOT NULL,
			  {$create}
			  UNIQUE KEY id (id)
			);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		return $this;
	}

	static function search_view($args, $magic_args) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}futon_view_{$magic_args[1]}_by_{$magic_args[2]} WHERE {$magic_args[2]} = '{$args[0]}';";
		if($results = $wpdb->get_results($sql)) {
			$return = $results;	
		} else {
			$return = false;
		}

		return $return;
	}

	function compile($since = 0) {
		global $wpdb;
		$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."futon_documents WHERE collection = '{$this->collection}'");
		$query = '';
		foreach($rows as $row) {
			$values = array();
			$row->data = json_decode($row->data);
			$values[] = '\''.$row->id.'\'';
			foreach($this->fields as $field => $type){
				if($type) {
					//error_log(print_r($row->data->$field,true));
					$values[] = '\''.$row->data->$field.'\'';
				}
			}
			$fields_string = implode(", ", array_keys((array)$this->fields));
			$values_string = implode(', ', $values);
			$query = "INSERT INTO {$this->table} (id, ".$fields_string.") VALUES(".$values_string."); ";
			$wpdb->query($query);
		}
		
		return $this;
	}

}