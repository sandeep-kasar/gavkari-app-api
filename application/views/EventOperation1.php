<?php

require 'Notification.php';

class EventOperation extends CI_Controller{
    
    function __construct() { 
         parent::__construct();
         $this->load->database(); 
      } 
      
   
    public function add_event(){
          
          
          //init model
         $this->load->model('Event_Model');
         
          //init notification class
          $notification=new Notification();
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        
        //check any value is null or not
        $validate=$this->validate($json);
        
        if($validate=='done'){ // if validation is done
            
            //set timezone
            date_default_timezone_set('Asia/Kolkata');
	    //get todays date
            $today=date("Y-m-d H:i:s");
            
            if($json->e_discount_code=="" || $json->e_discount_code == NULL){
                
                $json->e_discount_code="not_available";
            }
            
            if($json->e_discount_amount=="" || $json->e_discount_amount == NULL){
                
                $json->e_discount_amount="not_available";
            }
            
             if($json->e_book_link=="" || $json->e_book_link == NULL){
                
                $json->e_book_link="not_available";
            }
            
             $data = array( 
                 'c_id' => $json->c_id, 
                 'ur_id' => $json->ur_id,
                 'e_discount_code' => $json->e_discount_code,
                 'e_discount_amount' => $json->e_discount_amount,
                 'e_latitude' => $json->e_latitude,
                 'e_longitude' => $json->e_longitude,
                 'e_city' => $json->e_city,
                 'e_country' => $json->e_country,
                 'e_start_date' => $json->e_start_date,
                 'e_end_date' => $json->e_end_date,
                 'e_created_at' => $today,
                 'e_name' => $json->e_name,
                 'e_place' => $json->e_place,
                 'e_address' => $json->e_address,
                 'e_book_link' => $json->e_book_link,
                 'e_photo_1' => $json->e_photo,
                 'e_description' => $json->e_description
                
            ); 
             
             
            //query to get data to check duplicate event
            $sql = "SELECT * FROM event WHERE e_start_date= ? AND ur_id = ? AND e_name = ?";
            $query=$this->db->query($sql, array($json->e_start_date, $json->ur_id, $json->e_name));
            
            if(empty($query->result())){
                
              //insert data and get status
              $eventId=$this->Event_Model->insert($data);
              
             //insert event images in  table
            foreach ( $json->images as $number) {
                
                $dataImages = array( 
                 'event_id' => $eventId,
                 'image_name' => $number->number
                
                );
                    //update event_images table
                     $this->Event_Model->insert_images($dataImages);    
            }
                  
              
            if($eventId>0){
                
                 //create response
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
                
                for($i=1;$i<10;$i++){
                    
                     //query to get data of an event
                    $sql = "SELECT * FROM (
                                            SELECT user.*,user_category.*,(
                                            (
                                            (
                                            ACOS( SIN( ( $json->e_latitude * PI( ) /180 ) ) * SIN( (
                                            ur_latitude * PI( ) /180 ) ) + COS( ( $json->e_latitude * PI( ) /180 ) ) * COS( (
                                            ur_latitude * PI( ) /180 )
                                            ) * COS( (
                                            ( $json->e_longitude - ur_longitude ) * PI( ) /180 )
                                            )
                                            )
                                            ) *180 / PI( )
                                            ) *60 * 1.1515 * 1.609344
                                            ) AS distance
                                            FROM user 
                                            JOIN user_category on user.ur_id = user_category.uc_ur_id
                                            WHERE (user_category.uc_c_id=0 || user_category.uc_c_id=$json->c_id) AND ur_range=$i
                                            )t
                                            WHERE ur_city= ? AND ur_country = ? AND distance<=$i";

                    $query=$this->db->query($sql, array($json->e_city, $json->e_country));
                    
                    foreach ($query->result() as $row)
                    {
                        //sent notification
                        $notiSent=$notification->sent_event($json->e_name,$today,$row->ur_device_token);
                        
                        if ($notiSent){//add entry in notification 
                            
                            $data = array( 
                                'event_id' => $eventId, 
                                'user_id' => $row->ur_id,
                                'nt_sent_at' => $today

                           ); 
                            
                            //insert data notification table
                            $this->Event_Model->insert_notification($data);  
                            
                        }
                    }
                    
                }  
                
            }else{
                
                //create rerspose
                $response = array("status" => -3, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
              
            }  else {
                
                //create rerspose
                $response = array("status" => -4, "message" => "false", "events" => "Duplicate event");
                
                //return data in json format
                echo json_encode($response);
            }
            
        }  else {
           //show validation
            echo $validate;
        }
        
    }

    
    public function edit_event(){
        
          //init model
         $this->load->model('Event_Model');
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-2,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        
        //check any value is null or not
        $validate=$this->validate($json);
        
        if($validate=='done'){ // if validation is done
            
            $data = array( 
                 'e_id' => $json->e_id,
                 'c_id' => $json->c_id,
                 'e_discount_code' => $json->e_discount_code,
                 'e_latitude' => $json->e_latitude,
                 'e_longitude' => $json->e_longitude,
                 'e_datetime' => $json->e_datetime,
                 'e_name' => $json->e_name,
                 'e_address' => $json->e_address,
                 'e_photo' => $json->e_photo,
                 'e_description' => $json->e_description
                
            );
            
            //update event table
             $result=$this->Event_Model->update($data, $json->e_id);
             
            //show response 
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
            
        }
        
    }

    
    public function delete_event() { 
        
        //init event model
         $this->load->model('Event_Model'); 
         $e_no = $this->uri->segment('3'); 
         $result=$this->Event_Model->delete($e_no);
         
         //show response 
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
    } 
    
    
    public function rate_event() { 
        
         //init model
         $this->load->model('Event_Model');
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-2,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        
         if ($json->e_id == "" || $json->e_id == NULL) {
            
            $response["message"] = 'Required field event id is missing or empty';
            echo json_encode($response);
            
        } else if ($json->ur_id == "" || $json->ur_id == NULL) {
            
            $response["message"] = 'Required field user id is missing or empty';
            echo json_encode($response);
            
        } else if ($json->r_point == "" || $json->r_point == NULL) {
            
            $response["message"] = 'Required field rating points  is missing or empty';
            echo json_encode($response);
            
        }  else {
            
            
             //set timezone
             date_default_timezone_set('Asia/Kolkata');
	    //get todays date
            $today=date("Y-m-d H:i:s");
            
             $data = array( 
                 'e_id' => $json->e_id, 
                 'ur_id' => $json->ur_id,
                 'r_point' => $json->r_point,
                 'r_comment' => $json->r_comment,
                 'r_datetime ' =>  $today
                ); 
             
             
             //query to get data to check duplicate event
            $sql = "SELECT * FROM rating WHERE e_id= ? AND ur_id = ? ";
            $query=$this->db->query($sql, array($json->e_id, $json->ur_id));
            
            if(empty($query->result())){
                
              //insert data and get status
              $result=$this->Event_Model->insert_rate($data);  
              
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
              
            }  else {
                
                //create rerspose
                $response = array("status" => -3, "message" => "false", "rating" => "You already rated the event");
                
                //return data in json format
                echo json_encode($response);
            }
            
        }
         
         
    } 


    public function interesting_event(){
        
        //init model
         $this->load->model('Event_Model');
         
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-2,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        
         if ($json->e_id == "" || $json->e_id == NULL) {
            
            $response["message"] = 'Required field event id is missing or empty';
            echo json_encode($response);
            
        } else if ($json->ur_id == "" || $json->ur_id == NULL) {
            
            $response["message"] = 'Required field user id is missing or empty';
            echo json_encode($response);
            
        } else {
            
            
             //set timezone
             date_default_timezone_set('Asia/Kolkata');
	    //get todays date
            $today=date("Y-m-d H:i:s");
            
             $data = array( 
                 'e_id' => $json->e_id, 
                 'ur_id' => $json->ur_id,
                 'i_datetime ' =>  $today
                ); 
             
             
             //query to get data to check duplicate event
            $sql = "SELECT * FROM interestee WHERE e_id= ? AND ur_id = ? ";
            $query=$this->db->query($sql, array($json->e_id, $json->ur_id));
            
            if(empty($query->result())){
                
              //insert data and get status
              $result=$this->Event_Model->insert_interest($data);  
              
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
              
            }  else {
                
                //create rerspose
                $response = array("status" => -3, "message" => "false", "interest" => "You already shown your interest");
                
                //return data in json format
                echo json_encode($response);
            }
            
        }
        
        
    }

   
    public function view_event(){
        
          //init model
         $this->load->model('Event_Model');
         $e_no = $this->uri->segment('3'); 
         
         //query to get view count for a event
         $sql = "SELECT e_views FROM event WHERE e_id=?";
         $query=$this->db->query($sql, array($e_no));
         $row = $query->row();
         $views=$row->e_views;
         $viewCount=$views+1;
         
         
         $data = array( 
                 'e_views' => $viewCount
                
            );
            
             //update event table
             $result=$this->Event_Model->update_view($data, $e_no);
         
         //show response 
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");
                
                //return data in json format
                echo json_encode($response);
            }else{
                
                //create rerspose
                $response = array("status" => -1, "message" => "false");
                
                //return data in json format
                echo json_encode($response);
                
            }
         
 }
 

