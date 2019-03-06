    <?
    require_once("base_model.php");
    class customer_model extends base_model {
        public function getListCustomer($current_page){
            $current_page =($current_page-1)*10;
        // $selectColum = $current_page+$per_page;
            $db = $this->getDb();
            $db->select('customer_id,first_name, email');
            $db->limit(10,$current_page);
            $query = $db->get('customer');
            return $query->result();
            
        
        }
        public function totalRow(){
            $db = $this->getDb();
            $query = $db->get('customer');
                return $query->num_rows();

        }


    }
    ?>    