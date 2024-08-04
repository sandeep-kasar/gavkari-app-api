<?php

/**
 *
 */
class VillageBoy extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function getMyAd()
  {
     //extract user id from url
     $vb_id=$this->uri->segment('3');
     //quert to get village data
     $sql="SELECT * FROM event WHERE village_boy_id=?";
     $query=  $this->db->query($sql,array($vb_id));
     //final result
     $ads=$query->result();

         //show response
         if(!empty($ads)){

             //create rerspose
             $response = array("status" => 1, "message" => "success","MyAds"=>$ads);

             //return data in json format
             echo json_encode($response);

         }else{

             //create rerspose
             $response = array("status" => -1, "message" => "No data Available !");

             //return data in json format
             echo json_encode($response);

         }
    }


    public function getMyNews()
    {
       //extract user id from url
       $vb_id=$this->uri->segment('3');
       //quert to get village data
       $sql="SELECT * FROM vb_news WHERE village_boy_id=?";
       $query=  $this->db->query($sql,array($vb_id));
       //final result
       $news=$query->result();

           //show response
           if(!empty($news)){

               //create rerspose
               $response = array("status" => 1, "message" => "success","MyNews"=>$news);

               //return data in json format
               echo json_encode($response);

           }else{

               //create rerspose
               $response = array("status" => -1, "message" => "No data Available !");

               //return data in json format
               echo json_encode($response);

           }

    }


    public function getMyPayment()
    {
       //extract user id from url
       $vb_id=$this->uri->segment('3');
       //quert to get village data
       $sql="SELECT account_debit.*,user.name,event.title,event.description

       FROM account_debit
       INNER JOIN event
       INNER JOIN user
       WHERE account_debit.village_boy_id=event.village_boy_id=?
       AND account_debit.user_id=user.id=?";
       $query=  $this->db->query($sql,array($vb_id,$vb_id));
       //final result
       $payment=$query->result();

           //show response
           if(!empty($payment)){

               //create rerspose
               $response = array("status" => 1, "message" => "success","MyPayment"=>$payment);

               //return data in json format
               echo json_encode($response);

           }else{

               //create rerspose
               $response = array("status" => -1, "message" => "No data Available !");

               //return data in json format
               echo json_encode($response);

           }

    }


    public function getAccountSummury()
    {
       //extract user id from url
       $vb_id=$this->uri->segment('4');
       //query to get adCount, how many ads created by vb
       $sql="SELECT count(id) AS adCount FROM event  WHERE village_boy_id=?";
       $query=  $this->db->query($sql,array($vb_id));
       //final result
       $adsCount=$query->row();

       //query to get totalAmount debited from vb account
       $sql="SELECT sum(amount) as totalAmount FROM account_debit  WHERE village_boy_id=? AND status=1";
       $query=  $this->db->query($sql,array($vb_id));
       //final result
       $dA=$query->row()->totalAmount;
       $debitAmount=($dA*25)/100;

       //query to get totalAmount credited in vb account
       $sql="SELECT sum(amount) as totalAmount FROM vb_credit WHERE village_boy_id=?";
       $query=  $this->db->query($sql,array($vb_id));
       //final result
       $creditedAmount=$query->row()->totalAmount;
       $balance=$debitAmount-$creditedAmount;

           //show response
           if(!empty($adsCount)){

               //create rerspose
               $response = array("status" => 1, "message" => "success",
               "adsCount"=>$adsCount->adCount,
                "payableAmount"=>$debitAmount,
                "paid"=>$creditedAmount,
                "balance"=>$balance);

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
