<?php

class Connection_Model extends CI_Model{


    function __construct(){
        parent::__construct();
    }

    //insert data in event table
    public function insert_village($data){
        if ($this->db->insert("connection",$data)){
            return $this->db->insert_id();
        }
    }

    //delete data in connection table
    public function delete_village($connection_id){
      if ($this->db->delete("connection", "id = ".$connection_id)) {
         return true;
      }
    }



}


?>
