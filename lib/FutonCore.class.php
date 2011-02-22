<?
$futon = new FutonCore();

Class FutonCore {
	var $dir_root;
	var $load;
	var $prefs;
	function FutonCore() {
		$this->dir_root = dirname(dirname(__FILE__)).'/';
		//error_log('dirroot :: '.$this->dir_root);
		require_once("FutonLoader.class.php");
		$this->load = new FutonLoader();
		$this->load->libs(array('Collection', 'Document', 'Page', 'View', 'Settings'));

		FutonView::create_getters();
		//$this->document = new FutonDocument();
	}

	public function __call($method, $args) {
		error_log("method :: ".$method);
		if (preg_match("/find_([a-z_]+)_by_[([a-z_]+)[and_]*]+/", $method, $magic_args)) {
			error_log("MagicArgs :: ".print_r($magic_args,true));
			return FutonView::search_view($args, $magic_args);
		}
    }

	function create_view_hooks() {
		
	}

}