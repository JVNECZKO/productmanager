<?php

class ProductManagerModel extends ObjectModel
{
    public $id_product;
    public $manager_name;
    public $manager_phone;
    public $manager_email;
    public $manager_avatar;

    public static $definition = array(
        'table' => 'product_manager',
        'primary' => 'id_product',
        'fields' => array(
            'manager_name' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'manager_phone' => array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true),
            'manager_email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true),
            'manager_avatar' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
        ),
    );
}
