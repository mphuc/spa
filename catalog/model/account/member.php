<?php
class ModelAccountMember extends Model {

	public function getmember($customer_id, $limit, $offset) {
		$query = $this->db->query("
			SELECT c.*
			FROM " . DB_PREFIX . "customer_ml AS ml
			JOIN " . DB_PREFIX . "customer c
				ON c.customer_id = ml.customer_id
			WHERE ml.p_node = ".$customer_id."
			ORDER BY c.customer_id DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query->rows;
	}

	public function countMember($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer_ml WHERE p_node = ".$customer_id."");
		return $query->row['number'];
	}

	public function getusermember($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer A LEFT JOIN " . DB_PREFIX . "customer_ml B ON A.customer_id = B.customer_id WHERE A.customer_id = ".$customer_id."");
		return $query->row;
	}
	public function getchild($customer_id) {
		$query = $this->db->query("SELECT p_node FROM " . DB_PREFIX . "customer_ml WHERE customer_id = ".$customer_id."");
		return $query->row;
	}
	public function insert_messmage($title,$content){
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "massage SET title = '".$title."',content = '".$content."', date_add = NOW(), status = 0");
		return $query;
	}
//////////////////////////////////////////////
	public function getnode($customer_id) {
		$query = $this->db->query("SELECT B.customer_id,username FROM " . DB_PREFIX . "customer_ml A INNER JOIN " . DB_PREFIX . "customer B ON A.customer_id = B.customer_id WHERE A.p_node = ".$customer_id."");
		return $query->rows;
	}
}