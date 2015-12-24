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

  public function get_message_list($uid, $type, $count, $offset, $sort) {
    $select = 'select * ';
    $from   = ' from messages as m ';
    // sent by myself
    $mine_where     = ' m.m_from = ' . $uid . ' ';

    // sent to me
    $private_where  = ' m.m_type = \'private\' and m.m_to = ' . $uid . ' ';

    // sent from friends
    $friend_where   = ' m.m_type = \'friend\'  and m.m_from in ( select f.uid1 as uid from friends as f where f.uid2 = '. $uid . ' and (f.state = \'active\' or f.state = \'accepted\') union select f.uid2 as uid from friends as f where f.uid1 = '. $uid . ' and (f.state = \'active\' or f.state = \'accepted\')) ';

    // sent from following users
    $neighbor_where = ' m.m_type = \'neighbor\' and m.m_from in ( select n.uid2 from neighbor as n where n.uid1 = ' . $uid . ') ';

    $all_where = '(' . $mine_where . ') or (' . $private_where . ') or (' . $friend_where . ') or (' . $neighbor_where . ')';

    $detail_query = $select . $from;
    $total_query  = 'select count(*) as total ' . $from;

    if ($type === 'private') {
      $detail_query = $detail_query . ' where ' . $private_where;
      $total_query  = $total_query  . ' where ' . $private_where;
    } else if ($type === 'friend') {
      $detail_query = $detail_query . ' where ' . $friend_where;
      $total_query  = $total_query  . ' where ' . $friend_where;
    } else if ($type === 'neighbor') {
      $detail_query = $detail_query . ' where ' . $neighbor_where;
      $total_query  = $total_query  . ' where ' . $neighbor_where;
    } else {
      $detail_query = $detail_query . ' where ' . $all_where;
      $total_query  = $total_query  . ' where ' . $all_where;
    }

    $detail_query = $detail_query . ' ORDER BY m_time ' . ($sort < 0 ? 'DESC' : 'ASC');
    if ($count > 0) {
      $detail_query = $detail_query . ' LIMIT ' . $offset . ', ' . ($offset + $count);
    }

    $total  = $this->db->query($total_query )->row_array();
    $total  = empty($total) ? 0 : intval($total['total']);
    $result = $this->db->query($detail_query)->result_array();

    return array(
      'type'       => $type,
      'total'      => $total,
      'count'      => count($result),
      'offset'     => $count > 0 ? $offset : 0,
      'nextOffset' => $count > 0 ? $offset + $count : -1,
      'messages'   => $result
    );
  }

}
?>
