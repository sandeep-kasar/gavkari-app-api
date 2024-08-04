<?php

class Directory_Model extends CI_Model{


    function __construct(){
        parent::__construct();
    }

    public function insert_dir($data){
        if ($this->db->insert("directory",$data)){
             return $this->db->insert_id();
        }
    }
    
    public function update_dir($data,$user_id){
          $this->db->set($data);
          $this->db->where("user_id", $user_id);
          $result= $this->db->update("directory", $data);
          return $result;
     }
}


?>
