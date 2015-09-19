<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 class Vehicle_detail_model extends PIXOLO_Model 
 { 

 
 	 //Write functions here 
 	public function positionofdriver($latitude,$longitude,$type)
 	{
 		$lat = floatval($latitude);
 		$long = floatval($longitude);
 		$lat1 = $lat-0.50;
 		$lat2 = $lat+0.50;
 		$long1 = $long-0.50;
 		$long2 = $long+0.50;
 		$sql = "SELECT * FROM `vehicle_details` WHERE (`latitude` BETWEEN $lat1 AND $lat2) AND (`longitude` BETWEEN $long1 AND $long2) AND (`v_type`= '$type')";
 		$query = $this->db->query($sql)->result();
 		return $query;
 		
 	}

 	public function vehicleinfo($latitude1,$longitude1,$latitude2,$longitude2,$type)
 	 {


 	 		 	if($latitude1>$latitude2)
		        {
		        	$lat1 = $latitude1;
		        	$lat2 = $latitude2;       	
		        }
		        else
		        {
		        	$lat1 = $latitude2;
		        	$lat2 = $latitude1; 
		        };

		        if($longitude1>$longitude2)
		        {
		        	$long1 = $longitude1;
		        	$long2 = $longitude2;
		        }
		        else
		        {
		        	$long1 = $longitude2;
		        	$long2 = $longitude1;
		        }

			
			$sql = "SELECT `register`.`id` AS `vendorid`, `register`.`firstname` AS `vendorfirstname`, `register`.`lastname` AS `vendorlastname`,`register`.`route` AS `preferedroute`, `vehicle_details`.`id` AS `vehicleid`, `register`.`phone` AS `vendorcontact`, `vehicle_details`.`sub_vendor_contact` AS `drivercontact`, `vehicle_details`.`latitude` AS `latitude`, `vehicle_details`.`longitude` AS `longitude`,";

			if($type == "tempo")
			{
				$sql = $sql. " `vehicle_tempo_make`.`vehicle_name` AS `vehiclemake`, `vehicle_tempo_model`.`vehiclemodel_name` AS `vehiclemodel`, `vehicle_tempo_model`.`image` AS `vehiclephoto`, `vehicle_details`.`trolley_length` AS `trollylength`, `vehicle_details`.`ton` AS `ton` ";
			}elseif ($type == "tourist") 
			{
				$sql = $sql. " `vehicle_tourist_make`.`vehicle_name` AS `vehiclemake`, `vehicle_tourist_model`.`vehiclemodel_name` AS `vehiclemodel`,`vehicle_tourist_model`.`image` AS `vehiclephoto`";
			};

			$sql = $sql." FROM `register`
			INNER JOIN `vehicle_details` ON `register`.`id` =`vehicle_details`.`pid` ";

			if($type == "tempo")
			{
				$sql = $sql."INNER JOIN `vehicle_tempo_model` ON `vehicle_details`.`model`=`vehicle_tempo_model`.`vehiclemodel_name` 
				INNER JOIN `vehicle_tempo_make` ON `vehicle_details`.`make`=`vehicle_tempo_make`.`id` WHERE `vehicle_tempo_model`.`vehicle_makeID` = `vehicle_details`.`make` AND";
			}elseif($type == "tourist")
			{
				$sql = $sql."INNER JOIN `vehicle_tourist_model` ON `vehicle_details`.`model`=`vehicle_tourist_model`.`vehiclemodel_name` 
				INNER JOIN `vehicle_tourist_make` ON `vehicle_details`.`make`=`vehicle_tourist_make`.`id` WHERE `vehicle_tourist_model`.`vehicle_makeID` = `vehicle_details`.`make` AND";
			};

			$sql = $sql."  `vehicle_details`.`latitude` BETWEEN '$lat2' AND '$lat1' 
			AND `vehicle_details`.`longitude` BETWEEN '$long2' AND '$long1'
			AND `vehicle_details`.`v_type`= '$type'
			AND `vehicle_details`.`activestatus`=1
			AND `vehicle_details`.`availabilitystatus`=1";
					
		        $query = $this->db->query($sql)->result();
		        	//print_r($sql);	        
			    return $query;						
 	 }


 	  public	function driverupdate($id,$latitude,$longitude)
 	  {
 	  	$query = $this->db->query("SELECT `activestatus` FROM `vehicle_details` WHERE `id`= '$id' AND `latitude` = '$latitude' AND `longitude` = '$longitude'")->row();
 	  	if(`activestatus` == 0)
 	  	{
 	  		$query = $this->db->query("UPDATE `vehicle_details` SET `activestatus` = 1 WHERE `id`= '$id'");
 	  	}

 	  	return $query;

 	  }

 	  public	function driverstatus($id,$latitude,$longitude)
 	  {
 	  	$sql = "SELECT `activestatus` FROM `vehicle_details` WHERE `id`= '$id' AND `latitude` = '$latitude' AND `longitude` = '$longitude'"; 	  	 
 	 	$query = $this->db->query($sql)->row();
 	 		
 	 		if($query->activestatus == '1')
 	  	
 	  	{
 	  		return true;
 	  		
 	  	}
 	  	else
 	  	{

 	  		return false;
 	  	};
 	  	
 	  }  

 	  	public	function driveravailabilitystatus($id,$latitude,$longitude)
 	  {
 	  	$sql = "SELECT `availabilitystatus` FROM `vehicle_details` WHERE `id`= '$id' AND `latitude` = '$latitude' AND `longitude` = '$longitude'"; 	


 	 	$query = $this->db->query($sql)->row();

 	  	if($query->availabilitystatus == '1')
 	  	{
 	  		return true;
 	  		 
 	  	}
 	  	else
 	  	{

 	  		return false;
 	  	};  	  
 	  }

 	  public function getprofile($id)
 	  {
 	  		$type = $this->db->query("SELECT `v_type` FROM `vehicle_details` WHERE `id` = '$id' ")->row();
 	  		if($type->v_type == "tourist")
 	  		{
 	  			$query = $this->db->query("SELECT `vehicle_tourist_make`.`vehicle_name` AS `vehiclemake`, `vehicle_tourist_model`.`vehiclemodel_name` AS `vehiclemodel`, `vehicle_tourist_model`.`image` AS `vehiclephoto`, `register`.`id` AS `vendorid` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid` = `register`.`id` INNER JOIN `vehicle_tourist_make` ON `vehicle_details`.`make` = `vehicle_tourist_make`.`id` INNER JOIN `vehicle_tourist_model` ON `vehicle_details`.`model`=`vehicle_tourist_model`.`vehiclemodel_name` WHERE `vehicle_tourist_model`.`vehicle_makeID` = `vehicle_details`.`make` AND `vehicle_details`.`id` = '$id'")->row();
 	  		}
 	  		if($type->v_type == "tempo")
 	  		{
				$query = $this->db->query("SELECT `vehicle_tempo_make`.`vehicle_name` AS `vehiclemake`, `vehicle_tempo_model`.`vehiclemodel_name` AS `vehiclemodel`, `vehicle_tempo_model`.`image` AS `vehiclephoto`, `register`.`id` AS `vendorid` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid` = `register`.`id` INNER JOIN `vehicle_tourist_make` ON `vehicle_details`.`make` = `vehicle_tourist_make`.`id` INNER JOIN `vehicle_tourist_model` ON `vehicle_details`.`model`=`vehicle_tourist_model`.`vehiclemodel_name` WHERE `vehicle_tourist_model`.`vehicle_makeID` = `vehicle_details`.`make` AND `vehicle_details`.`id` = '$id'")->row();
 	  		}
 	  		if($type->v_type == "rickshaw")
 	  		{}
	 	  	if($type->v_type == "taxi")
	 	  	{}
	 	  return $query;

 	  }

 	 
 } 
 
 ?>