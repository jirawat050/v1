    <?
    require_once("base_model.php");
    class staff_model extends base_model {

        public function getSelectFieldArray(){
            $field = array();
            $field["staff_id"] = "staff.staff_id";
            $field["first_name"] = "staff.first_name";
            $field["last_name"] = "staff.last_name";
            $field["gender"] = "staff.gender";
            $field["email"] = "staff.email";
            $field["username"] = "staff.username";
            $field["mobile_phone"] = "staff.mobile_phone";
            $field["reg_status"] = "staff.reg_status";
            $field["status"] = "staff.status";
            return $field;
        }
        public function matchEmailstaff($email,$staff_id){
                $db = $this->getDb();
                $db->where('email',$email);
                $db->where('staff_id',$staff_id);
                $query = $db->get('staff');
                if($query->row()){
                    return $query->row()->email;
                }
                return false ;
        }
        public function matcheUsernamestaff($username,$staff_id){
            $db = $this->getDb();
            $db->where('username',$username);
            $db->where('staff_id',$staff_id);
            $query = $db->get('staff');
            if($query->row()){
                return $query->row()->username;
            }
            return false ;
    }
        public function checkExistsEmail($email) {
                
            if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return $email;
            }
                return false;
     }

   
        public function checkDuplicateEmail($email,$staff_id){
            
            $db = $this->getDb();
            $db->where('email',$email);
            $query = $db->get('staff');
            $count_row = $query->num_rows();
            if ($count_row >= 1) {
                $match= $this->matchEmailstaff($email,$staff_id);
                if(!$match){
                    return FALSE; // here I change TRUE to false.
                }
                return true;
            }
            return true; 
        }

        public function checkDuplicateUsername($username,$staff_id) {
            $match_username = $this->checkDuplicateEmail($username,$staff_id);
            if(!$match_username){
                return false;
            }
            else{
                $db = $this->getDb();
                $db->where('username',$username);
                $query = $db->get('staff');
                $count_row = $query->num_rows();
                if ($count_row >= 1) {
                    $match= $this->matcheUsernamestaff($username,$staff_id);
                    if(!$match){
                        return FALSE; // here I change TRUE to false.
                    }
                    return true;
                }
                return true; 
            }
        }
        public function checkValidpassword($password) {
        
        $lenTHpassword = strlen($password);
        if($lenTHpassword >=6){
            return $password;
        }
        else{
            return false;
        }
            
        }
        public function getDataById($staff_id,$extendDb=array()){

            $fieldArray = $this->getSelectFieldArray();
            $this->db = $this->getDbDetail($extendDb);
            $this->db->select(fieldArrayToSql($fieldArray));
            $this->db->from('staff');
            $this->db->where('staff.staff_id', $staff_id);
            $this->db->where('staff.status >', 0);
            $resultArray = $this->db->get()->result_array();
            if(intval(@$resultArray[0]["staff_id"])>0){
                $resultData = $this->generateMediaArray(@$resultArray[0]);
                return $this->dataDetailExtraFields($resultData);
            }
            return false;

        }
        public function dataDetailExtraFields($rowData){
            return $rowData;
        }
        public function generateMediaArray($rowData){
            $staff_id = intval(@$rowData["staff_id"]);
            return $rowData;
        }
        function checkLogin($email,$password){
            $db = $this->getDb();
            $db->where('email',$email);
            $db->where('password',$password);
            $query = $db->get('staff');
            if($query->row()){
                return $query->row()->staff_id;
            }
            return false;
        }
        public function addNew($dbData=array()){

            $status = intval(@$dbData["status"]);
            if($status<=0){
                $status = 2;
            }
            $dbData["status"] = $status;
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $this->db = $this->getDb();
            if(!$this->db->insert("staff", $dbData)){
                return false;   
            }
            return $this->db->insert_id(); 

        }
    public function onlyStaff(){
            $staff_id = $this->currentStaffId();
            if(!$staff_id){
                resBad(array("error"=>"login"),"require user login");
            }
            return $staff_id;
    }
    public function currentStaffId(){
    
            $this->load->model("access_model");
            $access_token = $this->access_model->currentAccessToken();
        
            $staff_id = $this->access_model->getEnableStaffByAccessToken($access_token);
            if(!$staff_id){
                return false;
            }
            return $staff_id;
    }
    public function getStaffByEmail($email){
        $db = $this->getDb();
        $db->where('email',$email);
        $query = $db->get('staff');
            if($query->row()){
                return $query->row()->staff_id;
            }
            return false;
    }
    public function resetPassword($staff_id,$new_password){
        $dbData = array(
            'password' => $new_password
        );

        $this->db = $this->getDb();
        $this->db->where('staff_id',$staff_id);
        $result =$this->db->update('staff', $dbData);
        return $result;
    }
    public function checkExistsUsername($username){
      
        $db = $this->getDb();
        $db->where('username',$username);
        $query = $db->get('staff');
            if($query->row()){
                return $query->row()->username;
            }
        return false;
    }
    public function checkLoginUsername($username,$password){
        $db = $this->getDb();
        $db->where('username',$username);
        $db->where('password',$password);
        $query = $db->get('staff');
        if($query->row()){
            return $query->row()->staff_id;
        }
        return false;
    }
    public function update_status($staff_id,$dbData=array()){
        $this->db = $this->getDb();
        $this->db->where('staff_id',$staff_id);
        $this->db->where('status >',0);
        $dbData["reg_status"] = 200;
        $dbData["update_time"] = time();
        $result =$this->db->update('staff', $dbData);
        return $staff_id;
    }

}

    ?>
