<?php

class Users_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function login($username, $password) {
    $this->db->from('user');
    $this->db->where('u_name', $username);
    $user = $this->db->get()->row_array();
    $error = null;
    if (empty($user)) {
      $error = 'User has not been registered';
    } else if ($user['password'] !== $password) {
      $error = 'Wrong password';
    } else {
      unset($user['password']);
    }
    return array('error' => $error, 'user' => $user);
  }

  public function get_user_info_by_id($uid){
    return $this->get_user_info(array('uid' => intval($uid)))->row_array();
  }

  public function get_user_info($query){
    $this->db->select('uid, u_name, u_gender, u_profile, u_photo, address, block_id');
    $this->db->from('user');
    $this->db->where($query);
    return $this->db->get();
  }

  public function register($data) {
    $this->db->trans_start();

    $block_id = $data['block_id'];
    $this->db->from('block');
    $this->db->where('block_id', $block_id);
    $block = $this->db->get()->row_array();
    if (empty($block)) {
      $this->db->trans_complete();
      return array('error' => 'Invalid block_id');
    }

    $user = $this->get_user_info(array('u_name' => $data['u_name']))->row_array();
    if (!empty($user)) {
      $this->db->trans_complete();
      return array('error' => 'username has been taken');
    }

    $this->db->insert('user', $data);
    $data['uid'] = $this->db->insert_id();
    $this->db->trans_complete();

    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('user' => $data);
    }
  }

  public function follow($follwer, $followed) {
    $this->db->trans_start();

    $user = $this->get_user_info_by_id($followed);
    if (empty($user)) {
      $this->db->trans_complete();
      return array('error' => 'cannot find user');
    }

    # overwrite record
    $this->db->delete('friends', array('uid1' => $follwer, 'uid2' => $followed));
    $this->db->delete('friends', array('uid2' => $follwer, 'uid1' => $followed));
    $this->db->insert('friends', array(
      'uid1' => $follwer,
      'uid2' => $followed,
      'state'=> 'pending'
    ));

    if ($this->db->affected_rows() === 0) {
      $this->db->trans_complete();
      return array('error' => 'insert failed for unkown reason.');
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('user' => $user);
    }
  }

  public function unfollow($follwer, $followed) {
    $this->db->delete('friends', array(
      'uid1' => $follwer,
      'uid2' => $followed
    ));
  }

  public function get_friend_list($uid, $count, $offset) {
    $from  = ' from user as u, friends as f ';
    $query = ' where u.uid = ' . $uid . ' and (u.uid = f.uid1 or u.uid = f.uid2) and (f.state = \'active\' or f.state = \'accepted\') ';

    $count_all = 'select COUNT(*) as total ' . $from . $query;
    $result = $this->db->query($count_all)->row_array();
    $total = empty($result) ? 0 : intval($result['total']);

    $get_friends = 'select uid, u_name, u_gender, u_profile, u_photo, address, block_id ' . $from . $query;
    if ($count > 0) {
      $get_friends = $get_friends . ' limit ' . $offset . ', ' . ($offset + $count) . ';';
    }
    $friends = $this->db->query($get_friends)->result_array();

    return array(
      'total'      => $total,
      'count'      => count($friends),
      'offset'     => $count > 0 ? $offset : 0,
      'nextOffset' => $count > 0 ? $offset + $count : -1,
      'friends'    => $friends
    );
  }
}

?>
