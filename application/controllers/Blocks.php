<?php
defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Blocks extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('blocks_model');
  }

  public function get_members($block_id) {
    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count or sort.');
    }

    $result = $this->blocks_model->get_member_list($block_id, $count, $offset);
    $this->json($result);
  }

  public function post_apply($block_id) {
    $this->required_login();
    $current = $this->session->user;
    $result = $this->blocks_model->join_block($current['uid'], $block_id);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->json($result['block']);
    }
  }
}
?>
