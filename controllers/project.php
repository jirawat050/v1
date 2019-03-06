    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class project extends base_controller {
        public function __construct(){
            parent::__construct();
            $this->load->library("pagination");
        }
        public function index(){
            $this->load->view('project/index');
        }
        public function detailproject(){
            $this->load->view('detailproject/index');
        }
        public function page(){
            $this->load->view('project/page');
        }
        public function all(){
            $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
            $this->load->model("project_model");
            $data['project'] =   $this->project_model->getAllProject();
            resGood($data);
        }
    public function lists(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('current_page','trim|required');
        $this->form_validation->set_rules('per_page','trim|required');
        $this->form_validation->check(); 
        
        //Receive 
        $current_page = safe_request("current_page");
        $per_page = safe_request("per_page");
        //business logic
        $this->load->model("project_model");
        $data1 =   $this->project_model->getListProject($current_page,$per_page);
        $countRow = $this->project_model->totalRow();
        $total_page =$this->project_model->total_page($per_page,$countRow);
        $data['current_page'] =  $current_page;
        $data['per_page'] =  $per_page;
        $data['total_row'] =  $countRow;
        $data['total_page'] =  $total_page;
        $data['result_row'] = count($data1);
        $data['project'] =  $data1;
    
        resGood($data);
        }
        public function add(){
            $this->load->model("staff_model");
            $this->staff_model->onlyStaff();
            //Validate
            $this->load->library('form_validation');
            $this->form_validation->onlyPost();
            $this->form_validation->set_rules('name','trim|required');
            $this->form_validation->set_rules('description','trim|required');
            $this->form_validation->set_rules('gps_lat','trim|required');
            $this->form_validation->set_rules('gps_long','trim|required');
            $this->form_validation->check(); 
            //Receive
            $name = safe_post("name");
            $description = safe_post("description");
            $gps_lat = safe_post("gps_lat");
            $gps_long = safe_post("gps_long");
        
            //business logic
            $this->load->model("project_model");
            $gps_lat = $this->project_model->checkNumertic($gps_lat);
            if(!$gps_lat){
                resBad(array(),"cannot lat");
            }
            $gps_long = $this->project_model->checkNumertic($gps_lat);
            if(!$gps_long){
                resBad(array(),"cannot long");
            }
            $datasend = array(
                'name' => $name,
                'description'=> $description,
                'gps_lat' => $gps_lat, 
                'gps_long' => $gps_long
            );

            
            $projectID = $this->project_model->addProject($datasend);
            if(!$projectID){
                resBad(array(),"cannot add project");
            }
            $projectData = $this->project_model->getDataById($projectID);
            resGood($projectData);

        }
    public function update(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->onlyPost();
        $this->form_validation->set_rules('project_id','trim|required');
        $this->form_validation->set_rules('name','trim|required');
        $this->form_validation->set_rules('description','trim|required');
        $this->form_validation->set_rules('gps_lat','trim|required');
        $this->form_validation->set_rules('gps_long','trim|required');
        $this->form_validation->check(); 
        //Receive
        $project_id = safe_post("project_id");
        $name = safe_post("name");
        $description = safe_post("description");
        $gps_lat = safe_post("gps_lat");
        $gps_long = safe_post("gps_long");
        //business logic
        $this->load->model("project_model");
        $gps_lat = $this->project_model->checkNumertic($gps_lat);
        $gps_long = $this->project_model->checkNumertic($gps_lat);
        $project_id =$this->project_model->checkIdProject($gps_long);
        if(!$project_id){
            resBad(array(),"cannot find id");
        }
        $data = array(
            'name' => $name,
            'description'=> $description,
            'gps_lat' => $gps_lat, 
            'gps_long' => $gps_long
        );
        $update = $this->project_model->updateProject($project_id,$data);
        resGood($update);
    }
    public function detail(){

        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('project_id','trim|required');
        $this->form_validation->check(); 
        //Receive 
        $project_id = safe_request("project_id");
        //business logic
        $this->load->model("project_model");
        $project_id =$this->project_model->checkIdProject($project_id);
        if(!$project_id){
            resBad(array(),"cannot find id");
        }
        $projectData = $this->project_model->getDataById($project_id);
        resGood($projectData);

    }
    public function delete() {
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('project_id','trim|required');
        $this->form_validation->check(); 
        //Receive 
        $project_id = safe_request("project_id");
        $this->load->model("project_model");
        $project_id =$this->project_model->checkIdProject($project_id);
        if(!$project_id){
            resBad(array(),"cannot find id");
        }
        $this->project_model->deleteById($project_id);
        resGood();
    }
    }

    ?>
