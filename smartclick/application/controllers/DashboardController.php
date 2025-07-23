<?php defined('BASEPATH') or exit('No direct script access allowed');
class DashboardController extends CI_Controller
{
	
   public function __construct()
   {
      parent::__construct();
      // $this->load->model('admin_model');
      // $this->load->helper('url');
      // $this->adminmodel->not_logged_in();
   }

   public function index()
   {
		if ($this->session->userdata('aid') == "") {
			redirect(base_url('admin/login'));
		}
      $data['page'] = 'index';
      $this->load->view('admin/template', $data);
   }

	// public function count_user(){

	// 	$qry = $this->db->query("SELECT join_month as months, COUNT(id) as total FROM users GROUP BY join_month");
		
	// 	$data= $qry->result();
	// 	if(!empty($data)){
	// 		$response = $data;
	// 	}else{
	// 		$response = array('months'=> "0", 'total' => "0");
	// 	}
		
	// 	echo json_encode($response);
	// 	exit;
	// }
}

