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

    $username = $this->input->post('u_name', true);
    $password = $this->input->post('password', true);

    if (empty($username) or empty($password)) {
      return $this->error(400, 'username or password is empty.');
    }

    $result = $this->users_model->login($username, $password);
    if ($result['error']) {
      $this->error(401, $result['error']);
    } else {
      $this->session->set_userdata(array('user' => $result['user']));
      $this->json($result['user']);
    }
  }

  public function delete_session(){
    $this->logout_conflict_check();
    $this->session->unset_userdata('user');
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
    $this->json($this->session->userdata('user'));
  }

  public function post_users() {
    $data = array(
      'u_name'    => $this->input->post('u_name', true),
      'password'  => $this->input->post('password', true),
      'block_id'  => $this->input->post('block_id', true),
      'u_gender'  => !empty($this->input->post('u_gender' , true))  ? $this->input->post('u_gender', true)  : 'm',
      'u_profile' => !empty($this->input->post('u_profile', true))  ? $this->input->post('u_profile', true) : '',
      'u_photo'   => !empty($this->input->post('u_photo'  , true))  ? $this->input->post('u_photo', true)   : '/publc/avatars/default.png',
      'address'   => !empty($this->input->post('address'  , true))  ? $this->input->post('address', true)   : ''
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
      $this->session->set_userdata(array('user' => $result['user']));
      $this->json($result['user']);
    }
  }

  public function post_follow($uid) {
    $this->required_login();
    $current = $this->session->userdata('user');
    if ($uid == $current['uid']) {
      return $this->error(400, 'cannot follow yourself.');
    }
    $result  = $this->users_model->follow($current['uid'], $uid);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->json($result['user']);
    }
  }

  public function delete_follow($uid) {
    $this->required_login();
    $current = $this->session->userdata('user');
    $this->users_model->unfollow($current['uid'], $uid);
    $this->json((object)(null));
  }

  public function get_friend_list() {
    $this->required_login();

    $current = $this->session->userdata('user');

    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count.');
    }

    $result = $this->users_model->get_friend_list($current['uid'], $count, $offset);
    $this->json($result);
  }

  public function delete_friend($uid) {
    $this->required_login();
    $current = $this->session->userdata('user');
    $this->users_model->remove_friends($current['uid'], $uid);
    $this->json((object)null);
  }

  public function get_friend_request() {
    $this->required_login();

    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count.');
    }

    $current = $this->session->userdata('user');
    $result = $this->users_model->get_friend_requests($current['uid'], $count, $offset);
    $this->json($result);
  }

  public function post_friend_request() {
    $this->required_login();
    $username = $this->input->post('u_name', true);
    if (empty($username)) {
      return $this->error(400, 'Empty u_name');
    }
    $current = $this->session->userdata('user');
    $result = $this->users_model->send_friend_request($current['uid'], $username);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->json($result['user']);
    }
  }

  public function put_friend_request($uid) {
    $this->required_login();
    $action = $this->input->input_stream('action', true);
    if (empty($action) || !in_array($action, array('accept', 'reject'))) {
      return $this->error(400, 'Invalid action');
    }
    $current = $this->session->userdata('user');
    $result = $this->users_model->handle_friend_request($uid, $current['uid'], $action);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->json($result['user']);
    }
  }
}
?>
