<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class REST extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
  }

  public function json($data) {
    $this->output
      ->set_status_header(200)
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function error($status, $message) {
    $this->output
      ->set_status_header($status)
      ->set_output($message);
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
