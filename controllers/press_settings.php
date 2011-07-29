<?

	class Press_Settings extends Backend_Controller {
		public $implement = 'Db_FormBehavior, Db_ListBehavior';
		protected $required_permissions = array('press:manage_settings');

		public function __construct() {
			parent::__construct();
			$this->app_tab = 'press';
			$this->app_page = 'settings';
			$this->app_module_name = 'Press';
		}

		public function index() {
			$this->app_page_title = 'Settings';
		}
	}