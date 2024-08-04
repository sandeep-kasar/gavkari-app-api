<?php

//defined a new constant for firebase api 
//staging key
define('FIREBASE_API_KEY', '');


 class MultiplePush extends CI_Controller
  {
      public function __construct() {
           parent::__construct();
           $this->load->database();
        }
      
      public function pushNotification(){
          
            $result="";

            $sql = "SELECT village_id
                    FROM event
                    WHERE status=1
                    AND event_date between CURDATE() 
                    AND CURDATE() + INTERVAL 2 DAY
                    GROUP BY village_id ASC";
            $query=$this->db->query($sql);
            $villageIds = $query->result();

            foreach($villageIds as $villageId){

                $registrationIds = array();

                $sql = "SELECT device_id FROM `user` WHERE is_active=1 AND noti_status =1 AND village_id=?";
                $query=$this->db->query($sql,$villageId->village_id);
                $ids = $query->result();
                
                foreach($ids as $id){
                    array_push($registrationIds,$id->device_id);
                }
        
                $sql = "SELECT * FROM `event` WHERE DATE(`created_at`) = CURDATE() AND status=1 AND village_id=? ";
                $query=$this->db->query($sql,$villageId->village_id);
                $events = $query->result();
                
                if(empty($events)) 
                    echo "No events for today"; 
                
                
                foreach($events as $event){
                    
                        $msg = array(
                            'body' 	=> urldecode($event->subtitle),
                            'title'	=> urldecode($event->title),
                            'icon'	=> 'default',
                            'sound' => 'default',
                            'image'=>'',
                            "click_action" => "HomeActivity"
                        );
                    
                    $data = array(
                            'body' 	=> urldecode($event->subtitle),
                            'title'	=> urldecode($event->title),
                            'icon'	=> 'default',
                            'sound' => 'default',
                            'image'=>'',
                            "click_action" => "HomeActivity"
                    );
                    
                    
                    
                    $fields = array(
                            'registration_ids'		=> $registrationIds,
                            'notification'	=> $msg,
                            'data'=>$data,
                            'priority'=>'high'
                        );
            
            
                    $headers = array(
                            'Authorization: key=' .FIREBASE_API_KEY,
                            'Content-Type: application/json'
                        ); 
        
                    #Send Reponse To FireBase Server	
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                    $result = curl_exec($ch );
                    curl_close( $ch );
                }

                unset($registrationIds);
            }    
            
            #Echo Result Of FireBase Server
            echo $result;    
         
      }

      public function pushAllNotification(){
          
        $result="No event for notification";

        $sql = "SELECT event.*
                FROM event
                WHERE status=1
                AND event_date between CURDATE() 
                AND CURDATE() + INTERVAL 2 DAY
                AND type=1 OR type=5";
        $query=$this->db->query($sql);
        $events = $query->result();
        // echo json_encode($events);

        if(empty($events)) 
            echo "No events for today";

        foreach($events as $event){

            $registrationIds = array();

            $sql = "SELECT DISTINCT `user_id` FROM connection 
                    INNER JOIN event_villages 
                    ON connection.village_id = event_villages.village_id 
                    WHERE event_villages.event_id = ?";
            $query=$this->db->query($sql,$event->id);
            $userIds = $query->result();

            foreach($userIds as $userId){
                $sql = "SELECT device_id FROM `user` WHERE is_active=1 AND noti_status =1 AND id=?";
                $query=$this->db->query($sql,$userId->user_id);
                $device_id = $query->row();
                if(!empty($device_id)){
                    array_push($registrationIds,$device_id->device_id);
                }
                
            }

            // echo json_encode($registrationIds);
     
            $msg = array(
                        'body' 	=> urldecode($event->subtitle),
                        'title'	=> urldecode($event->title),
                        'icon'	=> 'default',
                        'sound' => 'default',
                        'image'=>'',
                        "click_action" => "HomeActivity"
                    );        
                
            $data = array(
                        'body' 	=> urldecode($event->subtitle),
                        'title'	=> urldecode($event->title),
                        'icon'	=> 'default',
                        'sound' => 'default',
                        'image'=>'',
                        "click_action" => "HomeActivity"
                );
                
            $fields = array(
                        'registration_ids'		=> $registrationIds,
                        'notification'	=> $msg,
                        'data'=>$data,
                        'priority'=>'high'
                    );
        
            $headers = array(
                        'Authorization: key=' .FIREBASE_API_KEY,
                        'Content-Type: application/json'
                    ); 
    
            #Send Reponse To FireBase Server	
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );    
            
            unset($registrationIds);
            // echo json_encode($userIds);
        }    
        
        #Echo Result Of FireBase Server
        echo $result;    
     
      }
           
  }        
?>