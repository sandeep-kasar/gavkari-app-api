<?php

/**
 * model
 */
class VillageBoy_Model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  public function insert_user($data)
  {
    if ($this->db->insert("village_boy",$data)){
        return $this->db->insert_id();
    }
  }

  //update data in user table
   public function update_deviceId($data,$email){
        $this->db->set($data);
        $this->db->where("email", $email);
        $result= $this->db->update("village_boy", $data);
        return $result;
   }

   //update data in village_boy table
   public function update_profile($data,$userId){
        $this->db->set($data);
        $this->db->where("id", $userId);
        $result= $this->db->update("village_boy", $data);
        return $result;
   }



}




 ?>
