<?php
  
   class Upload extends CI_Controller {
	
      public function __construct() { 
         parent::__construct();
      }
		
		
      public function index() {
          
         //upload image path
         $config['upload_path']   = './uploads/'; 
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