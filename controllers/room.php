<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");

class room extends base_controller {
   public function allbyBuildingID(){
    $this->load->library('form_validation');
    $this->form_validation->allRequest();
    $this->form_validation->set_rules('building_id','trim|required');
    $this->form_validation->check(); 

    $building_id = safe_request("building_id");
    $this->load->model("room_model");
    $data['room'] =   $this->room_model->getDataByBuilding($building_id);
    resGood($data);
   }
   public function detailroom(){
    $this->load->model("staff_model");
    $this->staff_model->onlyStaff();
    $this->load->view('detailroom/index');
}
    public function add(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        //Validate
        $this->load->library('form_validation');
        $this->form_validation->onlyPost();
        $this->form_validation->set_rules('building_id','trim|required');
        $this->form_validation->set_rules('number','trim|required');
        $this->form_validation->set_rules('description','trim|required');
        $this->form_validation->set_rules('floor_index','trim|required');
        $this->form_validation->set_rules('floor_name','trim|required');

        $this->form_validation->check(); 
        //Receive
        $building_id = safe_post("building_id");
        $number = safe_post("number");
        $description = safe_post("description");
        $floor_index = safe_post("floor_index");
        $floor_name = safe_post("floor_name");
      
       
        //business logic
        $this->load->model("building_model");
        $building_id =$this->building_model->checkIdBuilding($building_id);
        if(!$building_id){
            resBad(array(),"cannot find id in building");
        }
    
       
        $datasend = array(
            'building_id' => $building_id,
            'number' => $number,
            'description'=> $description,
            'floor_index' => $floor_index,
            'floor_name' => $floor_name
          
        );
        $this->load->model("room_model");
        $room_id = $this->room_model->addroom($datasend);
        if(!$room_id){
            resBad(array(),"cannot add building");
        }
        $room_data = $this->room_model->getDataById($room_id);
        resGood($room_data);

    }
    public function update(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
             //Validate
             $this->load->library('form_validation');
             $this->form_validation->onlyPost();
             $this->form_validation->set_rules('room_id','trim|required');
             $this->form_validation->set_rules('number','trim|required');
             $this->form_validation->set_rules('description','trim|required');
             $this->form_validation->set_rules('floor_index','trim|required');
             $this->form_validation->set_rules('floor_name','trim|required');
             $this->form_validation->check(); 
             //Receive
             $room_id = safe_post("room_id");
             $number = safe_post("number");
             $description = safe_post("description");
             $floor_index = safe_post("floor_index");
             $floor_name = safe_post("floor_name");
           
            
             //business logic
             $this->load->model("room_model");
             $room_id =$this->room_model->checkIdroom($room_id);
             if(!$room_id){
                 resBad(array(),"cannot find id in room_id");
             }
             $data = array(
                'number' => $number,
                'description'=> $description,
                'floor_index' => $floor_index,
                'floor_name' => $floor_name
              
            );
            $update = $this->room_model->updateroom($room_id,$data);
            resGood($update);

    }
 public function detail(){
    $this->load->model("staff_model");
    $this->staff_model->onlyStaff();
     //validate
    $this->load->library('form_validation');
    $this->form_validation->allRequest();
    $this->form_validation->set_rules('room_id','trim|required');
    $this->form_validation->check(); 
    //Receive
    $room_id = safe_request("room_id");
    $this->load->model("room_model");
    $room_id =$this->room_model->checkIdroom($room_id);
    if(!$room_id){
        resBad(array(),"cannot find id");
    }
    $room_Data = $this->room_model->getDataById($room_id);
    resGood($room_Data);
 }
 public function delete(){
    $this->load->model("staff_model");
    $this->staff_model->onlyStaff();
       //validate
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('room_id','trim|required');
        $this->form_validation->check(); 
       //Receive
        $room_id = safe_request("room_id");
    //business logic
        $this->load->model("room_model");
        $room_id =$this->room_model->checkIdroom($room_id);
        if(!$room_id){
            resBad(array(),"cannot find id");
        }
        $this->room_model->deleteById($room_id);
        resGood(); 
 }
}

?>
