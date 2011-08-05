<?

	class Press_Article extends Db_ActiveRecord {
		public $strings = array(
			'model_title' => 'Article',
			'model_name' => 'article',
			'model_code' => 'press_article',
			'table_name' => 'press_articles',
			'module_name' => 'press'
		);
		
		public $has_many = array(
			'images' => array('class_name' => 'Db_File', 'foreign_key' => 'master_object_id', 'conditions' => "master_object_class='Press_Article' and field='images'", 'order' => 'sort_order, id', 'delete' => true),
			'files' => array('class_name' => 'Db_File', 'foreign_key' => 'master_object_id', 'conditions' => "master_object_class='Press_Article' and field='files'", 'order' => 'sort_order, id', 'delete' => true)
		);
		
		protected $api_added_columns = array();
		
		public function __construct() {
			$this->table_name = $this->strings['table_name'];
			$this->module_name = $this->strings['module_name'];
			$this->model_name = $this->strings['model_name'];
			
			parent::__construct();
		}
		
		public static function create() {
			return new self();
		}

		public function define_columns($context = null) {
			$this->define_column('title', 'Title')->order('asc')->validation()->fn('trim')->required("Please specify the title.");
			$this->define_column('slug', 'Slug')->validation()->fn('trim');
			$this->define_column('description', 'Description')->invisible()->validation()->fn('trim');
			$this->define_column('content', 'Content')->invisible()->validation()->fn('trim');
			$this->define_column('sort_order', 'Sort Order')->validation()->fn('trim')->unique("This sort order is already in use.");
			$this->define_column('is_enabled', 'Enabled');
			$this->define_column('published_at', 'Published Date');
			$this->define_multi_relation_column('images', 'images', 'Images', '@name')->invisible();
			$this->define_multi_relation_column('files', 'files', 'Files', '@name')->invisible();
			
			$this->defined_column_list = array();
			Backend::$events->fireEvent("{$this->strings['module_name']}:onExtend{$this->strings['model_title']}Model", $this, $context);
			$this->api_added_columns = array_keys($this->defined_column_list);
		}

		public function define_form_fields($context = null) {
			$this->add_form_field('is_enabled')->tab($this->strings['model_title'])->renderAs(frm_checkbox);
			$this->add_form_field('title', 'left')->tab($this->strings['model_title'])->renderAs(frm_text);
			$this->add_form_field('slug', 'right')->tab($this->strings['model_title'])->renderAs(frm_text);
			$this->add_form_field('published_at', 'left')->tab($this->strings['model_title']);

			$editor_config = System_HtmlEditorConfig::get($this->module_name, "{$this->strings['model_code']}_description");
			$field = $this->add_form_field('description')->tab($this->strings['model_title']);
			$field->renderAs(frm_html)->size('small');
			$editor_config->apply_to_form_field($field);
			
			$editor_config = System_HtmlEditorConfig::get($this->module_name, "{$this->strings['model_code']}_content");
			$field = $this->add_form_field('content')->tab($this->strings['model_title']);
			$field->renderAs(frm_html)->size('small');
			$editor_config->apply_to_form_field($field);
			
			$this->add_form_field('images')->renderAs(frm_file_attachments)->renderFilesAs('image_list')->addDocumentLabel('Add image(s)')->tab('Images')->noAttachmentsLabel('There are no images uploaded')->noLabel()->imageThumbSize(555)->fileDownloadBaseUrl(url('ls_backend/files/get/'));
			
			$this->add_form_field('files')->renderAs(frm_file_attachments)->renderFilesAs('file_list')->addDocumentLabel('Add file(s)')->tab('Files')->noAttachmentsLabel('There are no files uploaded.')->noLabel()->fileDownloadBaseUrl(url('ls_backend/files/get/'));
			
			Backend::$events->fireEvent("{$this->strings['module_name']}:onExtend{$this->strings['model_title']}Form", $this, $context);
			
			foreach($this->api_added_columns as $column_name) {
				$form_field = $this->find_form_field($column_name);
				
				if($form_field)
					$form_field->optionsMethod('get_added_field_options');
			}
		}
		
		public static function sort_items($first, $second) {
			if($first->sort_order == $second->sort_order)
				return 0;
				
			if($first->sort_order > $second->sort_order)
				return 1;
				
			return -1;
		}
		
		public static function set_orders($ids, $orders) {
			if(is_string($ids))
				$ids = explode(',', $ids);
				
			if(is_string($orders))
				$orders = explode(',', $orders);

			foreach($ids as $index => $id) {
				$order = $orders[$index];
				
				Db_DbHelper::query("update {$this->table_name} set sort_order=:sort_order where id=:id", array(
					'sort_order' => $order,
					'id' => $id
				));
			}
		}
		
		public function after_create() {
			Db_DbHelper::query("update {$this->table_name} set sort_order=:sort_order where id=:id", array(
				'sort_order' => $this->id,
				'id' => $this->id
			));

			$this->sort_order = $this->id;
		}
	}