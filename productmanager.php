<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductManager extends Module
{
    public function __construct()
    {
        $this->name = 'productmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = 'Product Manager';
        $this->description = 'Assigns a manager to each product and displays the information on the product page.';

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayProductExtraContent') &&
            $this->registerHook('actionProductSave') &&
            $this->installDb() &&
            $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb() && $this->uninstallTab();
    }

    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."product_manager` (
            `id_product` int(10) unsigned NOT NULL,
            `manager_name` varchar(255) NOT NULL,
            `manager_avatar` varchar(255) DEFAULT NULL,
            `manager_phone` varchar(32) DEFAULT NULL,
            `manager_email` varchar(255) NOT NULL,
            PRIMARY KEY (`id_product`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."product_manager`;";
        return Db::getInstance()->execute($sql);
    }

    private function installTab()
    {
        $id_parent = (int)Tab::getIdFromClassName('AdminCatalog');
    
        $tab1 = new Tab();
        $tab1->class_name = 'AdminProductManager';
        $tab1->module = $this->name;
        $tab1->id_parent = $id_parent;
        $tab1->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab1->name[$lang['id_lang']] = 'Product List';
        }
        $tab1->add();
    
        $tab2 = new Tab();
        $tab2->class_name = 'AdminManagers';
        $tab2->module = $this->name;
        $tab2->id_parent = $id_parent;
        $tab2->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab2->name[$lang['id_lang']] = 'Managers';
        }
        $tab2->add();
    
        return true;
    }
    
    private function uninstallTab()
    {
        $id_tab1 = (int)Tab::getIdFromClassName('AdminProductManager');
        if ($id_tab1) {
            $tab1 = new Tab($id_tab1);
            $tab1->delete();
        }
    
        $id_tab2 = (int)Tab::getIdFromClassName('AdminManagers');
        if ($id_tab2) {
            $tab2 = new Tab($id_tab2);
            $tab2->delete();
        }
    
        return true;
    }    

    public function hookDisplayProductExtraContent($params)
    {
        $id_product = (int)$params['product']['id_product'];
        $managerData = $this->getManagerData($id_product);

        $this->context->smarty->assign(array(
            'manager_name' => $managerData['manager_name'],
            'manager_avatar' => $managerData['manager_avatar'],
            'manager_phone' => $managerData['manager_phone'],
            'manager_email' => $managerData['manager_email'],
        ));

        return $this->display(__FILE__, 'views/templates/hook/displayProductManager.tpl');
    }

    public function hookActionProductSave($params)
    {
        $id_product = (int)$params['id_product'];

        if (!$this->managerExists($id_product)) {
            $this->assignDefaultManager($id_product);
        }
    }

    private function getManagerData($id_product)
    {
        $sql = "SELECT * FROM `"._DB_PREFIX_."product_manager` WHERE `id_product` = $id_product";
        return Db::getInstance()->getRow($sql);
    }

    private function managerExists($id_product)
    {
        $sql = "SELECT COUNT(*) FROM `"._DB_PREFIX_."product_manager` WHERE `id_product` = $id_product";
        return (bool)Db::getInstance()->getValue($sql);
    }

    private function assignDefaultManager($id_product)
    {
        $defaultManager = array(
            'manager_name' => 'Default Manager',
            'manager_avatar' => 'default_avatar.jpg',
            'manager_phone' => '123-456-789',
            'manager_email' => 'manager@example.com',
        );

        $sql = "INSERT INTO `"._DB_PREFIX_."product_manager` 
            (`id_product`, `manager_name`, `manager_avatar`, `manager_phone`, `manager_email`)
            VALUES ($id_product, '{$defaultManager['manager_name']}', '{$defaultManager['manager_avatar']}', '{$defaultManager['manager_phone']}', '{$defaultManager['manager_email']}')";

        return Db::getInstance()->execute($sql);
    }
}
