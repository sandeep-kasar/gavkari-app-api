<?php

class EventOperation extends CI_Controller{

      function __construct() {
         parent::__construct();
         $this->load->database();
      }

      public function createEvent(){

        //init model
         $this->load->model('Event_Model');

         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

          //extract data from json
          $json = json_decode($jsonText);

          if ($json->event_date == "" || $json->event_date == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field event date is missing or empty");
              echo json_encode($response);

          }else if ($json->address == "" || $json->address == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field address is missing or empty");
            echo json_encode($response);

          }else if ($json->contact_no == "" || $json->contact_no == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field contact no is missing or empty");
            echo json_encode($response);

          }else if (empty($json->event_media)) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required media is missing or empty");
            echo json_encode($response);

          }else if ($json->title == "" || $json->title == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field address is missing or empty");
            echo json_encode($response);

          }else if ($json->family == "" || $json->family == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field family is missing or empty");
            echo json_encode($response);

          }else if ($json->muhurt == "" || $json->muhurt == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field muhurt is missing or empty");
            echo json_encode($response);

          }else if ($json->subtitle == "" || $json->subtitle == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field subtitle is missing or empty");
            echo json_encode($response);

          }else if ($json->note == "" || $json->note == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field note is missing or empty");
            echo json_encode($response);

          } else if ($json->description == "" || $json->description == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field description is missing or empty");
            echo json_encode($response);

          } else if ($json->photo == "" || $json->photo == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field photo is missing or empty");
            echo json_encode($response);

          } else {
              
            $today = date("dmy");
            $rand = "EI".strtoupper(substr(uniqid(sha1(time())),0,4));
            $unique = $rand . $today;  
            
            $status = 2;
            if($json->type == "1"){
                $status = 0;
            }
            $data = array(
                 'user_id' => $json->user_id,
                 'village_id' => $json->village_id,
                 'status' => $status,
                 'village_boy_id' => $json->village_boy_id,
                 'type' => $json->type,
                 'event_date' => $json->event_date,
                 'event_date_ms' => $json->event_date_ms,
                 'event_aid' => $unique,
                 'latitude' => $json->latitude,
                 'longitude' => $json->longitude,
                 'address' => $json->address,
                 'location' => $json->location,
                 'contact_no' => $json->contact_no,
                 'title' => $json->title,
                 'family' => $json->family,
                 'muhurt' => $json->muhurt,
                 'subtitle' => $json->subtitle,
                 'note' => $json->note,
                 'description' => $json->description,
                 'photo' => $json->photo
            );


            //query to get data to check duplicate event
            $sql = "SELECT * FROM event WHERE user_id= ? AND village_id=? AND event_date=? AND title=?";
            $query=$this->db->query($sql, array($json->user_id,$json->village_id,$json->event_date,$json->title));
            $row = $query->row();

            if($row==NULL){ //first time event creation

                //insert in event table
               $eventId=$this->Event_Model->insert_event($data);


               if($eventId>0){

                      //insert media
                       foreach ($json->event_media as $image) {

                         $imagesUrls = array(
                                                        'event_id'=>$eventId,
                                                        'photo' =>$image->photo
                                                       );

                         $result=$this->Event_Model->insert_media($imagesUrls);

                       }
                       
                       $amount_add = "0";
                       if($json->type == "1"){
                           $amount_add = "400";
                       }

                       //insert amount
                       $amount = array(
                                        'user_id' => $json->user_id,
                                        'village_boy_id' => $json->village_boy_id,
                                        'event_id'=>$eventId,
                                        'amount' =>$amount_add
                                       );

                       $this->Event_Model->insert_amount($amount);
                       
                       
                        $village = array(
                             'event_id' => $eventId,
                             'village_id' => $json->village_id
                        );
                        
                        
                        $result=$this->Event_Model->insert_village($village);
                       
                       
                        $sql = "SELECT * FROM event WHERE id= ?";
                        $query=$this->db->query($sql, array($eventId));
                        $event = $query->row();
                       
                       //show response
                       $this->response($result,$event);


                 }else {

                   //create rerspose
                   $response = array("status" => -1, "message" => "false");

                   //return data in json format
                   echo json_encode($response);
                 }


          }else {

              //create rerspose
              $response = array("status" => -3, "message" => "Event already present !");

              //return data in json format
              echo json_encode($response);

            }

           }
      }
  }
  
      public function addEventVillage(){

        //init model
         $this->load->model('Event_Model');

         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

          //extract data from json
          $json = json_decode($jsonText);

          if ($json->event_id == "" || $json->event_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field event id is missing or empty");
              echo json_encode($response);

          }else if ($json->village_id == "" || $json->village_id == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field village id is missing or empty");
            echo json_encode($response);

          } else {

            $village = array(
                 'event_id' => $json->event_id,
                 'village_id' => $json->village_id
            );


            //query to get data to check duplicate event
            $sql = "SELECT * FROM event_villages WHERE event_id= ? AND village_id=?";
            $query=$this->db->query($sql, array($json->event_id,$json->village_id));
            $row = $query->row();

            if($row==NULL){ 
                
                $this->Event_Model->insert_village($village);
                
                $sql = "SELECT * FROM event WHERE id= ?";
                $query=$this->db->query($sql, array($json->event_id));
                $row_event = $query->row();
                
                $sql = "SELECT * FROM account_debit WHERE event_id= ?";
                $query=$this->db->query($sql, array($json->event_id));
                $row_amt = $query->row();
                
                if($row_event->type == 1){
                    $amount=$row_amt->amount+100;
                    $amt = 100;
                }else{
                    $amount=$row_amt->amount+0;
                    $amt = 0;
                }
               
                
                //insert amount
                       $amount_data = array(
                                        'event_id'=>$json->event_id,
                                        'amount' =>$amount
                                       );

                       $result=$this->Event_Model->update_amount($amount_data,$json->event_id);
              
                if($result){

                   //create rerspose
                    $response = array("status" => 1, "message" => "Village added successfully","amount"=>$amt);

                    //return data in json format
                    echo json_encode($response);
                    
              }else{
        
                  //create rerspose
                  $response = array("status" => -4, "message" => "Village already present","amount"=>"0");
        
                  //return data in json format
                  echo json_encode($response);
        
              }



            }else {

              //create rerspose
              $response = array("status" => -3, "message" => "Village already added");

              //return data in json format
              echo json_encode($response);

            }

         }
      }
  }
  
      public function addDefaultUserVillage(){

        //init model
         $this->load->model('Event_Model');

        //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

          //extract data from json
          $json = json_decode($jsonText);

          if ($json->event_id == "" || $json->event_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field event id is missing or empty");
              echo json_encode($response);

          }else if ($json->village_id == "" || $json->village_id == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field village id is missing or empty");
            echo json_encode($response);

          } else {
              
           $village = array(
                 'event_id' => $json->event_id,
                 'village_id' => $json->village_id
            );


            //query to get data to check duplicate event
            $sql = "SELECT * FROM event_villages WHERE event_id= ? AND village_id=?";
            $query=$this->db->query($sql, array($json->event_id,$json->village_id));
            $row = $query->row();

            if($row==NULL){ 
                
                $result=$this->Event_Model->insert_village($village);
              
                if($result){
                    
                    $sql = "SELECT * FROM village WHERE id= ?";
                    $query=$this->db->query($sql, array($json->village_id));
                    $village = $query->row();
                    
                    $dataList['id']=$village->id;
                    $dataList['state_id']=$village->state_id;
                    $dataList['district_id']=$village->district_id;
                    $dataList['taluka_id']=$village->taluka_id;
                    $dataList['taluka_id']=$village->taluka_id;
                    $dataList['english']=$village->english;
                    $dataList['hindi']=$village->hindi;
                    $dataList['marathi']=$village->marathi;
                    $dataList['latitude']=$village->latitude;
                    $dataList['longitude']=$village->longitude;
                    $dataList['distance']='0';
                    
                    //create rerspose
                    $response = array("status" => 1, "message" => "Your village added successfully",
                                        "VillageList"=>$dataList,"amount"=>'500');
        
                    //return data in json format
                    echo json_encode($response);
                    
              }else{
        
                  //create rerspose
                  $response = array("status" => -3, "message" => "Please try again later","amount"=>"0");
        
                  //return data in json format
                  echo json_encode($response);
        
              }

            }else {

              //create rerspose
              $response = array("status" => -3, "message" => "Village already added");

              //return data in json format
              echo json_encode($response);

            }
          
         }
      }
  }
  
      public function response($result,$data){
      //show response
      if($result){

          //create rerspose
          $response = array("status" => 1, "message" => "success","event"=>$data);

          //return data in json format
          echo json_encode($response);
      }else{

          //create rerspose
          $response = array("status" => -1, "message" => "false","event"=>$data);

          //return data in json format
          echo json_encode($response);

      }

  }

      public function removeVillage(){
          
          //init model
         $this->load->model('Event_Model');

        //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

          //extract data from json
          $json = json_decode($jsonText);

            //query to get data to check duplicate event
            $sql = "DELETE FROM event_villages WHERE event_id= ? AND village_id=?";
            $this->db->query($sql, array($json->event_id,$json->village_id));
            
            $sql = "SELECT * FROM event WHERE id= ?";
            $query=$this->db->query($sql, array($json->event_id));
            $row_event = $query->row();
                
             //query to get data to check duplicate event
            $sql = "SELECT * FROM account_debit WHERE event_id= ?";
            $query=$this->db->query($sql, array($json->event_id));
            $row = $query->row();
            
            
            if($row_event->type == 1){
                $calculte=$row->amount-100;     
            }else{
                $calculte=$row->amount;
            }
                
                //insert amount
                       $amount = array(
                                        'event_id'=>$json->event_id,
                                        'amount' =>$calculte
                                       );

                       $result=$this->Event_Model->update_amount($amount,$json->event_id);
            
            if($result){

                   //create rerspose
                    $response = array("status" => 1, "message" => "Village deleted successfully","calculte"=>$calculte);

                    //return data in json format
                    echo json_encode($response);
                    
              }else{
        
                  //create rerspose
                  $response = array("status" => -4, "message" => "Try again later");
        
                  //return data in json format
                  echo json_encode($response);
        
         }
      }
   }
   
      public function sendSms(){
           //init model
         $this->load->model('Event_Model');

         //get json data from url
        $jsonText = file_get_contents('php://input');

        //if there is no data in json
        if(empty($jsonText))
        {
            $response = array("status"=>-1,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);

        }else {

              //extract data from json
              $json = json_decode($jsonText);
    
              if ($json->event_id == "" || $json->event_id == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field event id is missing or empty");
                  echo json_encode($response);
    
              }else if ($json->message == "" || $json->message == NULL) {
    
                //create rerspose
                $response = array("status" => -2, "message" => "Required field message is missing or empty");
                echo json_encode($response);
    
              }else if ($json->mobile_nums == "" || $json->mobile_nums == NULL) {
    
                //create rerspose
                $response = array("status" => -2, "message" => "Required field mobile_nums is missing or empty");
                echo json_encode($response);
    
              } else {
   
                //query to get data to check duplicate sms
                $sql = "SELECT * FROM sms WHERE event_id= ? AND user_id=?";
                $query=$this->db->query($sql, array($json->event_id,$json->user_id));
                $row = $query->row();
                
                if($row==NULL){ 
                
                    $string_version = implode(',', $json->mobile_nums);
                    
                    //send sms to all numbers
                    $response=$this->sendSmsTo($string_version,$json->message); 
                    
                     $data = array(
                     'event_id' => $json->event_id,
                     'user_id' => $json->user_id,
                     'message' => $json->message,
                     'mobile_nums' => $string_version,
                     "sms_count" => count($json->mobile_nums)
                    );
                    
                    //insert in sms table
                    $this->Event_Model->insert_sms($data);

                    //create rerspose
                    $response = array("status" => 1, "message" => "SMS sending is in progress. Thank you.");
        
                    //return data in json format
                    echo json_encode($response);
        
                  }else { //eror in accessing data
                    
                    if($row->sms_count<100){
                        $string_version = implode(',', $json->mobile_nums);
                        //send sms to all numbers
                        $response=$this->sendSmsTo($string_version,$json->message); 
                        $cont = $row->sms_count + count($json->mobile_nums);
                        $data = array(
                            'message' => $json->message,
                            'mobile_nums' => $string_version,
                            "sms_count" => $cont
                           );
                           $this->Event_Model->update_sms($data, $json->user_id,$json->event_id );

                           //create rerspose
                            $response = array("status" => 1, "message" => "SMS sending is in progress. Thank you.");
                
                            //return data in json format
                            echo json_encode($response);
                    }

                    if($row->sms_count>=100){
                        $response = array("status" => -1, "message" => "100 SMS already sent.");
                        echo json_encode($response);
                    }

                  }
    
             }
          }
      
    }
    
      public function sendSmsTo($mobileNo,$smsInfo){
       
        	// Authorisation details.
        	$username = "sandeepk784@gmail.com";
        	$hash = "f8c2c435bbcf45b8e5c0a9a9129ac8a8ae546534ab64ecc6aa06edf90d9cdece";
        	// Config variables. Consult http://api.textlocal.in/docs for more info.
            $test = "0";
            $true = true;
        	// Message details
        	$numbers = $mobileNo;
        	$sender = "TXTLCL"; // This is who the message appears to be from.
        	$message = urldecode($smsInfo);
        	// 612 chars or less
        	// A single number or a comma-seperated list of numbers
        	$message = urlencode($message);
        	$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test."&unicode=".$true;
        	$ch = curl_init('http://api.textlocal.in/send/?');
        	curl_setopt($ch, CURLOPT_POST, true);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$result = curl_exec($ch); // This is the result from the API
        	curl_close($ch);
        	return $result;
   }
   
    public function loadEventMatter(){
                      
                      $sql="SELECT * FROM event_matter";
                      $query=  $this->db->query($sql);
                      $event_matter=$query->result();
                     
                          //show response
                          if(!empty($event_matter)){

                              //create rerspose
                              $response = array("status" => 1, "message" => "success","Matter"=>$event_matter);

                              //return data in json format
                              echo json_encode($response);

                          }else{

                              //create rerspose
                              $response = array("status" => -1, "message" => "No data Available !");

                              //return data in json format
                              echo json_encode($response);

                          }

                } 

}







 ?>
