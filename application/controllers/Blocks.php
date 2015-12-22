<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Blocks extends REST {

  public function get_members($block_id) {
    echo 'get member list';
  }

  public function post_apply($block_id) {
    echo 'join block';
  }
}
?>


