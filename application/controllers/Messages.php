<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Messages extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('messages_model');
  }

  public function get_messages() {
    $type  = $this->input->get('type', true);
    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));
    $sort  = intval($this->input->get('sort', true));

    $type   = empty($type)   ? 'all' : $type;
    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;
    $sort   = empty($sort)   ? -1 : $sort;

    if (!in_array($type, array('all', 'friend', 'private', 'neighbor'))) {
      return $this->error(400, 'invalid type');
    }

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count or sort.');
    }

    $uid = -1; // never exist
    if ($this->session->has_userdata('user')) {
      $current = $this->session->userdata('user');
      $uid = $current['uid'];
    }

    $result = $this->messages_model->get_message_list($uid, $type, $count, $offset, $sort);
    $this->json($result);
  }

  public function post_messages() {
    $this->required_login();
    $current = $this->session->userdata('user');

    $data = array(
      'm_type'    => $this->input->post('m_type', true),
      'm_title'   => $this->input->post('m_title', true),
      'm_content' => $this->input->post('m_content', true),
      'm_to'      => $this->input->post('m_to' , true),
      'm_hood'    => $this->input->post('m_hood'  , true)
    );

    if (!in_array($data['m_type'], array('private', 'friend', 'neighbor'))) {
      return $this->error(400, 'invalid m_type');
    }

    $data['m_title'] = empty($data['m_title']) ? '' : $data['m_title'];

    if (empty($data['m_content'])) {
      return $this->error(400, 'invalid m_content');
    }

    if (empty($data['m_hood'])) {
      return $this->error(400, 'invalid m_hood');
    }

    if ($data['m_type'] === 'private' && empty($data['m_to'])) {
      return $this->error(400, 'm_to cannot miss when m_type is private');
    }

    $data['m_from'] = $current['uid'];
    $result = $this->messages_model->create_message($data);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $data['m_from'] = array(
        'uid'       => $current['uid'],
        'u_name'    => $current['u_name'],
        'u_gender'  => $current['u_gender'],
        'u_profile' => $current['u_profile'],
        'u_photo'   => $current['u_photo'],
        'address'   => $current['address'],
        'block_id'  => $current['block_id'],
      );
      $this->json($result['message']);
    }
  }

  public function get_search() {
    $keyword = $this->input->get('keyword', true);
    if (empty($keyword)) {
      return $this->error(400, 'invalid keyword');
    }

    $uid = -1; // never exist
    if ($this->session->has_userdata('user')) {
      $current = $this->session->userdata('user');
      $uid = $current['uid'];
    }
    $result = $this->messages_model->search($uid, $keyword);
    $this->json($result);
  }

  public function post_comments($message_id) {
    $this->required_login();
    
    $current = $this->session->userdata('user');
    $data = array(
      'c_from'    => $current['uid'],
      'c_content' => $this->input->post('c_content', true),
      'mid'       => $message_id
    );

    if (empty($data['c_content'])) {
      return $this->error(400, 'invlaid c_content');
    }
    $result = $this->messages_model->create_comments($data);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $result['comment']['c_from'] = array(
        'uid'       => $current['uid'],
        'u_name'    => $current['u_name'],
        'u_gender'  => $current['u_gender'],
        'u_profile' => $current['u_profile'],
        'u_photo'   => $current['u_photo'],
        'address'   => $current['address'],
        'block_id'  => $current['block_id'],
      );
      $this->json($result['comment']);
    }
  }

  public function get_comments($message_id) {
    $count = intval($this->input->get('count', true));
    $offset= intval($this->input->get('offset', true));

    $count  = empty($count)  ? 10 : $count;
    $offset = empty($offset) ? 0  : $offset;

    if (is_nan($count) || is_nan($offset)) {
      return $this->error(400, 'Invalid offset or count or sort.');
    }

    $result = $this->messages_model->get_comment_list($message_id, $count, $offset);
    $this->json($result);
  }
}
?>
