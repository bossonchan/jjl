<?php
defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Hoods extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('hoods_model');
  }

  public function get_list() {
    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count or sort.');
    }

    $result = $this->hoods_model->get_hood_list($count, $offset);
    $this->json($result);
  }

  public function get_block_list($hood_id) {
    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count or sort.');
    }

    $result = $this->hoods_model->get_block_list($hood_id, $count, $offset);
    $this->json($result);
  }
}
?>
