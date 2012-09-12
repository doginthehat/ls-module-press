<?

	class Press_Actions extends Cms_ActionScope {
		public function article() {
			$this->data['article'] = null;

			$slug = $this->request_param(0);
			
			if(!$slug)
				return;
			
			$this->data['article'] = Press_Article::create()->find_by_slug($slug);
		}
		
		public function articles() {
			$this->data['articles'] = Press_Article::create()->where('is_enabled=1')->where('published_at is not null')->order('published_at desc')->find_all();
		}
	}
	