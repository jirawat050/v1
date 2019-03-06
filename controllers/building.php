    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class building extends base_controller {
        
        
        public function detailbuilding(){
            // $this->load->model("staff_model");
            // $this->staff_model->onlyStaff();
            $this->load->view('detailbuilding/index');
        }
        
    }

    ?>
