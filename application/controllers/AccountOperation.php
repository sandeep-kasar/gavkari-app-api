<?php

/**
 *
 */
class AccountOperation extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function createMyAccount()
  {

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

          if ($json->account_no == "" || $json->account_no == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field account no is missing or empty");
              echo json_encode($response);

          }else if ($json->ifsc_code == "" || $json->ifsc_code == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field ifsc code is missing or empty");
              echo json_encode($response);

          } else {

            $data = array(
                 'user_id' => $json->user_id,
                 'account_no' => $json->account_no,
                 'ifsc_code' => $json->ifsc_code

            );

              //quert to get village data
              $sql="SELECT * FROM account WHERE user_id=?";
              $query=  $this->db->query($sql,array($json->user_id));
              //final result
              $account=$query->row();

              if ($account==null) {
                 //upadte account
                 $acct_id=$this->User_Model->insert_account($data);

                 //show response
                 if($acct_id!=null){

                     //quert to get village data
                     $sql="SELECT * FROM account WHERE id=?";
                     $query=  $this->db->query($sql,array($acct_id));
                     //final result
                     $account=$query->row();
                     //create rerspose
                     $response = array("status" => 1, "message" => "success","account"=>$account);
                     //return data in json format
                     echo json_encode($response);
                 }else{

                     //create rerspose
                     $response = array("status" => -1, "message" => "false");

                     //return data in json format
                     echo json_encode($response);
                 }
              }else {
                //create rerspose
                $response = array("status" => -3, "message" => "Account is already present");

                //return data in json format
                echo json_encode($response);
          }

         }
      }
   }


     public function editMyAccount()
     {

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

             if ($json->account_no == "" || $json->account_no == NULL) {

                 //create rerspose
                 $response = array("status" => -2, "message" => "Required field account no is missing or empty");
                 echo json_encode($response);

             }else if ($json->ifsc_code == "" || $json->ifsc_code == NULL) {

                 //create rerspose
                 $response = array("status" => -2, "message" => "Required field ifsc code is missing or empty");
                 echo json_encode($response);

             } else {

               $data = array(
                    'user_id' => $json->user_id,
                    'account_no' => $json->account_no,
                    'ifsc_code' => $json->ifsc_code

               );

                   //upadte account
                  $result=$this->User_Model->update_account($data,$json->acct_id);

                  $this->response($result);

              }
           }
        }



  public function deleteMyAccount()
  {

    //init model
    $this->load->model("User_Model");

    //get data from url
    $acct_id=  $this->uri->segment('4');

    $result=$this->User_Model->delete_account($acct_id);

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

  public function refundUser()
  {

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

          if ($json->user_id == "" || $json->user_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field user id is missing or empty");
              echo json_encode($response);

          }else if ($json->village_boy_id == "" || $json->village_boy_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field village boy id is missing or empty");
              echo json_encode($response);

          }else if ($json->event_id == "" || $json->event_id == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field event id is missing or empty");
              echo json_encode($response);

          }else if ($json->amount == "" || $json->amount == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field amount is missing or empty");
              echo json_encode($response);

          }else if ($json->transaction_no == "" || $json->transaction_no == NULL) {

              //create rerspose
              $response = array("status" => -2, "message" => "Required field amount is missing or empty");
              echo json_encode($response);

          } else {

            $data = array(
                 'user_id' => $json->user_id,
                 'village_boy_id' => $json->village_boy_id,
                 'event_id' => $json->event_id,
                 'amount' => $json->amount,
                 'transaction_no' => $json->transaction_no,
                 'refund_date' => $json->refund_date

            );

              //quert to check duplicate entry
              $sql="SELECT * FROM refund WHERE user_id=? and event_id=?";
              $query=  $this->db->query($sql,array($json->user_id,$json->event_id));
              //final result
              $refund=$query->row();

              if ($refund==null) {
                 //upadte refund
                 $refund_id=$this->User_Model->insert_refund($data);

                 //show response
                 if($refund_id!=null){

                     //quert to get village data
                     $sql="SELECT * FROM refund WHERE id=?";
                     $query=  $this->db->query($sql,array($refund_id));
                     //final result
                     $refund=$query->row();
                     //create rerspose
                     $response = array("status" => 1, "message" => "success","refund"=>$refund);
                     //return data in json format
                     echo json_encode($response);
                 }else{

                     //create rerspose
                     $response = array("status" => -1, "message" => "false");

                     //return data in json format
                     echo json_encode($response);
                 }
              }else {
                //create rerspose
                $response = array("status" => -3, "message" => "Account is already refunded");

                //return data in json format
                echo json_encode($response);
          }

         }
      }
   }

  public function response($result){
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




  ?>
