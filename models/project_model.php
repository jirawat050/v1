    <?
    require_once("base_model.php");
    class project_model extends base_model {


    
        public function getDataById($project_id){
            $db = $this->getDb();
            $db->where('project_id',$project_id);
            $db->where('status >',0);
            $query = $db->get('project');
            if($query->row()){
                return $query->row();
            }
            return false;

        }
        public function getAllProject(){
            $db = $this->getDb();
            $db->select('project_id, name, description');
            $query = $db->get('project');
        
                return $query->result();
            
        
        }
        public function getListProject($current_page,$per_page){
            $current_page =($current_page-1)*$per_page;
        // $selectColum = $current_page+$per_page;
            $db = $this->getDb();
            $db->select('project_id, name, description');
            $db->limit($per_page,$current_page);
            
            $query = $db->get('project');
                return $query->result();
            
        
        }
        public function totalRow(){
            $db = $this->getDb();
            $query = $db->get('project');
                return $query->num_rows();

        }
        public function dataDetailExtraFields($rowData){
            return $rowData;
        }
        public function generateMediaArray($rowData){
            $staff_id = intval(@$rowData["project_id"]);
            return $rowData;
        }
    
        public function addProject($dbData=array()){

            $status = intval(@$dbData["status"]);
            if($status<=0){
                $status = 1;
            }
            $dbData["status"] =  $status;
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $this->db = $this->getDb();
            if(!$this->db->insert("project", $dbData)){
                return false;   
            }
            return $this->db->insert_id(); 

        }
    public function checkIdProject($project_id){
        $db = $this->getDb();
        $db->where('project_id',$project_id);
        $db->where('status >',0);
        $query = $db->get('project');
        if($query->row()){
            return $query->row()->project_id;
        }
        return false;
        
    }
    public function updateProject($project_id,$dbData=array()){
        $this->db = $this->getDb();
        $this->db->where('project_id',$project_id);
        $this->db->where('status >',0);
        $dbData["update_time"] = time();
        $result =$this->db->update('project', $dbData);
        return $result;
    }
    public function deleteById($project_id){
        $dbData = array(
            'status' => 0
        );
        $this->db = $this->getDb();
        $this->db->where('project_id',$project_id);
        $result =$this->db->update('project', $dbData);
        return $result;
    }
    public function   checkNumertic($num){
        if (preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/',$num)) {
            return  $num;
        }
        return false;
    }
    public function total_page($per_page,$countRow){
        $page = $countRow/$per_page;
        $page = ceil( $page);
        return $page;

    }
    }

    ?>