<?
require_once("base_model.php");
class access_model extends base_model {

	function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
    public function createRefreshToken($user_id){
        $dbData = array();
        
        $dbData["token"] = $this->generateRefreshToken($user_id);
        
        $dbData["user_id"] = $user_id;
        
        $ci_session_id = $this->session->userdata('session_id');
        
       
        $ip = "172.47.41.10";
        $dbData["status"] = 1;
        $dbData["user_type"] = "customer";
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
    
    public function generateRefreshToken($refreshToken="refresh_",$user_id){
    
        $token = sha1($refreshToken."-".$user_id."-".currentTimeMilisec()."-".rand(0,10000));
        return $token;
    }
    public function createAccessToken($user_id,$refresh_token){
        $dbData =array();
        $dbData["token"] = $this->generateAccessToken($user_id);
        $dbData["refresh_token"] = $refresh_token;
       
       $dbData["user_id"] =$user_id;
        $ci_session_id = $this->session->userdata('session_id');
        $ip = "172.47.41.10";
        $dbData["status"] = 1;
        $dbData["user_type"] = "customer";
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

    public function  generateAccessToken($accessToken="access_",$user_id){
        $token = sha1($accessToken."-".$user_id."-".currentTimeMilisec()."-".rand(0,10000));
        return $token;
    }
}