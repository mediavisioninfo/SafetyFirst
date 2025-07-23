<?php
class Admin_model extends CI_model
{



   public function login_user($email, $pass)
   {

      $this->db->select('*');
      $this->db->from('admin');
      $this->db->where('email', $email);
      $this->db->where('password', $pass);

      if ($query = $this->db->get()) {
         return $query->row_array();
      } else {
         return false;
      }
   }

	public function get_all_details($tablename){
		$this->db->select('*');
		$this->db->from($tablename);
		// $this->db->where('status', "report");
		$this->db->order_by("id", "desc");
		$query = $this->db->get();
		return $query->result_array();

	}
   public function get_admin($id)
   {
      $query = $this->db->get_where('admin', array('id' => $id));
      return $query->row_array();
   }

   public function get_user_by_id($id)
   {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where("id", $id);
      $query = $this->db->get();
      if ($query) {
         return $query->row();
      } else {
         return false;
      }
   }

   public function set_admin($id, $res_image)
   {
      $this->load->helper('url');

      if ($res_image == "") {
         $data = array(
            'email' => $this->input->post('email'),
            'name' => $this->input->post('name')
         );
      } else {
         $data = array(
            'email' => $this->input->post('email'),
            'name' => $this->input->post('name'),
            'img' => $res_image
         );
      }
      $this->db->where('id', $id);
      return $this->db->update('admin', $data);
   }

   public function update_user_by_id($cat_id, $data)
   {
      $res = $this->db->update('users', $data, ['id' => $cat_id]);
      if ($res == 1)
         return true;
      else
         return false;
   }

