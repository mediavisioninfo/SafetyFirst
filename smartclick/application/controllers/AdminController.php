<?php defined('BASEPATH') or exit('No direct script access allowed');
class AdminController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->helper('url');
		// $this->load->model('admin_model');
		// $this->load->library('session');
		// $this->load->helper('form');
		// $this->load->library('form_validation');
		// $this->load->model('firebase_model');
		// $this->load->library("pagination");
		// if ($this->session->userdata('aid') == "") {
		// 	redirect(base_url('admin/login'));
		// }
	}

	public function chart_data()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$qry = $this->db->query("SELECT join_month, COUNT(id)as total FROM `users` GROUP BY join_month");
		$res = $qry->result();
		echo json_encode($res);
		return json_encode($res);
	}
	public function login()
	{
		$this->load->view('admin/login');
	}

	public function login_admin()
	{
		$login = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password'))
		);

		$data = $this->admin_model->login_user($login['email'], $login['password']);
		if ($data) {
			$this->session->set_userdata('aid', $data['id']);
			$this->session->set_userdata('aemail', $data['email']);
			$this->session->set_userdata('aname', $data['name']);
			$this->session->set_userdata('aimg', $data['img']);

			redirect(base_url('admin/dashboard'));
		} else {
			$this->session->set_flashdata('error', 'Email Id And Password Wrong..');
			redirect(base_url('admin/login'));
		}
	}

	public function logout()
	{

		$this->session->sess_destroy();
		redirect(base_url() . 'admin/login', 'refresh');
	}

	public function user_profile()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['page'] = 'profile';
		$this->load->view('admin/template', $data);
	}

	public function admin_edit()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		// $id = $this->uri->segment(3);
		$id = $this->session->userdata('aid');

		if (empty($id)) {
			show_404();
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$profile = $this->admin_model->get_admin($id);

		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');

		if ($this->form_validation->run() === FALSE) {
			$data['page'] = 'profile';
			$this->load->view('admin/template', $data);
		} else {
			if ($_FILES['img']['name'] == "") {
				$this->admin_model->set_admin($id, $res_image = "");
				$this->session->set_flashdata('success', 'successfully Updated..');
				redirect(base_url('admin/profile'));
			} else {
				$image_exts = array("tif", "jpg", "jpeg", "gif", "png");

				$configVideo['upload_path'] = './uploads/profile_pics/'; # check path is correct
				$configVideo['max_size'] = '102400';
				$configVideo['allowed_types'] = $image_exts; # add video extenstion on here
				$configVideo['overwrite'] = FALSE;
				$configVideo['remove_spaces'] = TRUE;
				$configVideo['file_name'] = uniqid();

				$this->load->library('upload', $configVideo);
				$this->upload->initialize($configVideo);

				if (!$this->upload->do_upload('img')) # form input field attribute
				{
					$this->session->set_flashdata('error', 'Image Type Error...');
					$data['page'] = 'profile';
					$this->load->view('admin/template', $data);
				} else {
					# Upload Successfull
					$upload_data = $this->upload->data();
					$res_image = $upload_data['file_name'];

					$this->admin_model->set_admin($id, $res_image);
					$this->session->set_flashdata('success', 'successfully Updated..');
					redirect(base_url('admin/profile'));
				}
			}
		}
	}

	public function change_password()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->session->userdata('aid');

		if (empty($id)) {
			show_404();
		}

		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('npassword', 'New Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');

		if ($this->form_validation->run() === FALSE) {
			// $this->load->view("Admin/header.php");
			// $this->load->view("Admin/sidebar.php");
			// $this->load->view("Admin/profile.php",$data);
			// $this->load->view("Admin/footer.php");
			$data['page'] = 'profile';
			$this->load->view('admin/template', $data);
		} else {
			$password = md5($this->input->post('password'));
			$npassword = md5($this->input->post('npassword'));
			$cpassword = md5($this->input->post('cpassword'));

			if ($npassword == $cpassword) {
				$password_check = $this->admin_model->password_check($password, $id);

				if ($password_check) {
					$this->admin_model->change_pass($npassword, $id);
					$this->session->set_flashdata('msg_success', 'Successfully Changed..');
					// redirect(base_url().'admin/profile/');
					redirect(base_url('admin/profile'));
				} else {
					$this->session->set_flashdata('msg_error', 'Old Password Wrong..');
					redirect(base_url() . 'admin/profile/');
				}
			} else {
				$this->session->set_flashdata('msg_error', 'New Password And Confirm Password Not Match..');
				redirect(base_url() . 'admin/profile/');
			}
		}
	}

	public function users()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'user';
		$this->load->view('admin/template', $data);
	}

	public function user_edit()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'user_edit';
		$this->load->view('admin/template', $data);
	}

	public function update_user()
	{
		$user_id = $_REQUEST['id'];

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}


		$this->form_validation->set_rules('username', 'User Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('phone', 'Phone Number', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');
		if ($this->form_validation->run() == false) {
			//Error
		} else {
			$data = array(
				'username' => $_REQUEST['username'],
				'email' => $_REQUEST['email'],
				'phone' => $_REQUEST['phone'],

			);
			if (!empty($_FILES['profile_pic']['name'])) {
				$config['upload_path'] = './uploads/profile_pics/';
				$config['allowed_types'] = 'jpg|png|jpeg|tif|gif';
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('profile_pic')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error_msg', $error['error']);
				} else {

					$image_name = $this->admin_model->get_image($user_id);
					if ($image_name != 'default_profile.png') {
						$path = './uploads/profile_pics/' . $image_name;
						unlink($path);
					}
					$img_file = $this->upload->data();
					$data['profile_pic'] = $img_file['file_name'];
				}
			}

			$check = $this->admin_model->update_user_by_id($user_id, $data);
			if ($check) {
				$this->session->set_flashdata('success_msg', 'User has been successfully Updated.');
				// redirect('admin/category-list',$data);
				redirect(base_url('admin/user'));
			}
		}
		redirect(base_url('admin/user-edit/' . $user_id));
		// $data['page'] = 'user_edit';
		// $data['users'] = $this->admin_model->get_user_by_id($user_id); 
		// $this->load->view('admin/template',$data);
	}

	public function delete_user()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->uri->segment(3);
		if (empty($id)) {
			show_404();
		}

		$query = $this->db->get_where("posts", array('user_id' => $id));
		$posts = $query->result();

		foreach ($posts as $key => $post) {

			$this->db->where('post_id', $post->post_id);
			$this->db->delete('likes');

			$this->db->where('post_id', $post->post_id);
			$this->db->delete('comments');

			$this->db->where('post_id', $post->post_id);
			$this->db->delete('bookmark');

			$this->db->where('post_id', $post->post_id);
			$this->db->delete('comment_report');

			$this->db->where('blockedPostsId', $post->post_id);
			$this->db->delete('posts_report');
		}
		$this->admin_model->delete_post($post->post_id);

		$this->db->where('user_id', $id);
		$this->db->delete('posts');

		$this->db->where('user_id', $id);
		$this->db->delete('likes');

		$this->db->where('user_id', $id);
		$this->db->delete('comments');

		$this->db->where('user_id', $id);
		$this->db->delete('bookmark');

		$this->db->where('from_user', $id);
		$this->db->delete('follow');

		$this->db->where('to_user', $id);
		$this->db->delete('follow');

		$this->db->where('reportByUserId', $id);
		$this->db->delete('users_report');

		$this->db->where('reportedUserId', $id);
		$this->db->delete('users_report');

		$this->db->where('blockedByUserId', $id);
		$this->db->delete('posts_report');
		
		$this->db->where('blockedByUserId', $id);
		$this->db->delete('profile_blocklist');

		$this->db->where('blockedUserId', $id);
		$this->db->delete('profile_blocklist');
			
		$this->db->where('user_id', $id);
		$this->db->delete('story');
			
		
		$this->db->where('from_user', $id);
		$this->db->delete('user_notification');
			
		
		$this->db->where('to_user', $id);
		$this->db->delete('user_notification');
		
		$this->db->where('user_id', $id);
		$this->db->delete('admin_notifications');
			
		$this->db->where('user_id', $id);
		$this->db->delete('comment_report');
		
		$this->admin_model->delete_user($id);

		$this->session->set_flashdata('success_msg', 'successfully Deleted..');
		redirect(base_url('admin/user'));
	}

	public function all_post()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$config = array();
		$config["base_url"] = base_url() . 'admin-all-post';
		$config["total_rows"] = $this->db->count_all('posts');
		$config["per_page"] = 30;
		$config["uri_segment"] = 2;

		//paging configuration
		$config['num_links'] = 2;
		$config['use_page_numbers'] = FALSE;
		$config['page_query_string'] = FALSE;
		$config['reuse_query_string'] = FALSE;

		$config['prefix'] = "";
		$config['suffix'] = "";
		$config['use_global_url_suffix'] = FALSE;

		$config['full_tag_open'] = '<ul class="pagination justify-content-center pagination-primary">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = array('class' => 'page_link');
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li class="page-item page-link">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="page-item page-link">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item page-link">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item page-link">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
		$config['num_tag_open'] = '<li class="page-item page-link">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(2)) ? ($this->uri->segment(2)) : 1;

		$params['all_post'] = $this->admin_model->all_posts($config["per_page"], $page);

		$params["links"] = $this->pagination->create_links();
		$params['echoho'] = $page;
		$params['page'] = 'all_post';
		$this->load->view('admin/template', $params);
	}
	public function admin_notifications()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['adminnotify'] = $this->admin_model->get_admin_notify();
		$data['page'] = 'notify_admin';
		$this->load->view('admin/template', $data);
	}

	public function user_notifications()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['usernotify'] = $this->admin_model->get_user_notify();
		$data['page'] = 'notify_user';
		$this->load->view('admin/template', $data);
	}


	public function delete_post()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->uri->segment(3);
		if (empty($id)) {
			show_404();
		}

		$this->db->where('post_id', $id);
		$this->db->delete('likes');

		$this->db->where('post_id', $id);
		$this->db->delete('comments');

		$this->db->where('post_id', $id);
		$this->db->delete('bookmark');

		$this->db->where('post_id', $id);
		$this->db->delete('comment_report');

		$this->db->where('blockedPostsId', $id);
		$this->db->delete('posts_report');

		$this->admin_model->delete_post($id);

		$this->session->set_flashdata('success_msg', 'successfully Deleted..');
		redirect(base_url('admin/all-post'));
	}
	public function delete_notification()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->uri->segment(3);
		if (empty($id)) {
			show_404();
		}

		$this->db->where('id', $id);
		$this->db->delete('admin_notifications');

		// $this->session->set_flashdata('success_msg', 'successfully Deleted..');
		redirect(base_url('admin/admin-notifications'));
	}

	public function trending_post()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['trending_post'] = $this->admin_model->get_all_trending_post();
		$data['page'] = 'trending_post';
		$this->load->view('admin/template', $data);
	}

	public function comment()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'comment';
		$this->load->view('admin/template', $data);
	}

	public function comment_edit()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'comment_edit';
		$this->load->view('admin/template', $data);
	}

	public function update_comment()
	{
		$comment_id = $_REQUEST['id'];

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}


		$this->form_validation->set_rules('text', 'Text', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');
		if ($this->form_validation->run() == false) {
			//Error
		} else {
			$data = array(
				'text' => $_REQUEST['text'],
			);

			$check = $this->admin_model->update_comment_by_id($comment_id, $data);
			if ($check) {
				$this->session->set_flashdata('success_msg', 'User has been successfully Updated.');
				// redirect('admin/category-list',$data);
				redirect(base_url('admin/comment'));
			}
		}
		redirect(base_url('admin/comment-edit/' . $comment_id));
		// $data['page'] = 'user_edit';
		// $data['users'] = $this->admin_model->get_user_by_id($comment_id); 
		// $this->load->view('admin/template',$data);
	}

	public function delete_comment()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->uri->segment(3);
		if (empty($id)) {
			show_404();
		}
		$this->admin_model->delete_comment($id);

		$this->session->set_flashdata('success_msg', 'successfully Deleted..');
		redirect(base_url('admin/comment'));
	}

	public function like()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'like';
		$this->load->view('admin/template', $data);
	}

	public function followers_following()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'followers_following';
		$this->load->view('admin/template', $data);
	}

	public function comment_report()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'comment_report';
		$this->load->view('admin/template', $data);
	}

	public function interests()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'interests';
		$this->load->view('admin/template', $data);
	}

	public function create_interest()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'add_interest';
		$this->load->view('admin/template', $data);
	}

	public function add_interest()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$this->form_validation->set_rules('type', 'Interest Type', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');
		if ($this->form_validation->run() == false) {
			//Error
		} else {
			$data = array(
				'type' => $_REQUEST['type'],
				'create_date' => time(),
			);

			$check = $this->db->insert('interests', $data);
			if ($check) {
				$this->session->set_flashdata('success_msg', 'Interest has been successfully Add.');
				redirect(base_url('admin/interests'));
			}
		}

		$data['page'] = 'add_interest';
		$this->load->view('admin/template', $data);
	}

	public function interest_edit()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'interest_edit';
		$this->load->view('admin/template', $data);
	}

	public function update_interest()
	{
		$interest_id = $_REQUEST['id'];

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$this->form_validation->set_rules('type', 'Interest Type', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');
		if ($this->form_validation->run() == false) {
			//Error
		} else {
			$data = array(
				'type' => $_REQUEST['type'],
			);

			$check = $this->admin_model->update_interest_by_id($interest_id, $data);
			if ($check) {
				$this->session->set_flashdata('success_msg', 'Interest has been successfully Updated.');
				// redirect('admin/category-list',$data);
				redirect(base_url('admin/interests'));
			}
		}
		redirect(base_url('admin/interest-edit/' . $interest_id));
	}

	public function push_notifications()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'user_notifications';
		$this->load->view('admin/template', $data);
	}

	public function send_user_notifications()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$title = $this->input->post('title');
		$message = $this->input->post('message');

		if (!empty($_FILES['image']['name'])) {
			$config['upload_path'] = './uploads/notifications/';
			$config['allowed_types'] = 'jpg|png|jpeg|gif|tif';
			$config['file_name'] = uniqid();
			$config['overwrite'] = TRUE;

			// Load and initialize upload library
			$this->load->library('upload');
			$this->upload->initialize($config);

			// Upload file to server
			if ($this->upload->do_upload('image')) {
				// Uploaded file data
				$fileData = $this->upload->data();
				$image = $fileData['file_name'];
			} else {
				$error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
			}
		} else {
			$image = "";
		}

		$user = $this->db->query("SELECT * FROM users ORDER BY id ASC")->result();

		foreach ($user as $key => $list) {

			$this->firebase_model->save_admin_notification($list->id, $title, $message, $image);
			$response = $this->firebase_model->admin_send_user_notification($list->id, $title, $message, "Message");
		}

		$this->session->set_flashdata('success', 'Send Successfully.');
		redirect('admin/user-notifications');
	}


	public function report_post()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['page'] = 'report_post';
		$this->load->view('admin/template', $data);
	}

	public function report_users()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['report_user'] = $this->admin_model->get_all_details($tablename = 'users_report');
		$data['page'] = 'report_user';
		$this->load->view('admin/template', $data);
	}

	public function settings(){
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$data['setting_dtl'] = $this->admin_model->get_setting();
		$data['page'] = 'settings';
		$this->load->view('admin/template',$data);
	}
	public function chenge_settings(){

		$setid = $_REQUEST['id'];
		$noti_key = $_REQUEST['noti_key'];
		$pnp = $_REQUEST['pnp'];
		$tnc = $_REQUEST['tnc'];

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		
		$this->form_validation->set_rules('noti_key', 'Notification Key', 'required');
		$this->form_validation->set_rules('pnp', 'Privacy Policy', 'required');
		$this->form_validation->set_rules('tnc', 'Term & Condition', 'required');
		$this->form_validation->set_error_delimiters('<span class="error" style="color:red;">', '</span>');

		if ($this->form_validation->run() != false) {

			$data= array(
				
				'notify_key' => $noti_key,
				'prv_pol_url' => $pnp,
				'tnc_url' => $tnc,
			);

			$check = $this->admin_model->change_setting($setid, $data);
			if ($check) {
				// $this->session->set_flashdata('success_msg', 'Interest has been successfully Updated.');
				redirect(base_url('admin/settings'));
			}
		}else{
			// echo "errorrr";
			redirect(base_url('admin/settings'));
		}
	}

	public function newstorys(){

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['storydtl'] = $this->admin_model->get_newstory();
		$data['title']="New Storys";
		$data['page'] = 'storys';
		$this->load->view('admin/template', $data);
	}
	public function paststorys()
	{

		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
		$data['storydtl'] = $this->admin_model->get_paststory();
		$data['title'] = "Past Storys";
		$data['page'] = 'storys';
		$this->load->view('admin/template', $data);
	}

	public function delete_story()
	{
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}

		$id = $this->uri->segment(3);
		if (empty($id)) {
			show_404();
		}
		$this->admin_model->delete_story($id);
		redirect(base_url('admin/newstorys'));
	}
}
