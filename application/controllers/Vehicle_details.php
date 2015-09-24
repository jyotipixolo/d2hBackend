<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 header('Access-Control-Allow-Origin: *'); 
 
 class Vehicle_details extends PIXOLO_Controller { 
 
 	 function __construct(){ 
 	 	 parent::__construct(); 
 
 	 	 $this->load->model('Vehicle_detail_model', 'model'); 
 	 } 

 	 public function index() 
 	 { 
 	 	 $message['json']=$this->model->get_all(); 
 	 	 $this->load->view('json', $message); 
 	 }

 	 public function vehicleinfo()
 	 {
 	 	$type = $this->input->get('type');
 	 	$location1 = $this->input->get('location1'); 	 
 	 	$location2 = $this->input->get('location2'); 	 	
 	 	$location1 = json_decode($location1);
 	 	$location2 = json_decode($location2);  	 
 	 	$latitude1 = $location1->latitude;
 	 	$longitude1 = $location1->longitude;
 	 	$latitude2 = $location2->latitude;
 	 	$longitude2 = $location2->longitude;
 	 	$message['json'] = $this->model->vehicleinfo($latitude1,$longitude1,$latitude2,$longitude2,$type);
 	 	$this->load->view('json', $message);
 	 }

 	 public function changeactivestatus()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->changeactivestatus($id);
 	 	$this->load->view('json', $message);
 	 }

 	 public function changeavailibilitystatus()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->changeavailibilitystatus($id);
 	 	$this->load->view('json', $message);
 	 }

 	 public function getprofile()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->getprofile($id);
 	 	$this->load->view('json', $message);
 	 }

 	 public function getactivestatus()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->getactivestatus($id);
 	 	$this->load->view('json', $message);
 	 }

 	 public function getavailibilitystatus()
 	 {
 	 	$id = $this->input->get('id');
 	 	$message['json'] = $this->model->getavailibilitystatus($id);
 	 	$this->load->view('json', $message);
 	 }



 }