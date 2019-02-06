<?
require_once("base_model.php");
class customer_model extends base_model {
public $currentCustomerId=null;
    public function getSelectFieldArray(){
		$field = array();
		$field["customer_id"] = "customer.customer_id";
        $field["first_name"] = "customer.first_name";
        $field["last_name"] = "customer.last_name";
        $field["gender"] = "customer.gender";
      //  $field["password"] = "customer.password";
        $field["email"] = "customer.email";
        $field["phone"] = "customer.phone";
        $field["status"] = "customer.status";
  
        return $field;
	}
    public function checkExistsEmail($email) {
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return $email;
          } 
          else{
            return false;
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
    public function getDataById($customer_id,$extendDb=array()){

		$fieldArray = $this->getSelectFieldArray();
        $this->db = $this->getDbDetail($extendDb);
        $this->db->select(fieldArrayToSql($fieldArray));
        $this->db->from('customer');
        $this->db->where('customer.customer_id', $customer_id);
        $this->db->where('customer.status >', 0);
        $resultArray = $this->db->get()->result_array();
        if(intval(@$resultArray[0]["customer_id"])>0){
        	$resultData = $this->generateMediaArray(@$resultArray[0]);
            return $this->dataDetailExtraFields($resultData);
        }
        return false;

    }
    public function dataDetailExtraFields($rowData){

        // $product_id = intval(@$rowData["product_id"]);
        // $media_json = @$rowData["media_json"];
        // $mediaArray = jsonToArray($media_json);

        // $rowData["product_media_array"] = $mediaArray;
        // unset($rowData["media_json"]);

        return $rowData;
    }
    public function generateMediaArray($rowData){

        $customer_id = intval(@$rowData["customer_id"]);
        return $rowData;
    }
    function checkLogin($email,$password){
        $db = $this->getDb();
        $db->where('email',$email);
        $db->where('password',$password);
        $query = $db->get('customer');
        if($query->row()){
            return $query->row()->customer_id;
        }
        return false;
    }
    public function addNew($dbData=array()){

        $status = intval(@$dbData["status"]);
        if($status<=0){
			$status = 2;
		}
        $dbData["status"] = $status;
        // $this->load->helper('date');
        $dbData["create_time"] = time();
        $dbData["upload_time"] = time();
      

        $this->db = $this->getDb();
   
        if(!$this->db->insert("customer", $dbData)){
            return false;   
        }
        return $this->db->insert_id(); 

    }
   public function onlyCustomer(){
    $customer_id = $this->currentCustomerId();
    if(!$customer_id){
        resBad(array("error"=>"login"),"require user login");
    }
    return $customer_id;
   }
   public function currentCustomerId(){
        $this->load->model("access_model");
        $access_token = $this->access_model->currentAccessToken();
     
        $customer_id = $this->access_model->getEnableCustomerByAccessToken($access_token);
        if(!$customer_id){
        	return false;
        }
        return $customer_id;
   }
}

?>
