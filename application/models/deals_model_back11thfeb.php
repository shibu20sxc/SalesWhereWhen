<?php 

Class deals_model extends CI_Model{
	
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function getDealDetails($id=null){
        $this->db->select('m.email_id,s.id as shopid,s.logo_thumb,s.shop_name,s.shop_address,s.shop_city,s.shop_highlights,s.shop_url,m.email_id,sc.category as parentCatg,c.category,c.parent_id,d.*');
        $this->db->from('deals d');
        $this->db->join('category c', 'c.id = d.category_id','LEFT');
        $this->db->join('category sc', 'sc.id = c.parent_id','LEFT');
        $this->db->join('shop s', 's.id = d.shop_id','LEFT');
        $this->db->join('merchant m', 'm.id = d.merchant_id','LEFT');
        if($id){
            $this->db->where(array('d.id' => $id));
            $query = $this->db->get();
            $result = ($query->num_rows() > 0) ? $query->first_row() : '';
        }else{
            $query = $this->db->get();
            $result= $query->result();
        }
        //echo"<pre>";print_r($result);exit;
        return $result;
    }
    public function manage($input, $id=null){
            if($id){
                $this->db->update('deals', $input, array('id'=>$id));
                $result = ($this->db->affected_rows() != 1) ? '' : 1;
            }else{
                $this->db->insert('deals', $input);
                $result = $this->db->insert_id();
            }
            return $result;
    }
    public function manageDealImages($files,$deal_id){
        foreach($files as $file){
            $input = array(
               'deal_id' => $deal_id ,
               'deal_image' => $file
            );
            $this->db->insert('deal_images', $input);
        }
    }
    
    public function getDealsByMerchant($merchant_id,$id=null){
        if($id){
            $query = $this->db->get_where('deals', array('id' => $id,'merchant_id'=>$merchant_id));
            $result= $query->first_row();
        }else{
            $query = $this->db->get_where('deals', array('merchant_id'=>$merchant_id));
            $result= $query->result();
        }
        return $result;
    }
    public function getDealsByCategory($category){
        $this->db->select('m.email_id,s.id as shopid,s.logo_thumb,s.shop_name,s.shop_url,m.email_id,c.category,d.*');
        $this->db->from('deals d');
        $this->db->join('category c', 'c.id = d.category_id','LEFT');
        $this->db->join('shop s', 's.id = d.shop_id','LEFT');
        $this->db->join('merchant m', 'm.id = d.merchant_id','LEFT');
        $this->db->where(array('d.category_id' => $category));
        $query = $this->db->get();
        $result= $query->result();
        return $result;
    }
    public function getDealList($type){
        $this->db->select('*');
        $this->db->from('deals');
        $this->db->where(array('deal_type'=>$type,'status'=>1));
        if($type == 1){
        $this->db->limit(15);
        }else{
        $this->db->limit(2);    
        }
        $this->db->order_by("id", "asc"); 
        //$query = $this->db->get_where('deals', array('deal_type'=>$type,'status'=>1));
        $query = $this->db->get();
        $result= $query->result();
        return $result;
    }
    public function getDealImages($id){
        $this->db->select('di.*');
        $this->db->from('deal_images di');
        $this->db->join('deals d', 'd.id = di.deal_image','LEFT');
        $this->db->where(array('di.deal_id' => $id,'di.status' => 1));
        $query = $this->db->get();
        $result= $query->result();
        return $result;
    }
    public function getSearchResult($search_key){
        $sql="SELECT S.id as shopId,S.shop_name, C.category,D.* FROM deals AS D LEFT JOIN category AS C ON C.id=D.category_id LEFT JOIN shop AS S ON S.id =D.shop_id WHERE (D.brand_name LIKE '%".$search_key."%' OR C.category LIKE '%".$search_key."%' OR S.shop_name LIKE '%".$search_key."%')";
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $result= $query->result();
        return $result;
    }
}

?>

