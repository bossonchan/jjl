<?php

class Users_model extends CI_Model {

  protected $__select = 'uid, u_name, u_gender, u_profile, u_photo, address, block_id';

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
    $this->db->select($this->__select);
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

    if ($this->db->affected_rows() === 0) {
      $this->db->trans_complete();
      return array('error' => $this->db->error());
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('user' => $data);
    }
  }

  public function follow($follower, $followed) {
    $this->db->trans_start();

    $user = $this->get_user_info_by_id($followed);
    if (empty($user)) {
      $this->db->trans_complete();
      return array('error' => 'cannot find user');
    }

    # overwrite 
    $data = array('uid1' => $follower, 'uid2' => $followed);
    $this->db->delete('neighbor', $data);
    $this->db->insert('neighbor', $data);

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
    $this->db->delete('neighbor', array(
      'uid1' => $follwer,
      'uid2' => $followed
    ));
  }

  public function get_friend_list($uid, $count, $offset) {

    $count_all = '
      select COUNT(*) as total
      from user as u
      where u.uid in (
        select uid1 from friends where uid2 = ? and (state = \'active\' or state = \'accepted\')
        union
        select uid2 from friends where uid1 = ? and (state = \'active\' or state = \'accepted\')
      )
    ';

    $get_friends = '
      select ' . $this->__select . '
      from user as u
      where u.uid in (
        select uid1 from friends where uid2 = ? and (state = \'active\' or state = \'accepted\')
        union
        select uid2 from friends where uid1 = ? and (state = \'active\' or state = \'accepted\')
      )
    ';

    $result = $this->db->query($count_all, array($uid, $uid))->row_array();
    $total = empty($result) ? 0 : intval($result['total']);

    if ($count > 0) {
      $get_friends = $get_friends . ' limit ' . $offset . ', ' . ($offset + $count) . ';';
    }
    $friends = $this->db->query($get_friends, array($uid, $uid))->result_array();

    return array(
      'total'      => $total,
      'count'      => count($friends),
      'offset'     => $count > 0 ? $offset : 0,
      'nextOffset' => $count > 0 ? $offset + $count : -1,
      'friends'    => $friends
    );
  }

  public function remove_friends($uid1, $uid2) {
    $this->db->delete('friends', array('uid1' => $uid1, 'uid2' => $uid2));
    $this->db->delete('friends', array('uid2' => $uid1, 'uid1' => $uid2));
  }

  public function get_friend_requests($uid, $count, $offset) {
    $count_all = '
      select COUNT(*) as total
      from user as u, friends as f
      where u.uid = ? and u.uid = f.uid2 and f.state = \'pending\'
    ';

    $get_requests = '
      select ' . $this->__select . '
      from user where uid in (
        select f.uid1
        from user as u, friends as f
        where u.uid = ? and u.uid = f.uid2 and f.state = \'pending\'
      )
    ';

    $result = $this->db->query($count_all, array($uid))->row_array();
    $total = empty($result) ? 0 : intval($result['total']);

    if ($count > 0) {
      $get_requests = $get_requests . ' limit ' . $offset . ', ' . ($offset + $count) . ';';
    }
    $requests = $this->db->query($get_requests, array($uid))->result_array();

    return array(
      'total'      => $total,
      'count'      => count($requests),
      'offset'     => $count > 0 ? $offset : 0,
      'nextOffset' => $count > 0 ? $offset + $count : -1,
      'requests'    => $requests
    );
  }

  public function send_friend_request($sender, $receiver_name) {
    $this->db->trans_start();

    $receiver = $this->get_user_info(array('u_name' => $receiver_name))->row_array();
    if (empty($receiver)) {
      $this->db->trans_complete();
      return array('error' => 'cannot find user');
    }

    if ($receiver['uid'] === $sender) {
      $this->db->trans_complete();
      return array('error' => 'cannot send request to yourself.');
    }

    $this->db->delete('friends', array('uid1' => $sender, 'uid2' => $receiver['uid']));
    $this->db->delete('friends', array('uid2' => $sender, 'uid1' => $receiver['uid']));
    $this->db->insert('friends', array(
      'uid1' => $sender,
      'uid2' => $receiver['uid'],
      'state' => 'pending'
    ));
    if ($this->db->affected_rows() === 0) {
      $this->db->trans_complete();
      return array('errror' => 'insert failed for unknown reason.');
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('errror' => $this->db->error());
    } else {
      return array('user' => $receiver);
    }
  }

  public function handle_friend_request($sender, $receiver, $action) {
    $this->db->trans_start();

    $sql = '
      select ' . $this->__select . '
      from user as u, friends as f
      where u.uid = f.uid1 and f.uid1 = ? and f.uid2 = ? and f.state = \'pending\'
    ';
    $request = $this->db->query($sql, array($sender, $receiver))->row_array();

    if (empty($request)) {
      $this->db->trans_complete();
      return array('error' => 'No request found');
    }

    $this->db->where(array('uid1' => $sender, 'uid2' => $receiver));
    if ($action === 'accept') {
      $this->db->update('friends', array('state' => 'accepted'));
    } else {
      $this->db->update('friends', array('state' => 'rejected'));
    }

    if ($this->db->affected_rows() === 0) {
      $this->db->trans_complete();
      return array('error' => 'update failed for unkown reason.');
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      return array('error' => $this->db->error());
    } else {
      return array('user' => $request);
    }
  }
}

?>