    public function validate( $json){
          
          if ($json->c_id == "" || $json->c_id == NULL) {
            
           
            $response = array("status" => -2, "message" => "Required field category is missing or empty");
            return json_encode($response);
            
        } else if ($json->ur_id == "" || $json->ur_id == NULL) {
            
            $response = array("status" => -2, "message" => "Required field user id is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_latitude == "" || $json->e_latitude == NULL) {
            
            $response = array("status" => -2, "message" => "Required field latitude is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_longitude == "" || $json->e_longitude == NULL) {
            
            $response = array("status" => -2, "message" => "Required field longitude is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_start_date == "" || $json->e_start_date == NULL) {
            
            $response = array("status" => -2, "message" => "Required field start date is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_end_date == "" || $json->e_end_date == NULL) {
            
             $response = array("status" => -2, "message" => "Required field end date is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_name == "" || $json->e_name == NULL) {
            
            $response = array("status" => -2, "message" => "Required field event name is missing or empty");
            echo json_encode($response);
            
        }else if ($json->e_address == "" || $json->e_address == NULL) {
            
            $response = array("status" => -2, "message" => "Required field event address is missing or empty");
            return $response;
            
        }else if ($json->e_photo == "" || $json->e_photo == NULL) {
            
            $response = array("status" => -2, "message" => "Required field event photo is missing or empty");
            return json_encode($response);
            
        }else if ($json->e_description == "" || $json->e_description == NULL) {
            
            $response = array("status" => -2, "message" => "Required field event description is missing or empty");
            return $response;
            
        }else if ($json->images == "" || $json->images == NULL) {
            
            $response = array("status" => -2, "message" => "Required field images is missing or empty");
            return $response;
            
        }else {
            
            return 'done';
            
        }
          
          
      }
      
      
   public function search_event(){
       
       
         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);
        
        
         if ($json->keyword == "" || $json->keyword == NULL) {
            
           
            $response = array("status" => -2, "message" => "Required field keyword is missing or empty");
            echo json_encode($response);
            
        }else if (empty($json->category)) {
            
             $response = array("status" => -2, "message" => "Select any one category");
             echo json_encode($response);
            
        }else {
            
            
            //init array
        $finalArray = array();


        //get  event data as per list of categories
        foreach ($json->category as $number) {

            $category=$number->number;

            //query to get data
             $this->db->query('SELECT * FROM event');    
             $this->db->from('event');
             $this->db->like('e_name', $json->keyword);
             $this->db->like('e_country', $json->country);
             $this->db->where('c_id', $category);
             $query=$this->db->get()->result_array();
             
             foreach ($query as $row)
                    {
                        //We add the result array to the $finalArray
                        $finalArray[] = $row;
                    }


            }
            
        //final response back
        if (empty($finalArray)){//empty result

                    //create rerspose
                    $response = array("status" => -5, "message" => "false", "events" => $finalArray);

                    //return data in json format
                    echo json_encode($response);

                }  else {

                    //create rerspose
                    $response = array("status" => 1, "message" => "success", "events" => $finalArray);

                    //return data in json format
                    echo json_encode($response);
                }   

       }
        
    }
    
    
   
}