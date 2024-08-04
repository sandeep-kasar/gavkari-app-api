<?php

class Event_Model extends CI_Model{
    
    
    function __construct(){
        parent::__construct(); 
    }


    //insert data in event table
    public function insert($data){
        if ($this->db->insert("event",$data)){
             return $this->db->insert_id();
        }
    }
    
     //insert data in event_images table
    public function insert_images($data){
        if ($this->db->insert("event_images",$data)){
            return TRUE;
        }
    }
    
    //update event data
     public function update($data,$eventid) { 
         $this->db->set($data); 
         $this->db->where("e_id", $eventid); 
         $result= $this->db->update("event", $data); 
         if($result){
             return TRUE;
         }
      } 
      
      //delete event
      public function delete($e_id) { 
         if ($this->db->delete("event", "e_id = ".$e_id)) { 
            return true; 
         } 
      } 
      
      
    //insert data in rating table
    public function insert_rate($data){
        if ($this->db->insert("rating",$data)){
            return TRUE;
        }
    }
    
    
     //insert data in interestee table
    public function insert_interest($data){
        if ($this->db->insert("interestee",$data)){
            return TRUE;
        }
    }
    
    
    //update event data
     public function update_view($data,$eventid) { 
         $this->db->set($data); 
         $this->db->where("e_id", $eventid); 
         $result= $this->db->update("event", $data); 
         if($result){
             return TRUE;
         }
      } 
      
      
    //insert data in notification table
    public function insert_notification($data){
        if ($this->db->insert("notification",$data)){
             return TRUE;
        }
    }

}


?>




   