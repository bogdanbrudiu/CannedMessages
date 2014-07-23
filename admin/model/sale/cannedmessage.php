<?php
class ModelSaleCannedMessage extends Model {
	public function addCannedMessage($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "cannedmessage SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', title = '" . $this->db->escape($data['cannedmessage_title']) . "', description = '" . $this->db->escape($data['cannedmessage_description']) . "'");


		$this->cache->delete('cannedmessage');
	}
	
	public function editCannedMessage($cannedmessage_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "cannedmessage SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', title = '" . $this->db->escape($data['cannedmessage_title']) . "', description = '" . $this->db->escape($data['cannedmessage_description']) . "' WHERE cannedmessage_id = '" . (int)$cannedmessage_id . "'");
		
		
		
		$this->cache->delete('cannedmessage');
	}
	
	public function deleteCannedMessage($cannedmessage_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cannedmessage WHERE cannedmessage_id = '" . (int)$cannedmessage_id . "'");

		$this->cache->delete('cannedmessage');
	}	

	public function getCannedMessage($cannedmessage_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cannedmessage WHERE cannedmessage_id = '" . (int)$cannedmessage_id . "'");
		
		return $query->row;
	}
		
	public function getCannedMessages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "cannedmessage";
		
			$sort_data = array(
				'title',
				'sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$cannedmessage_data = $this->cache->get('cannedmessage');
		
			if (!$cannedmessage_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cannedmessage ORDER BY title");
	
				$cannedmessage_data = $query->rows;
			
				$this->cache->set('cannedmessage',$cannedmessage_data );
			}	
	
			return $cannedmessage_data;			
		}
	}
	
	
		
	public function getTotalCannedMessages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cannedmessage");
		
		return $query->row['total'];
	}	
	
	
}
?>