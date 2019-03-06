    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class project extends base_controller {
        public function __construct(){
            parent::__construct();
            $this->load->library("pagination");
        }
        public function index(){
            $this->load->view('project/index');
        }
        public function detailproject(){
            $this->load->view('detailproject/index');
        }
        public function page(){
            $this->load->view('project/page');
        }
        
    }

    ?>
