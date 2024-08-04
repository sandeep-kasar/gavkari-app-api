<?php

/**
 * operation of user
 */
class VbOperation extends CI_Controller
{

  function __construct() {
       parent::__construct();
       $this->load->database();
    }

  public function register(){

    //init model
     $this->load->model('VillageBoy_Model');

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

    if ($json->avatar == "" || $json->avatar == NULL) {

        //create rerspose
        $response = array("status" => -2, "message" => "Required field avatar is missing or empty");
        echo json_encode($response);

    }else if ($json->first_name == "" || $json->first_name == NULL) {

        //create rerspose
        $response = array("status" => -2, "message" => "Required field first name is missing or empty");
        echo json_encode($response);

    }else if ($json->middle_name == "" || $json->middle_name == NULL) {

      //create rerspose
      $response = array("status" => -2, "message" => "Required field middle name is missing or empty");
      echo json_encode($response);

    }else if ($json->last_name == "" || $json->last_name == NULL) {

      //create rerspose
      $response = array("status" => -2, "message" => "Required field last name is missing or empty");
      echo json_encode($response);

    }else if ($json->password == "" || $json->password == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field password is missing or empty");

    } else if ($json->village_id == "" || $json->village_id == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field village is missing or empty");

    } else if ($json->email == "" || $json->email == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field email is missing or empty");

    } else if ($json->mobile == "" || $json->mobile == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field mobile is missing or empty");

    } else if ($json->addhar_no == "" || $json->addhar_no == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field addhar no is missing or empty");

    } else if ($json->addhar_front_photo == "" || $json->addhar_front_photo == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field addhar front photo is missing or empty");

    } else if ($json->addhar_back_photo == "" || $json->addhar_back_photo == NULL) {

      //create rerspose
      echo json_encode($response);
      $response = array("status" => -2, "message" => "Required field addhar back photo is missing or empty");

    } else {

        $data = array(
             'avatar' => $json->avatar,
             'first_name' => $json->first_name,
             'midle_name' => $json->middle_name,
             'last_name' => $json->last_name,
             'password' => $json->password,
             'village_id' => $json->village_id,
             'email' => $json->email,
             'mobile' => $json->mobile,
             'addhar_no' => $json->addhar_no,
             'addhar_front_photo' => $json->addhar_front_photo,
             'addhar_back_photo' => $json->addhar_back_photo,
             'device_id' => $json->device_id
        );

        //query to get data to check duplicate user
        $sql = "SELECT * FROM village_boy WHERE email= ?";
        $query=$this->db->query($sql, array($json->email));
        $row = $query->row();

        if($row==NULL){ //first time registrarion

            //insert in village_boy table
           $userId=$this->VillageBoy_Model->insert_user($data);

           if($userId>0){
               $result=TRUE;
           }

          //show response
          $this->response($result,$userId);

      }else {

          //create rerspose
          $response = array("status" => -3, "message" => "User already exist !");

          //return data in json format
          echo json_encode($response);

        }
     }
  }

  public function login(){

          //init model
          $this->load->model('VillageBoy_Model');

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

          if ($json->email == "" || $json->email == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field email is missing or empty");
              echo json_encode($response);

          }else if ($json->password == "" || $json->password == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field password is missing or empty");
            echo json_encode($response);

          }else {

              $data = array(
                   'device_id' => $json->device_id
              );

              //query to get data to check duplicate user
              $sql = "SELECT * FROM village_boy WHERE email= ? and password=?";
              $query=$this->db->query($sql, array($json->email,$json->password));
              $row = $query->row();

              if($row!=NULL){ //check user availability

                  //insert in user table
                 $result=$this->VillageBoy_Model->update_deviceId($data,$json->email);

                 //show response
                 $this->response($result,$row->id);

            }else {

                //create rerspose
                $response = array("status" => -3, "message" => "User is not exist !");

                //return data in json format
                echo json_encode($response);

              }
           }
      }


      public function editProfile(){

        //init model
         $this->load->model('VillageBoy_Model');

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

        if ($json->avatar == "" || $json->avatar == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field avatar is missing or empty");
            echo json_encode($response);

        }else if ($json->first_name == "" || $json->first_name == NULL) {

            //create rerspose
            $response = array("status" => -2, "message" => "Required field first name is missing or empty");
            echo json_encode($response);

        }else if ($json->middle_name == "" || $json->middle_name == NULL) {

          //create rerspose
          $response = array("status" => -2, "message" => "Required field middle name is missing or empty");
          echo json_encode($response);

        }else if ($json->last_name == "" || $json->last_name == NULL) {

          //create rerspose
          $response = array("status" => -2, "message" => "Required field last name is missing or empty");
          echo json_encode($response);

        }else if ($json->password == "" || $json->password == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field password is missing or empty");

        } else if ($json->village_id == "" || $json->village_id == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field village is missing or empty");

        } else if ($json->email == "" || $json->email == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field email is missing or empty");

        } else if ($json->mobile == "" || $json->mobile == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field mobile is missing or empty");

        } else if ($json->addhar_no == "" || $json->addhar_no == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field addhar no is missing or empty");

        } else if ($json->addhar_front_photo == "" || $json->addhar_front_photo == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field addhar front photo is missing or empty");

        } else if ($json->addhar_back_photo == "" || $json->addhar_back_photo == NULL) {

          //create rerspose
          echo json_encode($response);
          $response = array("status" => -2, "message" => "Required field addhar back photo is missing or empty");

        } else {

            $data = array(
                 'avatar' => $json->avatar,
                 'first_name' => $json->first_name,
                 'midle_name' => $json->middle_name,
                 'last_name' => $json->last_name,
                 'password' => $json->password,
                 'village_id' => $json->village_id,
                 'email' => $json->email,
                 'mobile' => $json->mobile,
                 'addhar_no' => $json->addhar_no,
                 'addhar_front_photo' => $json->addhar_front_photo,
                 'addhar_back_photo' => $json->addhar_back_photo
            );

            //upadte in user table
           $update=$this->VillageBoy_Model->update_profile($data,$json->id);

           //show response
           if($update){

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





  public function response($result,$userId){
      //show response
      if($result){

        //query to get data
        $sql = "SELECT * FROM village_boy WHERE id= ?";
        $query=$this->db->query($sql, array( $userId));
        $row = $query->row();

          //create rerspose
          $response = array("status" => 1, "message" => "success", "user" => $row);

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
