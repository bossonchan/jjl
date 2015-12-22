<?php

class Blocks_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function get_member_list($block_id, $count, $offset) {
    $this->db->where('block_id', $block_id);
    $total = $this->db->count_all_results('user');

    $this->db->where('block_id', $block_id);
    $this->db->from('user');
    if ($count < 0) {
      $this->db->limit($total);
    } else {
      $this->db->limit($count, $offset);
    }
    $this->db->order_by('lastVisit', 'DESC');

    $result = $this->db->get()->result_array();
    return array(
      'count'      => count($result),
      'offset'     => $count < 0 ? 0 : $offset,
      'total'      => $total,
      'nextOffset' => $count < 0 ? -1 : $count + $offset,
      'blocks'     => $result
    );
  }

  public function join_block($uid, $block_id) {
    $this->db->trans_start();

    $this->db->from('block');
    $this->db->where('block_id', $block_id);
    $block = $this->db->get()->row_array();
    if (empty($block)) {
      $this->db->trans_complete();
      return array('error' => 'Cannot find block');
    }

    $this->db->where('uid', $uid);
    $this->db->update('user', array('block_id' => $block_id));

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('block' => $block);
    }
  }
}
?>
