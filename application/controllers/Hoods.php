<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Hoods extends REST {

  public function get_list() {
    echo 'get hoods list';
  }

  public function get_block_list($hood_id) {
    echo 'get block list';
  }
}
?>

