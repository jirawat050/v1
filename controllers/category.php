<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class category extends base_controller {
        public function all(){
            $this->load->model("category_model");
            $data = $this->category_model->getDataAll();
            resGood($data);
        }
        public function search(){
            $this->load->library('form_validation');
            $this->form_validation->allRequest();
            $this->form_validation->set_rules('search','trim|required');
            $this->form_validation->set_rules('current_page','trim|required');
            $this->form_validation->set_rules('per_page','trim|required');
            $this->form_validation->check(); 
        
            //Receive 
            $search = safe_request("search");
            $current_page = safe_request("current_page");
            $per_page = safe_request("per_page");
            //business logiv
            $this->load->model("category_model");
            $list_search = (array)$this->category_model->search($search,$current_page,$per_page);
            $total = $this->category_model->totalSearch($search);
            $list_search_data['category'] = $list_search ;
            $list_search_data['limit_data'] =count( $list_search);
            $list_search_data['total_data'] =$total;
            resGood($list_search_data);
        }
        
     public function add(){
            //Validate
            $this->load->library('form_validation');
            $this->form_validation->onlyPost();
            $this->form_validation->set_rules('category_name','trim|required');
            $this->form_validation->set_rules('category_description','trim|required');
            $this->form_validation->check(); 
            //Receive
            $category_name = safe_post("category_name");
            $category_description = safe_post("category_description");
            //business logic
            $this->load->model("category_model");
            $data = array(
                'category_name' => $category_name,
                'category_description' => $category_description
            );
            $catagory_id = $this->category_model->add($data);
            if(!$catagory_id){
                resBad(array(),"cannot add categoty");
            }
            $catagory_data = $this->category_model->getDataById($catagory_id);
            resGood($catagory_data);
        }
        public function update(){
            //Validate
            $this->load->library('form_validation');
            $this->form_validation->onlyPost();
            $this->form_validation->set_rules('category_id','trim|required');
            $this->form_validation->set_rules('category_name','trim|required');
            $this->form_validation->set_rules('category_description','trim|required');
            $this->form_validation->set_rules('status','trim|required');
            $this->form_validation->check(); 
            //Receive
            $category_id = safe_post("category_id");
            $category_name = safe_post("category_name");
            $category_description = safe_post("category_description");
            $status = safe_post("status");
            //business logic
            $this->load->model("category_model");
            $category_id =$this->category_model->checkIdcategory($category_id);
            if(!$category_id){
                resBad(array(),"cannot find id in category_id");
            }
          
            $data = array(
                'category_name' => $category_name,
                'category_description' => $category_description,
                'status' => $status
            );
            $update = $this->category_model->update($category_id,$data);
            resGood($update);

        }
        public function detail(){
            
            //validate
            $this->load->library('form_validation');
            $this->form_validation->allRequest();
            $this->form_validation->set_rules('category_id','trim|required');
            $this->form_validation->check(); 
            //Receive
            $category_id = safe_request("category_id");
            $this->load->model("category_model");
            $category_id =$this->category_model->checkIdcategory($category_id);
            if(!$category_id){
                resBad(array(),"cannot find id");
            }
            $category_Data = $this->category_model->getDataById($category_id);
            resGood($category_Data);
        }
        public function delete(){
            //validate
            $this->load->library('form_validation');
            $this->form_validation->allRequest();
            $this->form_validation->set_rules('category_id','trim|required');
            $this->form_validation->check(); 
            //Receive
            $category_id = safe_request('category_id');
            $this->load->model("category_model");
            $category_id =$this->category_model->checkIdcategory($category_id);
            if(!$category_id){
                resBad(array(),"cannot find id");
            }
            $this->category_model->deleteById($category_id);
            resGood("success"); 

        }
     
  
    }

    ?>
