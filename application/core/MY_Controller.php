<?php
/**
 * Created by Jacky.
 * User: jacky
 * Date: 6/5/2015
 * Time: 8:44 AM
 */

class MY_Controller extends CI_Controller
{
    public function is_login()
    {
        $user_id = $this->session->userdata('auth_user_id');
        if (!$user_id) {
            return false;
        }
        return true;
    }

    public function check_login()
    {
        if (!$this->is_login()) {
            redirect('/home/login');
        }
    }
}