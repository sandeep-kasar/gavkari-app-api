<?php

class UserOperation extends CI_Controller{

    function __construct() {
         parent::__construct();
         $this->load->database();
      }

    public function register(){

        //init model
         $this->load->model('User_Model');

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

        if ($json->name == "" || $json->name == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field name is missing or empty");
            echo json_encode($response);

        }else if ($json->mobile == "" || $json->mobile == NULL) {

          //create rerspose
          $response = array("status" => -2, "message" => "Required field mobile is missing or empty");
          echo json_encode($response);

        }else if ($json->password == "" || $json->password == NULL) {

          //create rerspose
          $response = array("status" => -2, "message" => "Required field password is missing or empty");
          echo json_encode($response);

        }else if ($json->village_id == "" || $json->village_id == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field village is missing or empty");

        } else {

            $data = array(
                 'name' => $json->name,
                 'mobile' => $json->mobile,
                 'password' => $json->password,
                 'village_id' => $json->village_id,
                 'device_id' => $json->device_id
            );

            //query to get data to check duplicate user
            $sql = "SELECT * FROM user WHERE mobile= ?";
            $query=$this->db->query($sql, array($json->mobile));
            $row = $query->row();

            if($row==NULL){ //first time registrarion

                //insert in user table
               $userId=$this->User_Model->insert_user($data);

               if($userId>0){
                    $result=TRUE;
                    $sql = "SELECT * FROM village WHERE id= ?";
                    $query=$this->db->query($sql, array($json->village_id));
                    $row = $query->row();
                    
                    $sql='SELECT * FROM (SELECT *,(
                            (
                            (
                            ACOS( SIN( ( ? *PI( ) /180 ) ) *SIN( (
                            latitude  *PI( ) /180 ) ) + COS( ( ? *PI( ) /180 ) ) *COS( (
                            latitude  *PI( ) /180 )
                            ) *COS( (
                            ( ?  - longitude  ) *PI( ) /180 )
                            )
                            )
                            ) *180 / PI( )
                            ) *60 * 1.1515 * 1.609344
                            ) AS distance
                                FROM village
                            )village
                            WHERE distance >0 AND distance<4
                            ORDER BY distance ASC';
                    
                            //quert to get village list
                            $query=  $this->db->query($sql,array($row->latitude,$row->latitude,$row->longitude));
                            //final result
                            $villageList=$query->result();
                            //show response
                        if(!empty($villageList)){
                            
                            //init model
                            $this->load->model("Connection_Model");
                    
                           foreach ($villageList as $village) {
                               $villageArr = array(
                                      'user_id' => $userId,
                                      'village_id' => $village->id
                                      );
                            $this->Connection_Model->insert_village($villageArr); 
                           }
                        }
                        
               }

              //show response
              $this->response($result,$userId);

          }else {

              //create rerspose
              $response = array("status" => -3, "message" => "User already present !");

              //return data in json format
              echo json_encode($response);

            }
     }
  }

    public function login(){

        //init model
        $this->load->model('User_Model');

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

        if ($json->mobile == "" || $json->mobile == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field email is missing or empty");
            echo json_encode($response);

        }else if ($json->password == "" || $json->password == NULL) {

          //create rerspose
          $response = array("status" => -2, "message" => "Required field password is missing or empty");
          echo json_encode($response);

        }else {

            $data = array(
                 'device_id' => $json->device_id,
                 'is_active'=>1
            );

            //query to get data to check duplicate user
            $sql = "SELECT * FROM user WHERE mobile= ? and password=?";
            $query=$this->db->query($sql, array($json->mobile,$json->password));
            $row = $query->row();

            if($row!=NULL){ //check user availability

                //insert in user table
               $result=$this->User_Model->update_deviceId($data,$json->mobile);

               //show response
               $this->response($result,$row->id);

          }else {

              //create rerspose
              $response = array("status" => -3, "message" => "User dose not exist !");

              //return data in json format
              echo json_encode($response);

            }
     }
    }

    public function fclogin(){

          //init model
          $this->load->model('User_Model');

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

          if ($json->facebook_id == "" || $json->facebook_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field facebook_id is missing or empty");
              echo json_encode($response);

          }else if ($json->name == "" || $json->name == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field name is missing or empty");
            echo json_encode($response);

          }else if ($json->email == "" || $json->email == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field email is missing or empty");
            echo json_encode($response);

          }else if ($json->village_id == "" || $json->village_id == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field village id is missing or empty");
            echo json_encode($response);

          }else {

              $data = array(
                   'device_id' => $json->device_id,
                   'village_id' => $json->village_id
              );

              //query to get data to check duplicate user
              $sql = "SELECT * FROM user WHERE facebook_id= ?";
              $query=$this->db->query($sql, array($json->facebook_id));
              $row = $query->row();

              if($row!=NULL){ //already registered User, so just update device token

                  //insert in user table
                 $result=$this->User_Model->update_deviceId($data,$json->email);
                 //show response
                 $this->response($result,$row->id);

            }else { //new user , so save data

              $data = array(
                   'facebook_id' => $json->facebook_id,
                   'name' => $json->name,
                   'email' => $json->email,
                   'gender' => $json->gender,
                   'device_id' => $json->device_id,
                   'village_id' => $json->village_id
              );

              //insert in user table
             $userId=$this->User_Model->insert_user($data);

             if($userId>0){
                 $result=TRUE;
                 //show response
                 $this->response($result,$userId);
             }
          }
       }

  }

    public function response($result,$userId){
    //show response
    if($result){

      //query to get data
      $sql = "SELECT * FROM user WHERE id= ?";
      $query=$this->db->query($sql, array( $userId));
      $user = $query->row();
      
      $sql = "SELECT * FROM village WHERE id= ?";
      $query=$this->db->query($sql, array( $user->village_id));
      $village = $query->row();


        //create rerspose
        $response = array("status" => 1, "message" => "success", "user" => $user, "village"=>$village);

        //return data in json format
        echo json_encode($response);
    }else{

        //create rerspose
        $response = array("status" => -1, "message" => "false");

        //return data in json format
        echo json_encode($response);

    }

}

    public function getState(){

          //query to get states
          $sql = "SELECT * FROM state";
          $query=$this->db->query($sql);
          $row = $query->result();

          if($row!=NULL){ //states are there

            //create rerspose
            $response = array("status" => 1, "message" => "success", "state" => $row);

            //return data in json format
            echo json_encode($response);

          }else { //eror in accessing data

            $response = array("status" => -1, "message" => "false");
            //create rerspose

            //return data in json format
            echo json_encode($response);
      }
   }

    public function getDistrict(){

             //init model
             $this->load->model('User_Model');

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

               //query to get district
               $sql = "SELECT * FROM district WHERE state_id= ? AND id=1";
               $query=$this->db->query($sql, array($json->state_id));
               $row = $query->result();

               if($row!=NULL){ //states are there

                 //create rerspose
                 $response = array("status" => 1, "message" => "success", "districtList" => $row);

                 //return data in json format
                 echo json_encode($response);

               }else { //eror in accessing data

                 $response = array("status" => -1, "message" => "false");
                 //create rerspose

                 //return data in json format
                 echo json_encode($response);
           }

         }

       }

    public function getTaluka(){

                 //init model
                 $this->load->model('User_Model');

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

                   //query to get district
                   $sql = "SELECT * FROM taluka WHERE state_id= ? and district_id=?";
                   $query=$this->db->query($sql, array($json->state_id,$json->district_id));
                   $row = $query->result();

                   if($row!=NULL){ //states are there

                     //create rerspose
                     $response = array("status" => 1, "message" => "success", "talukaList" => $row);

                     //return data in json format
                     echo json_encode($response);

                   }else { //eror in accessing data

                     $response = array("status" => -1, "message" => "false");
                     //create rerspose

                     //return data in json format
                     echo json_encode($response);
               }

             }

           }

    public function getVillage(){

                     //init model
                     $this->load->model('User_Model');

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

                       //query to get district
                       $sql = "SELECT * FROM village WHERE state_id= ? and district_id=? and taluka_id=?";
                       $query=$this->db->query($sql, array($json->state_id,$json->district_id,$json->taluka_id));
                       $row = $query->result();

                       if($row!=NULL){ //states are there

                         //create rerspose
                         $response = array("status" => 1, "message" => "success", "villageList" => $row);

                         //return data in json format
                         echo json_encode($response);

                       }else { //eror in accessing data

                          //create rerspose
                         $response = array("status" => -1, "message" => "false");

                         //return data in json format
                         echo json_encode($response);
               }

          }

     }

    public function editProfile(){

      //init model
       $this->load->model('User_Model');
    
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
    
      if ($json->name == "" || $json->name == NULL) {
    
          //create rerspose
          $response = array("status" => -2, "message" => "Required field name is missing or empty");
          echo json_encode($response);
    
      }else if ($json->email == "" || $json->email == NULL) {
    
          //create rerspose
          $response = array("status" => -2, "message" => "Required field email is missing or empty");
          echo json_encode($response);
    
      }else if ($json->bio == "" || $json->bio == NULL) {
    
          //create rerspose
          $response = array("status" => -2, "message" => "Required field email is missing or empty");
          echo json_encode($response);
    
      }else if ($json->mobile == "" || $json->mobile == NULL) {
    
        //create rerspose
        $response = array("status" => -2, "message" => "Required field mobile is missing or empty");
        echo json_encode($response);
    
      }else if ($json->village_id == "" || $json->village_id == NULL) {
    
        //create rerspose
        echo json_encode($response);
        $response = array("status" => -2, "message" => "Required field village is missing or empty");
    
      } else {
    
          $data = array(
               'id' => $json->id,
               'name' => $json->name,
               'bio' => $json->bio,
               'email' => $json->email,
               'mobile' => $json->mobile,
               'village_id' => $json->village_id,
               'avatar' => $json->avatar,
          );
    
          //upadte in user table
         $result=$this->User_Model->update_profile($data,$json->id);
    
         //show response
         $this->response($result, $json->id);
               
        }
  }

    public function getMyEvent(){
    //extract user id from url
    $user_id=$this->uri->segment('3');
    //quert to get village data
    $sql="SELECT event.*,account_debit.*
           FROM event,account_debit
            WHERE event.id=account_debit.event_id";
    $query=  $this->db->query($sql,array($user_id));
    //final result
    $events=$query->result();

    $villageArray=array();
    $mediaArray=array();
    $finalArray=array();
    $object=new stdClass();

    foreach($events as $event){

        $event_id=$event->id;
        $village_id=$event->village_id;

        $sql="SELECT * FROM event_villages WHERE event_id=? AND village_id=?";
        $query=  $this->db->query($sql,array($event_id,$village_id));
        $village=$query->row();

        $sql="SELECT * FROM village WHERE id=?";
        $query=  $this->db->query($sql,array($village->village_id));
        $villageList=$query->row();
        $villageArray[]=$villageList;
       

        $sql="SELECT * FROM event_media WHERE event_id=?";
        $query=  $this->db->query($sql,array($event_id));
        $mediaList=$query->row();
        $mediaArray[]=$mediaList;

        $finalArray[]=$event;

        //$finalArray=array_push(array("event_media"=>$mediaArray,"villages"=>$villageArray));
    }

        

        //show response
        if(!empty($events)){

            //create rerspose
            $response = array("status" => 1, "message" => "success","MyAd"=>$object);

            //return data in json format
            echo json_encode($response);

        }else{

            //create rerspose
            $response = array("status" => -1, "message" => "No data Available !");

            //return data in json format
            echo json_encode($response);

        }

 }

    public function updateNotificationStatus(){

          //init model
          $this->load->model('User_Model');

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

          if ($json->user_id == "" || $json->user_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field user_id is missing or empty");
              echo json_encode($response);

          }else {

              $data = array(
                   'noti_status' => $json->noti_status
              );

                //insert in user table
                 $result=$this->User_Model->update_noti_status($data,$json->user_id);
                 
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
  
    public function logout(){

          //init model
          $this->load->model('User_Model');

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

          if ($json->user_id == "" || $json->user_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field user_id is missing or empty");
              echo json_encode($response);

          }else {

              $data = array(
                   'device_id' => $json->device_id,
                   'id' => $json->user_id,
                   'is_active'=>'0'
              );

                //insert in user table
                 $result=$this->User_Model->update_user($data,$json->user_id);
                 
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
  
    public function getNotification(){
        
            
          $user_id=  $this->uri->segment('3');

          //query to get states
          $sql = "SELECT * FROM notification WHERE user_id=? GROUP by `date` DESC";
          $query=$this->db->query($sql,$user_id);
          $row = $query->result();

          if($row!=NULL){ //states are there

            //create rerspose
            $response = array("status" => 1, "message" => "success", "notificationList" => $row);

            //return data in json format
            echo json_encode($response);

          }else { //eror in accessing data

            $response = array("status" => -1, "message" => "false");
            //create rerspose

            //return data in json format
            echo json_encode($response);
      }
   }
	
	public function requestOtp(){
	    
	          //init model
              $this->load->model('User_Model');
    
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
    
              if ($json->mobile == "" || $json->mobile == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field mobile is missing or empty");
                  echo json_encode($response);
    
              }else {
                  
                  $otp=$this->generateNumericOTP(5); 
                  $response=$this->sendOtp($json->mobile,$otp); 
                  if($response){
                        $data = array(
                           'otp' => $otp,
                           'id' => $json->user_id
                        );
    
                        //insert in user table
                        $result=$this->User_Model->update_user($data,$json->user_id);
                        
                        if($result){
                            $response = array("status" => 1, "message" => 'success');
                            echo json_encode($response);
                        }else{
                            $response = array("status" => -1, "message" => "false");
                            echo json_encode($response);
                        }
                        
                        
                  }else{
                        $response = array("status" => -1, "message" => "false");
                        echo json_encode($response);
                  }   
            }
   }
   
    public function sendOtp($mobileNo,$otp){
       
        	// Authorisation details.
        	$username = "sandeepk784@gmail.com";
        	$hash = "f8c2c435bbcf45b8e5c0a9a9129ac8a8ae546534ab64ecc6aa06edf90d9cdece";
        	// Config variables. Consult http://api.textlocal.in/docs for more info.
            $test = "0";
            $true = true;
        	// Message details
        	$numbers = $mobileNo;
        	$sender = "TXTLCL"; // This is who the message appears to be from.
        	// $smsInfo = 'Dear Customer, '. $otp. ' is your SECRET OTP(One Time Password) to verify your mobile in Gavkari App. Do not share it with anyone.';
            $smsInfo = ' प्रिय ग्राहक,  '. $otp. '  हा तुमचा OTP(पहिल्या वेळेचा पासवर्ड) आहे, गावकरी अँप मध्ये तुमचा मोबाईल क्रमांक व्हेरिफाय करण्यासाठी. OTP कुणाला सांगू नये.';
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
   
   //Function to generate OTP 
    function generateNumericOTP($n) { 
          
        // Take a generator string which consist of 
        // all numeric digits 
        $generator = "1357902468"; 
      
        // Iterate for n-times and pick a single character 
        // from generator and append it to $result 
          
        // Login for generating a random character from generator 
        //     ---generate a random number 
        //     ---take modulus of same with length of generator (say i) 
        //     ---append the character at place (i) from generator to result 
      
        $result = ""; 
      
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        } 
      
        // Return result 
        return $result; 
    } 

    public function verifyMobile(){
        
          //init model
              $this->load->model('User_Model');
    
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
    
              if ($json->otp == "" || $json->otp == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field mobile is missing or empty");
                  echo json_encode($response);
    
              }else {
                  
                  //query to get states
                  $sql = "SELECT otp FROM user WHERE id=? ";
                  $query=$this->db->query($sql,array($json->user_id));
                  $row = $query->row();
                  
                  if($json->otp==$row->otp){
                        $data = array(
                           'is_verified' => '1',
                           'id' => $json->user_id
                        );
    
                        //insert in user table
                        $result=$this->User_Model->update_user($data,$json->user_id);
                        
                        if($result){
                            $response = array("status" => 1, "message" => 'success');
                            echo json_encode($response);
                        }else{
                            $response = array("status" => -1, "message" => "false");
                            echo json_encode($response);
                        }
                        
                        
                  }else{
                        $response = array("status" => -1, "message" => "Please enter valid OTP");
                        echo json_encode($response);
                  }   
            }
   }
   
    public function getAccountInfo(){

                     
                    $user_id=$this->uri->segment('4');
                    $sql="SELECT * FROM account WHERE user_id=?";
                    $query=  $this->db->query($sql,array($user_id));
                    $account=$query->row();

                   if($account!=NULL){ //states are there

                     //create rerspose
                     $response = array("status" => 1, "message" => "success", "account" => $account);

                     //return data in json format
                     echo json_encode($response);

                   }else { //eror in accessing data

                     $response = array("status" => -1, "message" => "false");
                     //create rerspose

                     //return data in json format
                     echo json_encode($response);
               }

             }

    public function submitAccount(){
	    
	          //init model
              $this->load->model('User_Model');
    
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
    
              if ($json->acct_holder_name == "" || $json->acct_holder_name == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field acct_holder_name is missing or empty");
                  echo json_encode($response);
    
              }elseif ($json->account_no == "" || $json->account_no == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field account_no is missing or empty");
                  echo json_encode($response);
    
              }elseif ($json->ifsc_code == "" || $json->ifsc_code == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field ifsc_code is missing or empty");
                  echo json_encode($response);
    
              }elseif ($json->branch_name == "" || $json->branch_name == NULL) {
    
                  //create rerspose
                  $response = array("status" => -2, "message" => "Required field branch_name is missing or empty");
                  echo json_encode($response);
    
              }else {
                    $sql="SELECT * FROM account WHERE user_id=?";
                    $query=  $this->db->query($sql,array($json->user_id));
                    $account=$query->result();

                   if($account==NULL){
                        
                        $data = array(
                           'user_id' => $json->user_id,
                           'acct_holder_name' => $json->acct_holder_name,
                           'account_no' => $json->account_no,
                           'ifsc_code' => $json->ifsc_code,
                           'branch_name' => $json->branch_name,
                        );
    
                        //insert in user table
                        $result=$this->User_Model->insert_account($data);
                        
                        if($result){
                            $response = array("status" => 1, "message" => 'success');
                            echo json_encode($response);
                        }else{
                            $response = array("status" => -1, "message" => "false");
                            echo json_encode($response);
                        }
                   }else{
                       
                          $data = array(
                           'acct_holder_name' => $json->acct_holder_name,
                           'account_no' => $json->account_no,
                           'ifsc_code' => $json->ifsc_code,
                           'branch_name' => $json->branch_name,
                        );
                        
                        $result=$this->User_Model->update_account($data,$json->user_id);
                        
                        if($result){
                            $response = array("status" => 1, "message" => 'Account updated success');
                            echo json_encode($response);
                        }else{
                            $response = array("status" => -1, "message" => "false");
                            echo json_encode($response);
                        }
                    }        
             }
       }
       
       public function refundHistory(){
                   $user_id=$this->uri->segment('4');
                   $sql="SELECT DISTINCT event.title,event.subtitle,refund.*
                            FROM event
                            INNER JOIN refund
                            ON event.user_id=refund.user_id
                            WHERE event.user_id=?";
                    $query=  $this->db->query($sql,array($user_id));
                    $refund=$query->result();

                   if($refund!=NULL){ //states are there

                     //create rerspose
                     $response = array("status" => 1, "message" => "success", "refund" => $refund);

                     //return data in json format
                     echo json_encode($response);

                   }else { //eror in accessing data

                     $response = array("status" => -1, "message" => "false");
                     //create rerspose

                     //return data in json format
                     echo json_encode($response);
               }

             }
           
}


 ?>
