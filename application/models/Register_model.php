<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 class Register_model extends PIXOLO_Model 
 { 
	 public $_table = 'register';  
 
 	 //Write functions here
 	 public function driverlogin($phone)
 	 {
 	 	$query = $this->db->query("SELECT `id` FROM `register` WHERE `phone`=$phone");
 		
 		if ($query->num_rows() > 0)
 			{
 			 
 		     $query = $this->db->query("SELECT `id`, `firstname`, `lastname`, `phone` FROM `register` WHERE `phone`= $phone")->row();
 		     $query->type='vendor';
 		     return $query;
 			}
 		else
 		   {
	 		   	 $query = $this->db->query("SELECT `id` FROM `vehicle_details` WHERE `sub_vendor_contact`=$phone");
	 		   	 if ($query->num_rows() > 0)
	 		   	 {
		 		   	 	$query = $this->db->query("SELECT `vehicle_details`.`id`, `register`.`firstname`, `register`.`lastname`, `register`.`phone` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid`=`register`.`id` WHERE `vehicle_details`.`sub_vendor_contact`= $phone")->row();
		             	$query->type='driver'; 		     
		 		     	return $query;   	 	
	 		   	 }
	 		   	 else
	 		   	 {
	 		   	 		return false;
	 		   	 }
             
 		   	}
 	 }
 	 public function getallvendorvehicles($id){
 	 	$query = $this->db->query("SELECT `vehicle_details`.`id`, `vehicle_details`.`activestatus`, `vehicle_details`.`availabilitystatus`, `vehicle_details`.`v_type`, `vehicle_details`.`balance`, `vehicle_details`.`sms_count`, `vehicle_details`.`sub_vendor_contact` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid`=`register`.`id` WHERE `register`.`id`= '$id'")->result();     
		return $query; 
 	 } 
 } 
 
 ?>