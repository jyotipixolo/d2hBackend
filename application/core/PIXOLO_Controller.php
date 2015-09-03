<?php

class PIXOLO_Controller extends CI_Controller {
    
    public $data = array();
    
    function __construct(){
        parent::__construct();
    }

    public function insert()
	{
		$data = $this->input->get('data');
		$message['json']=$this->model->insert(json_decode($data));
		$this->load->view('json', $message);
	}

	public function getall()
	{
		$message['json']=$this->model->get_all();
		$this->load->view('json', $message);
	}

	public function getbyid()
	{
		$id = $this->input->get('id');
		$message['json']=$this->model->get($id);
		$this->load->view('json', $message);
	}

	public function getoneby()
	{
		$field = $this->input->get('field');
		$value = $this->input->get('value');
		$message['json']=$this->model->get_by($field, $value);
		$this->load->view('json', $message);
	}

	public function getmanyby()
	{
		$field = $this->input->get('field');
		$value = $this->input->get('value');
		$message['json']=$this->model->get_many_by($field, $value);
		$this->load->view('json', $message);
	}

	public function getmanybytwo(){
		$field1 = $this->input->get('field1');
		$value1 = $this->input->get('value1');
		$field2 = $this->input->get('field2');
		$value2 = $this->input->get('value2');
		$message['json']=$this->model->get_many_by_two($field1, $value1, $field2, $value2);
		$this->load->view('json', $message);
	}

	public function getmanybythree(){
		$field1 = $this->input->get('field1');
		$value1 = $this->input->get('value1');
		$field2 = $this->input->get('field2');
		$value2 = $this->input->get('value2');
		$field3 = $this->input->get('field3');
		$value3 = $this->input->get('value3');
		$message['json']=$this->model->get_many_by_three($field1, $value1, $field2, $value2, $field3, $value3);
		$this->load->view('json', $message);
	}

	public function updatebyid()
	{
		$id = $this->input->get('id');
		$data = $this->input->get('data');
		$message['json']=$this->model->update($id, json_decode($data));
		$this->load->view('json', $message);
	}


	public function updatemanyby()
	{
		$field = $this->input->get('field');
		$value = $this->input->get('value');
		$data = $this->input->get('data');
		$message['json']=$this->model->update_by($field, $value, json_decode($data));
		$this->load->view('json', $message);
	}

	public function deletebyid()
	{
		$id = $this->input->get('id');
		$message['json']=$this->model->delete($id);
		$this->load->view('json', $message);
	}

	public function deletemanyby()
	{
		$field = $this->input->get('field');
		$value = $this->input->get('value');
		$message['json']=$this->model->delete_by($field, $value);
		$this->load->view('json', $message);
	}
}

