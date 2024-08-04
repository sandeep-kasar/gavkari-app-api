<?php

class User_Model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    //insert data in user table
    public function insert_village($data){
        if ($this->db->insert("user",$data)){
            return $this->db->insert_id();
        }
    }

    //insert data in user table
    public function insert_user($data){
        if ($this->db->insert("user",$data)){
            return $this->db->insert_id();
        }
    }

    //update data in user table
     public function update_deviceId($data,$mobile){
          $this->db->set($data);
          $this->db->where("mobile", $mobile);
          $result= $this->db->update("user", $data);
          return $result;
     }
     
      //update data in user table
     public function update_noti_status($data,$userId){
          $this->db->set($data);
          $this->db->where("id", $userId);
          $result= $this->db->update("user", $data);
          return $result;
     }


     //insert data in account table
     public function insert_account($data){
         if ($this->db->insert("account",$data)){
             return $this->db->insert_id();
         }
     }

     //update data in account table
     public function update_account($data,$user_id){
          $this->db->set($data);
          $this->db->where("user_id", $user_id);
          $result= $this->db->update("account", $data);
          return $result;
     }

     //delete data in account table
     public function delete_account($id){
       if ($this->db->delete("account", "id = ".$id)) {
          return true;
       }
     }

     //insert data in refund table
     public function insert_refund($data){
         if ($this->db->insert("refund",$data)){
             return $this->db->insert_id();
         }
     }

     //update data in user table
     public function update_profile($data,$userId){
          $this->db->set($data);
          $this->db->where("id", $userId);
          $result= $this->db->update("user", $data);
          return $result;
     }
     
     //update data in user table
     public function update_user($data,$userId){
          $this->db->set($data);
          $this->db->where("id", $userId);
          $result= $this->db->update("user", $data);
          return $result;
     }
     
     //insert data in notification table
    public function insert_notification($data){
        if ($this->db->insert("notification",$data)){
            return $this->db->insert_id();
        }
    }
     

}

 ?>
