<?php

require 'sendgrid-php/vendor/autoload.php';

//defined a new constant for firebase api key
define('FIREBASE_API_KEY', '');

//defined a new constant for sendgrid api key
define('SENDGRID_API_KEY', '');


 class SendAlert extends CI_Controller
  {
      public function __construct() {
           parent::__construct();
           $this->load->database();
        }
        
      public function sendSms($data, $result){
          
            // Authorisation details.
        	$username = "";
        	$hash = "";
        	// Config variables. Consult http://api.textlocal.in/docs for more info.
            $test = "0";
            $true = true;
        	// Message details
        	$numbers = $result->mobile;
        	$sender = "TXTLCL"; // This is who the message appears to be from.
        	// $smsInfo = 'Your payment of Amount : '.$data->amount.' is received has Transaction Id : '.$data->transaction_no.'. Your event will be publish soon. Thank you.';
            $smsInfo = 'रक्कम रु. '.$data->amount.' प्राप्त झाली. तुमचा व्यवहार आयडी आहे : '.$data->transaction_no.'. कार्यक्रम लवकरच प्रकाशित होईल. धन्यवाद.';
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
      
      public function pushNotification($data,$result){
        //$smsInfo = 'Your payment of Amount : '.$data->amount.' is received has Transaction Id : '.$data->transaction_no.'. Your event will be publish soon. Thank you.';
        //$title = "Payment Notification";    
        $smsInfo = 'रक्कम रु. '.$data->amount.' प्राप्त झाली. तुमचा व्यवहार आयडी आहे : '.$data->transaction_no.'. कार्यक्रम लवकरच प्रकाशित होईल. धन्यवाद.';
        $title = "पेमेंट नोटिफिकेशन";
        $msg = array(
        'body' 	=> urldecode($smsInfo),
        'title'	=> urldecode($title),
        'icon'	=> 'default',
        'sound' => 'default',
        'image'=>'',
        "click_action" => "HomeActivity"
        );
                  
        $info = array(
            'body' 	=> urldecode($smsInfo),
            'title'	=> urldecode("Payment Notification"),
            'icon'	=> 'default',
            'sound' => 'default',
            'image'=>'',
            "click_action" => "HomeActivity"
        );
        
        $fields = array(
                'registration_ids'=> array($result->device_id),
                'notification'=> $msg,
                'data'=>$info,
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
        
        //insert notification in table
        $this->load->model('User_Model');
        $data = array(
                 'user_id' => $data->user_id,
                 'title' => $title,
                 'description' => $smsInfo
            );
        $this->User_Model->insert_notification($data);
        
        #Echo Result Of FireBase Server
        return $result;              

    }
      
      public function sendEmail($data,$result){
        //  $smsInfo = 'Your payment of Amount : '.$data->amount.' is received has Transaction Id : '.$data->transaction_no.'. Your event will be publish soon. Thank you.';
         $smsInfo = 'रक्कम रु. '.$data->amount.' प्राप्त झाली. तुमचा व्यवहार आयडी आहे : '.$data->transaction_no.'. कार्यक्रम लवकरच प्रकाशित होईल. धन्यवाद.';
         $title = "पेमेंट माहिती";
         $email = new \SendGrid\Mail\Mail(); 
         $email->setFrom("me.gavkari@gmail.com", "Gavkari Care");
         $email->setSubject($title);
         $email->addTo($result->email, $result->name);
        //  $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
         $email->addContent("text/html", "<strong>$smsInfo</strong>");
         $sendgrid = new \SendGrid(SENDGRID_API_KEY);
         try {
                $response = $sendgrid->send($email);
                // print $response->statusCode() . "\n";
                // print_r($response->headers());
                // print $response->body() . "\n";
                } catch (Exception $e) {
                    echo 'Caught exception: '. $e->getMessage() ."\n";
                }
        return $response->statusCode();        
      }
          
  }        
?>