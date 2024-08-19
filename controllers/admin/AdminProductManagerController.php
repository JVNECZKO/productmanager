<?php

class AdminProductManagerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'product'; // Upewnij się, że tabela jest poprawna
        $this->className = 'Product';
        $this->lang = false;
        $this->bootstrap = true;

        $this->identifier = 'id_product';

        // Definicja pól do wyświetlania w liście
        $this->fields_list = array(
            'id_product' => array(
                'title' => 'Product ID',
                'align' => 'center',
                'width' => 30,
            ),
            'name' => array(
                'title' => 'Product Name',
                'align' => 'center',
            ),
            'price' => array(
                'title' => 'Price',
                'align' => 'center',
                'type' => 'price',
                'currency' => true,
            ),
        );

        parent::__construct();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function renderForm()
    {
        // Przygotuj listę menedżerów do wybrania
        $managers = Employee::getEmployees();
        $manager_options = [];
        foreach ($managers as $manager) {
            $manager_options[] = array(
                'id_option' => $manager['id_employee'],
                'name' => $manager['firstname'] . ' ' . $manager['lastname'],
            );
        }

        // Struktura formularza
        $this->fields_form = array(
            'legend' => array(
                'title' => 'Assign Product Manager',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => 'Product Name',
                    'name' => 'name',
                    'size' => 20,
                    'required' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => 'Manager',
                    'name' => 'id_manager',
                    'options' => array(
                        'query' => $manager_options,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
            ),
            'submit' => array(
                'title' => 'Save',
                'class' => 'btn btn-default pull-right',
            ),
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddproduct_manager')) {
            $id_product = (int)Tools::getValue('id_product');
            $id_manager = (int)Tools::getValue('id_manager');
            $product = new Product($id_product);

            // Zapisz dane menedżera w tabeli 'product_manager'
            $manager = new ProductManagerModel();
            $manager->id_product = $id_product;
            $manager->id_manager = $id_manager;
            $manager->save();

            Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . $this->token);
        }

        return parent::postProcess();
    }
}
