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
		 		   	 	$query = $this->db->query("SELECT `vehicle_details`.`id`,`vehicle_details`.`pid`, `register`.`firstname`, `register`.`lastname`, `vehicle_details`.`sub_vendor_contact` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid`=`register`.`id` WHERE `vehicle_details`.`sub_vendor_contact`= $phone")->row();
		             	$query->type='driver'; 		     
		 		     	return $query;   	 	
	 		   	 }
	 		   	 else
	 		   	 {
	 		   	 		return false;
	 		   	 }
             
 		   	}
 	 }

 	 public function getaccount($phone)
 	 {
	 	 	$query = $this->db->query("SELECT `balance`, `sms_count` AS `smscount` FROM `user_account` WHERE `mobile` = '$phone'")->row();
	 	 	return $query;
 	 }

 	 public function getvendorprofile($id)
 	 {
 	 	$drivers = $this->db->query("SELECT `id` FROM `vehicle_details` WHERE `pid` = '$id'")->result();
        $driversarray = array();
        foreach ($drivers as $driver) {
            array_push($driversarray, $driver->id);
        };
 	 	$this->db->select('`register`.`type` AS `type`, `register`.`vehicle_count` AS `vehiclecount`,COUNT(*) AS `inquiries`');
 	 	$this->db->where_in('`inquiry`.`vehicleid`', $driversarray);
 	 	$this->db->from('inquiry');
 	 	$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid');
 	 	$this->db->join('register', 'vehicle_details.pid = register.id');
        $query = $this->db->get()->row();
        return $query;

 	 }

 	 public function getdriverprofile($id)
 	 {
 	 		$type = $this->db->query("SELECT `v_type`, `make` FROM `vehicle_details` WHERE `id`='$id'")->row();

 	 		if($type->v_type == "tempo")
 	 		{
 	 			$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_tempo_make`.`id` AS `makeid`, `vehicle_tempo_make`.`vehicle_name` AS `make`, `vehicle_tempo_model`.`vehiclemodel_name` AS `model`, `vehicle_tempo_model`.`image` AS `image`, COUNT(*) AS `inquiries`');
 	 			$array = array('`inquiry`.`vehicleid`' => $id, '`vehicle_tempo_model`.`vehicle_makeID`' => $type->make);
 	 			$this->db->where($array);
 	 		};
 	 		if($type->v_type == "tourist")
 	 		{
 	 			$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_tourist_make`.`id` AS `makeid`, `vehicle_tourist_make`.`vehicle_name` AS `make`, `vehicle_tourist_model`.`vehiclemodel_name` AS `model`, `vehicle_tourist_model`.`image` AS `image`, COUNT(*) AS `inquiries`');
 	 			$array = array('`inquiry`.`vehicleid`' => $id, '`vehicle_tourist_model`.`vehicle_makeID`' => $type->make);
 	 			$this->db->where($array);
 	 		};
 	 		if($type->v_type == "rickshaw")
 	 		{
 	 			$this->db->select('`vehicle_details`.`v_type`, COUNT(*) AS `inquiries`');
 	 			$this->db->where('`inquiry`.`vehicleid`',$id);
 	 		};
 	 		if($type->v_type == "local taxi")
 	 		{
 	 			$this->db->select('`vehicle_details`.`v_type`, COUNT(*) AS `inquiries`');
 	 			$this->db->where('`inquiry`.`vehicleid`',$id);
 	 		};

	 	 	
	 	 	$this->db->from('inquiry');
	 	 	$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid', 'inner');

	 	 	if($type->v_type == "tempo")
	 	 	{
	 	 		$this->db->join('vehicle_tempo_make', 'vehicle_details.make = vehicle_tempo_make.id', 'inner');
	 	 		$this->db->join('vehicle_tempo_model', 'vehicle_details.model = vehicle_tempo_model.vehiclemodel_name', 'inner');
	 	 	};
	 	 	if($type->v_type == "tourist")
	 	 	{
	 	 		$this->db->join('vehicle_tourist_make', 'vehicle_details.make = vehicle_tourist_make.id', 'inner');
	 	 		$this->db->join('vehicle_tourist_model', 'vehicle_details.model = vehicle_tourist_model.vehiclemodel_name', 'inner');
	 	 	};

	        $query = $this->db->get()->row();

	        return $query;

 	 }

 	 public function getallvendorvehicles($id){

		$type = $this->db->query("SELECT `type` FROM `register` WHERE `id` = '$id'")->row();
		$drivers = $this->db->query("SELECT `id`,`make` FROM `vehicle_details` WHERE `pid` = '$id'")->result();
		$driversarray = array();

		if($type->type == "tourist operator")
			{
				foreach ($drivers as $driver) {
				
					$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_details`.`activestatus` AS `activestatus`, `vehicle_details`.`availabilitystatus` AS `availabilitystatus`, `vehicle_tourist_make`.`id` AS `makeid`, `vehicle_tourist_make`.`vehicle_name` AS `make`, `vehicle_tourist_model`.`vehiclemodel_name` AS `model`, `vehicle_tourist_model`.`image` AS `image`, COUNT(*) AS `inquiries`');
 	 				$array = array('`inquiry`.`vehicleid`' => $driver->id, '`vehicle_tourist_model`.`vehicle_makeID`' => $driver->make);
 	 				$this->db->where($array);
 	 				$this->db->from('inquiry');
	 	 			$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid', 'inner');
 	 				$this->db->join('vehicle_tourist_make', 'vehicle_details.make = vehicle_tourist_make.id', 'inner');
	 	 			$this->db->join('vehicle_tourist_model', 'vehicle_details.model = vehicle_tourist_model.vehiclemodel_name', 'inner');
	 	 			 $query = $this->db->get()->row();
	 	 			 array_push($driversarray, $query);
 	 			}
			};

		if($type->type == "transporter")
			{
				foreach ($drivers as $driver) {
				
					$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_details`.`activestatus` AS `activestatus`, `vehicle_details`.`availabilitystatus` AS `availabilitystatus`, `vehicle_tempo_make`.`id` AS `makeid`, `vehicle_tempo_make`.`vehicle_name` AS `make`, `vehicle_tempo_model`.`vehiclemodel_name` AS `model`, `vehicle_tempo_model`.`image` AS `image`, COUNT(*) AS `inquiries`');

 	 				$array = array('`inquiry`.`vehicleid`' => $driver->id, '`vehicle_tempo_model`.`vehicle_makeID`' => $driver->make);
 	 				$this->db->where($array);

 	 				$this->db->from('inquiry');

	 	 			$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid', 'inner');
 	 				$this->db->join('vehicle_tempo_make', 'vehicle_details.make = vehicle_tempo_make.id', 'inner');
	 	 			$this->db->join('vehicle_tempo_model', 'vehicle_details.model = vehicle_tempo_model.vehiclemodel_name', 'inner');

	 	 			$query = $this->db->get()->row();
	 	 			array_push($driversarray, $query);
 	 			}
			};

		if($type->type == "rickshaw")
			{
				foreach ($drivers as $driver) {
				
					$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_details`.`activestatus` AS `activestatus`, `vehicle_details`.`availabilitystatus` AS `availabilitystatus`, COUNT(*) AS `inquiries`');
 	 				$array = array('`inquiry`.`vehicleid`' => $driver->id);
 	 				$this->db->where($array);
 	 				$this->db->from('inquiry');
	 	 			$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid', 'inner');
	 	 			 $query = $this->db->get()->row();
	 	 			 array_push($driversarray, $query);
 	 			}
			};

		if($type->type == "local taxi")
			{
				foreach ($drivers as $driver) {
					$this->db->select('`vehicle_details`.`v_type` AS `vtype`,`vehicle_details`.`activestatus` AS `activestatus`, `vehicle_details`.`availabilitystatus` AS `availabilitystatus`, COUNT(*) AS `inquiries`');
 	 				$array = array('`inquiry`.`vehicleid`' => $driver->id);
 	 				$this->db->where($array);
 	 				$this->db->from('inquiry');
	 	 			$this->db->join('vehicle_details', 'vehicle_details.id = inquiry.vehicleid', 'inner');
	 	 			$query = $this->db->get()->row();
	 	 			array_push($driversarray, $query);
 	 			}
			};

	 	 	return $driversarray;
 	 } 

			

			
 } 
 
 ?>