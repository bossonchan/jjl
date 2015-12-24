<?php

class Messages_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function create_message($data) {
    $to = $data['m_to'];
    $this->db->trans_start();

    $this->db->from('hood');
    $this->db->where('hood_id', $data['m_hood']);
    $hood = $this->db->get()->row_array();
    if (empty($hood)) {
      $this->db->trans_complete();
      return array('error' => 'cannot find hood');
    }

    if ($data['m_type'] === 'private') {
      $this->db->select('uid, u_name, u_gender, u_profile, u_photo, address, block_id');
      $this->db->from('user');
      $this->db->where('uid', $data['m_to']);
      $to = $this->db->get()->row_array();
      if (empty($to)) {
        $this->db->trans_complete();
        return array('error' => 'cannot find m_to user');
      }
    } else {
      unset($data['m_to']);
    }

    $this->db->insert('messages', $data);
    $data['mid']  = $this->db->insert_id();
    $data['m_to'] = $to;

    if ($this->db->affected_rows() === 0) {
      $this->db->trans_complete();
      return array('error' => 'insert failed for unknown reason.');
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('message' => $data);
    }
  }
}
?>
