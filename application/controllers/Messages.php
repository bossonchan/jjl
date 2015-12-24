<?php

defined('APPPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST.php';

class Messages extends REST {

  public function __construct() {
    parent::__construct();
    $this->load->model('messages_model');
  }

  public function get_messages() {
    echo 'get message list';
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

    if ($data['m_to'] === $current['uid']) {
      return $this->error(400, 'cannot send private message to yourself');
    }

    $data['m_from'] = $current['uid'];
    $result = $this->messages_model->create_message($data);
    if (!empty($result['error'])) {
      $this->error(400, $result['error']);
    } else {
      $this->json($result['message']);
    }
  }
}
?>
