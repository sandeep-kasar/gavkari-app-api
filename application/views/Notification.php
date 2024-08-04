<?php

class Notification extends CI_Controller{
    
    function __construct() { 
         parent::__construct();
         $this->load->database(); 
      } 
      
    public function sent_event($eTitle,$today,$uDeviceId){
          
          //create message
          $msg = array
                (
                    'text' => 'Date : '.$today.'',
                    'title' => "$eTitle",
                    "icon" => "appicon",
                    "sound" => "default"
                );
          
          
         if(strpos( $uDeviceId, 'iOS-' ) !== false ){//from ios
                
                //FCM key
                $apiKey="";
                $tokens = str_replace("iOS-","",$uDeviceId);

                            $notification = array
                            (
                                    'text' => 'Date : '.$today.'',
                                    'title' => "$eTitle",
                                    "icon" => "appicon",
                                    "sound" => "default"
                            );

                             $fields = array
                            (
                                    'to' => $tokens,
                                    'data' => $msg,
                                    'notification' => $notification,
                                    'priority' => 'high'

                            );
                             
                  //sent notification
                   return $this->sent_notification($apiKey, $fields);

            }else{ //from android
                
                //FCM key
                 $apiKey = '';

                 $tokens=$uDeviceId;
                            
                             $fields = array
                            (
                                    'to' => $tokens,
                                    'data' => $msg,
                                    'priority' => 'high'

                            );

                   //sent notification
                 return $this->sent_notification($apiKey, $fields);
                
            }
        }
        
    private function sent_notification($apiKey,$fields){
            
             $headers = array
            (
                'https://fcm.googleapis.com/fcm/send',
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_exec($ch);
            curl_close($ch);
            
            return true;
        }
          
 }
      



