<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Messages extends REST {

  public function get_messages() {
    echo 'get message list';
  }

  public function post_messages() {
    echo 'create a message';
  }
}
?>



