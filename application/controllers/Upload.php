<?php

class Upload extends CI_Controller
{

  function __construct() {
       parent::__construct();
       $this->load->database();
    }

  public function uploadEventPhoto()
  {

      //upload image path
      $config['upload_path']   = './event/';
      $config['allowed_types'] = 'gif|jpg|png';

      //load config lib
      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload('userfile')) {
         $error = array('status' => $this->upload->display_errors());
         echo json_encode($error);
      }else {
         $data = array('status' => $this->upload->data('file_name'));
         echo json_encode($data);
      }

  }

  public function uploadNewsPhoto()
  {

      //upload image path
      $config['upload_path']   = './news/';
      $config['allowed_types'] = 'gif|jpg|png';

      //load config lib
      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload('userfile')) {
         $error = array('status' => $this->upload->display_errors());
         echo json_encode($error);
      }else {
         $data = array('status' => $this->upload->data('file_name'));
         echo json_encode($data);
      }

  }

  public function uploadUserPhoto()
  {

      //upload image path
      $config['upload_path']   = './avatar/';
      $config['allowed_types'] = 'gif|jpg|png';

      //load config lib
      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload('userfile')) {
         $error = array('status' => $this->upload->display_errors());
         echo json_encode($error);
      }else {
         $data = array('status' => $this->upload->data('file_name'));
         echo json_encode($data);
      }

  }


}



 ?>
