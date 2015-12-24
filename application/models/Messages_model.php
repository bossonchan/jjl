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
      $this->db->or_where('u_name', $data['m_to']);
      $to = $this->db->get()->row_array();
      if (empty($to)) {
        $this->db->trans_complete();
        return array('error' => 'cannot find m_to user');
      }
      $data['m_to'] = $to['uid'];
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
    // sent by myself
    $mine_where = '
      m.m_from = ?
    ';

    // sent to me
    $private_where = '
      m.m_type = \'private\'
      and
      m.m_to = ?
    ';

    // sent from friends
    $friend_where   = '
      m.m_type = \'friend\'
      and
      m.m_from in (
        select f.uid1 as uid
        from friends as f
        where f.uid2 = ? and (f.state = \'active\' or f.state = \'accepted\')
        union
        select f.uid2 as uid
        from friends as f
        where f.uid1 = ? and (f.state = \'active\' or f.state = \'accepted\')
      )
    ';

    // sent from following users
    $neighbor_where = '
      m.m_type = \'neighbor\'
      and
      m.m_from in (
        select n.uid2
        from neighbor as n
        where n.uid1 = ?
      )
    ';

    $all_where = '
      (' . $mine_where . ')
      or
      (' . $private_where . ')
      or
      (' . $friend_where . ')
      or
      (' . $neighbor_where . ')
    ';

    $detail_query = '
      select *
      from messages as m, user as us
    ';

    $total_query  = '
      select count(*) as total
      from messages as m
    ';

    $query_bindings = array();
    if ($type === 'private') {
      $detail_query = $detail_query . ' where m.m_from = us.uid and (' . $private_where . ') ';
      $total_query  = $total_query  . ' where ' . $private_where;
      $query_bindings = array($uid);
    } else if ($type === 'friend') {
      $detail_query = $detail_query . ' where m.m_from = us.uid and (' . $friend_where . ') ';
      $total_query  = $total_query  . ' where ' . $friend_where;
      $query_bindings = array($uid, $uid);
    } else if ($type === 'neighbor') {
      $detail_query = $detail_query . ' where m.m_from = us.uid and (' . $neighbor_where . ') ';
      $total_query  = $total_query  . ' where ' . $neighbor_where;
      $query_bindings = array($uid);
    } else {
      $detail_query = $detail_query . ' where m.m_from = us.uid and (' . $all_where . ') ';
      $total_query  = $total_query  . ' where ' . $all_where;
      $query_bindings = array($uid, $uid, $uid, $uid, $uid);
    }

    // get total count of messages
    $total  = $this->db->query($total_query, $query_bindings)->row_array();
    $total  = empty($total) ? 0 : intval($total['total']);

    // get message list
    $detail_query = $detail_query . ' ORDER BY m_time ' . ($sort < 0 ? 'DESC' : 'ASC');
    if ($count > 0) {
      $detail_query = $detail_query . ' LIMIT ?, ?';
      array_push($query_bindings, $offset, $offset + $count);
    }
    $result = $this->db->query($detail_query, $query_bindings)->result_array();
    $result = array_map(function ($item) {
      return array(
        'mid'       => $item['mid'],
        'm_type'    => $item['m_type'],
        'm_hood'    => $item['m_hood'],
        'm_title'   => $item['m_title'],
        'm_content' => $item['m_content'],
        'm_time'    => $item['m_time'],
        'm_to'      => $item['m_to'],
        'm_from'    => array(
          'uid'       => $item['uid'],
          'u_name'    => $item['u_name'],
          'u_gender'  => $item['u_gender'],
          'u_profile' => $item['u_profile'],
          'u_photo'   => $item['u_photo'],
          'address'   => $item['address'],
          'block_id'  => $item['block_id'],
        )
      );
    }, $result);

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
