<?php

class UserOperation extends CI_Controller{

    function __construct() {
         parent::__construct();
         $this->load->database();
      }

    public function set_cat_dist(){

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

         if ($json->ur_id == "" || $json->ur_id == NULL) {


            $response = array("status" => -2, "message" => "Required field user id is missing or empty");
            return $response;

        } else if ($json->ur_range == "" || $json->ur_range == NULL) {

             $response = array("status" => -2, "message" => "Required field user range is missing or empty");
            return $response;

        }else if ($json->category == "" || $json->category == NULL) {

            $response = array("status" => -2, "message" => "Required field category is missing or empty");
            return $response;

        }else if ($json->ur_sub_status == "" || $json->ur_sub_status == NULL) {

            $response = array("status" => -2, "message" => "Required field ur_sub_status is missing or empty");
            return $response;

        } else {

             $data = array(
                 'ur_id' => $json->ur_id,
                 'ur_range' => $json->ur_range,
                 'ur_sub_status' => $json->ur_sub_status

                );

             //update event table
             $result=$this->User_Model->update($data, $json->ur_id);

            //insert data in user category table
            foreach ( $json->category as $number) {

                $dataCategory = array(
                 'uc_ur_id' => $json->ur_id,
                 'uc_c_id' => $number->number

                );

                //query to get data to check duplicate event
                $sql = "SELECT * FROM user_category WHERE uc_ur_id= ? AND uc_c_id = ? ";
                $query=$this->db->query($sql, array($json->ur_id, $number->number));
                 if(empty($query->result())){
                     //update user_category table
                     $this->User_Model->insert_category($dataCategory);
                 }



            }

             //show response
            if($result){
                //create rerspose
                $response = array("status" => 1, "message" => "success");

                //return data in json format
                echo json_encode($response);
            }else{

                //create rerspose
                $response = array("status" => -3, "message" => "false");

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
            $response = array("status"=>-2,"message"=>"Empty request");
            die(json_encode($response));
            echo json_encode($response);
        }

        //extract data from json
        $json = json_decode($jsonText);

        //check null values
        if ($json->ur_email == "" || $json->ur_email == NULL) {

                $json->ur_email="not_avail";
        }

        if ($json->ur_photo == "" || $json->ur_photo == NULL) {

                $json->ur_photo="not_avail";
        }

        if ($json->device_token == "" || $json->device_token == NULL) {

                 $json->device_token="not_avail";
        }

        if ($json->ur_latitude == "" || $json->ur_latitude == NULL) {

                 $json->ur_latitude="not_avail";
        }

        if ($json->ur_longitude == "" || $json->ur_longitude == NULL) {

                 $json->ur_longitude="not_avail";
        }
        if ($json->ur_address == "" || $json->ur_address == NULL) {

                 $json->ur_address="not_avail";
        }
        if ($json->ur_city == "" || $json->ur_city == NULL) {

                 $json->ur_city="not_avail";
        }
        if ($json->ur_country == "" || $json->ur_country == NULL) {

                 $json->ur_country="not_avail";
        }

        if ($json->facebook_id == "" || $json->facebook_id == NULL) {

            $response["message"] = 'Required field facebook id is missing or empty';
            echo json_encode($response);

        }else if ($json->ur_name == "" || $json->ur_name == NULL) {

            $response["message"] = 'Required field user name is missing or empty';
            echo json_encode($response);

        }else if ($json->ur_gender == "" || $json->ur_gender == NULL) {

            $response["message"] = 'Required field user name is missing or empty';
            echo json_encode($response);

        } else {

            //set timezone
             date_default_timezone_set('Asia/Kolkata');
	         //get todays date
            $today=date("Y-m-d H:i:s");

            $data = array(
                 'facebook_id' => $json->facebook_id,
                 'ur_name' => $json->ur_name,
                 'ur_email' => $json->ur_email,
                 'ur_gender' => $json->ur_gender,
                 'ur_photo' => $json->ur_photo,
                 'ur_range' => $json->ur_range,
                 'ur_latitude' => $json->ur_latitude,
                 'ur_longitude' => $json->ur_longitude,
                 'ur_city' => $json->ur_city,
                 'ur_country' => $json->ur_country,
                 'ur_address' => $json->ur_address,
                 'ur_device_token' => $json->device_token,
                 'created_at'=>$today
            );

             //query to get data to check duplicate user
            $sql = "SELECT * FROM user WHERE facebook_id= ?";
            $query=$this->db->query($sql, array($json->facebook_id));
            $result=$query->result();

            if(mysql_num_rows($result==0)){ //first time login

                //insert in user table
               $userId=$this->User_Model->insert_login($data);

               if($userId>0){
                   $result=TRUE;
               }

               $dataCategory = array(
                 'uc_ur_id' => $userId,
                 'uc_c_id' => $json->ur_category

                );

                //query to get data to check duplicate event
                $sql = "SELECT * FROM user_category WHERE uc_ur_id= ? AND uc_c_id = ? ";
                $query=$this->db->query($sql, array($userId, $json->ur_category));
                 if(empty($query->result())){
                     //update user_category table
                     $this->User_Model->insert_category($dataCategory);
                 }

            }  else { //already login before

                $userId=$row->ur_id;

                $dataDeviceId = array(
                 'ur_device_token' => $json->device_token
                );

                //insert in device table
                $result=$this->User_Model->update_deviceId($dataDeviceId,$userId);

            }

            //show response
            if($result){

                 //query to get data
                 $sql = "SELECT * FROM user WHERE ur_id= ?";
                 $query=$this->db->query($sql, array( $userId));
                 $row = $query->row();

                //create rerspose
                $response = array("status" => 1, "message" => "success", "user" => $row);

                //return data in json format
                echo json_encode($response);
            }else{23

                //create rerspose
                $response = array("status" => -1, "message" => "false");

                //return data in json format
                echo json_encode($response);

            }
        }

    }
}
