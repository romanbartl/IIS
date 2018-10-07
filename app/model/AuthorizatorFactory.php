<?php

namespace App;

use Nette\Security\Permission;


class AuthorizatorFactory {
    /**
     * &return \Nette\Security\IAutharizator
     */
    public static function create() {
        $authorizator = new Permission();

        /* Define user roles */
        $authorizator->addRole('guest');
        $authorizator->addRole('user', 'guest');
        $authorizator->addRole('admin', 'user');

        /* Define resources */
        /** Must be continually added */
        $authorizator->addResource('sign');
        $authorizator->addResource('user');

        /* Define 'host' permissions */
        $authorizator ->allow('guest', 'sign', array('in', 'up'));

        /* Define 'user' permissions */
        $authorizator->deny('user', 'sign', array('in', 'up'));
        /* Define 'admin' permissions */
    }
}