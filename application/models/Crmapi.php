<?php
/**
 * Created by Jacky.
 * User: jacky
 * Date: 6/8/2015
 * Time: 11:41 AM
 */

class Crmapi extends MY_Model
{
    protected $api_url;
    protected $api_username;
    protected $api_password;
    protected $api_session_id;
    protected $application_name = 'CustomerPortal';

    public function setSessionId($session_id)
    {
        $this->api_session_id = $session_id;
    }

    public function getSessionId()
    {
        return $this->api_session_id;
    }

    public function setUserAuth($username, $password)
    {
        $this->api_username = $username;
        $this->api_password = md5($password);
    }

    public function call($method, $parameters)
    {
        ob_start();
        $curl_request = curl_init();

        curl_setopt($curl_request, CURLOPT_URL, $this->config->item('crm_api_url'));
        curl_setopt($curl_request, CURLOPT_POST, 1);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, 1);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

        $jsonEncodedData = json_encode($parameters);

        $post = array(
            "method" => $method,
            "input_type" => "JSON",
            "response_type" => "JSON",
            "rest_data" => $jsonEncodedData
        );

        curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl_request);
        curl_close($curl_request);

        $result = explode("\r\n\r\n", $result, 2);
        $response = json_decode($result[1]);
        ob_end_flush();

        return $response;
    }

    public function login()
    {
        $login_parameters = array(
            "user_auth" => array(
                "user_name" => $this->api_username,
                "password" => $this->api_password,
                "version" => "1"
            ),
            "application_name" => $this->application_name,
            "name_value_list" => array(),
        );

        $login_result = $this->call("login", $login_parameters);
        // login fail
        if (empty($login_result->id)) {
            $this->api_session_id = false;
        } else {
            //get session id
            $this->api_session_id = $login_result->id;
        }

        if ($this->api_session_id)
        {
            return $this->api_session_id;
        }

        return false;
    }

    public function error_message($message)
    {
        return array(
            'status' => '0',
            'message' => $message,
            'data' => array()
        );
    }

    public function response($data_response)
    {
        return json_encode(array(
            'status' => 1,
            'message' => '',
            'data' => $data_response
        ));
    }
}