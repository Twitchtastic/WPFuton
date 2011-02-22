<?
/**
  * Type: Library
  * Package: Futon
  *	Name: settings
  *	Description: The API used for setting up defaults or site settings in WPFuton.
 */
Class FutonSettings {
	var $settings_file;
	var $by_name;
	function FutonSettings() {
		require_once(dirname(__FILE__).'/FutonCore.class.php');
		$futon = new FutonCore();
		$this->settings_file = $futon->dir_root.'settings.json';
		error_log($this->settings_file);
		$settings = json_decode(file_get_contents($this->settings_file));
		if(count($settings)) {
			foreach($settings as $name => $value) {
				$this->by_name->$name = $value;
			}
		}
	}

	function get($name) {
		return $this->by_name->$name;
	}

	function set($name, $value) {
		$this->by_name->$name = $value;
		return $this;
	}

	function save() {
		$settings = json_encode((array)$this->by_name);
		file_put_contents($this->settings_file,$settings);
		chmod($this->settings_file, 770);
		return $this;
	}
/*
	private static function indent_settings() {
		$result	  			= '';
		$position		 	= 0;
		$string_length	  	= strLen($json);
		$indent_string   	= '  ';
		$new_line	 		= "\n";
		$previous_character	= '';
		$out_of_quotes 		= true;

		for ($i=0; $i<=$string_length; $i++) {

			// Grab the next character in the string.
			$character = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($character == '"' && $previous_character != '\\') {
				$out_of_quotes = !$out_of_quotes;
			
			// If this character is the end of an element, 
			// output a new line and indent the next line.
			} else if(($character == '}' || $character == ']') && $out_of_quotes) {
				$result .= $new_line;
				$position --;
				for ($j=0; $j<$position; $j++) {
					$result .= $indent_string;
				}
			}
			// Add the character to the result string.
			$result .= $character;
			// If the last character was the beginning of an element, 
			// output a new line and indent the next line.
			if (($character == ',' || $character == '{' || $character == '[') && $out_of_quotes) {
				$result .= $new_line;
				if ($character == '{' || $character == '[') {
					$position ++;
				}
				
				for ($j = 0; $j < $position; $j++) {
					$result .= $indent_string;
				}
			}
			$previous_character = $character;
		}
		return $result;
	}*/

}