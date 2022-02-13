<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Category_model extends CI_Model {
                        
    public function create($formArray){
        $this->db->insert('categories', $formArray);
    }

	public function getCategories($params=[]){
		if (!empty($params['queryString'])) {
			$this->db->like('name',$params['queryString']);
		}
		$result = $this->db->get('categories')->result_array();
		// echo $this->db->last_query();
		return $result;
	}

	public function getCategory($id)
	{
		# code...
		$this->db->where('id',$id);
		$category = $this->db->get('categories')->row_array();
		// SELECT = FROM categories WHERE id = {ID}
		return $category;
	}

	public function update($id, $formArray) {
        $this->db->where('id',$id);
        $this->db->update('categories',$formArray);
    }

	public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('categories');
    }

                        
}
                        
/* End of file Category_model.php */
/* Location: ./application/model/Category_model.php */
    
                        