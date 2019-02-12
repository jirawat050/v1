<?
require_once("base_model.php");
class claim_model extends base_model {

	function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
    public function createClaimData($customer_id){
       
        $str = '1234567890';
       $shuffled = str_shuffle($str);
    $dbData =array();
    $dbData["token"] = $this->generateToken($customer_id);
    $dbData["otp"] = substr($shuffled ,0,6);
    $dbData["user_id"] =$customer_id;
    $ci_session_id = $this->session->userdata('session_id');
    $ip = "172.47.41.10";
    $dbData["status"] = 1;
    $dbData["user_type"] = "customer";
    $dbData["create_time"] = time();
    $dbData["update_time"] = time();
    $dbData["ip_address"] = $ip;
    $dbData["session_id"] = $ci_session_id;
        $this->db = $this->getDb();
        if(!$this->db->insert("claim", $dbData)){
            return false;   
        }
        $this->db->insert_id(); 
        return  $dbData;
    }
 public function generateToken($customer_id){
    $eToken="claim_";
    $token = sha1($eToken."-".$customer_id."-".currentTimeMilisec()."-".rand(0,10000));
    return $token;
 }
 public function getEnableCustomerByClaimData($claim_token,$otp){
    $db = $this->getDb();
    $db->where('token',$claim_token);
    $db->where('otp',$otp);
    $status=1;
    $db->where('status',$status);
    // $check_time = time()-1;
    // $db->where('create_time <',$check_time);
    $query = $db->get('claim');
    if($query->row()){
        return $query->row()->user_id;
    }
    return false;
 }
 public function unSetClaimToken($claim_token){
    $dbData = array(
        'status' => 0
    );

    $this->db = $this->getDb();
    $this->db->where('token',$claim_token);
    $result= $this->db->update('claim', $dbData);
    return $result;
 }

}
