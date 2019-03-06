    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class building extends base_controller {
        public function all(){
            // $this->load->model("staff_model");
            // $this->staff_model->onlyStaff();
            $this->load->library('form_validation');
            $this->form_validation->allRequest();
            $this->form_validation->set_rules('current_page','trim|required');
            $this->form_validation->set_rules('per_page','trim|required');
            $this->form_validation->check(); 
        
            //Receive 
            $current_page = safe_request("current_page");
            $per_page = safe_request("per_page");
            //business logic
            $this->load->model("building_model");
            $data1 =   $this->building_model->getListBuilding($current_page,$per_page);
            $countRow = $this->building_model->totalRow();
            $data['current_page'] =  $current_page;
            $data['per_page'] =  $per_page;
            $data['total_row'] =  $countRow;
            $data['result_row'] = count($data1);
            $data['building'] =  $data1;
        
            resGood($data);
        }
        
        public function detailbuilding(){
            // $this->load->model("staff_model");
            // $this->staff_model->onlyStaff();
            $this->load->view('detailbuilding/index');
        }
        public function allbyProjectID(){
            $this->load->model("staff_model");
            $this->staff_model->onlyStaff();
            $this->load->library('form_validation');
            $this->form_validation->allRequest();
            $this->form_validation->set_rules('project_id','trim|required');
        // $this->form_validation->set_rules('current_page','trim|required');
            $this->form_validation->check(); 

            $project_id = safe_request("project_id");
            $current_page = safe_request("current_page");
            $this->load->model("building_model");
            $data['building'] =   $this->building_model->getDataByProject($project_id);
            resGood($data);
        }
        public function add(){
            $this->load->model("staff_model");
            $this->staff_model->onlyStaff();
            //Validate
            $this->load->library('form_validation');
            $this->form_validation->onlyPost();
            $this->form_validation->set_rules('project_id','trim|required');
            $this->form_validation->set_rules('name','trim|required');
            $this->form_validation->set_rules('description','trim|required');
            $this->form_validation->check(); 
            //Receive
            $project_id = safe_post("project_id");
            $name = safe_post("name");
            $description = safe_post("description");
        
        
            //business logic
            $this->load->model("project_model");
            $project_id =$this->project_model->checkIdProject($project_id);
            if(!$project_id){
                resBad(array(),"cannot find id in project");
            }
        
        
            $datasend = array(
                'project_id' => $project_id,
                'name' => $name,
                'description'=> $description
            
            );
            $this->load->model("building_model");
            $building_id = $this->building_model->addBuilding($datasend);
            if(!$building_id){
                resBad(array(),"cannot add building");
            }
            $building_Data = $this->building_model->getDataById($building_id);
            resGood($building_Data);

        }
        public function update(){
            $this->load->model("staff_model");
            $this->staff_model->onlyStaff();
                //Validate
                $this->load->library('form_validation');
                $this->form_validation->onlyPost();
                $this->form_validation->set_rules('building_id','trim|required');
                $this->form_validation->set_rules('name','trim|required');
                $this->form_validation->set_rules('description','trim|required');
                $this->form_validation->check(); 
                //Receive
                $building_id = safe_post("building_id");
                $name = safe_post("name");
                $description = safe_post("description");
            
                
                //business logic
                $this->load->model("building_model");
                $building_id =$this->building_model->checkIdbuilding($building_id);
                if(!$building_id){
                    resBad(array(),"cannot find id in building");
                }
                $data = array(
                    'name' => $name,
                    'description'=> $description
                );
                $update = $this->building_model->updatebuilding($building_id,$data);
                resGood($update);

        }
    public function detail(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        //validate
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('building_id','trim|required');
        $this->form_validation->check(); 
        //Receive
        $building_id = safe_request("building_id");
        $this->load->model("building_model");
        $building_id =$this->building_model->checkIdbuilding($building_id);
        if(!$building_id){
            resBad(array(),"cannot find id");
        }
        $building_Data = $this->building_model->getDataById($building_id);
        resGood($building_Data);
    }
    public function delete(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        //validate
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('building_id','trim|required');
        $this->form_validation->check();
        $building_id = safe_request("building_id");
        $this->load->model("building_model");
        $building_id =$this->building_model->checkIdbuilding($building_id);
        if(!$building_id){
            resBad(array(),"cannot find id");
        }
        $this->building_model->deleteById($building_id);
        resGood(); 
    }
    }

    ?>
