<?

	define('PATH_MOD_PRESS', realpath(dirname(__FILE__) . '/../'));
	
	class Press_Module extends Core_ModuleBase {
		const PATH = PATH_MOD_PRESS;
		
		protected function get_info() {
			return new Core_ModuleInfo(
				"Press",
				"Provides press releases for your store.",
				"Limewheel Creative Inc."
			);
		}
		
		public function build_ui_permissions($host) {
			$host->add_field($this, 'manage_articles', 'Manage articles', 'left')->renderAs(frm_checkbox)->comment('View and manage the articles.', 'above');
			$host->add_field($this, 'manage_settings', 'Manage settings', 'left')->renderAs(frm_checkbox)->comment('View and manage the settings.', 'above');
		}
		
		public function list_tabs($tab_collection) {
			$user = Phpr::$security->getUser();
			
			$tabs = array(
				'articles' => array('articles', 'Articles', 'articles'),
				'settings' => array('settings', 'Settings', 'settings')
			);

			$first_tab = null;
			
			foreach($tabs as $tab_id => $tab_info) {
				if(($tabs[$tab_id][3] = $user->get_permission('press', $tab_info[2])) && !$first_tab)
					$first_tab = $tab_info[0];
			}

			if($first_tab) {
				$tab = $tab_collection->tab('press', 'Press', $first_tab, 30);
				
				foreach($tabs as $tab_id => $tab_info) {
					if($tab_info[3])
						$tab->addSecondLevel($tab_id, $tab_info[1], $tab_info[0]);
				}
			}
		}
		
		public function list_html_editor_configs() {
			return array(
				'press_article_description' => 'Article description',
				'press_article_content' => 'Article content'
			);
		}
		
		/**
		 * Awaiting deprecation
		 */
		
		protected function createModuleInfo() {
			return $this->get_info();
		}
		
		public function buildPermissionsUi($host) {
			return $this->build_ui_permissions($host);
		}
		
		public function listTabs($tab_collection) {
			return $this->list_tabs($tab_collection);
		}
		
		public function listHtmlEditorConfigs() {
			return $this->list_html_editor_configs();
		}
	}
