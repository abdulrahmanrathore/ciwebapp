<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Article extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $admin = $this->session->userdata('admin');
        
        if (empty($admin)) {
            $this->session->set_flashdata('msg','Your session has been expired');
            redirect(base_url().'admin/login/index');
        }
    }

// This method will show article listing page
public function index($page = 1) {
	$perpage = 5;
	$param['offset'] = $perpage;
	$param['limit'] = ($page*$perpage)-$perpage;
	$param['q'] = $this->input->get('q');

	$this->load->model('Article_model');
	$this->load->library('pagination');
	$config['base_url'] = base_url('admin/article/index');
	$config['total_rows'] = $this->Article_model->getArticlesCount($param);
	$config['per_page'] = 5;
	$config['use_page_numbers'] = true;

	$config['first_link'] = 'First';
	$config['last_link'] = 'Last';
	$config['next_link'] = 'Next';
	$config['prev_link'] = 'Prev';
	$config['full_tag_open'] = "<ul class='pagination'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li class="page-item">';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled page-item'><li class='active page-item'><a href='#' class=\"page-link\">";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li class=\"page-item\">";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li  class=\"page-item\">";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li  class=\"page-item\">";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li  class=\"page-item\">";
	$config['last_tagl_close'] = "</li>";
	$config['attributes'] = array('class' => 'page-link');

	$this->pagination->initialize($config);
	$pagination_links = $this->pagination->create_links();


	$articles = $this->Article_model->getArticles($param);

	$data['q'] = $this->input->get('q');
	$data['articles'] = $articles;
	$data['pagination_links'] = $pagination_links;
	$data['mainModule'] = 'article';
	$data['subModule'] = 'viewArticle';
	// print_r($articles);
    $this->load->view('admin/article/list',$data);
	
}

// This method will show article create page
public function create() {
	$this->load->model('Category_model');
	$this->load->model('Article_model');
	$this->load->helper('common_helper');

	$data['mainModule'] = 'article';
	$data['subModule'] = 'createArticle';
	

	$categories = $this->Category_model->getCategories();
	$data['categories'] = $categories;


	// File upload settings
	$config['upload_path'] = './public/uploads/articles/';
	$config['allowed_types'] = 'gif|png|jpg';
	$config['encrypt_name'] = true;
	$this->load->library('upload', $config);
	

	
	$this->load->library('form_validation');
	$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
	$this->form_validation->set_rules('category_id','Category','trim|required');
	$this->form_validation->set_rules('title','title','trim|required|min_length[20]');
	$this->form_validation->set_rules('author','author','trim|required');

	if ($this->form_validation->run() == true) {
		// Form validated successfully and we can proceed

		if (!empty($_FILES['image']['name'])){
			//Here we will save images
			if($this->upload->do_upload('image')){
				// Image successfully uploaded here.
				$data = $this->upload->data();
				
				resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb_front/'.$data['file_name'],1120,800);
				resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb_admin/'.$data['file_name'],300,250);

				$formArray['image'] = $data['file_name'];
				$formArray['title'] = $this->input->post('title');
				$formArray['category'] = $this->input->post('category_id');
				$formArray['description'] = $this->input->post('description');
				$formArray['author'] = $this->input->post('author');
				$formArray['status'] = $this->input->post('status');
				$formArray['created_at'] = date('Y-m-d H:i:s');
				$this->Article_model->addArticle($formArray);
				$this->session->set_flashdata('success', 'Article added successfully');	
				redirect(base_url().'admin/article/index');
			} else {
				// Image selected has some errors.
				$error = $this->upload->display_errors('<p class="invalid-feedback">','</p>');
				$data['imageError'] = $error;
				$this->load->view('admin/article/create',$data);
			}
		} else {
			// Here we will save article without image

			$formArray['title'] = $this->input->post('title');
			$formArray['category'] = $this->input->post('category_id');
			$formArray['description'] = $this->input->post('description');
			$formArray['author'] = $this->input->post('author');
			$formArray['status'] = $this->input->post('status');
			$formArray['created_at'] = date('Y-m-d H:i:s');
			$this->Article_model->addArticle($formArray);
			$this->session->set_flashdata('success', 'Article added successfully');
			redirect(base_url().'admin/article/index');
		}




	} else {
		// Form not validated, you can show errors
		$this->load->view('admin/article/create',$data);
	}
    
}

