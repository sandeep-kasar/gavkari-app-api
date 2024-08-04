<?php

require 'application/controllers/SendAlert.php';

/**
 * My event operation
 */
class MyEventOperation extends CI_Controller
{

   function __construct(){
    parent::__construct();
    $this->load->database();
  }
  
   public function editMyAd(){

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

            $data = array(
                 'user_id' => $json->user_id,
                 'village_id' => $json->village_id,
                 'status'=>2,
                 'village_boy_id' => $json->village_boy_id,
                 'event_date' => $json->event_date,
                 'event_date_ms' => $json->event_date_ms,
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
            $sql = "SELECT * FROM event WHERE user_id= ? AND village_id=? AND id=?";
            $query=$this->db->query($sql, array($json->user_id,$json->village_id,$json->id));
            $row = $query->row();

            if($row!=NULL){ 

               $result=$this->Event_Model->update_event($data,$row->id);

               if($result){

                      $sql = "DELETE FROM event_media WHERE event_id=?";
                      $query=$this->db->query($sql, array($row->id));

                      //update media
                       foreach ($json->event_media as $image) {


                         $imagesUrls = array(
                                              'event_id'=>$row->id,
                                              'photo' =>$image->photo
                                               );

                         $result=$this->Event_Model->insert_media($imagesUrls);


                       }
                       
                       //show response
                       $this->response($result);

                 }else {

                   //create rerspose
                   $response = array("status" => -1, "message" => "false");

                   //return data in json format
                   echo json_encode($response);
                 }


          }else {

              //create rerspose
              $response = array("status" => -1, "message" => "Event is not available");

              //return data in json format
              echo json_encode($response);

            }

         }
      }
  }
  
   public function deleteMyAd() {

    //init model
    $this->load->model("Event_Model");

    //get data from url
    $event_id=  $this->uri->segment('4');

    $result=$this->Event_Model->delete_event($event_id);

    //show response
    if($result){

        //create rerspose
        $response = array("status" => 1, "message" => "deleted");

        //return data in json format
        echo json_encode($response);
    }else{

        //create rerspose
        $response = array("status" => -1, "message" => "false");
        //return data in json format
        echo json_encode($response);

    }

  }

   public function payMyAd(){

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

            $data = array(
                 'user_id' => $json->user_id,
                 'village_boy_id' => $json->village_boy_id,
                 'event_id' => $json->event_id,
                 'pay_status' => "1",
                 'amount' => $json->amount,
                 'transaction_no' => $json->transaction_no,
                 'payment_date' => $json->payment_date

            );

            $this->Event_Model->update_pay($data,$json->user_id,$json->village_boy_id,$json->event_id);
            
            
            $status = array(
                 'status' => 1,
            );
            
            
            $result=$this->Event_Model->update_event_status($status,$json->user_id,$json->village_boy_id,$json->event_id);
             
            $sql = "SELECT * FROM `user` WHERE id=? ";
            $query=$this->db->query($sql,$json->user_id);
            $user = $query->row();
            
            $sendAlert = new SendAlert(); 
            $sendAlert->sendSms($json, $user);
            $sendAlert->sendEmail($json, $user);
            $sendAlert->pushNotification($json, $user);

            //show response
            // $this->response($result.'\n'.$result1.'\n'.$result2); 
            $this->response($result); 
          }




  }

   public function response($result){
      //show response
      if($result){

          //create rerspose
          $response = array("status" => 1, "message" => $result);

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




 ?>
