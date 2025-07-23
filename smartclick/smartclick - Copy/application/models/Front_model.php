<?php

class Front_model extends CI_Model
{

    public function login_user($email, $pass)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("(users.email ='$email' OR users.username ='$email')");
        $this->db->where("password", $pass);
        
        if ($query = $this->db->get()) {
            return $query->row_array();
        } else {
            return false;
        }
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
    
    public function username_email_check($username, $email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("(users.username = '$username' OR users.email ='$email')");
        // $this->db->where('otp_status','1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function register_user($user)
    {

        // return $this->db->insert('users', $user);
        
        $res = $this->db->insert('users', $user);
        $insert_id = $this->db->insert_id();
        if ($res == 1) {
            return $insert_id;
        } else {
            return false;
        }
    }

    public function get_postby_userid($id)
    {
        $this->db->select('count(*)');
        $this->db->where("user_id", $id);
        $query = $this->db->get('posts');
        $cnt = $query->row_array();
        return $cnt['count(*)'];
    }

    public function likeCheck($user_id, $res_id)
    {
        $result = $this->db->get_where('likes', array('user_id' => $user_id, 'post_id' => $res_id));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
   
    public function unlike($res_id, $user_id)
    {
        $this->db->where('post_id', $res_id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('likes');
    }

    public function get_user($id)
    {
        $query = $this->db->get_where('users', array('id' => $id), 1);
        return $query->row();
    }

    public function get_useridby_followers($id)
    {
        $this->db->select('count(*)');
        $this->db->where("to_user", $id);
        $query = $this->db->get('follow');
        $cnt = $query->row_array();
        return $cnt['count(*)'];
    }

    public function get_useridby_following($id)
    {
        $this->db->select('count(*)');
        $this->db->where("from_user", $id);
        $query = $this->db->get('follow');
        $cnt = $query->row_array();
        return $cnt['count(*)'];
    }
    
    public function user_id_check($user_id)
    {
        $this->db->select('*');
        $this->db->from('story');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
   
    public function update_story($data, $user_id)
    {
        $this->db->where('user_id', $user_id);

        return $this->db->update('story', $data);
    }
   
    public function delete_story()
    {
        $this->db->select('*');
        $this->db->from('story');
        $this->db->where('create_date<=DATE_SUB(NOW(), INTERVAL 24 HOUR)');
        $query = $this->db->get();
        $story = $query->result();
        if (!empty($story)) {
            foreach ($story as $key => $url) {
                unlink('./uploads/story_image/'.$url->url);
            }
        }

        $this->db->where('create_date<=DATE_SUB(NOW(), INTERVAL 24 HOUR)');
        return $this->db->delete("story");
    }
    
    public function bookmarkCheck($user_id, $res_id)
    {
        $result = $this->db->get_where('bookmark', array('user_id' => $user_id, 'post_id' => $res_id));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function delete_bookmark($res_id, $user_id)
    {
        $this->db->where('post_id', $res_id);
        $this->db->where('user_id', $user_id);
        return $this->db->delete('bookmark');
    }
    
    public function profile_blockCheck($blockedByUserId, $blockedUserId)
    {
        $result = $this->db->get_where('profile_blocklist', array('blockedByUserId' => $blockedByUserId, 'blockedUserId' => $blockedUserId));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function unblock_profile($blockedByUserId, $blockedUserId)
    {
        $this->db->where('blockedByUserId', $blockedByUserId);
        $this->db->where('blockedUserId', $blockedUserId);
        return $this->db->delete('profile_blocklist');
    }

    public function posts_blockCheck($blockedByUserId, $blockedPostsId)
    {
        $result = $this->db->get_where('posts_report', array('blockedByUserId' => $blockedByUserId, 'blockedPostsId' => $blockedPostsId));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function unblock_posts($blockedByUserId, $blockedPostsId)
    {
        $this->db->where('blockedByUserId', $blockedByUserId);
        $this->db->where('blockedPostsId', $blockedPostsId);
        return $this->db->delete('posts_report');
    }

    public function posts_reportCheck($blockedByUserId, $blockedPostsId)
    {
        $result = $this->db->get_where('posts_report', array('blockedByUserId' => $blockedByUserId, 'blockedPostsId' => $blockedPostsId));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function profile_block_Check($blockedByUserId, $blockedUserId)
    {
        $result = $this->db->get_where('profile_blocklist', array('blockedByUserId' => $blockedByUserId, 'blockedUserId' => $blockedUserId));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function users_reportCheck($reportByUserId, $reportedUserId)
    {
        $result = $this->db->get_where('users_report', array('reportByUserId' => $reportByUserId, 'reportedUserId' => $reportedUserId));
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

}
