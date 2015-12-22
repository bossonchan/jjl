<?php

class Users_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function login($username, $password) {
    return $this->db->get('user', 1)->row();
  }
}

?>
