<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(root_framework_controller_path()."root_controller.php");
class base_controller extends root_controller {


	public $currentStaffData = null;

	function __construct() {
        parent::__construct();

       	//$package_path = base_APPPATH()."api/v1/application/";
        //$this->load->add_package_path($package_path);
    }


	

	protected function currentStaffData(){
		$this->load->model("user_model");
		return $this->user_model->currentStaffData();
	}

	protected function currentStaffId(){
		
		$this->load->model("user_model");
		return $this->user_model->currentStaffId();
	}

	protected function onlyStaff(){
		$this->load->model("user_model");
		return $this->user_model->onlyStaff();
	}

	protected function onlyStaffId(){
	
		$this->load->model("user_model");
		return $this->user_model->onlyStaffId();
	}

	public function getDb(){
		$this->load->model("base_model");
		return $this->base_model->getDb();
	}

	

	protected function currentCustomerData(){
		$this->load->model("user_model");
		return $this->user_model->currentCustomerData();
	}

	protected function currentCustomerId(){
		
		$this->load->model("user_model");
		return $this->user_model->currentCustomerId();
	}

	protected function onlyCustomer(){
		$this->load->model("user_model");
		return $this->user_model->onlyCustomer();
	}

	protected function onlyCustomerId(){
	
		$this->load->model("user_model");
		return $this->user_model->onlyCustomerId();
	}


	

	


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */