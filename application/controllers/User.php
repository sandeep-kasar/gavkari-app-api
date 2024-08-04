<?php

/**
 * accees users Data
 */
class User extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

 public function getMyEvent()
 {
    //extract user id from url
    $user_id=$this->uri->segment('3');
    $sql="SELECT event.*,account_debit.*
	        FROM event
		    JOIN account_debit ON event.id=account_debit.event_id
            WHERE event.user_id=?
            GROUP by created_at DESC";
    $query=  $this->db->query($sql,array($user_id));
    $events=$query->result();

    
    $FinalEventArray=array();
    $mediaArray=array();
   
    
    foreach($events as $event){

        $sql="SELECT village.* FROM village
        JOIN event_villages
        WHERE event_villages.village_id=village.id
        AND event_villages.event_id=?";
        $query=  $this->db->query($sql,array($event->event_id));
        $villageList=$query->result();
        
        $distance = 0;
        if($event->type == 1){
            $distance = 100;
        }
        $villageArray=array();
        foreach($villageList as $village){
            $village->distance=$distance;
            $villageArray[]= $village;
        }
        
        $sql="SELECT * FROM event_media WHERE event_id=?";
        $query=  $this->db->query($sql,array($event->event_id));
        $mediaList=$query->result();
        
         $eventList['id']=$event->event_id;
         $eventList['user_id']=$event->user_id;
         $eventList['village_id']=$event->village_id;
         $eventList['status']=$event->status;
         $eventList['village_boy_id']=$event->village_boy_id;
         $eventList['type']=$event->type;
         $eventList['event_aid']=$event->event_aid;
         $eventList['created_at']=$event->created_at;
         $eventList['event_date']=$event->event_date;
         $eventList['event_date_ms']=$event->event_date_ms;
         $eventList['latitude']=$event->latitude;
         $eventList['longitude']=$event->longitude;
         $eventList['address']=$event->address;
         $eventList['location']=$event->location;
         $eventList['contact_no']=$event->contact_no;
         $eventList['title']=$event->title;
         $eventList['subtitle']=$event->subtitle;
         $eventList['family']=$event->family;
         $eventList['muhurt']=$event->muhurt;
         $eventList['note']=$event->note;
         $eventList['description']=$event->description;
         $eventList['photo']=$event->photo;
         $eventList['amount']=$event->amount;
         $eventList['event_media']=$mediaList;
         $eventList['villages']=$villageList;
         $FinalEventArray[]=$eventList;
    
    }

    

        //show response
        if(!empty($events)){

            //create rerspose
            $response = array("status" => 1, "message" => "success","MyAd"=>$FinalEventArray);

            //return data in json format
            echo json_encode($response);

        }else{

            //create rerspose
            $response = array("status" => -1, "message" => "No data Available !");

            //return data in json format
            echo json_encode($response);

        }

 }


 public function getMyAccount()
 {
    //extract user id from url
    $user_id=$this->uri->segment('3');
    //quert to get village data
    $sql="SELECT * FROM account WHERE user_id=?";
    $query=  $this->db->query($sql,array($user_id));
    //final result
    $events=$query->result();

        //show response
        if(!empty($events)){

            //create rerspose
            $response = array("status" => 1, "message" => "success","MyAccount"=>$events);

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