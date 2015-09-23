<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 class Inquiry_model extends PIXOLO_Model 
 { 
	 public $_table = 'inquiry';   	
	  //Write functions here 
 	 public function Inquiry($vehicleid, $userid, $ip, $fromloc, $toloc)
 	 {

    $sql3 = $this->db->query("SELECT `vehicle_details`.`activestatus` AS `activestatus`,  `vehicle_details`.`v_type` AS `v_type`, `vehicle_details`.`availabilitystatus` AS `availabilitystatus`, `register`.`phone` AS `phone`, `vehicle_details`.`model` AS `model` FROM `vehicle_details` INNER JOIN `register` ON `vehicle_details`.`pid` = `register`.`id` WHERE `vehicle_details`.`id`='$vehicleid'")->row();

    if($sql3->activestatus==1 and $sql3->availabilitystatus==1)
     {
 	 	 $sql2 = $this->db->query("SELECT `name`, `contact` FROM `users` WHERE `id`='$userid'")->row();

 	 	 $query = $this->db->query("INSERT INTO `inquiry` (`vehicleid`, `name`, `mobile`, `ip`, `date`, `fromloc`, `toloc`,`userid`) VALUES ('$vehicleid', '$sql2->name', '$sql2->contact', '$ip', NOW(), '$fromloc', '$toloc','$userid')");
 	 	 $id = $this->db->insert_id();

 	 	 if($query==true)
 	 	 {
 	 		
 	 		$sql = $this->db->query("SELECT `vehicle_details`.`pid`, `register`.`firstname` FROM `vehicle_details` INNER JOIN `register` ON `register`.`id` = `vehicle_details`.`pid` WHERE `vehicle_details`.`id` = $vehicleid")->row();  		
 	 		
 	 		

 	 		$query = $this->db->query("INSERT INTO `cust_inquiry` (`inq_no`,`name`, `mobile`, `ip`, `date`, `v_name`, `v_type`, `v_phone`, `v_model`, `pid`,`userid`) VALUES ('$id', '$sql2->name', '$sql2->contact', '$ip', NOW(), '$sql->firstname', '$sql3->v_type', '$sql3->phone', '$sql3->model','$sql->pid','$userid')");

 	 		if($query==true)	
 	 		{
 	 			$query2=$this->db->query("SELECT pid FROM vehicle_details WHERE id=$vehicleid");

 	 			$query1=$this->db->query ("UPDATE `user_account` SET `user_account`.`balance` = `user_account`.`balance`-25, `user_account`.`sms_count` = `user_account`.`sms_count`+1 
								 WHERE `user_account`.`mobile` =  (SELECT `register`.`phone`                                 
                                 FROM `register`                                  
                                 INNER JOIN `vehicle_details`                         
                                 ON `vehicle_details`.`pid`= `register`.`id`
                                 WHERE `vehicle_details`.`id`= $vehicleid)" );
 	 		}
 	 	
 	 	 }
 	    return $query;
 	    
 	   }
   
     else
     {
      return false;
     };

   }


 	 public function inquiriesbydriverid($id)
 	 {
      $this->db->select('`inq_no`, `name`, `mobile`, `date`, `driveraccept`, `useraccept`, `fromloc`, `toloc`');
      $this->db->where('vehicleid', $id);
      $this->db->order_by("date", "desc"); 
      $query = $this->db->get('inquiry')->result();
   	 	return $query;
 	 }

   public function inquiriesbyvendorid($id)
   {
        $drivers = $this->db->query("SELECT `id` FROM `vehicle_details` WHERE `pid` = '$id'")->result();
        $driversarray = array();
        foreach ($drivers as $driver) {
            array_push($driversarray, $driver->id);
        };
        $this->db->select('`inq_no`, `name`, `mobile`, `date`, `driveraccept`, `useraccept`, `fromloc`, `toloc`');
        $this->db->where_in('vehicleid', $driversarray);
        $this->db->order_by("date", "desc"); 
        $query = $this->db->get('inquiry')->result();
      
        return $query;
   }

 	 public function changedriverinquirystatusbyid($id)
 	 {
 	 	$sql = "SELECT `driveraccept` FROM `inquiry` WHERE `inq_no`= '$id'";
 	 	$query = $this->db->query($sql)->row();
 	 		if($query->driveraccept == '1')
 	 	{
 	 		$value=0;

  	 	}
  	 	    else
  	 	    {
  	 	    	$value=1;
  	 	    };

  	 	    $query1=$this->db->query("UPDATE `inquiry` SET `driveraccept` = '$value' WHERE `inq_no` = '$id'");

  	 	    if($query1=true)
  	 	    {
  	 	    	return $value;
  	 	    };
 	 }

 	  public function changeuserinquirystatusbyid($id)
 	 {
 	 	$sql = "SELECT `useraccept` FROM `inquiry` WHERE `inq_no`= '$id'";
 	 	$query = $this->db->query($sql)->row();
 	 		if($query->useraccept == '1')
 	 	{
 	 		$value=0;

  	 	}
  	 	    else
  	 	    {
  	 	    	$value=1;
  	 	    };

  	 	    $query1=$this->db->query("UPDATE `inquiry` SET `useraccept` = '$value' WHERE `inq_no` = '$id'");

  	 	    if($query1=true)
  	 	    {
  	 	    	return $value;
  	 	    };
  	 }	


  	 public function checkuserstatusbyinquiryid($id)
  	 {
  	 	$sql = "SELECT `useraccept` FROM `inquiry` WHERE `inq_no`= '$id'";
 	 	$query = $this->db->query($sql)->row();
 	 		if($query->useraccept == '1')
 	 		{
 	 			return true;
 	 		}
 	 		else
 	 		{
 	 			return false;
 	 		};	 		


  	 }  

  	 public function checkdriverstatusbyinquiryid($id)
  	 {
  	 	$sql = "SELECT `driveraccept` FROM `inquiry` WHERE `inq_no`= '$id'";
 	 	$query = $this->db->query($sql)->row();
 	 		if($query->driveraccept == '1')
 	 		{
 	 			return true;
 	 		}
 	 		else
 	 		{
 	 			return false;
 	 		};	


  	 }  

  	 public function vehicleinquiry($phone)
  	 {
  	 	$query = $this->db->query("SELECT `id` FROM `vehicle_details` WHERE `sub_vendor_contact`='$phone'");
 		
 		if ($query->num_rows() > 0)
 			{
 			 $query=$query->row();

			 $query = $this->db->query("SELECT `inquiry`.`inq_no` AS `inqno`, `inquiry`.`vehicleid` AS `vehicleid`, `inquiry`.`userid` AS `userid`, `inquiry`.`name` AS `username`, `inquiry`.`mobile` AS `usermobile`, `inquiry`.`useraccept`, `inquiry`.`driveraccept`, `inquiry`.`fromloc`, `inquiry`.`toloc`, `cust_inquiry`.`v_type` AS `vehicletype`, `cust_inquiry`.`v_rto` AS `rtono` 
								
								FROM `inquiry` 
								INNER JOIN `cust_inquiry` ON `inquiry`.`inq_no`=`cust_inquiry`.`inq_no`  WHERE `inquiry`.`vehicleid` = '$query->id'")->result();
			 		     
			 		        return $query;
			 		 }
			 	else
			 	{
			 		$query = $this->db->query("SELECT `id` FROM `register` WHERE `phone`='$phone'");
			 		if ($query->num_rows() > 0)
			 		{
			 			$query = $query->row();
			 			$registervehicles = $this->db->query("SELECT `id` FROM `vehicle_details` WHERE `pid`= '$query->id'")->result();

			 			foreach ($registervehicles as $vehicle) {
			 				$inquiry = $this->db->query("SELECT `inquiry`.`inq_no` AS `inqno`, `inquiry`.`vehicleid` AS `vehicleid`, `inquiry`.`userid` AS `userid`, `inquiry`.`name` AS `username`, `inquiry`.`mobile` AS `usermobile`, `inquiry`.`useraccept`, `inquiry`.`driveraccept`, `inquiry`.`fromloc`, `inquiry`.`toloc`, `cust_inquiry`.`v_type` AS `vehicletype`, `cust_inquiry`.`v_rto` AS `rtono` 
								
								FROM `inquiry` 
								INNER JOIN `cust_inquiry` ON `inquiry`.`inq_no`=`cust_inquiry`.`inq_no`  WHERE `inquiry`.`vehicleid` = '$vehicle->id'")->result();
			 				$vehicle->inquiries = $inquiry;
			 			};
			 			return $registervehicles;

			 		};
			 		return false;

			 		
			 	};
	     
  	 }


 } 
 
 ?>