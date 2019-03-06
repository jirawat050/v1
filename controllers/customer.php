<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");

class customer extends base_controller {
public function index(){

}
public function list(){
    $this->load->library('form_validation');
    $this->form_validation->allRequest();
    $this->form_validation->set_rules('current_page','trim|required');
    $this->form_validation->check(); 

    //Receive 
    $current_page = safe_request("current_page");
    
    //business logic
    $this->load->model("customer_model");
    $data1 =   $this->customer_model->getListCustomer($current_page);
    $countRow = $this->customer_model->totalRow();
    $data['current_page'] =  $current_page;
    $data['per_page'] =  $per_page;
    $data['total_customer'] =  $countRow;
    $data['result_row'] = count($data1);
    $data['project'] =  $data1;
  
    resGood($data);
    }
  
    
   
}

?>
