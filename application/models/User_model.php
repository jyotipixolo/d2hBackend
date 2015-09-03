<?php 
 defined('BASEPATH') OR exit('No direct script access allowed'); 
 
 class User_model extends PIXOLO_Model 
 { 

 
 	 //Write functions here 
 	public function login($contact)
 	{
 		$query = $this->db->query("SELECT * FROM `users` WHERE `contact`=$contact");
 		if ($query->num_rows() > 0)
 			{
 			 /*$query = $this->db->query("INSERT INTO `users` (`fullname`, `mobileno`) VALUES ('$fullname', '$mobileno')");
 		     $id = $this->db->insert_id();*/
 		     $query = $this->db->query("SELECT * FROM `users` WHERE `id`= $id")->row();
 		     return $query;

 			}
 		else
 		   {
             return false;
 		   }
    }  

    public function addusers($name, $contact)
    {
    	$query = $this->db->query("INSERT INTO `users` (`name`, `contact`) VALUES ('$name', '$contact')");
 		$id = $this->db->insert_id();
 		return $query;
    }
 }
 
 ?>