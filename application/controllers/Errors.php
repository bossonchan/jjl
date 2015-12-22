<?php

class Errors extends CI_Controller {
  public function response404()  {
    $this->output
      ->set_status_header(404)
      ->set_output('Not Found');
  }
}

?>
