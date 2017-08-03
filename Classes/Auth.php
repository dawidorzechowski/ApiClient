<?php



namespace Classes;

class Auth {

    private $login;
    private $password;
    private $api_key;

    public function authorization($login, $password, $api_key) {

        function setLogin($login) {
            $this->login = $login;
        }

        function setPassword($password) {
            $this->password = $password;
        }

        function setApiKey($api_key) {
            $this->api_key = $api_key;
        }

    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function getApiKey() {
        return $this->api_key;
    }

    function getAuthData() {
        $auth = array();
        $auth['login'] = $this->getLogin();
        $auth['password'] = $this->getPassword();
        $auth['api_key'] = $this->getApiKey();

        return $auth;
    }

}
