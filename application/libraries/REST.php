<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class REST extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
  }

  public function login_conflict_check() {
    $user = $this->session->userdata('user');
    if (!empty($user)) {
       $this->error(409, 'Current user has been logged in');
       exit();
    }
  }

  public function logout_conflict_check() {
    $user = $this->session->userdata('user');
    if (empty($user)) {
      $this->error(409, 'Current user not logged in');
      exit();
    }
  }

  public function required_login() {
    $user = $this->session->userdata('user');
    if (empty($user)) {
      $this->error(401, 'You should login first');
      exit();
    }
  }

  public function json($data) {
    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function error($status, $message) {
    $this->output->set_status_header($status);
    echo $message;
  }

  public function _remap($method, $params = array()) {
    $method = $this->input->method() . '_' . $method;
    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $params);
    }

    $this->output
      ->set_status_header(405)
      ->set_output('Method Not Supported');
  }

}

?>
