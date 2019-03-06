<?
    require_once("base_model.php");
    class category_model extends base_model {
       
        
        public function getDataById($category_id){
            $db = $this->getDb();
            $db->where('category_id',$category_id);
            $db->where('status >',0);
            $query = $db->get('category');
            if($query->row()){
                return $query->row();
            }
            return false;

        }
        public function search($search,$current_page,$per_page){
            $current_page =($current_page-1)*10;
            $db = $this->getDb();
            $db->select('category.category_id');
            $db->select('category.category_name');
            $db->select('category.category_description');
            $db->limit($per_page,$current_page);
            $db->like('category.category_name',$search);
            $db->or_like('category.category_description',$search);
            $query = $db->get('category');
            return $query->result();
        }
        public function totalSearch($search){
            $db = $this->getDb();
            $db->select('category.category_id');
            $db->select('category.category_name');
            $db->select('category.category_description');
            $db->like('category.category_name',$search);
            $db->or_like('category.category_description',$search);
            $query = $db->get('category');
            return $query->num_rows();
        } 
        public function getDataAll(){
            $db = $this->getDb();
            $query = $db->get('category');
            return $query->result();
            
        }
        
        public function add($dbData=array()){

            $status = intval(@$dbData["status"]);
            if($status<=0){
                $status = 1;
            }
            $dbData["status"] =  $status;
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $this->db = $this->getDb();
            if(!$this->db->insert("category", $dbData)){
                return false;   
            }
            return $this->db->insert_id(); 

        }
        public function checkIdcategory($category_id){
            $db = $this->getDb();
            $db->where('category_id',$category_id);
            $db->where('status >',0);
            $query = $db->get('category');
            if($query->row()){
                return $query->row()->category_id;
            }
            return false;
            
        }
        public function update($category_id,$dbData=array()){
            $this->db = $this->getDb();
            $this->db->where('category_id',$category_id);
            $this->db->where('status >',0);
            $dbData["update_time"] = time();
            $result =$this->db->update('category', $dbData);
            return $result;
        }
        public function deleteById($category_id){
            $dbData = array(
                'status' => 0
            );
            $this->db = $this->getDb();
            $this->db->where('category_id',$category_id);
            $result =$this->db->update('category', $dbData);
            return $result;
        }
        public function Findname($category_id){
            $db = $this->getDb();
            $db->where('category_id',$category_id);
            $db->where('status >',0);
            $query = $db->get('category');
            if($query->row()){
                return $query->row()->category_name;
            }
            return false;
        }
    }

    ?>