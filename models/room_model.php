    <?
    require_once("base_model.php");
    class room_model extends base_model {
        public function getDataByBuilding($building_id, $current_page,$per_page){
            $current_page =($current_page-1)*10;
            $db = $this->getDb();
            $db->where('building_id',$building_id);
            $db->where('status >',0);
            $db->limit($per_page,$current_page);
            $query = $db->get('room');
            if($query->row()){
                return $query->result();
            }
            return false;
        }
        public function total_page($per_page,$countRow){
            $page = $countRow/$per_page;
            $page = ceil( $page);
            return $page;
    
        }
        public function getDataById($room_id){
            $db = $this->getDb();
            $db->where('room_id',$room_id);
            $db->where('status >',0);
            $query = $db->get('room');
            if($query->row()){
                return $query->row();
            }
            return false;

        }
      
        public function search($search){
            $db = $this->getDb();
            $db->select('*');
            $db->like('room.number',$search);
            $db->or_like('room.description',$search);
            $db->or_like('category.category_name',$search);
            $db->join('category', 'room.category_id = category.category_id');
            $query = $db->get('room');
            return $query->result();
        } 
        public function getListRoom($current_page,$per_page){
            $current_page =($current_page-1)*10;
            $db = $this->getDb();
            $db->select('room.room_id,room.number,room.description,room.building_id,room.category_id,category.category_name');
            $db->limit($per_page,$current_page);
            $db->join('category', 'room.category_id = category.category_id');
            $query = $db->get('room');
            return $query->result();
        }
        public function totalRow(){
            $db = $this->getDb();
            $db->where('status >',0);
           
            $query = $db->get('room');
            
            $d = $query->num_rows();

            return $d;
        }
        public function totalRowID($building_id){
            $db = $this->getDb();
            $db->where('status >',0);
            $db->where('building_id',$building_id);
            $query = $db->get('room');
            
            $d = $query->num_rows();

            return $d;
        }
        public function addroom($dbData=array()){

            $status = intval(@$dbData["status"]);
            if($status<=0){
                $status = 1;
            }
            $dbData["status"] =  $status;
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $this->db = $this->getDb();
            if(!$this->db->insert("room", $dbData)){
                return false;   
            }
            return $this->db->insert_id(); 

        }
    public function checkIdroom($room_id){
        $db = $this->getDb();
        $db->where('room_id',$room_id);
        $db->where('status >',0);
        $query = $db->get('room');
        if($query->row()){
            return $query->row()->room_id;
        }
        return false;
        
    }
    public function updateroom($room_id,$dbData=array()){
        $this->db = $this->getDb();
        $this->db->where('room_id',$room_id);
        $this->db->where('status >',0);
        $dbData["update_time"] = time();
        $result =$this->db->update('room', $dbData);
        return $result;
    }
    public function deleteById($room_id){
        $dbData = array(
            'status' => 0
        );
        $this->db = $this->getDb();
        $this->db->where('room_id',$room_id);
        $result =$this->db->update('room', $dbData);
        return $result;
    }

    }

    ?>