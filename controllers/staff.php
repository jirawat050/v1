<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require("base_controller.php");

class staff extends base_controller {
    public function index(){
        $this->load->view('login');
    }
    public function dashboard(){
        $this->load->view('Dashboard');
    }
	
}

?>
