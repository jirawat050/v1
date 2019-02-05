<?php

require_once(root_framework_model_path()."root_model.php");
class base_model extends root_model {

	//public $db = null;

    public function get_db_config($db_name){
        return getDbConfig("paym");
    }   

	public function getDb($initDb=null){
        $db = null;
		if($initDb!=null){
            $db = clone $initDb;
        }else{
            $db = $this->load->database($this->get_db_config("paym"), true);
            $db->db_debug = FALSE;
        }
        return $db;
	}

	public function validCurPage($value){
        $value = intval($value);
        if($value<1){
            $value = 1;
        }
        return $value;
    }
    public function validPerPage($value){
        $value = intval($value);
        if($value<1){
            $value = 1;
        }
        if($value>300){
            $value = 300;
        }
        return $value;
    }

	public function getDbTotalRow($initDb=null){
        if($initDb!=null){
            $this->db = clone $initDb;
        }else{
            $this->db = $this->getDb();
        }
        return $this->db;
    }

    public function getDbLists($cur_page,$per_page,$initDb=null){

        if($initDb!=null){
            $this->db = clone $initDb;
        }else{
            $this->db = $this->getDb();
        }
        
        $cur_page = $this->validCurPage($cur_page);
        $per_page = $this->validPerPage($per_page);

        $real_curPage = ($cur_page-1);
        if($real_curPage<0){
            $real_curPage=0;   
        }
        $start_num = ($real_curPage*$per_page);

        $this->db->limit($per_page, $start_num);
        return $this->db;
    }

    public function getDbDetail($initDb=null){
        if($initDb!=null){
            $this->db = clone $initDb;
        }else{
            $this->db = $this->getDb();
        }
        $this->db->limit(1,0);
        return $this->db;
    }

    public function currentTime(){
        return time();
    }

     function getDeviceId(){
        $device_id = safe_Request("device_id","");
        if($device_id==""){
            $headerData = getallheaders();
            $device_id = nTob(@$headerData["device_id"]);
        }
        return $device_id;
    }

    function getPlatFromName(){
        $headerData = getallheaders();
        $platform_name = nTob(@$headerData["platform_name"]);
        return $platform_name;
    }

    function getDeviceType(){
        $device_type = safe_Request("device_type","");
        if($device_type==""){
            $headerData = getallheaders();
            $device_type = @$headerData["device_type"];
        }
        $device_type = strtolower($device_type);

        $result_text = $device_type;
        if($device_type=="ios"){
            $result_text = "Iphone";
        }else if($device_type=="android"){
            $result_text = "Android";
        }else if($device_type=="pc"){
            $result_text = "Pc";
        }
        return $result_text;
    }

    function getClientIP(){
        return getenv("REMOTE_ADDR");
    }

    function currentCustomerId(){
        $this->load->model("user_model");
        return $this->user_model->currentCustomerId();
    }


   

}

?>