<?php

class UserData extends CI_Controller{
    
    function _construct(){
        parent::__construct();
        $this->load->database();
    }
    
   
    
    public function get_interest(){
        
        //get data from url
        $ur_id=  $this->uri->segment('3');
        
        //quert to get user interested events id
        $sql="SELECT event.*
               FROM event
                INNER JOIN interestee ON interestee.e_id=event.e_id 
                 WHERE interestee.ur_id=?;";
        $query=  $this->db->query($sql,array($ur_id));
        
        //final result
        $result=$query->result();
            //show response 
            if(!empty($result)){
                //create rerspose
                $response = array("status" => 1, "message" => "success","myevent"=>$result);
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false","myevent"=>$result);
                
                //return data in json format
                echo json_encode($response);
                
            }
        
        
    }
    
    
    public function get_my_event(){
        
        //get data from url
        $ur_id=  $this->uri->segment('3');
        
        //quert to get user interested events id
        $sql="SELECT * FROM event WHERE ur_id=?";
        $query=  $this->db->query($sql,array($ur_id));
        
        //final result
        $result=$query->result();
            //show response 
            if(!empty($result)){
                //create rerspose
                $response = array("status" => 1, "message" => "success","myevent"=>$result);
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false","myevent"=>$result);
                
                //return data in json format
                echo json_encode($response);
                
            }
        
        
    }
    
    
    
    
}

