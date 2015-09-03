<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 header('Access-Control-Allow-Origin: *'); 
 
 class Users extends PIXOLO_Controller { 
 
 	 function __construct()
 	 { 
 	 	 parent::__construct(); 
 
 	 	 $this->load->model('User_model', 'model'); 
 	 } 

 	 public function index() 
 	 { 
 	 	 $message['json']=$this->model->get_all(); 
 	 	 $this->load->view('json', $message); 
 	 } 

 	 public function login()
 	 { 	    
 	 	
 	 	$contact = $this->input->get('contact');
 	 	$message['json'] = $this->model->login($contact);
 	 	$this->load->view('json', $message);
 	 }

 	 public function addusers()
 	 {
 	 	$data = $this->input->get('data'); 	 	
 	 	$data = json_decode($data); 	 
 	 	$name = $data->name;
 	 	$contact = $data->contact;
 	 	$message['json'] = $this->model->addusers($name,$contact);
 	 	$this->load->view('json', $message);

 	 }
 }