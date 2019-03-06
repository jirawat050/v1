    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class room extends base_controller {
    public function allbyBuildingID(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('building_id','trim|required');
        $this->form_validation->set_rules('current_page','trim|required');
        $this->form_validation->set_rules('per_page','trim|required');
        $this->form_validation->check(); 

        $building_id = safe_request("building_id");
        $current_page = safe_request("current_page");
        $per_page = safe_request("per_page");

        $this->load->model("room_model");
        $data['room'] =   $this->room_model->getDataByBuilding($building_id, $current_page,$per_page);
        $countRow = $this->room_model->totalRowID($building_id);
        $total_page =$this->room_model->total_page($per_page,$countRow);
        $data['current_page'] =  $current_page;
        $data['per_page'] =  $per_page;
        $data['total_row'] =  $countRow;
        $data['total_page'] =  $total_page;
        $data['result_row'] = count($data['room']);
        resGood($data);
    }
    public function search(){
        $this->load->model("staff_model");
        $this->staff_model->onlyStaff();
        $this->load->library('form_validation');
        $this->form_validation->allRequest();
        $this->form_validation->set_rules('search','trim|required');
        $this->form_validation->check(); 
    
        //Receive 
        $search = safe_request("search");
        //business logiv
        $this->load->model("room_model");
        $list_search = (array)$this->room_model->search($search);
        resGood($list_search);
    }
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
        $this->load->model("room_model");
        $data1 = (array) $this->room_model->getListRoom($current_page,$per_page);
        $countRow = $this->room_model->totalRow();
        
        
        $data['current_page'] =  $current_page;
        $data['per_page'] =  $per_page;
        $data['total_row'] =  $countRow;
        $data['result_row'] = count($data1);
        $data['room'] =  $data1;
    
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
           // Validate
            $this->load->library('form_validation');
            $this->form_validation->onlyPost();
            $this->form_validation->set_rules('building_id','trim|required');
            $this->form_validation->set_rules('number','trim|required');
            $this->form_validation->set_rules('description','trim|required');
            $this->form_validation->set_rules('category_id','trim|required');
            $this->form_validation->set_rules('floor_index','trim|required');
            $this->form_validation->set_rules('floor_name','trim|required');

            $this->form_validation->check(); 
            //Receive
            $building_id = safe_post("building_id");
            $number = safe_post("number");
            $description = safe_post("description");
            $category_id = safe_post("category_id");
            $floor_index = safe_post("floor_index");
            $floor_name = safe_post("floor_name");
        
        
            //business logic
            $this->load->model("building_model");
            $building_id =$this->building_model->checkIdBuilding($building_id);
            if(!$building_id){
                resBad(array(),"cannot find id in building");
            }
            $this->load->model("category_model");
            $category_id =$this->category_model->checkIdcategory($category_id);
            if(!$category_id){
                resBad(array(),"cannot find id in category_id");
            }
            $catagory_name =$this->category_model->Findname($category_id);
        
            $datasend = array(
                'building_id' => $building_id,
                'number' => $number,
                'description'=> $description,
                'category_id' => $category_id,
                'floor_index' => $floor_index,
                'floor_name' => $floor_name
            
            );
            $this->load->model("room_model");
            $room_id = $this->room_model->addroom($datasend);
            if(!$room_id){
                resBad(array(),"cannot add building");
            }
            $room_data = (array)$this->room_model->getDataById($room_id);
         
            $room_data['category'] = $catagory_name;
            //$data['category'] =$catagory_name;
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
                $this->form_validation->set_rules('category_id','trim|required');
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
                    'category_id' => $category_id,
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
            $room_Data = (array)$this->room_model->getDataById($room_id);
            $this->load->model("category_model");
            $category_id = $room_Data['category_id'];
            $category_id =$this->category_model->checkIdcategory($category_id);
            if(!$category_id){
                resBad(array(),"cannot find id in category_id");
            }
            $catagory_name =$this->category_model->Findname($category_id);
            $room_Data['category'] = $catagory_name;
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
