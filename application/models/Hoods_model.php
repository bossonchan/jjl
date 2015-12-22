<?php

class Hoods_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function get_hood_list($count, $offset) {
    $total = $this->db->count_all('hood');

    $this->db->from('hood');
    if ($count < 0) {
      $this->db->limit($total);
    } else {
      $this->db->limit($count, $offset);
    }
    $this->db->order_by('h_name', 'ASC');

    $result = $this->db->get()->result_array();
    return array(
      'count'  => count($result),
      'offset' => $count < 0 ? 0 : $offset,
      'total'  => $total,
      'nextOffset' => $count < 0 ? -1 : $count + $offset,
      'hoods' => $result
    );
  }

  public function get_block_list($hood_id, $count, $offset) {
    $this->db->where('hood_id', $hood_id);
    $total = $this->db->count_all_results('block');

    $this->db->where('hood_id', $hood_id);
    $this->db->from('block');
    if ($count < 0) {
      $this->db->limit($total);
    } else {
      $this->db->limit($count, $offset);
    }
    $this->db->order_by('b_name', 'ASC');

    $result = $this->db->get()->result_array();
    return array(
      'count'      => count($result),
      'offset'     => $count < 0 ? 0 : $offset,
      'total'      => $total,
      'nextOffset' => $count < 0 ? -1 : $count + $offset,
      'blocks'     => $result
    );
  }
}
?>
