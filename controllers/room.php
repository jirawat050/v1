    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require("base_controller.php");

    class room extends base_controller {
    
    
    public function detailroom(){
      
        $this->load->view('detailroom/index');
    }
        
    }

    ?>
