    <?
    require_once("base_model.php");
    class access_model extends base_model {

        function __construct() {
            parent::__construct();
            $this->load->library('session');
        }
        
        public function createRefreshToken($staff_id){
            
            $dbData = array();
            $dbData["token"] = $this->generateRefreshToken($staff_id);
            $dbData["user_id"] = $staff_id;
            $ci_session_id = $this->session->userdata('session_id');
            $ip = "172.47.41.10";
            $dbData["status"] = 1;
            $dbData["user_type"] = "staff";
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $dbData["ip_address"] = $ip;
            $dbData["session_id"] = $ci_session_id;
            $this->db = $this->getDb();
            if(!$this->db->insert("refresh", $dbData)){
                return false;   
            }
            $this->db->insert_id(); 
            return $dbData["token"];

        }
        
        public function generateRefreshToken($staff_id){
            $refreshToken="refresh_";
            $token = sha1($refreshToken."-".$staff_id."-".currentTimeMilisec()."-".rand(0,10000));
            return $token;
        }
        public function createAccessToken($staff_id,$refresh_token){
            
            $dbData =array();
            $dbData["token"] = $this->generateAccessToken($staff_id);
            $dbData["refresh_token"] = $refresh_token;
            $dbData["user_id"] =$staff_id;
            $ci_session_id = $this->session->userdata('session_id');
            $ip = "172.47.41.10";
            $dbData["status"] = 1;
            $dbData["user_type"] = "staff";
            $dbData["create_time"] = time();
            $dbData["update_time"] = time();
            $dbData["ip_address"] = $ip;
            $dbData["session_id"] = $ci_session_id;
            $this->db = $this->getDb();
            if(!$this->db->insert("access", $dbData)){
                return false;   
            }
            $this->db->insert_id(); 
            return $dbData['token'];

        }
        public function  generateAccessToken($staff_id){
            $accessToken="access_";
            $token = sha1($accessToken."-".$staff_id."-".currentTimeMilisec()."-".rand(0,10000));
            return $token;
        }
        public function currentAccessToken(){
        
            $access_token = safe_request("access_token");
            if($access_token==""){
                $headerData = getallheaders();
                $access_token = nTob(@$headerData["access_token"]);
            }
            if($access_token==""){
                $access_token = trim(@$_COOKIE['access_token']);
            }
            
            if($access_token==""){
                return false;
            }
    
            return $access_token;
        }
        public function getEnableStaffByAccessToken($access_token){
            $check_time = time()-30*60;
            $status=1;
            $db = $this->getDb();
            $db->where('token',$access_token);
            $db->where('status',$status);
            $db->where('create_time >',$check_time);
            $query = $db->get('access');
            if($query->row()){
                return $query->row()->user_id;
            }
            return false;
        }
        public function currentRefreshToken(){
            $refresh_token = safe_request("refresh_token");
            if($refresh_token==""){
                $headerData = getallheaders();
                $refresh_token = nTob(@$headerData["refresh_token"]);
            }
            if($refresh_token==""){
                $refresh_token = trim(@$_COOKIE['refresh_token']);
            }
            if($refresh_token==""){
                return false;
            }
            
            return $refresh_token;
        
        }
    
        
        public function unSetRefresh($refresh_token){
            $dbData = array(
                'status' => 0
            );
            $this->db = $this->getDb();
            $this->db->where('token',$refresh_token);
            $result =$this->db->update('refresh', $dbData);
            return $result;
        }

        public function unSetAccessByRefresh($refresh_token){
            $dbData = array(
                'status' => 0
            );
            $this->db = $this->getDb();
            $this->db->where('refresh_token',$refresh_token);
            $result= $this->db->update('access', $dbData);
            return $result;
            
        }


    }