<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Users extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function post_session()  {
    $user = $this->users_model->login(1, 1);

    if (empty($user)) {
      $this->error(401, 'No registry');
    } else {
      $this->json($user);
    }
  }

  public function delete_session(){
    echo 'sign out';
  }

  public function get_info($uid) {
    echo 'get user info';
  }

  public function get_current() {
    echo 'get current user';
  }

  public function post_users() {
    echo 'sign up';
  }

  public function post_follow($uid) {
    echo 'follow someon';
  }

  public function delete_follow($uid) {
    echo 'cancel following someone';
  }

  public function get_friend_list() {
    echo 'get friend list';
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
