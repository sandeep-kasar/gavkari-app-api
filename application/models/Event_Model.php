<?php

class Event_Model extends CI_Model{


    function __construct(){
        parent::__construct();
    }


    //insert data in event table
    public function insert_event($data){
        if ($this->db->insert("event",$data)){
             return $this->db->insert_id();
        }
    }

     //insert data in event_images table
    public function insert_media($data){
        if ($this->db->insert("event_media",$data)){
            return TRUE;
        }
    }


     //insert data in event_villages
    public function insert_village($data){
        if ($this->db->insert("event_villages",$data)){
            return TRUE;
        }
    }

    //insert data in account debit
    public function insert_amount($data){
        if ($this->db->insert("account_debit",$data)){
            return TRUE;
        }
    }
    
    //update data in event table
     public function update_event($data,$event_id){
          $this->db->set($data);
          $this->db->where("id", $event_id);
          $result= $this->db->update("event", $data);
          return $result;
     }

     //update data in event_media table
      public function update_media($data,$media_id){
           $this->db->set($data);
           $this->db->where("id", $media_id);
           $result= $this->db->update("event_media", $data);
           return $result;
      }

        //update data in account_debit table
        public function update_amount($data,$eventId){
             $this->db->set($data);
             $this->db->where("event_id", $eventId);
             $result= $this->db->update("account_debit", $data);
             return $result;
        }

        //delete data in event villages table
        public function delete_village($event_id){
          if ($this->db->delete("event_villages", "event_id = ".$event_id)) {
             return true;
          }
        }

        //delete data in delete_event event table
        public function delete_event($event_id){
          if ($this->db->delete("event", "id = ".$event_id)) {
             return true;
          }
        }


        //update data in account_debit table
        public function update_pay($data,$user_id,$village_boy_id,$event_id){
             $this->db->set($data);
             $this->db->where("user_id", $user_id);
             $this->db->where("village_boy_id", $village_boy_id);
             $this->db->where("event_id", $event_id);
             $result= $this->db->update("account_debit", $data);
             return $result;
        }
        
        //update event status
        public function update_event_status($data,$user_id,$village_boy_id,$event_id){
             $this->db->set($data);
             $this->db->where("user_id", $user_id);
             $this->db->where("village_boy_id", $village_boy_id);
             $this->db->where("id", $event_id);
             $result= $this->db->update("event", $data);
             return $result;
        }
        
        
        //insert data in sms table
        public function insert_sms($data){
            if ($this->db->insert("sms",$data)){
                 return $this->db->insert_id();
            }
        }

         //update sms
         public function update_sms($data,$user_id,$event_id){
            $this->db->set($data);
            $this->db->where("user_id", $user_id);
            $this->db->where("event_id", $event_id);
            $result= $this->db->update("sms", $data);
            return $result;
       }

}


?>
