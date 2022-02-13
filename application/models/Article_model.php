<?php
class Article_model extends CI_model
{
    function getArticle($id){
		$this->db->select('articles.*,categories.name as category_name');
        $this->db->where('articles.id',$id);
        
        $this->db->join('categories','categories.id=articles.category','left');
        $query = $this->db->get('articles');
        
        $article = $query->row_array();
        //echo $this->db->last_query();
        

        // SELECT * FROM articles WHERE id={YourArticleID}
        return $article;
	}

    function getArticles($param = array()){
		if (isset($param['offset']) && isset($param['limit'])){
			$this->db->limit($param['offset'], $param['limit']);
		}

		if (isset($param['q'])) {
            $this->db->or_like('title',trim($param['q']));
            $this->db->or_like('author',trim($param['q']));
        }

		$query = $this->db->get('articles');
		// echo $this->db->last_query();
		$articles = $query->result_array();
		return $articles;
		
	}

    function getArticlesCount(){
		if (isset($param['q'])) {
            $this->db->or_like('title',trim($param['q']));
            $this->db->or_like('author',trim($param['q']));
        }

        if (isset($param['category_id'])) {
            $this->db->where('category',$param['category_id']);
        }

		$count = $this->db->count_all_results('articles');
		return $count;
		
	}

	// This method will save a article in DB.
    function addArticle($formArray){
		$this->db->insert('articles', $formArray);
		return $this->db->insert_id();
	}
	
	function updateArticle($id,$formArray){
		$this->db->where('id',$id);
        $this->db->update('articles',$formArray);
	}

    function deleteArticle($id){
		$this->db->where('id',$id);
        $this->db->delete('articles');
	}

	function getArticlesFront($param = array()) {
        
        if (isset($param['offset']) && isset($param['limit'])) {
            $this->db->limit($param['offset'],$param['limit']);
        }

        if (isset($param['q'])) {
            $this->db->or_like('title',trim($param['q']));
            $this->db->or_like('author',trim($param['q']));
        }

        if (isset($param['category_id'])) {
            $this->db->where('category',$param['category_id']);
        }

        $this->db->select('articles.*,categories.name as category_name');
        $this->db->where('articles.status',1);
        $this->db->order_by('articles.created_at','DESC');


        $this->db->join('categories','categories.id=articles.category','left');

        $query = $this->db->get('articles');

        $articles = $query->result_array();
        //echo $this->db->last_query();

        return $articles;
   }

	
}

?>