   public function password_check($password, $id)
   {

      $this->db->select('*');
      $this->db->from('admin');
      $this->db->where('password', $password);
      $this->db->where('id', $id);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
         return true;
      } else {
         return false;
      }
   }

   public function get_total_users()
   {
      return $this->db->get('users')->num_rows();
   }

   public function get_total_posts()
   {
      return $this->db->get('posts')->num_rows();
   }
	public function get_total_story()
	{
		return $this->db->get('story')->num_rows();
	}

   public function count_trending_post()
   {
      $this->db->select('follow.from_user, follow.to_user , posts.*, COUNT(likes.like_id) as like_cou')
         ->from('posts')
         ->order_by('like_cou', 'desc')
         ->join('likes', 'likes.post_id = posts.post_id')
         ->join('follow', 'follow.to_user = likes.user_id')
         ->group_by('post_id');
      return $res = $this->db->get()->num_rows();
   }

   public function get_trending_post()
   {
      $this->db->select('follow.from_user, follow.to_user , posts.*, COUNT(likes.like_id) as like_cou')
         ->from('posts')
         ->order_by('like_cou', 'desc')
         ->join('likes', 'likes.post_id = posts.post_id')
         ->join('follow', 'follow.to_user = likes.user_id')
         ->limit(10)
         ->group_by('post_id');
      return $res = $this->db->get()->result_array();
   }

   public function get_all_trending_post()
   {
      $this->db->select('users.id, posts.*, COUNT(likes.like_id) as like_cou')
         ->from('posts')
         ->join('likes', 'likes.post_id = posts.post_id')
         ->join('users', 'users.id = posts.user_id')
			->order_by('like_cou','desc')
         ->group_by('post_id');
			
      return $res = $this->db->get()->result_array();
   }

	public function all_posts($limit, $start)
	{

		$this->db->limit($limit, $start);
		$this->db->order_by("post_id", "desc");
		$query = $this->db->get('posts');

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
   public function get_all_post()
   {
      $this->db->select('*');
      $this->db->from('posts');
      $this->db->order_by("post_id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }
	public function get_user_notify()
	{
		$this->db->select('*');
		$this->db->from('user_notification');
		$this->db->order_by("not_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_admin_notify()
	{
		$this->db->select('*');
		$this->db->from('admin_notifications');
		$this->db->order_by("id", "desc");
		// $this->db->limit(100);
		$query = $this->db->get();
		return $query->result_array();
	}

   public function get_total_comment()
   {
      return $this->db->get('comments')->num_rows();
   }

   public function get_all_users()
   {
      $this->db->select('*');
      $this->db->from('users');
      $this->db->order_by("id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }

   public function delete_user($id)
   {
      $this->db->where('id', $id);
      return $this->db->delete('users');
   }

   public function delete_post($post_id)
   {
      $this->db->where('post_id', $post_id);
      return $this->db->delete('posts');
   }

   public function get_post_by_post_id($post_id)
   {
      $this->db->select('image');
      $this->db->from('posts');
      $this->db->where("post_id", $post_id);
      $query = $this->db->get();
      if ($query) {
         return $query->row()->image;
      } else {
         return false;
      }
   }

   public function get_post_by_user_id($user_id)
   {
      $this->db->select('*');
      $this->db->from('posts');
      $this->db->where("user_id", $user_id);
      $query = $this->db->get();
      return $query->result_array();
   }

   public function get_image($id)
   {
      $this->db->select('profile_pic');
      $this->db->from('users');
      $this->db->where("id", $id);
      $query = $this->db->get();
      if ($query) {
         return $query->row()->profile_pic;
      } else {
         return false;
      }
   }

   public function get_all_comment()
   {
      $this->db->select('*');
      $this->db->from('comments');
      $this->db->order_by("comment_id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }

   public function get_comment_by_id($comment_id)
   {
      $this->db->select('*');
      $this->db->from('comments');
      $this->db->where("comment_id", $comment_id);
      $query = $this->db->get();
      if ($query) {
         return $query->row();
      } else {
         return false;
      }
   }

   public function update_comment_by_id($comment_id, $data)
   {
      $res = $this->db->update('comments', $data, ['comment_id' => $comment_id]);
      if ($res == 1)
         return true;
      else
         return false;
   }

   public function delete_comment($comment_id)
   {
      $this->db->where('comment_id', $comment_id);
      return $this->db->delete('comments');
   }

   public function get_all_like($post_id)
   {
      $this->db->select('*');
      $this->db->from('likes');
      $this->db->where('post_id', $post_id);
      // $this->db->group_by('user_id');
      // $query = $this->db->get();
      // return $query->result_array();
      return $res = $this->db->get()->num_rows();
   }

   public function get_all_comment_report()
   {
      $this->db->select('*');
      $this->db->from('comment_report');
      $this->db->order_by("id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }

   public function get_total_Subscriptions()
   {
      return $this->db->get_where('users', array('isGold' => '1'))->num_rows();
   }

   public function get_users()
   {
      $this->db->select('users.*');
      $this->db->from('users');

      $query = $this->db->get();
      return $query->result_array();
   }

   function tuser()
   {
      $this->db->select('*');
      $this->db->from('users');
      $id = $this->db->get()->num_rows();
      return $id;
   }

   public function email_check($email)
   {

      $this->db->select('*');
      $this->db->from('users');
      $this->db->where('email', $email);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
         return false;
      } else {
         return true;
      }
   }

   public function register_user($user)
   {
      return $this->db->insert('users', $user);
   }
   public function get_profile($id)
   {
      $this->db->select('*');
      $this->db->from('admin');
      $this->db->where('id', $id);

      $query = $this->db->get();
      return $query->row_array();
   }

   public function set_profile($id, $img_name)
   {
      $this->load->helper('url');

      $data = array(
         'name' => $this->input->post('name'),
         'email' => $this->input->post('email'),
         'img' => $img_name
      );

      $this->db->where('id', $id);
      return $this->db->update('admin', $data);
   }

   public function change_pass($npassword, $id)
   {
      $this->load->helper('url');

      $data = array(
         'password' => $npassword
      );

      $this->db->where('id', $id);
      return $this->db->update('admin', $data);
   }

   public function get_like($id)
   {
      $this->db->select('likes.*,users.*');
      $this->db->from('likes');
      $this->db->join('users', 'user.id = likes.user_id');
      $this->db->where('res_id', $id);

      $query = $this->db->get();
      return $query->result();
   }

   public function get_all_interests()
   {
      $this->db->select('*');
      $this->db->from('interests');
      $this->db->order_by("id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }

   public function get_interest_by_id($interest_id)
   {
      $this->db->select('*');
      $this->db->from('interests');
      $this->db->where("id", $interest_id);
      $query = $this->db->get();
      if ($query) {
         return $query->row();
      } else {
         return false;
      }
   }

   public function update_interest_by_id($interest_id, $data)
   {
      $res = $this->db->update('interests', $data, ['id' => $interest_id]);
      if ($res == 1)
         return true;
      else
         return false;
   }
   
   public function get_all_report_post()
   {
      $this->db->select('*');
      $this->db->from('posts_report');
      $this->db->where('status', "report");
      $this->db->order_by("id", "desc");
      $query = $this->db->get();
      return $query->result_array();
   }

	public function get_setting(){
		$this->db->select('*');
		$this->db->from('settings');
		$query = $this->db->get();
		return $query->row();
	}
	public function change_setting($setid, $data){

		$res = $this->db->update('settings', $data, ['id' => $setid]);
		if ($res == 1)
			return true;
		else
			return false;
	}

	public function get_newstory(){
		
		$this->db->select('*');
		$this->db->from('story');
		$this->db->where('create_date >= NOW() - INTERVAL 1 DAY');
		$this->db->order_by('story_id','desc');
		$qry = $this->db->get();
		return $qry->result();
	}

	public function get_paststory()
	{

		$this->db->select('*');
		$this->db->from('story');
		$this->db->where('create_date < NOW() - INTERVAL 1 DAY');
		$this->db->order_by('story_id','desc');
		$qry = $this->db->get();
		return $qry->result();
	}
	public function delete_story($id)
	{
		$this->db->where('story_id', $id);
		return $this->db->delete('story');
	}
}
