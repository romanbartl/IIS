<?php

namespace App;

use Nette\Security\Permission;


class AuthorizatorFactory {
    /**
     * @return \Nette\Security\IAuthorizator
     */
    public static function create() {
        $authorizator = new Permission();

        /* Define user roles */
        $authorizator->addRole('user');
        $authorizator->addRole('admin', 'user');

        /* Define resources */
        $authorizator->addResource('userSource');
        $authorizator->addResource('interpret');
        $authorizator->addResource('concert');
        $authorizator->addResource('festival');

        /* Define 'user' permissions */
        $authorizator->deny('user', 'interpret', array('add', 'edit', 'remove'));
        $authorizator->deny('user', 'concert', array('add', 'edit', 'remove'));
        $authorizator->deny('user', 'festival', array('add', 'edit', 'remove'));
        $authorizator->deny('user', 'userSource', array('showList', 'changeRole'));

        /* Define 'admin' permissions */
        $authorizator->allow('admin', 'interpret', array('add', 'edit', 'remove'));
        $authorizator->allow('admin', 'concert', array('add', 'edit', 'remove'));
        $authorizator->allow('admin', 'festival', array('add', 'edit', 'remove'));
        $authorizator->allow('admin', 'userSource', array('showList', 'changeRole'));

        return $authorizator;
    }
}