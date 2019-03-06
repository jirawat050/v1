    <?
    require_once("base_model.php");
    class building_model extends base_model {


        public function getDataById($building_id){
            $db = $this->getDb();
            $db->where('building_id',$building_id);
            $db->where('status >',0);
            $query = $db->get('building');
            if($query->row()){
                return $query->row();
            }
            return false;

        }
        public function getListBuilding($current_page,$per_page){
            $current_page =($current_page-1)*10;
            $db = $this->getDb();
            $db->select('building_id, name, description,project_id');
            $db->limit($per_page,$current_page);
            $query = $db->get('building');
            return $query->result();
            
        
        }
        public function totalRow(){
            $db = $this->getDb();
            $query = $db->get('building');
            return $query->num_rows();

        }
        public function getDataByProject($project_id){
        // $current_page =($current_page-1)*10;
            $db = $this->getDb();
            $db->where('project_id',$project_id);
            $db->where('status >',0);
            // $db->limit(2,$current_page);
            $query = $db->get('building');
            if($query->row()){
                return $query->result();
            }
            return false;

        }    
        // public function getDataByProject($project_id,$current_page){
        //     $current_page =($current_page-1)*10;
        //     $db = $this->getDb();
        //     $db->where('project_id',$project_id);
        //     $db->where('status >',0);
        //     $db->limit(2,$current_page);
        //     $query = $db->get('building');
        //      if($query->row()){
        //         return $query->result();
        //     }
        //     return false;

        // }
        // public function getAllBuilding(){
        //     $db = $this->getDb();
        //     $query = $db->get('building');
        
        //         return $query->result();
            
        
        // }
        public function addBuilding($dbData=array()){

            $status = intval(@$dbData["status"]);
            if($status<=0){
                $status = 1;
            }
            $dbData["status"] =  $status;
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $this->db = $this->getDb();
            if(!$this->db->insert("building", $dbData)){
                return false;   
            }
            return $this->db->insert_id(); 

        }
    public function checkIdbuilding($building_id){
        $db = $this->getDb();
        $db->where('building_id',$building_id);
        $db->where('status >',0);
        $query = $db->get('building');
        if($query->row()){
            return $query->row()->building_id;
        }
        return false;
        
    }
    public function updatebuilding($building_id,$dbData=array()){
        $this->db = $this->getDb();
        $this->db->where('building_id',$building_id);
        $this->db->where('status >',0);
        $dbData["update_time"] = time();
        $result =$this->db->update('building', $dbData);
        return $result;
    }
    public function deleteById($building_id){
        $dbData = array(
            'status' => 0
        );
        $this->db = $this->getDb();
        $this->db->where('building_id',$building_id);
        $result =$this->db->update('building', $dbData);
        return $result;
    }

    }

    ?>