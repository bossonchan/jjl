<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Users extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function post_session()  {
    $this->login_conflict_check();

    $username = $this->input->post('u_name');
    $password = $this->input->post('password');

    if (empty($username) or empty($password)) {
      return $this->error(400, 'username or password is empty.');
    }

    $result = $this->users_model->login($username, $password);
    if ($result['error']) {
      $this->error(401, $result['error']);
    } else {
      $this->session->user = $result['user'];
      $this->json($result['user']);
    }
  }

  public function delete_session(){
    $this->logout_conflict_check();
    $this->session->user = null;
    $this->json((object)null);
  }

  public function get_info($uid) {
    $user = $this->users_model->get_user_info_by_id($uid);
    if (empty($user)) {
      $this->error(404, 'Cannot find specific user.');
    } else {
      $this->json($user);
    }
  }

  public function get_current() {
    $this->required_login();
    $this->json($this->session->user);
  }

  public function post_users() {
    $data = array(
      'u_name'    => $this->input->post('u_name'),
      'password'  => $this->input->post('password'),
      'block_id'  => $this->input->post('block_id'),
      'u_gender'  => !empty($this->input->post('u_gender'))  ? $this->input->post('u_gender')  : 'm',
      'u_profile' => !empty($this->input->post('u_profile')) ? $this->input->post('u_profile') : '',
      'u_photo'   => !empty($this->input->post('u_photo'))   ? $this->input->post('u_photo')   : '/publc/avatars/default.png',
      'address'   => !empty($this->input->post('address'))   ? $this->input->post('address')   : ''
    );

    if (empty($data['u_name']) || $data['u_name'] > 20) {
      return $this->error(400, 'invalid u_name');
    }
    
    if (empty($data['password'])) {
      return $this->error(400, 'Invalid password');
    }

    if (empty($data['u_gender']) || !in_array(strtolower($data['u_gender']), array('m', 'u', 'f'))) {
      return $this->error(400, 'Invalid u_gender');
    }

    $result = $this->users_model->register($data);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->session->user = $result['user'];
      $this->json($result['user']);
    }
  }

  public function post_follow($uid) {
    $this->required_login();
    $current = $this->session->user;
    if ($uid === $current['uid']) {
      return $this->error(400, 'cannot follow yourself.');
    }
    $result  = $this->users_model->follow($current['uid'], $uid);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->session->user = $result['user'];
      $this->json($result['user']);
    }
  }

  public function delete_follow($uid) {
    $this->required_login();
    $current = $this->session->user;
    $this->users_model->unfollow($current['uid'], $uid);
    $this->json((object)(null));
  }

  public function get_friend_list() {
    $this->required_login();

    $current = $this->session->user;

    $count = intval($this->input->get('count'));
    $offset= intval($this->input->get('offset'));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    $result = $this->users_model->get_friend_list($current['uid'], $count, $offset);
    $this->json($result);
  }

  public function delete_friend($uid) {
    echo 'delete friend';
  }

  public function get_friend_request() {
    echo 'get friend requests';
  }

  public function post_friend_request() {
    echo 'send friend request to someone via u_name';
  }

  public function put_friend_request($uid) {
    echo 'accept or reject friend request';
  }
}
?>
