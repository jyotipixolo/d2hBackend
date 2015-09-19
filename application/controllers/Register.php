<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 header('Access-Control-Allow-Origin: *'); 
 
 class Register extends PIXOLO_Controller { 
 
 	 function __construct(){ 
 	 	 parent::__construct(); 
 
 	 	 $this->load->model('Register_model', 'model'); 
 	 } 

 	 public function index() 
 	 { 
 	 	 $message['json']=$this->model->get_all(); 
 	 	 $this->load->view('json', $message); 
 	 } 

 	 public function driverlogin()
 	 {
 	 	$phone = $this->input->get('phone');
 	 	$message['json'] = $this->model->driverlogin($phone);
 	 	$this->load->view('json', $message); 
 	 }

 	 public function getallvendorvehicles()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->getallvendorvehicles($id);
 	 	$this->load->view('json', $message);
 	 }

 	
 	 }