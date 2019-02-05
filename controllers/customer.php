<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");

class customer extends base_controller {

	public function register(){
		
		//VALIDATE
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('first_name','trim|required');
        $this->form_validation->set_rules('last_name','trim|required');
        $this->form_validation->set_rules('gender','trim|required');
        $this->form_validation->set_rules('email','trim|required|');
        $this->form_validation->set_rules('password','trim|required');
        $this->form_validation->set_rules('phone','trim|required');
        $this->form_validation->set_rules('status','trim|required');
        $this->form_validation->check(); 

        //RECEIVE safe_request(), safe_post();
         $first_name = safe_request("first_name");
         $last_name = safe_request("last_name");
         $gender = safe_request("gender");
         $email = safe_request("email");
         $password = safe_request("password");
         $phone = safe_request("phone");
         $status = safe_request("status");
        //business logic
        $this->load->model("customer_model");
        $email =$this->customer_model->checkExistsEmail($email);
        if($email == false){
            resBad(array(),$email);
        }
        
        $password =$this->customer_model->checkValidpassword($password);
        if($password == false){
            resBad(array(),$password);
        }
      
        $datasend = array(
			'first_name'    =>  $first_name,
			'last_name'    =>  $last_name,
			'gender'    =>  $gender,
			'password'    =>  $password,
            'email'    =>  $email,
            'phone'    =>  $phone,
            'status'    =>  $status

        );
      
       
        $status = $this->customer_model->addNew($datasend);
        if(!$status){
            resBad(array(),"cannot add CUSTOMER");
        }
        $customerData = $this->customer_model->getDataById($status);
        resGood($customerData);
    }
    public function login(){
        ///VALIDATE
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('email','trim|required|');
        $this->form_validation->set_rules('password','trim|required');
        $this->form_validation->check(); 
        //RECEIVE
        $email = safe_request("email");
        $password = safe_request("password");
        //business logic
        
        $this->load->model("customer_model");
        $email =$this->customer_model->checkExistsEmail($email);
        if($email == false){
            resBad(array(),$email);
        }
        $customer_id = $this->customer_model->checkLogin($email,$password);
        if(!$customer_id){
            resBad(array(),"cannot login");
        }
        $this->load->model("access_model");
      
        $refreshtoken = $this->access_model->createRefreshToken($customer_id);
        $access_token =  $this->access_model->createAccessToken($customer_id,$refreshtoken);
        $customerData = $this->customer_model->getDataById($customer_id);
        $customerData["refresh_token"] = $refreshtoken;
        $customerData["access_token"] =$access_token;
        resGood($customerData);

    }


}

?>
