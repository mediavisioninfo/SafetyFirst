<?php

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('front_model');
        $this->load->model('firebase_model');
        header('Content-Type: application/json');
    }


    public function username_email_check()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) || empty($_POST['password']) || !isset($_POST['username']) || empty($_POST['username']) ) {
            $temp["response_code"] = "0";
            $temp["message"] = "Please Enter Data and not be empty..!";
            $temp["status"] = "failure";
            echo json_encode($temp);
            return;
        }

        $salt = mt_rand(100, 999);
        $password = $this->input->post('password');

        $data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => md5(md5($password) . $salt),
            'salt' => "$salt",
        );
        $username_email_check = $this->front_model->username_email_check($data['username'], $data['email']);

        if ($username_email_check) {

            $reg = $this->front_model->register_user($data);
            $id = $this->db->insert_id();
            $data["id"] = "$id";

            if ($reg) {
                $temp["response_code"] = "1";
                $temp["message"] = "user register success";
                $temp["status"] = "success";
                $temp["user"] = $data;
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "user register failure";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "username & Email Id Already Registered";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function register_new()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['email']) || !isset($_POST['id']) || !isset($_POST['username'])) {
            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        } else {
            // $time = round(microtime(true) * 1000);
            $user = array(
                'email' => $this->input->post('email'),
                // 'password' => md5($this->input->post('password')),
                'username' => $this->input->post('username'),
                'age' => $this->input->post('age'),
                'gender' => $this->input->post('gender'),
                'country' => $this->input->post('country'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'bio' => $this->input->post('bio'),
                'interests_id' => $this->input->post('interests_id'),
                'device_token' => $this->input->post('device_token'),
                // "create_date" => time(),
            );

            $temp = array();

            $id = $this->input->post('id');

            $this->db->where('id', $id);
            $reg = $this->db->update('users', $user);

            $users = $this->db->get_where('users', array('id' => $id), 1)->row();

            if (!empty($users->interests_id)) {
                $interests_id = explode(", ", $users->interests_id);
                $in_id = array();
                $category_name = array();
                foreach ($interests_id as $key => $id) {
                    array_push($in_id, $id);
                }
                $users->interests_id = $in_id;
            } else {
                $users->interests_id = [];
            }

            if ($reg) {
                $temp["response_code"] = "1";
                $temp["message"] = "user register success";
                $temp["status"] = "success";
                $temp["user"] = $users;
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "user register failure";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        }
    }

    public function login()
    {
        header('Content-Type: application/json');

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $device_token = $this->input->post('device_token');
        $user = $this->db->get_where('users', array('email' => $email), 1)->row();

        if (empty($user)) {
            $result['response_code'] = "0";
            $result['message'] = "email id Not Found";
            $result["status"] = "failure";
            echo json_encode($result);
            return;
        }

        $salt = $user->salt;

        $login = array(
            'email' => $this->input->post('email'),
            'password' => md5(md5($password) . $salt),
            'device_token' => $this->input->post('device_token')
        );
        if ($login['email'] == "") {
            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        } else {
            $temp = array();

            $user_data = $this->front_model->login_user($login['email'], $login['password']);

            if ($user_data['email'] = $login['email']) {
                $this->db->where(array("email" => $login['email']));
                $this->db->update('users', array('device_token' => $device_token));
            } else {
                $this->db->where(array("username" => $login['email']));
                $this->db->update('users', array('device_token' => $device_token));
            }

            $data = $this->front_model->login_user($login['email'], $login['password']);

            if ($data) {

                if (!empty($data['profile_pic'])) {
                    $url = explode(":", $data['profile_pic']);

                    if ($url[0] == "https" || $url[0] == "http") {
                        $data['profile_pic'] = $data['profile_pic'];
                    } elseif (!empty($data['profile_pic'])) {
                        $data['profile_pic'] = base_url() . "/assets/images/user/" . $data['profile_pic'];
                    } else {
                        $data['profile_pic'] = $data['profile_pic'];
                    }
                } else {
                    $data['profile_pic'] = "";
                }

                if (!empty($data['cover_pic'])) {
                    $url = explode(":", $data['cover_pic']);

                    if ($url[0] == "https" || $url[0] == "http") {
                        $data['cover_pic'] = $data['cover_pic'];
                    } elseif (!empty($data['cover_pic'])) {
                        $data['cover_pic'] = base_url() . "assets/images/user/" . $data['cover_pic'];
                    } else {
                        $data['cover_pic'] = $data['cover_pic'];
                    }
                } else {
                    $data['cover_pic'] = "";
                }

                if (!empty($data['interests_id'])) {
                    $interests_id = explode(", ", $data['interests_id']);
                    $in_id = array();
                    $category_name = array();
                    foreach ($interests_id as $key => $id) {
                        array_push($in_id, $id);
                    }
                    $data['interests_id'] = $in_id;
                } else {
                    $data['interests_id'] = [];
                }

                $temp["response_code"] = "1";
                $temp["message"] = "user login success";
                $temp["status"] = "success";
                $temp["user"] = $data;
                //   $temp["payment_status"]=$data['pstatus'];
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
                //   $this->db->where(array("email" => $login['email'], "password" => $login['password']));
                //   $this->db->update('user', array('device_token' => $login['device_token']));
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "user login failure";
                $temp["status"] = "failure";
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
            }
        }
    }

    public function social_login()
    {
        header('Content-Type: application/json');
        $device_token = $this->input->post('device_token');
        if (!isset($_POST['type'])) {
            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        } else {
            $type = $this->input->post("type");
            $email = $this->input->post("email");

            // $username = $this->input->post("username");

            $username = trim(str_replace(' ', '', $_POST['username']));

            $google_id = $this->input->post("google_id");
            $image_url = $this->input->post("image_url");

            if ($username == "") {
                $temp["response_code"] = "0";
                $temp["message"] = "No User Name or Email Given!";
                $temp["status"] = "failure";
                echo json_encode($temp);
                return;
            }

            $email_check = $this->front_model->email_check($email);
            // $facebook_id_check = $this->front_model->facebook_id_check($facebook_id);

            // $time = round(microtime(true) * 1000);

            $user = array(
                'username' => $username,
                "login_type" => $type,
                "google_id" => $google_id,
                "email" => $email,
                "password" => "",
                "gender" => "",
                'age' => "",
                "country" => "",
                'state' => "",
                'city' => "",
                'bio' => "",
                'device_token' => $device_token,
                // "create_date" => time(),
            );

            if (empty($image_url)) {
                $user["profile_pic"] = "";
            } else {
                $user["profile_pic"] = $image_url;
            }

            // if (empty($facebook_id)) {
            if (!$email_check) {

                $this->db->where(array("email" => $email));
                $this->db->update('users', array('device_token' => $device_token));

                $user = $this->db->get_where("users", array("email" => $email))->row();
                if (empty($image_url)) {
                    if ($user->profile_pic != "") {
                        $user->profile_pic = base_url("assets/images/user/" . $user->profile_pic);
                    }
                } else {
                    $user->profile_pic = $image_url;
                }

                if (!empty($user->interests_id)) {
                    $interests_id = explode(", ", $user->interests_id);
                    $in_id = array();
                    $category_name = array();
                    foreach ($interests_id as $key => $id) {
                        array_push($in_id, $id);
                    }
                    $user->interests_id = $in_id;
                } else {
                    $user->interests_id = [];
                }

                $temp["response_code"] = "1";
                $temp["message"] = "user login success";
                $temp["status"] = "success";
                $temp["user"] = $user;
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
                return;
            }
            // } else {
            //    if (!$facebook_id_check) {

            //       $this->db->where(array("facebook_id" => $facebook_id));
            //       $this->db->update('user', array('device_token' => $device_token));

            //       $user = $this->db->get_where("user", array("facebook_id" => $facebook_id))->row();

            //       if (empty($image_url)) {
            //          if ($user->profile_pic != "") {
            //             // $user->profile_pic = base_url("uploads/profile_pics/" . $user->profile_pic);
            //             $user->profile_pic = $user->profile_pic;
            //          }
            //       } else {
            //          $user->profile_pic = $image_url;
            //       }

            //       $temp["response_code"] = "1";
            //       $temp["message"] = "user register success";
            //       $temp["user"] = $user;
            //       $temp["status"] = "success";
            //       echo json_encode($temp);
            //       return;
            //    }
            // }

            if ($this->db->insert("users", $user)) {

                $id = $this->db->insert_id();

                $user = $this->db->get_where("users", array("id" => $id))->row();

                if (!empty($user->interests_id)) {
                    $interests_id = explode(", ", $user->interests_id);
                    $in_id = array();
                    $category_name = array();
                    foreach ($interests_id as $key => $id) {
                        array_push($in_id, $id);
                    }
                    $user->interests_id = $in_id;
                } else {
                    $user->interests_id = [];
                }

                // $user["id"] = "$id";

                $temp["response_code"] = "1";
                $temp["message"] = "user login success";
                $temp["status"] = "success";
                $temp["user"] = $user;
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
                return;
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "user login fail";
                $temp["status"] = "fail";
                $temp["user_token"] = md5(uniqid(rand(), true));
                echo json_encode($temp);
            }
        }
    }

    public function user_edit()
    {
        header('Content-Type: application/json');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $id = $this->input->post('id');

        $user = $this->db->get_where('users', array('id' => $id), 1)->row();

        $this->form_validation->set_rules('id', 'id', 'required');
        //   $this->form_validation->set_rules('username', 'username', 'required');
        //   $this->form_validation->set_rules('phone', 'phone', 'required');

        if ($this->form_validation->run() === FALSE) {
            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        } else {
            $res_image = $user->profile_pic;

            if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != "") {
                $image_exts = array("tif", "jpg", "jpeg", "gif", "png");

                $configVideo['upload_path'] = './assets/images/user/'; # check path is correct
                $configVideo['max_size'] = '102400';
                $configVideo['allowed_types'] = $image_exts; # add video extenstion on here
                $configVideo['overwrite'] = FALSE;
                $configVideo['remove_spaces'] = TRUE;
                $configVideo['file_name'] = uniqid();

                $this->load->library('upload', $configVideo);
                $this->upload->initialize($configVideo);

                if (!$this->upload->do_upload('profile_pic')) # form input field attribute
                {
                    $temp["response_code"] = "0";
                    $temp["message"] = "Image Type Error";
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                } else {
                    # Upload Successfull
                    $upload_data = $this->upload->data();
                    $res_image = $upload_data['file_name'];
                }
            }

            //  $user = array(
            //     'fullname' => $this->input->post('fullname'),
            //     'username' => $this->input->post('username'),
            //     'email' => $this->input->post('email'),
            //     'phone' => $this->input->post('phone'),
            //     'website' => $this->input->post('website'),
            //     'age' => $this->input->post('age'),
            //     'gender' => $this->input->post('gender'),
            //     'country' => $this->input->post('country'),
            //     'state' => $this->input->post('state'),
            //     'city' => $this->input->post('city'),
            //     'bio' => $this->input->post('bio'),
            //     'profile_pic' => $res_image
            //  );

            $user = array();

            $fullname = $this->input->post('fullname');
            if (!empty($fullname)) {
                $user['fullname'] = $this->input->post('fullname');
            }

            $username = $this->input->post('username');
            if (!empty($username)) {
                $user['username'] = $this->input->post('username');
            }

            $email = $this->input->post('email');
            if (!empty($email)) {
                $user['email'] = $this->input->post('email');
            }

            $phone = $this->input->post('phone');
            if (!empty($phone)) {
                $user['phone'] = $this->input->post('phone');
            }

            //  $website = $this->input->post('website');
            //  if (!empty($website)) {
            //     $user['website'] = $this->input->post('website');
            //  }

            $age = $this->input->post('age');
            if (!empty($age)) {
                $user['age'] = $this->input->post('age');
            }

            $gender = $this->input->post('gender');
            if (!empty($gender)) {
                $user['gender'] = $this->input->post('gender');
            }

            $country = $this->input->post('country');
            if (!empty($country)) {
                $user['country'] = $this->input->post('country');
            }

            $state = $this->input->post('state');
            if (!empty($state)) {
                $user['state'] = $this->input->post('state');
            }

            $city = $this->input->post('city');
            if (!empty($city)) {
                $user['city'] = $this->input->post('city');
            }

            $bio = $this->input->post('bio');
            if (!empty($bio)) {
                $user['bio'] = $this->input->post('bio');
            }

            $interests_id = $this->input->post('interests_id');
            if (!empty($interests_id)) {
                $user['interests_id'] = $this->input->post('interests_id');
            }

            $user['profile_pic'] = $res_image;

            $this->db->where('id', $id);
            $update = $this->db->update('users', $user);
            $users = $this->db->get_where('users', array('id' => $id), 1)->row();

            $url = explode(":", $users->profile_pic);

            if ($url[0] == "https" || $url[0] == "http") {
                $users->profile_pic = $users->profile_pic;
            } elseif (!empty($users->profile_pic)) {
                $users->profile_pic = base_url() . "assets/images/user/" . $users->profile_pic;
            } else {
                $users->profile_pic = $users->profile_pic;
            }

            if ($update) {
                $temp["response_code"] = "1";
                $temp["message"] = "Update Successfully";
                $temp["user"] = $users;
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "Database error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        }
    }

    public function forgot_pass()
    {
        header('Content-Type: application/json');
        $email = $this->input->post('email');

        $result = array();

        $user = $this->db->get_where('users', array('email' => $email), 1)->row();

        if ($user->email != "") {
            $data = array();

            $salt = mt_rand(100, 999);
            $new_pass = mt_rand(100000, 999999);
            $data['password'] = md5(md5($new_pass) . $salt);
            $data['salt'] = "$salt";

            $this->db->where('email', $email);
            $this->db->update('users', $data);

            //Send Email
            $message = "<h1>Hello " . $user->email . "</h1>";
            $message .= "<h1>Your password reset was Successful. New Pass: " . $new_pass . "</h1>";

            $message = array(
                'email' => $user->username,
                'password' => $new_pass,
            );

            $this->load->library('email');

            // Mail config
            $to = $user->email;
            $from = "keval.primocys@gmail.com";
            $fromName = "Lesbigay App Team";
            $mailSubject = "Password Reset Success";

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->to($to);
            $this->email->from($from, $fromName);
            $this->email->subject($mailSubject);

            $body = $this->load->view('admin/forgot_pass_email.php', $message, TRUE);
            $this->email->message($body);
            $send = $this->email->send();

            // $this->email->message($message);
            // // Send email & return status
            // $send = $this->email->send();

            if ($send) {
                $result['status'] = 1;
                $result['msg'] = "Password Changed";
                $result['new_pass'] = $new_pass;

                echo json_encode($result);
            } else {
                $result['status'] = 0;
                $result['msg'] = "Mail Sent Error";
                $result['new_pass'] = $new_pass;

                echo json_encode($result);
            }
        } else {
            $result['status'] = 0;
            $result['msg'] = "invalid Email";

            echo json_encode($result);
        }
    }


    public function user_data()
    {
        header('Content-Type: application/json');
        $id = $this->input->post('user_id');
        if (empty($id)) {
            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        } else {
            $temp = array();
            $profile = array();
            $profile = $this->front_model->get_user($id);
            $user_post = $this->front_model->get_postby_userid($id);
            $user_followers = $this->front_model->get_useridby_followers($id);
            $user_following = $this->front_model->get_useridby_following($id);

            // $profile->profile_pic = base_url() . "uploads/profile_pics/" . $profile->profile_pic;

            if (!empty($profile)) {

                if (!empty($profile->profile_pic)) {

                    $url = explode(":", $profile->profile_pic);

                    if ($url[0] == "https" || $url[0] == "http") {
                        $profile->profile_pic = $profile->profile_pic;
                    } elseif (!empty($profile->profile_pic)) {
                        $profile->profile_pic = base_url() . "assets/images/user/" . $profile->profile_pic;
                    } else {
                        $profile->profile_pic = $profile->profile_pic;
                    }
                } else {
                    $profile->profile_pic = "";
                }

                if (!empty($profile->cover_pic)) {

                    $url = explode(":", $profile->cover_pic);

                    if ($url[0] == "https" || $url[0] == "http") {
                        $profile->cover_pic = $profile->cover_pic;
                    } elseif (!empty($profile->cover_pic)) {
                        $profile->cover_pic = base_url() . "assets/images/user/" . $profile->cover_pic;
                    } else {
                        $profile->cover_pic = $profile->cover_pic;
                    }
                } else {
                    $profile->cover_pic = "";
                }

                if (!empty($profile->interests_id)) {
                    $interests_id = explode(", ", $profile->interests_id);
                    $in_id = array();
                    $category_name = array();
                    foreach ($interests_id as $key => $id) {
                        array_push($in_id, $id);
                    }
                    $profile->interests_id = $in_id;
                } else {
                    $profile->interests_id = [];
                }

                $temp["response_code"] = "1";
                $temp["message"] = "User Found";
                $temp['user'] = $profile;
                $temp['user_post'] = $user_post;
                $temp['followers'] = $user_followers;
                $temp['following'] = $user_following;
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "User Not Found";
                $temp['user_post'] = $user_post;
                $temp['followers'] = $user_followers;
                $temp['following'] = $user_following;
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        }
    }

    public function change_password()
    {
        header('Content-Type: application/json');
        $id = $this->input->post('user_id');

        if (empty($id)) {
            show_404();
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['profile'] = $this->front_model->get_user($id);


        $password = md5($this->input->post('password'));
        $npassword = md5($this->input->post('npassword'));
        $cpassword = md5($this->input->post('cpassword'));

        if ($npassword == $cpassword) {
            $password_check = $this->front_model->password_check($password, $id);

            if ($password_check) {
                $this->front_model->change_pass($npassword, $id);
                $temp = array();
                $temp["response_code"] = "1";
                $temp["message"] = "Successfully Changed";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {
                $temp = array();
                $temp["response_code"] = "0";
                $temp["message"] = "Old Password Wrong";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {
            $temp = array();
            $temp["response_code"] = "0";
            $temp["message"] = "'New Password And Confirm Password Not Match..";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function add_post()
    {
        header('Content-Type: application/json');
        if ($_POST['text'] != "" || $_POST['user_id'] != "") {

            $res_image = array();
            $res_video = "";
            $video_thumbnail = "";

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                //echo "image detected";
                if (is_array($_FILES['image']['name'])) {
                    $filesCount = count($_FILES['image']['name']);
                    for ($i = 0; $i < $filesCount; $i++) {
                        $_FILES['file']['name']     = $_FILES['image']['name'][$i];
                        $_FILES['file']['type']     = $_FILES['image']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                        $_FILES['file']['error']     = $_FILES['image']['error'][$i];
                        $_FILES['file']['size']     = $_FILES['image']['size'][$i];

                        // File upload configuration
                        $config['upload_path'] = './assets/images/post';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] = uniqid();
                        $config['overwrite'] = TRUE;


                        // Load and initialize upload library
                        $this->load->library('upload');
                        $this->upload->initialize($config);

                        // Upload file to server
                        if ($this->upload->do_upload('file')) {
                            // Uploaded file data
                            $fileData = $this->upload->data();
                            array_push($res_image, $fileData['file_name']);
                            //$res_image = $fileData['file_name'];

                        } else {
                            $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));

                            $temp["response_code"] = "0";
                            $temp["message"] = $error['error'];
                            $temp["status"] = "failure";
                            echo json_encode($temp);
                        }
                    }
                } else {

                    $temp["response_code"] = "0";
                    $temp["message"] = "Not an Array";
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                }
            }

            if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != "") {
                // File upload configuration
                $config['upload_path'] = './assets/images/post';
                $config['allowed_types'] = 'mp4|mkv';
                $config['file_name'] = uniqid();
                $config['overwrite'] = TRUE;


                // Load and initialize upload library
                $this->load->library('upload');
                $this->upload->initialize($config);

                // Upload file to server
                if ($this->upload->do_upload('video')) {
                    // Uploaded file data
                    $fileData = $this->upload->data();
                    $res_video = $fileData['file_name'];
                } else {
                    $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                    $temp["response_code"] = "0";
                    $temp["message"] = $error['error'];
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                }
            }

            if (isset($_FILES['video_thumbnail']['name']) && $_FILES['video_thumbnail']['name'] != "") {
                // File upload configuration
                $config['upload_path'] = './assets/images/post/video_thumbnail/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = uniqid();
                $config['overwrite'] = TRUE;


                // Load and initialize upload library
                $this->load->library('upload');
                $this->upload->initialize($config);

                // Upload file to server
                if ($this->upload->do_upload('video_thumbnail')) {
                    // Uploaded file data
                    $fileData = $this->upload->data();
                    $video_thumbnail = $fileData['file_name'];
                } else {
                    $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                    $temp["response_code"] = "0";
                    $temp["message"] = $error['error'];
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                }
            }

            $data['user_id'] = $this->input->post('user_id');
            $data['text'] = $this->input->post('text');
            $data['location'] = $this->input->post('location');
            $data['image'] = implode('::::', $res_image);
            $data['video'] = $res_video;
            $data['video_thumbnail'] = $video_thumbnail;
            $data['create_date'] = round(microtime(true) * 1000);

            if ($this->db->insert('posts', $data)) {


                $temp["response_code"] = "1";
                $temp["message"] = "success";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Database Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function edit_post()
    {
        header('Content-Type: application/json');
        if ($_POST['text'] != "" || $_POST['user_id'] != "") {

            $res_image = array();
            $res_video = "";
            $logo = array();
            $res_id = $this->input->post('res_id');
            $restaurant = $this->front_model->get_restaurant_by_id($res_id);
            if ($_FILES['image']['name'][0] != "") {
                //echo "image detected";
                if (is_array($_FILES['image']['name'])) {
                    $filesCount = count($_FILES['image']['name']);
                    for ($i = 0; $i < $filesCount; $i++) {
                        $_FILES['file']['name']     = $_FILES['image']['name'][$i];
                        $_FILES['file']['type']     = $_FILES['image']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                        $_FILES['file']['error']     = $_FILES['image']['error'][$i];
                        $_FILES['file']['size']     = $_FILES['image']['size'][$i];

                        // File upload configuration
                        $config['upload_path'] = './assets/images/post';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] = uniqid();
                        $config['overwrite'] = TRUE;


                        // Load and initialize upload library
                        $this->load->library('upload');
                        $this->upload->initialize($config);

                        // Upload file to server
                        if ($this->upload->do_upload('file')) {
                            // Uploaded file data
                            $fileData = $this->upload->data();
                            array_push($res_image, $fileData['file_name']);
                            //$res_image = $fileData['file_name'];

                        } else {
                            $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));

                            $temp["response_code"] = "0";
                            $temp["message"] = $error['error'];
                            $temp["status"] = "failure";
                            echo json_encode($temp);
                        }
                    }
                    $data['image'] = implode("::::", $res_image);
                }
            }

            if (!empty($restaurant->video)) {
                $res_video = $restaurant->video;
            } else {
                $res_video = "";
            }

            if ($_FILES['video']['name'] != "") {
                // File upload configuration
                $config['upload_path'] = './assets/images/post';
                $config['allowed_types'] = 'mp4|mkv';
                $config['file_name'] = uniqid();
                $config['overwrite'] = TRUE;


                // Load and initialize upload library
                $this->load->library('upload');
                $this->upload->initialize($config);

                // Upload file to server
                if ($this->upload->do_upload('video')) {
                    // Uploaded file data
                    $fileData = $this->upload->data();
                    $res_video = $fileData['file_name'];
                } else {
                    $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                    $temp["response_code"] = "0";
                    $temp["message"] = $error['error'];
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                }
            }

            $data['text'] = $this->input->post('text');
            $data['location'] = $this->input->post('location');
            $data['video'] = $res_video;

            $res_id = $this->input->post('post_id');

            $this->db->where('post_id', $res_id);
            if ($this->db->update('posts', $data)) {


                $temp["response_code"] = "1";
                $temp["message"] = "success";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Database Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function like_post()
    {
        header('Content-Type: application/json');
        if (isset($_POST['post_id']) && isset($_POST['user_id'])) {

            $like = array();
            $like['post_id'] = $this->input->post('post_id');
            $like['user_id'] = $this->input->post('user_id');
            $like['date'] = round(microtime(true) * 1000);

            $checkLike = $this->front_model->likeCheck($like['user_id'], $like['post_id']);

            if (!$checkLike) {
                $temp["response_code"] = "0";
                $temp["message"] = "Already Liked Post";
                $temp["status"] = "fail";
                echo json_encode($temp);

                return;
            }

            if ($this->db->insert('likes', $like)) {

                $from_user = $this->input->post('user_id');

                $post_id = $this->input->post('post_id');
                $posts = $this->db->get_where('posts', array('post_id' => $post_id))->row();
                $to_user = $posts->user_id;
                $user = $this->db->get_where('users', array('id' => $from_user))->row();

                $title = "Liked Post";
                $message = "" . $user->username . " liked your post.";
                $response = $this->firebase_model->send_user_notification($to_user, $title, $message, "Message");
                $this->firebase_model->save_user_notification($from_user, $to_user, $title, $message, $post_id);

                $temp["response_code"] = "1";
                $temp["message"] = "Liked Post";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Databse Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Missing Fields";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function unlike_post()
    {
        header('Content-Type: application/json');

        $res_id = $this->input->post('post_id');
        $user_id = $this->input->post('user_id');

        if ($this->front_model->unlike($res_id, $user_id)) {
            $temp["response_code"] = "1";
            $temp["message"] = "Successfully Unlike";
            $temp["status"] = "success";
            echo json_encode($temp);
        } else {
            $temp["response_code"] = "0";
            $temp["message"] = "Database error";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function likes_by_post()
    {
        $result = array();
        header('Content-Type: application/json');

        $post_id = $this->input->post('post_id');

        $result['status'] = 1;
        $result['message'] = "Likes Found";
        $res = $this->db->get_where('likes', array('post_id' => $post_id))->result();

        for ($i = 0; $i < count($res); $i++) {
            $user = $this->db->get_where('user', array('id' => $res[$i]->user_id))->row();
            $res[$i]->username = $user->username;
        }

        $result['likes'] = $res;

        echo json_encode($result);
    }

    public function add_comments()
    {
        header('Content-Type: application/json');
        if (isset($_POST['post_id']) && isset($_POST['user_id'])) {

            $like = array();
            $like['post_id'] = $this->input->post('post_id');
            $like['user_id'] = $this->input->post('user_id');
            $like['text'] = $this->input->post('text');
            $like['date'] = round(microtime(true) * 1000);

            if ($this->db->insert('comments', $like)) {

                $from_user = $this->input->post('user_id');

                $post_id = $this->input->post('post_id');
                $posts = $this->db->get_where('posts', array('post_id' => $post_id))->row();
                $to_user = $posts->user_id;
                $user = $this->db->get_where('users', array('id' => $from_user))->row();

                $title = "Comment Post";
                $message = "" . $user->username . " commented on your post.";
                $response = $this->firebase_model->send_user_notification($to_user, $title, $message, "Message");
                $this->firebase_model->save_user_notification($from_user, $to_user, $title, $message, $post_id);

                $temp["response_code"] = "1";
                $temp["message"] = "Comment Add";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Databse Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Missing Fields";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function delete_comment()
    {
        header('Content-Type: application/json');

        $comment_id = $this->input->post('comment_id');

        $this->db->where('comment_id', $comment_id);
        if ($this->db->delete('comments')) {
            $temp["response_code"] = "1";
            $temp["message"] = "Successfully Deleted";
            $temp["status"] = "success";
            echo json_encode($temp);
        } else {
            $temp["response_code"] = "0";
            $temp["message"] = "Database error";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function comments_by_post()
    {
        $result = array();
        header('Content-Type: application/json');

        $post_id = $this->input->post('post_id');

        $result['status'] = 1;
        $result['message'] = "Comments Found";
        $res = $this->db->get_where('comments', array('post_id' => $post_id))->result();

        for ($i = 0; $i < count($res); $i++) {
            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id))->row();
            $res[$i]->username = $user->username;

            if (!empty($user->profile_pic)) {
                $url = explode(":", $user->profile_pic);
                if ($url[0] == "https" || $url[0] == "http") {
                    $res[$i]->profile_pic = $user->profile_pic;
                } else {

                    $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                }
            } else {
                $res[$i]->profile_pic = "";
            }
        }

        $result['likes'] = $res;

        echo json_encode($result);
    }

    public function get_all_user()
    {
        $result = array();
        header('Content-Type: application/json');

        $result['status'] = 1;
        $result['msg'] = "Restaurnats Found";
        $res = $this->db->get('user')->result();

        for ($i = 0; $i < count($res); $i++) {
            $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $res[$i]->profile_pic;
        }

        $result['users'] = $res;

        echo json_encode($result);
    }

    public function follow_user()
    {
        header('Content-Type: application/json');
        if (isset($_POST['to_user']) && isset($_POST['from_user'])) {

            $like = array();
            $like['to_user'] = $this->input->post('to_user');
            $like['from_user'] = $this->input->post('from_user');
            $like['date'] = round(microtime(true) * 1000);

            if ($this->db->insert('follow', $like)) {

                $from_user = $this->input->post('from_user');
                $to_user = $this->input->post('to_user');

                $user = $this->db->get_where('users', array('id' => $from_user))->row();

                $post_id = 0;

                $title = "follow";
                $message = "" . $user->username . " started following you.";
                $response = $this->firebase_model->send_user_notification($to_user, $title, $message, "Message");
                $this->firebase_model->save_user_notification($from_user, $to_user, $title, $message, $post_id);

                $temp["response_code"] = "1";
                $temp["message"] = "follow Add";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Databse Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Missing Fields";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function unfollow_user()
    {
        header('Content-Type: application/json');
        if (isset($_POST['to_user']) && isset($_POST['from_user'])) {

            $to_user = $this->input->post('to_user');
            $from_user = $this->input->post('from_user');

            $this->db->where('to_user', $to_user);
            $this->db->where('from_user', $from_user);
            if ($this->db->delete('follow')) {

                $temp["response_code"] = "1";
                $temp["message"] = "unfollow success";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Databse Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Missing Fields";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function my_followers()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');

        $result['status'] = 1;
        $result['msg'] = "follower Found";

        $res = $this->db->get_where('follow', array('to_user' => $user_id, 'from_user !=' => $user_id))->result();

        for ($i = 0; $i < count($res); $i++) {
            $user = $this->db->get_where('users', array('id' => $res[$i]->from_user))->row();

            if (!empty($user)) {
                $res[$i]->username = $user->username;

                if (!empty($user->profile_pic)) {

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }

                    // $res[$i]->profile_pic = base_url() . "assets/images/user/" . $user->profile_pic;

                } else {
                    $res[$i]->profile_pic = "";
                }
                $res[$i]->follow_user_id = $user->id;
            } else {
                $res[$i]->username = "";
                $res[$i]->profile_pic = "";
                $res[$i]->follow_user_id = "";
            }
        }

        $result['follower'] = $res;

        echo json_encode($result);
    }

    public function my_following()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');

        $result['status'] = 1;
        $result['msg'] = "follower Found";
        $res = $this->db->get_where('follow', array('from_user' => $user_id, 'to_user !=' => $user_id))->result();

        for ($i = 0; $i < count($res); $i++) {
            $user = $this->db->get_where('users', array('id' => $res[$i]->to_user))->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if (!empty($user->profile_pic)) {
                    // $res[$i]->profile_pic = base_url() . "uploads/profile_pics/" . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                } else {
                    $res[$i]->profile_pic = "";
                }
                $res[$i]->follow_user_id = $user->id;
            } else {
                $res[$i]->username = "";
                $res[$i]->profile_pic = "";
                $res[$i]->follow_user_id = "";
            }
        }

        $result['follower'] = $res;

        echo json_encode($result);
    }

    public function post_by_user()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');
        $to_user_id = $this->input->post('to_user_id');

        $result['status'] = 1;
        $result['msg'] = "Post Found";
        $res = $this->db->order_by("post_id", "desc")->get_where('posts', array('user_id' => $user_id))->result();

        for ($i = 0; $i < count($res); $i++) {
            if (!empty($res[$i]->image)) {

                $url = explode(":", $res[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $res[$i]->image;

                    array_push($image_url, $image_url_a);

                    $res[$i]->all_image = $image_url;
                } else {
                    $images = explode("::::", $res[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $res[$i]->all_image = $imgsa;
                }
            } else {
                $res[$i]->all_image = array();
            }

            if ($res[$i]->video == "") {
                $res[$i]->video = "";
            } else {

                $url = explode(":", $res[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $res[$i]->video = $res[$i]->video;
                } else {
                    $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                }
            }

            if (!empty($res[$i]->video_thumbnail)) {
                $res[$i]->video_thumbnail = base_url() . '/assets/images/post/video_thumbnail/' . $res[$i]->video_thumbnail;
            } else {
                $res[$i]->video_thumbnail = "";
            }

            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $res[$i]->profile_pic = "";
                } else {

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $res[$i]->profile_pic = "";
                $res[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($to_user_id, $res[$i]->post_id);
            if (!empty($is_likes)) {
                $res[$i]->is_likes = "false";
            } else {
                $res[$i]->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($to_user_id, $res[$i]->post_id);
            if (!empty($bookmark)) {
                $res[$i]->bookmark = "false";
            } else {
                $res[$i]->bookmark = "true";
            }

            $posts_report = $this->front_model->posts_reportCheck($to_user_id, $res[$i]->post_id);
            if (!empty($posts_report)) {
                $res[$i]->posts_report = "false";
            } else {
                $res[$i]->posts_report = "true";
            }

            $posts_user_id = $res[$i]->user_id;

            $profile_block = $this->front_model->profile_block_Check($to_user_id, $posts_user_id);
            if (!empty($profile_block)) {
                $res[$i]->profile_block = "false";
            } else {
                $res[$i]->profile_block = "true";
            }
        }

        $result['follower'] = $res;

        echo json_encode($result);
    }

    public function all_post_by_user()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');

        $result['status'] = 1;
        $result['msg'] = "Post Found";

        $query = $this->db->query("SELECT * FROM follow WHERE from_user = '$user_id'");
        $fcount = $query->num_rows();
        if ($fcount > 0) {
            $query = $this->db->query("SELECT A.from_user AS from_user, A.to_user AS to_user, B.* FROM follow A, posts B WHERE  A.to_user = B.user_id AND A.from_user = '$user_id' ORDER BY B.post_id DESC");
            $all_post = $query->result();

            $query = $this->db->query("SELECT p.*, p.user_id as to_user FROM posts p ORDER BY post_id DESC LIMIT 20");
            $twenty_post = $query->result();

            $inputArray = array_merge($twenty_post, $all_post);

            $res = array();

            foreach ($inputArray as $inputArrayItem) {

                foreach ($res as $outputArrayItem) {

                    if ($inputArrayItem->post_id == $outputArrayItem->post_id) {
                        continue 2;
                    }
                }
                $res[] = $inputArrayItem;
            }
        } else {
            $query = $this->db->query("SELECT * FROM posts ORDER BY post_id DESC LIMIT 20");
            $res = $query->result();
        }

        for ($i = 0; $i < count($res); $i++) {
            if (!empty($res[$i]->image)) {

                $url = explode(":", $res[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $res[$i]->image;

                    array_push($image_url, $image_url_a);

                    $res[$i]->image = $image_url;

                    $res[$i]->all_image = $image_url;
                } else {
                    $images = explode("::::", $res[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $res[$i]->image = $imgsa;

                    $res[$i]->all_image = $imgsa;
                }
            } else {
                $res[$i]->image = [];
                $res[$i]->all_image = array();
            }
            if ($res[$i]->video == "") {
                $res[$i]->video = "";
            } else {

                $url = explode(":", $res[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $res[$i]->video = $res[$i]->video;
                } else {
                    $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                }
            }

            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $res[$i]->profile_pic = "";
                } else {
                    // $res[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $res[$i]->profile_pic = "";
                $res[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_comments = $total_comments;

            if ($fcount != '0') {
                $res[$i]->from_user = $user_id;
                $res[$i]->to_user = $res[$i]->to_user;
            } else {
                $res[$i]->from_user = "0";
                $res[$i]->to_user = "0";
            }

            if ($fcount != '0') {
                $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
                if (!empty($is_likes)) {
                    $res[$i]->is_likes = "false";
                } else {
                    $res[$i]->is_likes = "true";
                }
            } else {
                $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
                if (!empty($is_likes)) {
                    $res[$i]->is_likes = "false";
                } else {
                    $res[$i]->is_likes = "true";
                }
            }

            if ($fcount != '0') {
                $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
                if (!empty($bookmark)) {
                    $res[$i]->bookmark = "false";
                } else {
                    $res[$i]->bookmark = "true";
                }
            } else {
                $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
                if (!empty($bookmark)) {
                    $res[$i]->bookmark = "false";
                } else {
                    $res[$i]->bookmark = "true";
                }
            }

            $post_id = $res[$i]->post_id;
            $query = $this->db->query("SELECT A.comment_id,A.post_id,A.user_id,A.text,A.date, B.username,B.profile_pic FROM comments A, users B WHERE A.post_id = '$post_id' AND A.user_id = B.id ORDER BY A.comment_id DESC LIMIT 1");
            $comments = $query->row();

            if (!empty($comments)) {
                if (!empty($comments->profile_pic)) {
                    $url = explode(":", $comments->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $comments->profile_pic = $comments->profile_pic;
                    } elseif (!empty($comments->profile_pic)) {
                        $comments->profile_pic = base_url() . "assets/images/user/" . $comments->profile_pic;
                    } else {
                        $comments->profile_pic = $comments->profile_pic;
                    }
                } else {
                    $comments->profile_pic = "";
                }
            }
            if (!empty($comments)) {
                $res[$i]->comment = $comments;
            }
        }

        $result['post'] = $res;

        echo json_encode($result);
    }

    public function get_all_latest_post()
    {
        $result = array();
        header('Content-Type: application/json');

        if (!isset($_POST['user_id'])) {
            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "fail";
            echo json_encode($result);
            return;
        }

        $user_id = $this->input->post('user_id');

        $query = $this->db->query("SELECT * FROM posts WHERE image != '' ORDER BY post_id DESC LIMIT 20");
        $res = $query->result();

        for ($i = 0; $i < count($res); $i++) {
            if (!empty($res[$i]->image)) {

                $url = explode(":", $res[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $res[$i]->image;

                    array_push($image_url, $image_url_a);

                    $res[$i]->image = $image_url;
                    $res[$i]->all_image = $image_url;
                } else {
                    $images = explode("::::", $res[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;
                        array_push($imgsa, $imgs);
                    }
                    $res[$i]->image = $imgsa;
                    $res[$i]->all_image = $imgsa;
                }
            } else {
                $res[$i]->image = [];
                $res[$i]->all_image = array();
            }
            if ($res[$i]->video == "") {
                $res[$i]->video = "";
            } else {

                $url = explode(":", $res[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $res[$i]->video = $res[$i]->video;
                } else {
                    $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                }
            }

            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $res[$i]->profile_pic = "";
                } else {
                    // $res[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $res[$i]->profile_pic = "";
                $res[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
            if (!empty($is_likes)) {
                $res[$i]->is_likes = "false";
            } else {
                $res[$i]->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
            if (!empty($bookmark)) {
                $res[$i]->bookmark = "false";
            } else {
                $res[$i]->bookmark = "true";
            }

            $posts_report = $this->front_model->posts_reportCheck($user_id, $res[$i]->post_id);
            if (!empty($posts_report)) {
                $res[$i]->posts_report = "false";
            } else {
                $res[$i]->posts_report = "true";
            }


            $posts_user_id = $res[$i]->user_id;
            $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
            if (!empty($profile_block)) {
                $res[$i]->profile_block = "false";
            } else {
                $res[$i]->profile_block = "true";
            }
        }

        if (!empty($res)) {
            $result['response_code'] = "1";
            $result['message'] = "Post Found";
            $result['rescent_post'] = $res;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Post Not Found";
            $result['rescent_post'] = $res;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function trending_post()
    {
        $result = array();
        header('Content-Type: application/json');

        $this->db->select('follow.from_user, follow.to_user , posts.*, COUNT(likes.like_id) as like_cou')
            ->from('posts')
            ->order_by('like_cou', 'desc')
            ->join('likes', 'likes.post_id = posts.post_id')
            ->join('follow', 'follow.to_user = likes.user_id')
            ->group_by('post_id');
        $res = $this->db->get()->result();

        for ($i = 0; $i < count($res); $i++) {
            if (!empty($res[$i]->image)) {

                $url = explode(":", $res[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $res[$i]->image;

                    array_push($image_url, $image_url_a);

                    $res[$i]->all_image = $image_url;
                } else {

                    $images = explode("::::", $res[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $res[$i]->all_image = $imgsa;
                }
            } else {
                $res[$i]->all_image = array();
            }
            if ($res[$i]->video == "") {
                $res[$i]->video = "";
            } else {

                $url = explode(":", $res[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $res[$i]->video = $res[$i]->video;
                } else {
                    $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                }
            }
            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $res[$i]->profile_pic = "";
                } else {
                    // $res[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $res[$i]->profile_pic = "";
                $res[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($res[$i]->from_user, $res[$i]->post_id);
            if (!empty($is_likes)) {
                $res[$i]->is_likes = "false";
            } else {
                $res[$i]->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($res[$i]->from_user, $res[$i]->post_id);
            if (!empty($bookmark)) {
                $res[$i]->bookmark = "false";
            } else {
                $res[$i]->bookmark = "true";
            }
        }
        if (!empty($res)) {
            $result['response_code'] = "1";
            $result['message'] = "Trending Post Found";
            $result['post'] = $res;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Trending Post Not Found";
            $result['post'] = $res;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function get_post_details()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');
        $post_id = $this->input->post('post_id');

        $result['response_code'] = "1";
        $result['message'] = "Post Found";

        $post = $this->db->get_where('posts', array('post_id' => $post_id), 1)->row();

        if (!empty($post)) {

            if (!empty($post->image)) {

                $url = explode(":", $post->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $post->image;

                    array_push($image_url, $image_url_a);

                    $post->image = $image_url;

                    $post->all_image = $image_url;
                } else {
                    $images = explode("::::", $post->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $post->image = $imgsa;

                    $post->all_image = $imgsa;
                }
            } else {
                $post->all_image = array();
            }
            if ($post->video == "") {
                $post->video = "";
            } else {

                $url = explode(":", $post->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $post->video = $post->video;
                } else {
                    $post->video = base_url() . 'assets/images/post/' . $post->video;
                }
            }

            $user = $this->db->get_where('users', array('id' => $post->user_id), 1)->row();
            if (!empty($user)) {
                $post->username = $user->username;
                if ($user->profile_pic == "") {
                    $post->profile_pic = "";
                } else {
                    // $post->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $post->profile_pic = $user->profile_pic;
                    } else {

                        $post->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $post->profile_pic = "";
                $post->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $post->post_id))->num_rows();
            $post->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $post->post_id))->num_rows();
            $post->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($user_id, $post->post_id);
            if (!empty($is_likes)) {
                $post->is_likes = "false";
            } else {
                $post->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($user_id, $post->post_id);
            if (!empty($bookmark)) {
                $post->bookmark = "false";
            } else {
                $post->bookmark = "true";
            }

            $posts_report = $this->front_model->posts_reportCheck($user_id, $post->post_id);
            if (!empty($posts_report)) {
                $post->posts_report = "false";
            } else {
                $post->posts_report = "true";
            }

            $posts_user_id = $post->user_id;
            $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
            if (!empty($profile_block)) {
                $post->profile_block = "false";
            } else {
                $post->profile_block = "true";
            }

            $query = $this->db->query("SELECT A.comment_id,A.post_id,A.user_id,A.text,A.date, B.username,B.profile_pic FROM comments A, users B WHERE A.post_id = '$post_id' AND A.user_id = B.id ORDER BY A.comment_id DESC LIMIT 1");
            $comments = $query->row();

            if (!empty($comments)) {

                if (!empty($comments->profile_pic)) {
                    $url = explode(":", $comments->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $comments->profile_pic = $comments->profile_pic;
                    } elseif (!empty($comments->profile_pic)) {
                        $comments->profile_pic = base_url() . "assets/images/user/" . $comments->profile_pic;
                    } else {
                        $comments->profile_pic = $comments->profile_pic;
                    }
                } else {
                    $comments->profile_pic = "";
                }
            }

            $result['post'] = $post;
            if (!empty($comments)) {
                $result['comment'] = $comments;
            }
            $result["status"] = "success";

            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Post Not Found";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function add_story()
    {
        header('Content-Type: application/json');
        if ($_POST['user_id'] != "") {

            $res_image = "";
            $res_video = "";

            if (isset($_FILES['url']['name']) && $_FILES['url']['name'] != "") {
                $image_exts = array("tif", "jpg", "jpeg", "gif", "png", "mp4", "mkv", "MKV");

                $configVideo['upload_path'] = './assets/images/story'; # check path is correct
                $configVideo['max_size'] = '102400';
                $configVideo['allowed_types'] = $image_exts; # add video extenstion on here
                $configVideo['overwrite'] = FALSE;
                $configVideo['remove_spaces'] = TRUE;
                $configVideo['file_name'] = uniqid();

                $this->load->library('upload', $configVideo);
                $this->upload->initialize($configVideo);

                if (!$this->upload->do_upload('url')) # form input field attribute
                {
                    $temp["response_code"] = "0";
                    $temp["message"] = "Image Type Error";
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                    return;
                } else {
                    # Upload Successfull
                    $upload_data = $this->upload->data();
                    $res_image = $upload_data['file_name'];
                }
            }

            $data['user_id'] = $this->input->post('user_id');
            $data['url'] = $res_image;
            $data['type'] = $this->input->post('type');
            // $data['create_date'] = time();

            $user_id_check = $this->front_model->user_id_check($data['user_id']);
            if (!empty($data['url'])) {

                if ($this->db->insert('story', $data)) {
                    $temp["response_code"] = "1";
                    $temp["message"] = "success";
                    $temp["status"] = "success";
                    echo json_encode($temp);
                } else {

                    $temp["response_code"] = "0";
                    $temp["message"] = "Database Error";
                    $temp["status"] = "failure";
                    echo json_encode($temp);
                }
                // }
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "Database Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function get_story_by_user()
    {
        $result = array();
        header('Content-Type: application/json');

        $user_id = $this->input->post('user_id');

        $query = $this->db->query("SELECT * FROM follow WHERE from_user = '$user_id'");
        $fcount = $query->num_rows();

        if ($fcount > 0) {

            $query = $this->db->query("SELECT A.from_user AS from_user, A.to_user AS to_user, B.* FROM follow A, story B WHERE  A.to_user = B.user_id AND A.from_user = '$user_id' AND is_delete = 0 GROUP BY B.user_id ORDER BY B.story_id DESC");
            $all_story = $query->result();

            $query = $this->db->query("SELECT * FROM story WHERE user_id = '$user_id' AND is_delete = 0 GROUP BY user_id ORDER BY story_id DESC");
            $twenty_post = $query->result();

            $inputArray = array_merge($twenty_post, $all_story);

            $res = array();

            foreach ($inputArray as $inputArrayItem) {

                foreach ($res as $outputArrayItem) {

                    if ($inputArrayItem->story_id == $outputArrayItem->story_id) {
                        continue 2;
                    }
                }
                $res[] = $inputArrayItem;
            }
        } else {
            $query = $this->db->query("SELECT * FROM story WHERE user_id = '$user_id' AND is_delete = 0 GROUP BY user_id ORDER BY story_id DESC");
            $res = $query->result();
        }

        $story_image_list = array();

        foreach ($res as $key => $list) {

            $story_list['story_id'] = $list->story_id;
            $story_list['user_id'] = $list->user_id;
            $story_list['url'] = $list->url;
            $story_list['type'] = $list->type;
            $story_list['create_date'] = $list->create_date;

            $user = $this->db->get_where('users', array('id' => $list->user_id), 1)->row();
            if (!empty($user)) {
                $story_list['username'] = $user->username;

                $url = explode(":", $user->profile_pic);
                if ($url[0] == "https" || $url[0] == "http") {
                    $story_list['profile_pic'] = $user->profile_pic;
                } else {

                    $story_list['profile_pic'] = base_url() . 'assets/images/user/' . $user->profile_pic;
                }
            } else {
                $story_list['profile_pic'] = "";
                $story_list['username'] = "";
            }

            $story_arr = array();

            $story = $this->db->get_where('story', array('user_id' => $list->user_id))->result();
            foreach ($story as $key => $product) {

                $querycount = $this->db->query("SELECT story.url,story.type FROM story WHERE story_id = '$product->story_id'");
                $story_row = $querycount->row();

                if (!empty($story_row->url)) {

                    $story_row->url = base_url() . 'assets/images/story/' . $story_row->url;
                    $story_row->type = $story_row->type;
                }
                if (!empty($story_row->url)) {
                    array_push($story_arr, $story_row);
                }
            }

            $story_list['story_image'] = $story_arr;

            array_push($story_image_list, $story_list);
        }

        if (!empty($story_image_list)) {
            $result['status'] = "1";
            $result['msg'] = "Story Found";
            $result['post'] = $story_image_list;
            echo json_encode($result);
        } else {
            $result["status"] = "0";
            $result["msg"] = "Story Not Found";
            $result['post'] = $story_image_list;
            echo json_encode($result);
        }
    }

    public function delete_story()
    {
        $result = array();
        header('Content-Type: application/json');

        if ($this->front_model->delete_story()) {

            $result["response_code"] = "1";
            $result["message"] = "Successfully Delete";
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Database error";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function search_post()
    {
        $result = array();
        $res = array();
        header('Content-Type: application/json');

        if (!isset($_POST['user_id'])) {
            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "fail";
            echo json_encode($result);
            return;
        }

        $text = $this->input->post('text');

        if (empty($text)) {
            $result["response_code"] = "0";
            $result["message"] = "Post Not Found";
            $result['post'] = $res;
            $result["status"] = "failure";
            echo json_encode($result);
            return;
        }

        $user_id = $this->input->post('user_id');

        $query = $this->db->query("SELECT * FROM posts WHERE image != '' AND text LIKE '%$text%' ORDER BY post_id DESC");
        $res = $query->result();

        for ($i = 0; $i < count($res); $i++) {
            if (!empty($res[$i]->image)) {

                $url = explode(":", $res[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $res[$i]->image;

                    array_push($image_url, $image_url_a);

                    $res[$i]->image = $image_url;

                    $res[$i]->all_image = $image_url;
                } else {
                    $images = explode("::::", $res[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $res[$i]->image = $imgsa;

                    $res[$i]->all_image = $imgsa;
                }
            } else {
                $res[$i]->image = [];
                $res[$i]->all_image = [];
            }
            if ($res[$i]->video == "") {
                $res[$i]->video = "";
            } else {

                $url = explode(":", $res[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $res[$i]->video = $res[$i]->video;
                } else {
                    $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                }
            }

            $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
            if (!empty($user)) {
                $res[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $res[$i]->profile_pic = "";
                } else {
                    // $res[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $res[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $res[$i]->profile_pic = "";
                $res[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
            $res[$i]->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
            if (!empty($is_likes)) {
                $res[$i]->is_likes = "false";
            } else {
                $res[$i]->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
            if (!empty($bookmark)) {
                $res[$i]->bookmark = "false";
            } else {
                $res[$i]->bookmark = "true";
            }

            $posts_report = $this->front_model->posts_reportCheck($user_id, $res[$i]->post_id);
            if (!empty($posts_report)) {
                $res[$i]->posts_report = "false";
            } else {
                $res[$i]->posts_report = "true";
            }


            $posts_user_id = $res[$i]->user_id;
            $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
            if (!empty($profile_block)) {
                $res[$i]->profile_block = "false";
            } else {
                $res[$i]->profile_block = "true";
            }
        }

        if (!empty($res)) {
            $result['response_code'] = "1";
            $result['message'] = "Post Found";
            $result['post'] = $res;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Post Not Found";
            $result['post'] = $res;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function search_users()
    {
        $result = array();
        $users = array();
        header('Content-Type: application/json');

        $text = $this->input->post('text');

        if (empty($text)) {
            $result["response_code"] = "0";
            $result["message"] = "Users Not Found";
            $result['users'] = $users;
            $result["status"] = "failure";
            echo json_encode($result);
            return;
        }

        $sql = $this->db->query("SELECT * FROM users WHERE username LIKE '%$text%' ORDER BY id DESC");

        $users = $sql->result();

        for ($i = 0; $i < count($users); $i++) {

            if (!empty($users[$i]->profile_pic)) {

                $url = explode(":", $users[$i]->profile_pic);
                if ($url[0] == "https" || $url[0] == "http") {
                    $users[$i]->profile_pic = $users[$i]->profile_pic;
                } else {

                    $users[$i]->profile_pic = base_url() . 'assets/images/user/' . $users[$i]->profile_pic;
                }
            } else {
                $users[$i]->profile_pic = "";
            }
        }

        if (!empty($users)) {
            $result['response_code'] = "1";
            $result['message'] = "Users Found";
            $result['users'] = $users;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Users Not Found";
            $result['users'] = $users;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function users_filter()
    {
        $result = array();
        header('Content-Type: application/json');

        $interests_id = $this->input->post('interests_id');
        $name = $this->input->post('name');
        $country = $this->input->post('country');
        $age = $this->input->post('age');
        $gender = $this->input->post('gender');
        $state = $this->input->post('state');

        $sql_query = "";

        if (!empty($name) || !empty($country) || !empty($age) || !empty($gender) || !empty($state)) {

            if (!empty($age) && empty($name) && empty($country) && empty($gender) && empty($state)) {
                $age_a = explode(",", $age);
                $start_age = $age_a[0];
                $end_age = $age_a[1];

                $sql_query .= "age >= $start_age AND age <= $end_age ";
            }
            // else {
            //    // $age_a = explode(",", $age);
            //    // $start_age = 0;
            //    // $end_age = 0;

            //    $sql_query .= "age >= $start_age AND age <= $end_age AND ";
            // }

            // if (!empty($age)) {
            //    if (!empty($interests_id)) {
            //       $sql_query .= "interests_id like '%$interests_id%'";
            //    }
            // } else {
            //    if (!empty($interests_id)) {
            //       $sql_query .= "interests_id like '%$interests_id%'";
            //    }
            // }

            if (!empty($age)) {
                if (!empty($name)) {
                    $sql_query .= "username like '%$name%'";
                }
            } else {
                if (!empty($name)) {
                    $sql_query .= " username like '%$name%'";
                }
            }

            if (!empty($name)) {
                if (!empty($country)) {
                    $sql_query .= " or country like '%$country%'";
                }
            } else {
                if (!empty($country)) {
                    $sql_query .= " country like '%$country%'";
                }
            }

            if (!empty($country) || !empty($name)) {
                if (!empty($gender)) {
                    $sql_query .= " or gender like '%$gender%'";
                }
            } else {
                if (!empty($gender)) {
                    $sql_query .= " gender like '%$gender%'";
                }
            }

            if (!empty($country) || !empty($name) || !empty($gender)) {
                if (!empty($state)) {
                    $sql_query .= " or state like '%$state%'";
                }
            } else {
                if (!empty($state)) {
                    $sql_query .= " state like '%$state%'";
                }
            }

            $sql = $this->db->query("SELECT * FROM users WHERE " . $sql_query . " ORDER BY id DESC ");
            $users = $sql->result();
        } else {
            $sql = $this->db->query("SELECT * FROM users ORDER BY id DESC ");
            $users = $sql->result();
        }

        for ($i = 0; $i < count($users); $i++) {

            if (!empty($users[$i]->profile_pic)) {

                $url = explode(":", $users[$i]->profile_pic);
                if ($url[0] == "https" || $url[0] == "http") {
                    $users[$i]->profile_pic = $users[$i]->profile_pic;
                } else {

                    $users[$i]->profile_pic = base_url() . 'assets/images/user/' . $users[$i]->profile_pic;
                }
            } else {
                $users[$i]->profile_pic = "";
            }
        }

        if (!empty($users)) {
            $result['response_code'] = "1";
            $result['message'] = "Users Found";
            $result['users'] = $users;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Users Not Found";
            $result['users'] = $users;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function get_all_users()
    {
        $result = array();
        header('Content-Type: application/json');

        $sql = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");
        $users = $sql->result();

        for ($i = 0; $i < count($users); $i++) {

            if (!empty($users[$i]->profile_pic)) {

                $url = explode(":", $users[$i]->profile_pic);
                if ($url[0] == "https" || $url[0] == "http") {
                    $users[$i]->profile_pic = $users[$i]->profile_pic;
                } else {

                    $users[$i]->profile_pic = base_url() . 'assets/images/user/' . $users[$i]->profile_pic;
                }
            } else {
                $users[$i]->profile_pic = "";
            }
        }

        if (!empty($users)) {
            $result['response_code'] = "1";
            $result['message'] = "Users Found";
            $result['users'] = $users;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Users Not Found";
            $result['users'] = $users;
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function bookmark_post()
    {
        header('Content-Type: application/json');
        if (isset($_POST['post_id']) && isset($_POST['user_id'])) {

            $like = array();
            $like['post_id'] = $this->input->post('post_id');
            $like['user_id'] = $this->input->post('user_id');
            $like['date'] = round(microtime(true) * 1000);

            $bookmarkCheck = $this->front_model->bookmarkCheck($like['user_id'], $like['post_id']);

            if (!$bookmarkCheck) {
                $temp["response_code"] = "0";
                $temp["message"] = "Already Bookmark Post";
                $temp["status"] = "fail";
                echo json_encode($temp);

                return;
            }

            if ($this->db->insert('bookmark', $like)) {

                $temp["response_code"] = "1";
                $temp["message"] = "Bookmark Post";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {

                $temp["response_code"] = "0";
                $temp["message"] = "Databse Error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $temp["response_code"] = "0";
            $temp["message"] = "Missing Fields";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function delete_bookmark_post()
    {
        header('Content-Type: application/json');

        $post_id = $this->input->post('post_id');
        $user_id = $this->input->post('user_id');

        if ($this->front_model->delete_bookmark($post_id, $user_id)) {
            $temp["response_code"] = "1";
            $temp["message"] = "Successfully Delete";
            $temp["status"] = "success";
            echo json_encode($temp);
        } else {
            $temp["response_code"] = "0";
            $temp["message"] = "Database error";
            $temp["status"] = "failure";
            echo json_encode($temp);
        }
    }

    public function get_user_bookmark_post()
    {
        $result = array();
        header('Content-Type: application/json');

        if (!isset($_POST['user_id'])) {

            $temp["response_code"] = "0";
            $temp["message"] = "Enter Data";
            $temp["status"] = "failure";
            echo json_encode($temp);
            return;
        }

        $user_id = $this->input->post('user_id');

        $query = $this->db->query("SELECT * FROM bookmark WHERE user_id = '$user_id'");

        $query = $this->db->query("SELECT A.post_id, B.text,B.image,B.video,B.location,B.user_id as post_user_id,B.create_date FROM bookmark A, posts B WHERE A.post_id = B.post_id AND A.user_id = '$user_id' ORDER BY A.bookmark_id DESC");

        $post = $query->result();

        for ($i = 0; $i < count($post); $i++) {
            if (!empty($post[$i]->image)) {

                $url = explode(":", $post[$i]->image);
                if ($url[0] == "https" || $url[0] == "http") {
                    $image_url = array();

                    $image_url_a = $post[$i]->image;

                    array_push($image_url, $image_url_a);

                    $post[$i]->image = $image_url;

                    $post[$i]->all_image = $image_url;
                } else {
                    $images = explode("::::", $post[$i]->image);
                    $imgs = array();
                    $imgsa = array();
                    foreach ($images as $key => $image) {
                        $imgs = base_url('assets/images/post/') . $image;

                        array_push($imgsa, $imgs);
                    }
                    $post[$i]->image = $imgsa;

                    $post[$i]->all_image = $imgsa;
                }
            } else {
                $post[$i]->image = [];
                $post[$i]->all_image = [];
            }
            if ($post[$i]->video == "") {
                $post[$i]->video = "";
            } else {

                $url = explode(":", $post[$i]->video);
                if ($url[0] == "https" || $url[0] == "http") {

                    $post[$i]->video = $post[$i]->video;
                } else {
                    $post[$i]->video = base_url() . 'assets/images/post/' . $post[$i]->video;
                }
            }

            $user = $this->db->get_where('users', array('id' => $post[$i]->post_user_id), 1)->row();
            if (!empty($user)) {
                $post[$i]->username = $user->username;
                if ($user->profile_pic == "") {
                    $post[$i]->profile_pic = "";
                } else {
                    // $post[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                    $url = explode(":", $user->profile_pic);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $post[$i]->profile_pic = $user->profile_pic;
                    } else {

                        $post[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                    }
                }
            } else {
                $post[$i]->profile_pic = "";
                $post[$i]->username = "";
            }

            $total_likes = $this->db->get_where('likes', array('post_id' => $post[$i]->post_id))->num_rows();
            $post[$i]->total_likes = $total_likes;

            $total_comments = $this->db->get_where('comments', array('post_id' => $post[$i]->post_id))->num_rows();
            $post[$i]->total_comments = $total_comments;

            $is_likes = $this->front_model->likeCheck($user_id, $post[$i]->post_id);
            if (!empty($is_likes)) {
                $post[$i]->is_likes = "false";
            } else {
                $post[$i]->is_likes = "true";
            }

            $bookmark = $this->front_model->bookmarkCheck($user_id, $post[$i]->post_id);
            if (!empty($bookmark)) {
                $post[$i]->bookmark = "false";
            } else {
                $post[$i]->bookmark = "true";
            }

            $posts_report = $this->front_model->posts_reportCheck($user_id, $post[$i]->post_id);
            if (!empty($posts_report)) {
                $post[$i]->posts_report = "false";
            } else {
                $post[$i]->posts_report = "true";
            }

            $posts_user_id = $post[$i]->post_user_id;
            $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
            if (!empty($profile_block)) {
                $post[$i]->profile_block = "false";
            } else {
                $post[$i]->profile_block = "true";
            }
        }

        if (empty($post)) {
            $result['response_code'] = "0";
            $result['message'] = "Post Not Found";
            $result['post'] = $post;
            $result["status"] = "failure";

            echo json_encode($result);
        } else {
            $result['response_code'] = "1";
            $result['message'] = "Post Found";
            $result['post'] = $post;
            $result["status"] = "success";

            echo json_encode($result);
        }
    }

    public function get_all_interests()
    {
        header('Content-Type: application/json');
        $interests =  $this->db->get('interests')->result();
        if (!$interests) {
            $result["response_code"] = "0";
            $result["message"] = "Interests Not Found";
            $result["status"] = "fail";
            echo json_encode($result);
            return;
        }

        $result["response_code"] = "1";
        $result["message"] = "Interests Found";
        $result['interests'] = $interests;
        $result["status"] = "success";
        echo json_encode($result);
    }

    public function user_notification_listing()
    {
        header('Content-Type: application/json');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', "User ID", 'required');

        if ($this->form_validation->run() == TRUE) {

            $user_id = $this->input->post('user_id');

            $notifications = $this->db->order_by("not_id", "desc")->get_where("user_notification", array("to_user" => $user_id))->result();

            for ($i = 0; $i < count($notifications); $i++) {
                $user = $this->db->get_where('users', array('id' => $notifications[$i]->from_user), 1)->row();
                if (!empty($user)) {
                    $notifications[$i]->username = $user->username;
                    if ($user->profile_pic == "") {
                        $notifications[$i]->profile_pic = "";
                    } else {
                        // $notifications[$i]->profile_pic = base_url() . 'uploads/profile_pics/' . $user->profile_pic;

                        $url = explode(":", $user->profile_pic);
                        if ($url[0] == "https" || $url[0] == "http") {
                            $notifications[$i]->profile_pic = $user->profile_pic;
                        } else {

                            $notifications[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                        }
                    }
                } else {
                    $notifications[$i]->profile_pic = "";
                    $notifications[$i]->username = "";
                }
            }

            if ($notifications) {
                $temp["response_code"] = "1";

                $temp["message"] = "Found";

                $temp["status"] = "success";

                $temp['data'] = $notifications;

                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";

                $temp["message"] = "Not Found";

                $temp["status"] = "fail";

                $temp['data'] = [];

                echo json_encode($temp);
            }
        } else {
            $temp["response_code"] = "0";

            $temp["message"] = "Missing Field";

            $temp["status"] = "fail";

            echo json_encode($temp);
        }
    }

    public function add_comment_report()
    {
        header('Content-Type: application/json');
        if (isset($_POST['post_id']) && isset($_POST['user_id'])) {

            $data = array();
            $data['user_id'] = $this->input->post('user_id');
            $data['post_id'] = $this->input->post('post_id');
            $data['comment_id'] = $this->input->post('comment_id');
            $data['report_text'] = $this->input->post('report_text');
            $data['create_date'] = round(microtime(true) * 1000);

            if ($this->db->insert('comment_report', $data)) {

                $result["response_code"] = "1";
                $result["message"] = "Comment Report Add";
                $result["status"] = "success";
                echo json_encode($result);
            } else {

                $result["response_code"] = "0";
                $result["message"] = "Databse Error";
                $result["status"] = "failure";
                echo json_encode($result);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function all_post_by_user_pagination()
    {

        $result = array();
        header('Content-Type: application/json');

        $this->load->library('pagination');

        $limit = $_REQUEST['per_page'];
        $page_a = $_REQUEST['page'];
        $user_id = $_REQUEST['user_id'];

        $page = $page_a - 1;

        $query = $this->db->query("SELECT * FROM follow WHERE from_user = '$user_id'");
        $fcount = $query->num_rows();

        if ($fcount > 0) {

            $this->db->select('posts.*,follow.from_user as from_user, follow.to_user AS to_user');
            $this->db->from('posts');
            $this->db->join('follow', 'follow.to_user = posts.user_id');
            $this->db->where('follow.from_user', $user_id);
            $this->db->order_by('posts.post_id', 'desc');
            $this->db->limit($limit, $page);
            $query = $this->db->get();
            $all_post = $query->result();

            //  $query = $this->db->query("SELECT p.*, p.user_id as to_user FROM posts p ORDER BY post_id DESC LIMIT 20, 20");
            //  $twenty_post = $query->result();

            $this->db->select('posts.*, posts.user_id as to_user');
            $this->db->from('posts');
            $this->db->order_by("post_id", "desc");
            $this->db->limit($limit, $page);
            $query = $this->db->get();
            $twenty_post = $query->result();

            //  $inputArray = array_merge($all_post, $twenty_post);

            $inputArray = array_merge($twenty_post, $all_post);

            $res = array();

            foreach ($inputArray as $inputArrayItem) {

                foreach ($res as $outputArrayItem) {

                    if ($inputArrayItem->post_id == $outputArrayItem->post_id) {
                        continue 2;
                    }
                }
                $res[] = $inputArrayItem;
            }
        } else {
            $this->load->library('pagination');

            $this->db->select('posts.*');
            $this->db->from('posts');
            $this->db->order_by("post_id", "desc");
            $this->db->limit($limit, $page);
            $query = $this->db->get();
            $res = $query->result();
        }

        if (!empty($res)) {

            for ($i = 0; $i < count($res); $i++) {
                if (!empty($res[$i]->image)) {

                    $url = explode(":", $res[$i]->image);
                    if ($url[0] == "https" || $url[0] == "http") {
                        $image_url = array();

                        $image_url_a = $res[$i]->image;

                        array_push($image_url, $image_url_a);

                        $res[$i]->image = $image_url;

                        $res[$i]->all_image = $image_url;
                    } else {
                        $images = explode("::::", $res[$i]->image);
                        $imgs = array();
                        $imgsa = array();
                        foreach ($images as $key => $image) {
                            $imgs = base_url('assets/images/post/') . $image;

                            array_push($imgsa, $imgs);
                        }
                        $res[$i]->image = $imgsa;

                        $res[$i]->all_image = $imgsa;
                    }
                } else {
                    $res[$i]->image = [];
                    $res[$i]->all_image = array();
                }
                if ($res[$i]->video == "") {
                    $res[$i]->video = "";
                } else {

                    $url = explode(":", $res[$i]->video);
                    if ($url[0] == "https" || $url[0] == "http") {

                        $res[$i]->video = $res[$i]->video;
                    } else {
                        $res[$i]->video = base_url() . 'assets/images/post/' . $res[$i]->video;
                    }
                }

                $user = $this->db->get_where('users', array('id' => $res[$i]->user_id), 1)->row();
                if (!empty($user)) {
                    $res[$i]->username = $user->username;
                    if ($user->profile_pic == "") {
                        $res[$i]->profile_pic = "";
                    } else {

                        $url = explode(":", $user->profile_pic);
                        if ($url[0] == "https" || $url[0] == "http") {
                            $res[$i]->profile_pic = $user->profile_pic;
                        } else {

                            $res[$i]->profile_pic = base_url() . 'assets/images/user/' . $user->profile_pic;
                        }
                    }
                } else {
                    $res[$i]->profile_pic = "";
                    $res[$i]->username = "";
                }

                $total_likes = $this->db->get_where('likes', array('post_id' => $res[$i]->post_id))->num_rows();
                $res[$i]->total_likes = $total_likes;

                $total_comments = $this->db->get_where('comments', array('post_id' => $res[$i]->post_id))->num_rows();
                $res[$i]->total_comments = $total_comments;

                if ($fcount != '0') {
                    $res[$i]->from_user = $user_id;
                    $res[$i]->to_user = $res[$i]->to_user;
                } else {
                    $res[$i]->from_user = "0";
                    $res[$i]->to_user = "0";
                }

                if ($fcount != '0') {
                    $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
                    if (!empty($is_likes)) {
                        $res[$i]->is_likes = "false";
                    } else {
                        $res[$i]->is_likes = "true";
                    }
                } else {
                    $is_likes = $this->front_model->likeCheck($user_id, $res[$i]->post_id);
                    if (!empty($is_likes)) {
                        $res[$i]->is_likes = "false";
                    } else {
                        $res[$i]->is_likes = "true";
                    }
                }

                if ($fcount != '0') {
                    $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
                    if (!empty($bookmark)) {
                        $res[$i]->bookmark = "false";
                    } else {
                        $res[$i]->bookmark = "true";
                    }
                } else {
                    $bookmark = $this->front_model->bookmarkCheck($user_id, $res[$i]->post_id);
                    if (!empty($bookmark)) {
                        $res[$i]->bookmark = "false";
                    } else {
                        $res[$i]->bookmark = "true";
                    }
                }

                if ($fcount != '0') {
                    $posts_report = $this->front_model->posts_reportCheck($user_id, $res[$i]->post_id);
                    if (!empty($posts_report)) {
                        $res[$i]->posts_report = "false";
                    } else {
                        $res[$i]->posts_report = "true";
                    }
                } else {
                    $posts_report = $this->front_model->posts_reportCheck($user_id, $res[$i]->post_id);
                    if (!empty($posts_report)) {
                        $res[$i]->posts_report = "false";
                    } else {
                        $res[$i]->posts_report = "true";
                    }
                }

                $posts_user_id = $res[$i]->user_id;

                if ($fcount != '0') {
                    $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
                    if (!empty($profile_block)) {
                        $res[$i]->profile_block = "false";
                    } else {
                        $res[$i]->profile_block = "true";
                    }
                } else {
                    $profile_block = $this->front_model->profile_block_Check($user_id, $posts_user_id);
                    if (!empty($profile_block)) {
                        $res[$i]->profile_block = "false";
                    } else {
                        $res[$i]->profile_block = "true";
                    }
                }

                $post_id = $res[$i]->post_id;
                $query = $this->db->query("SELECT A.comment_id,A.post_id,A.user_id,A.text,A.date, B.fullname, B.username,B.profile_pic FROM comments A, users B WHERE A.post_id = '$post_id' AND A.user_id = B.id ORDER BY A.comment_id DESC LIMIT 1");
                $comments = $query->row();

                if (!empty($comments)) {
                    if (!empty($comments->profile_pic)) {
                        $url = explode(":", $comments->profile_pic);
                        if ($url[0] == "https" || $url[0] == "http") {
                            $comments->profile_pic = $comments->profile_pic;
                        } elseif (!empty($comments->profile_pic)) {
                            $comments->profile_pic = base_url() . "assets/images/user/" . $comments->profile_pic;
                        } else {
                            $comments->profile_pic = $comments->profile_pic;
                        }
                    } else {
                        $comments->profile_pic = "";
                    }
                }
                if (!empty($comments)) {
                    $res[$i]->comment = $comments;
                }
            }

            $result['response_code'] = "1";
            $result['message'] = "Post Found";
            $result['post'] = $res;
            $result["status"] = "success";
            echo json_encode($result);
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Post Not Found";
            $result['post'] = [];
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function profile_block()
    {
        $result = array();
        header('Content-Type: application/json');
        if (isset($_POST['blockedByUserId']) && isset($_POST['blockedUserId'])) {

            $data = array();
            $data['blockedByUserId'] = $this->input->post('blockedByUserId');
            $data['blockedUserId'] = $this->input->post('blockedUserId');
            $data['created_date'] = round(microtime(true) * 1000);

            $profile_blockCheck = $this->front_model->profile_blockCheck($data['blockedByUserId'], $data['blockedUserId']);

            if (!$profile_blockCheck) {
                $result["response_code"] = "0";
                $result["message"] = "Already Profile Block";
                $result["status"] = "fail";
                echo json_encode($result);

                return;
            }

            if ($this->db->insert('profile_blocklist', $data)) {

                $this->db->where('from_user', $data['blockedByUserId']);
                $this->db->where('to_user', $data['blockedUserId']);
                $this->db->delete('follow');

                $result["response_code"] = "1";
                $result["message"] = "Profile Block";
                $result["status"] = "success";
                echo json_encode($result);
            } else {

                $result["response_code"] = "0";
                $result["message"] = "Databse Error";
                $result["status"] = "failure";
                echo json_encode($result);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function profile_unblock()
    {
        $result = array();
        header('Content-Type: application/json');
        if (isset($_POST['blockedByUserId']) && isset($_POST['blockedUserId'])) {

            $blockedByUserId = $this->input->post('blockedByUserId');
            $blockedUserId = $this->input->post('blockedUserId');

            if ($this->front_model->unblock_profile($blockedByUserId, $blockedUserId)) {
                $temp["response_code"] = "1";
                $temp["message"] = "Successfully Unblock";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "Database error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function posts_report()
    {
        $result = array();
        header('Content-Type: application/json');
        if (isset($_POST['blockedByUserId']) && isset($_POST['blockedPostsId'])) {

            $data = array();
            $data['blockedByUserId'] = $this->input->post('blockedByUserId');
            $data['blockedPostsId'] = $this->input->post('blockedPostsId');
            $data['status'] = $this->input->post('status');

            $report_text = $this->input->post('report_text');
            if (!empty($report_text)) {
                $data['report_text'] = $this->input->post('report_text');
            }

            $data['created_date'] = round(microtime(true) * 1000);

            $posts_blockCheck = $this->front_model->posts_blockCheck($data['blockedByUserId'], $data['blockedPostsId']);

            if (!$posts_blockCheck) {
                $result["response_code"] = "0";
                $result["message"] = "Already Posts Report";
                $result["status"] = "fail";
                echo json_encode($result);

                return;
            }

            if ($this->db->insert('posts_report', $data)) {

                $result["response_code"] = "1";
                $result["message"] = "Posts Report";
                $result["status"] = "success";
                echo json_encode($result);
            } else {

                $result["response_code"] = "0";
                $result["message"] = "Databse Error";
                $result["status"] = "failure";
                echo json_encode($result);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function posts_unblock()
    {
        $result = array();
        header('Content-Type: application/json');
        if (isset($_POST['blockedByUserId']) && isset($_POST['blockedPostsId'])) {

            $blockedByUserId = $this->input->post('blockedByUserId');
            $blockedPostsId = $this->input->post('blockedPostsId');

            if ($this->front_model->unblock_posts($blockedByUserId, $blockedPostsId)) {
                $temp["response_code"] = "1";
                $temp["message"] = "Successfully Unblock";
                $temp["status"] = "success";
                echo json_encode($temp);
            } else {
                $temp["response_code"] = "0";
                $temp["message"] = "Database error";
                $temp["status"] = "failure";
                echo json_encode($temp);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }

    public function user_report()
    {
        $result = array();
        header('Content-Type: application/json');
        if (isset($_POST['reportByUserId']) && isset($_POST['reportedUserId'])) {

            $data = array();
            $data['reportByUserId'] = $this->input->post('reportByUserId');
            $data['reportedUserId'] = $this->input->post('reportedUserId');
            $data['status'] = $this->input->post('status');

            $report_text = $this->input->post('report_text');
            if (!empty($report_text)) {
                $data['report_text'] = $this->input->post('report_text');
            }

            $data['created_date'] = round(microtime(true) * 1000);

            $posts_blockCheck = $this->front_model->users_reportCheck($data['reportByUserId'], $data['reportedUserId']);

            if (!$posts_blockCheck) {
                $result["response_code"] = "0";
                $result["message"] = "Already User Report";
                $result["status"] = "fail";
                echo json_encode($result);
                return;
            }

            if ($this->db->insert('users_report', $data)) {

                $result["response_code"] = "1";
                $result["message"] = "User Report";
                $result["status"] = "success";
                echo json_encode($result);
            } else {

                $result["response_code"] = "0";
                $result["message"] = "Databse Error";
                $result["status"] = "failure";
                echo json_encode($result);
            }
        } else {

            $result["response_code"] = "0";
            $result["message"] = "Missing Fields";
            $result["status"] = "failure";
            echo json_encode($result);
        }
    }
    public function delete_user()
    {
        $id = $this->input->post('user_id');

        if ($id == "") {
            $result["response_code"] = "0";
            $result["message"] = "Please Enter user id..!";
            $result["status"] = "fail";
            echo json_encode($result);
            exit;
        }

        $userqry = $this->db->get_where("users", array('id' => $id))->row();
        if (empty($userqry)) {
            $result["response_code"] = "0";
            $result["message"] = "User id not found enter another user id..!";
            $result["status"] = "fail";
            echo json_encode($result);
            exit;
        }
        $query = $this->db->get_where("posts", array('user_id' => $id));
        $postbyuser = $query->result();
        // print_r($postbyuser) ;
        // exit;
        if (!empty($postbyuser)) {
            foreach ($postbyuser as $key => $postid) {

                $this->db->where('post_id', $postid->post_id);
                if ($this->db->delete('likes')) {
                    //$result["user_id_like"] = "deleted";
                }
                $this->db->where('post_id', $postid->post_id);
                if ($this->db->delete('comment_report')) {
                    //$result["user_id_like"] = "deleted";
                }
                $this->db->where('post_id', $postid->post_id);
                if ($this->db->delete('comments')) {
                    //$result["user_id_like"] = "deleted";
                }
                $this->db->where('post_id', $postid->post_id);
                if ($this->db->delete('bookmark')) {
                    //$result["user_id_like"] = "deleted";
                }

                $this->db->where('blockedPostsId', $postid->post_id);
                if ($this->db->delete('posts_report')) {
                    //$result["blockedPostsId"] = "deleted";
                }
            }
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('likes')) {
            //$result["user_id_like"] = "deleted";
        }


        $this->db->where('user_id', $id);
        if ($this->db->delete('comments')) {
            //$result["user_id_comments"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('comments')) {
            //$result["user_id_comments"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('bookmark')) {
            //$result["user_id_bookmark"] = "deleted";
        }

        $this->db->where('from_user', $id);
        if ($this->db->delete('follow')) {
            //$result["from_user_follow"] = "deleted";
        }

        $this->db->where('to_user', $id);
        if ($this->db->delete('follow')) {
            //$result["to_user_follow"] = "deleted";
        }

        $this->db->where('blockedByUserId', $id);
        if ($this->db->delete('posts_report')) {
            //$result["blockedByUserId"] = "deleted";
        }

        $this->db->where('blockedByUserId', $id);
        if ($this->db->delete('profile_blocklist')) {
            //$result["like"] = "deleted";
        }

        $this->db->where('blockedUserId', $id);
        if ($this->db->delete('profile_blocklist')) {
            //$result["like"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('story')) {
            //$result["story"] = "deleted";
        }

        $this->db->where('reportByUserId', $id);
        if ($this->db->delete('users_report')) {
            //$result["reportByUserId"] = "deleted";
        }

        $this->db->where('reportedUserId', $id);
        if ($this->db->delete('users_report')) {
            //$result["reportedUserId"] = "deleted";
        }

        $this->db->where('from_user', $id);
        if ($this->db->delete('user_notification')) {
            //$result["from_user_notification"] = "deleted";
        }

        $this->db->where('to_user', $id);
        if ($this->db->delete('user_notification')) {
            //$result["to_user_notification"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('admin_notifications')) {
            //$result["admin_notifications"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('comment_report')) {
            //$result["comment_report"] = "deleted";
        }

        $this->db->where('user_id', $id);
        if ($this->db->delete('posts')) {
            //$result["posts"] = "deleted";
        }
        if ($this->admin_model->delete_user($id)) {
            $result["response_code"] = "1";
            $result["message"] = "Users all data deleted sucess..!";
            $result["status"] = "sucess";
            echo json_encode($result);
            exit;
        } else {
            $result["response_code"] = "0";
            $result["message"] = "Data base error.. User not deleted..!";
            $result["status"] = "fail";
            echo json_encode($result);
        }
    }

    public function get_setting()
    {

        $seting =  $this->db->get('settings')->row();
        if (!$seting) {
            $result["response_code"] = "0";
            $result["message"] = "Setting Not Found";
            $result["status"] = "fail";
            echo json_encode($result);
            exit;
        }
        $result["response_code"] = "1";
        $result["message"] = "Settings Details";
        $result['settings'] = $seting;
        $result["status"] = "success";
        echo json_encode($result);
        exit;
    }
}
