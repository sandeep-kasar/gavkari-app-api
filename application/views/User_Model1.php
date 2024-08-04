<?php

class User_Model extends CI_Model{
    
    
    function __construct(){
        parent::__construct(); 
    }
    
    //update user setting data
     public function update($data,$userid) { 
         $this->db->set($data); 
         $this->db->where("ur_id", $userid); 
         $result= $this->db->update("user", $data); 
         if($result){
             return TRUE;
         }
      } 
      
    //insert data in user table
    public function insert_login($data){
        if ($this->db->insert("user",$data)){
            return $this->db->insert_id();
        }
    }
    
   //insert data in user table
    public function update_deviceId($data,$userid){
         $this->db->set($data); 
         $this->db->where("ur_id", $userid); 
         $result= $this->db->update("user", $data); 
         if($result){
             return TRUE;
         }
    }
    
    
    //insert data in user_category table
    public function insert_category($data){
        if ($this->db->insert("user_category",$data)){
            return TRUE;
        }
    }
}
