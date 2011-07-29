<?

	class Press_Actions extends Cms_ActionScope {
		public function articles() {
			$this->data['articles'] = Press_Article::create()->where('is_enabled=1')->find_all();
		}
	}
	