// This method will show edit article page
public function edit($id) {
	$this->load->library('form_validation');
	$this->load->model('Article_model');
	$this->load->model('Category_model');
	$this->load->helper('common_helper');

	$data['mainModule'] = 'article';
	$data['subModule'] = '';

	$article = $this->Article_model->getArticle($id);
	if (empty($article)) {
		$this->session->set_flashdata('error','Article not found');
		redirect(base_url('admin/article/index'));
	} 

	$categories = $this->Category_model->getCategories();
	$data['categories'] = $categories;
	$data['article'] = $article;


	// File upload settings
	$config['upload_path'] = './public/uploads/articles/';
	$config['allowed_types'] = 'gif|png|jpg';
	$config['encrypt_name'] = true;
	$this->load->library('upload',$config);


	$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
	$this->form_validation->set_rules('category_id','Category','trim|required');
	$this->form_validation->set_rules('title','Title','trim|required|min_length[20]'); 
	$this->form_validation->set_rules('author','Author','trim|required'); 

	if ($this->form_validation->run() == true) {
		// Form validated successfully and we can proceed

		if (!empty($_FILES['image']['name'])) {
			// Here we will save images
			if ($this->upload->do_upload('image')) {
				// Image successfully uploaded here
				$data = $this->upload->data();

				$path = './public/uploads/articles/thumb_admin/'.$article['image'];
				if ($article['image'] != "" && file_exists($path)) {
					unlink($path); // This method will remove old image in thumb_admin folder
				}

				$path = './public/uploads/articles/thumb_front/'.$article['image'];
				if ($article['image'] != "" && file_exists($path)) {
					unlink($path); // This method will remove old image in thumb_front folder
				}

				$path = './public/uploads/articles/'.$article['image'];
				if ($article['image'] != "" && file_exists($path)) {
					unlink($path); // This method will remove old image in articles folder
				}
				
				resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb_front/'.$data['file_name'],1120,800);
				resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb_admin/'.$data['file_name'],300,250);

				$formArray['image'] = $data['file_name'];
				$formArray['title'] = $this->input->post('title');
				$formArray['category'] = $this->input->post('category_id');
				$formArray['description'] = $this->input->post('description');
				$formArray['author'] = $this->input->post('author');
				$formArray['status'] = $this->input->post('status');
				$formArray['updated_at'] = date('Y-m-d H:i:s');
				$this->Article_model->updateArticle($id, $formArray);

				$this->session->set_flashdata('success','Article updated successfully');
				redirect(base_url().'admin/article/index');

			}  else {
				// Image selected has some errors
				$errors = $this->upload->display_errors('<p class="invalid-feedback">','</p>');
				$data['imageError'] = $errors;
				$this->load->view('admin/article/edit',$data);
			}


		} else {
			// Here we will save article without image

			$formArray['title'] = $this->input->post('title');
			$formArray['category'] = $this->input->post('category_id');
			$formArray['description'] = $this->input->post('description');
			$formArray['author'] = $this->input->post('author');
			$formArray['status'] = $this->input->post('status');
			$formArray['updated_at'] = date('Y-m-d H:i:s');
			$this->Article_model->updateArticle($id, $formArray);
			$this->session->set_flashdata('success','Article updated successfully');
			redirect(base_url().'admin/article/index');
		}
		

	} else {
		// Form not validated, you can show errors 
		$this->load->view('admin/article/edit',$data);
	}
}

// This method will delete an article
public function delete($id) {
	$this->load->model('Article_model');
	$article = $this->Article_model->getArticle($id);
	if (empty($article)) {
		$this->session->set_flashdata('error','Article not found');
		redirect(base_url('admin/article/index'));
	} 

	$path = './public/uploads/articles/thumb_admin/'.$article['image'];
	if ($article['image'] != "" && file_exists($path)) {
		unlink($path); // This method will remove old image in thumb_admin folder
	}

	$path = './public/uploads/articles/thumb_front/'.$article['image'];
	if ($article['image'] != "" && file_exists($path)) {
		unlink($path); // This method will remove old image in thumb_front folder
	}

	$path = './public/uploads/articles/'.$article['image'];
	if ($article['image'] != "" && file_exists($path)) {
		unlink($path); // This method will remove old image in articles folder
	}

	$this->Article_model->deleteArticle($id);  // This will delete article

	$this->session->set_flashdata('success','Article has been deleted successfully');
	redirect(base_url().'admin/article/index');
}
        
}

/* End of file  Article.php */
/* Location: ./application/controllers/admin/Article.php */
?>       

