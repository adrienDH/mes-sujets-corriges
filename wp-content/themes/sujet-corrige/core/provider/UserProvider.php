<?php

class UserProvider
{
    public function create($params):bool
    {
        if(empty($params['username']) ||
            empty($params['email']) ||
            empty($params['password']) ||
            empty($params['confirm_password'])) {
            die('A');
            return false;
        }

        if($params['password'] !== $params['confirm_password']) {
            die('B');
            return false;
        }

        wp_create_user($params['username'], $params['password'], $params['email']);

        return true;
    }
}