<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");
// header('Access-Control-Allow-Origin: localhost:3000');
// header("Access-Control-Allow-Methods: GET, OPTIONS");

class staff extends base_controller {
    public function index(){
        $this->load->view('login');
    }
    public function dashboard(){
        $this->load->view('Dashboard');
    }
	public function register(){
      
		//VALIDATE
        $this->load->library('form_validation');
        $this->form_validation->onlyPost();
        $this->form_validation->set_rules('first_name','trim|required');
        $this->form_validation->set_rules('last_name','trim|required');
        $this->form_validation->set_rules('gender','trim|required');
        $this->form_validation->set_rules('email','trim|required|');
        $this->form_validation->set_rules('password','trim|required');
        $this->form_validation->set_rules('mobile_phone','trim|required');
        $this->form_validation->set_rules('status','trim|required');
        $this->form_validation->check(); 
        //RECEIVE safe_request(), safe_post();
         $first_name = safe_post("first_name");
         $last_name = safe_post("last_name");
         $gender = safe_post("gender");
         $email = safe_post("email");
         $password = safe_post("password");
         $phone = safe_post("mobile_phone");
         $status = safe_post("status");
        //business logic
        $this->load->model("staff_model");
        $email =$this->staff_model->checkExistsEmail($email);
        if($email == false){
            resBad(array(),$email);
        }
        $password =$this->staff_model->checkValidpassword($password);
        if($password == false){
            resBad(array(),$password);
        }
        $datasend = array(
			'first_name'    =>  $first_name,
			'last_name'    =>  $last_name,
			'gender'    =>  $gender,
			'password'    =>  $password,
            'email'    =>  $email,
            'mobile_phone'    =>  $phone,
            'status'    =>  $status
        );
        $status = $this->staff_model->addNew($datasend);
        if(!$status){
            resBad(array(),"cannot add CUSTOMER");
        }
        $staffData = $this->staff_model->getDataById($status);
        resGood($staffData);
    }
    public function login(){
   
        $this->load->library('form_validation');
        $this->form_validation->onlypost();
        $this->form_validation->set_rules('username','trim|required|');
        $this->form_validation->set_rules('password','trim|required');
        $this->form_validation->check(); 
        //RECEIVE
        $email = safe_post("username");
        $password = safe_post("password");
        //business logic
        $this->load->model("staff_model");
        // $check =$this->staff_model->checkExistsEmail($email);
        $check =$this->staff_model->checkExistsUsername($email);
        //resBad(array("email"=>$email,"password"=>$password,"check"=>$check));
       
        if(!$check){
            $email =$this->staff_model->checkExistsEmail($email);
            if(!$email){
                resBad(array(),"cannot email");
            }
            $staff_id = $this->staff_model->checkLogin($email,$password);
           // $staff_id = $this->staff_model->checkLoginUsername($username,$password);

        }
        else{
            $staff_id = $this->staff_model->checkLoginUsername($email,$password);
            
        }
        if(!$staff_id){
            resBad(array(),"cannot login");
        }
        $this->load->model("access_model");
        $refreshtoken = $this->access_model->createRefreshToken($staff_id);
        $access_token =  $this->access_model->createAccessToken($staff_id,$refreshtoken);
        $staffData = $this->staff_model->getDataById($staff_id);
        $staffData["refresh_token"] = $refreshtoken;
        $staffData["access_token"] =$access_token;
        resGood($staffData);

    }
    public function me(){
        ///VALIDATE
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->check(); 
        //business logic
        $this->load->model("staff_model");
        $staff_id = $this->staff_model->onlyStaff();
        if(!$staff_id){
            resBad(array(),"cannot login");
        }
        $staffData = $this->staff_model->getDataById($staff_id);
        $this->load->model("access_model");
        $access_token = $this->access_model->currentAccessToken();
      //  $staffData["access_token"]  =  $access_token;
        resGood($staffData);
    }
    public function reg_update(){
        $this->load->model("staff_model");
      

        $this->load->library('form_validation');
        $this->form_validation->onlyPost();
        $this->form_validation->set_rules('first_name','trim|required');
        $this->form_validation->set_rules('last_name','trim|required');
        $this->form_validation->set_rules('username','trim|required');
        $this->form_validation->set_rules('gender','trim|required');
        $this->form_validation->set_rules('email','trim|valid_email');
        $this->form_validation->set_rules('mobile_phone','trim|required');
        $this->form_validation->check(); 

        $first_name = safe_post("first_name");
        $last_name = safe_post("last_name");
        $username = safe_post("username");
        $gender = safe_post("gender");
        $email = safe_post("email");
        $phone = safe_post("mobile_phone");
      
        $staff_id = $this->staff_model->onlyStaff();
        $check = $this->staff_model->checkDuplicateEmail($email,$staff_id);
        if(!$check){
            resBad(array(),"email have database");
        }
        $check_user= $this->staff_model->checkDuplicateUsername($username,$staff_id);
        if(!$check_user){
            resBad(array(),"username have database");
        }
        $data = array(
            'first_name' =>  $first_name,
            'last_name' =>  $last_name,
            'gender' => $gender,
            'email' => $email,
            'username' => $username,
            'mobile_phone' =>  $phone
        );
        if(!$staff_id){
            resBad(array(),"cannot login");
        }
        
        $staff_id = $this->staff_model->update_status($staff_id,$data);
        if(!$staff_id){
            resBad(array(),"cannot update");
        }
    $staffData = $this->staff_model->getDataById($staff_id);
        resGood($staffData);
    }
    public function logout(){
           ///VALIDATE
           $this->load->library('form_validation');
           $this->form_validation->allRequest();
           $this->form_validation->check();
            //business logic
           $this->load->model("access_model");
           $refresh_token = $this->access_model->currentRefreshToken();
        //    if($refresh_token == null){
        //     resBad(array(),"cannot refresh_token");
        //    }
           $this->access_model->unSetRefresh($refresh_token);
           $this->access_model->unSetAccessByRefresh($refresh_token);
           resGood();
        }
    public function forgot_password(){
           ///VALIDATE
           $this->load->library('form_validation');
           $this->form_validation->set_rules('email','trim|required|');
           $this->form_validation->allRequest();
           $this->form_validation->check();
            //RECEIVE
           $email = safe_request("email");
           $check =$this->staff_model->checkExistsEmail($email);
           if(!$check){
            resBad(array(),"cannot email not incorrect form");
           }
           //business logic
           $this->load->model("staff_model");
           $staff_id = $this->staff_model->getStaffByEmail($email);
           if(!$staff_id){
            resBad(array(),"cannot email");
        }
           $this->load->model("claim_model");
           $claimData = $this->claim_model->createClaimData($staff_id);
           resGood($claimData);

    }
    public function reset_password(){
        //Validate
        $this->load->library('form_validation');
        $this->form_validation->onlyPost();
        $this->form_validation->set_rules('claim_token','trim|required');
        $this->form_validation->set_rules('otp','trim|required');
        $this->form_validation->set_rules('new_password','trim|required');
        $this->form_validation->check();  
        //RECEIVE
        

        $claim_token = safe_post("claim_token");
        $otp = safe_post("otp");
        $new_password = safe_post("new_password");

        //business_model
        $this->load->model("claim_model");
        $this->load->model("staff_model");
        $staff_id = $this->claim_model->getEnableStaffByClaimData($claim_token,$otp);
        if(!$staff_id){
            resBad(array(),"cannot token");
        }     
        $new_password =$this->staff_model->checkValidpassword($new_password);
        if($new_password == false){
            resBad(array(),$new_password);
        }
        $this->staff_model->resetPassword($staff_id,$new_password);
        $this->claim_model->unSetClaimToken($claim_token);
        resGood();
     }
}

?>
