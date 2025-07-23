<?php

class Firebase_model extends CI_Model
{

   private $api_key = "AAAAjf434yI:APA91bEdL6yUh0O1SpuYMdG0sjJW2hxiu-TBLOuf-bPyzEUL5DkChfuX4HiUmkkr5WFv-BOdxG4_9xVTwpMXQYpHs8xMbgNQGW0zfLAqMjydXq42ZIxeVRxUDlU5j_eBmy_7EvaT5lXb";
   private $url = 'https://fcm.googleapis.com/fcm/send';

   public function save_user_notification($from_user, $to_user, $title, $message, $post_id)
   {
      // $date_a = new DateTime('now', new DateTimeZone('Europe/Stockholm'));
      // $date = $date_a->format('n/j/Y');
      // $date_time = $date_a->format('Y-m-d H:i');

      $user_notification = array(
         "from_user" => $from_user,
         "to_user" => $to_user,
         "post_id" => $post_id,
         "title" => $title,
         "message" => $message,
         "date" => date("d M, H:i A")
      );

      $this->db->insert("user_notification", $user_notification);
   }

   public function send_user_notification($to_user, $title, $message)
   {
      $user = $this->db->get_where("users", array("id" => $to_user))->row();

      if (!$user) {
         return "NO USER";
      }

      $fire_keys = explode("::::", $user->device_token);
      foreach ($fire_keys as $key => $fire_key) {
         $tokens[] = $fire_key;
      }

      $imageUrl = '';

      $result = $this->send_notification($title, $message, $tokens, $imageUrl);
      return $result;
   }

   public function save_admin_notification($user_id, $title, $message, $image)
   {

      $notification = array(
         "user_id" => $user_id,
         "title" => $title,
         "message" => $message,
         "image" => $image,
         "date" => date("d M, H:i A")
      );

      $this->db->insert("admin_notifications", $notification);
   }

   public function admin_send_user_notification($user_id, $title, $message)
   {
      $user = $this->db->get_where("users", array("id" => $user_id))->row();

      if (!$user) {
         return "NO USER";
      }

      $user_notification = $this->db->get_where("admin_notifications", array("title" => $title))->row();

      if (!empty($user_notification->image)) {
         $imageUrl = base_url() . 'uploads/notifications/' . $user_notification->image;
      } else {
         $imageUrl = '';
      }

      $fire_keys = explode("::::", $user->device_token);
      foreach ($fire_keys as $key => $fire_key) {
         $tokens[] = $fire_key;
      }

      $result = $this->send_notification($title, $message, $tokens, $imageUrl);
      return $result;
   }

   public function send_notification($title, $message, $tokens, $imageUrl)
   {
      $custom_object = array();
      $data = array(
         "title" => $title,
         "body" => $message,
         "image" => $imageUrl
      );

      $newdata = array_merge($data, $custom_object);

      $fields = array(
         'registration_ids' => $tokens,
         'priority' => "high",
         'data' => $newdata,
         'notification' => array('title' => $title, 'body' => $message, "image" => $imageUrl, 'sound' => 'Default'),
      );

      $fields  = json_encode($fields);

      $headers = array(
         'Authorization: key=' . $this->api_key,
         'Content-Type: application/json'
      );

      $ch      = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      $result = curl_exec($ch);

      curl_close($ch);

      return $result;
   }
}
