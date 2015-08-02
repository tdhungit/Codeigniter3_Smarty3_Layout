<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Publisher Publisher
 */
class Home extends MY_Controller
{
    public function index()
    {
        //$this->load->view('welcome_message');
        $this->smarty->assign("title", "Welcome To CI 3");
        $this->smarty->layout('index');
    }

}