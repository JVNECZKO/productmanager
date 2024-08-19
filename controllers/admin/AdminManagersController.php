<?php

require_once _PS_MODULE_DIR_ . 'productmanager/controllers/admin/AdminProductManagerController.php';

class ProductManagerAdminManagersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'employee'; // Assuming managers are employees
        $this->className = 'Employee';
        $this->lang = false;
        $this->bootstrap = true;

        // Defining the list of fields to display
        $this->fields_list = array(
            'id_employee' => array(
                'title' => 'ID',
                'align' => 'center',
                'width' => 30,
            ),
            'firstname' => array(
                'title' => 'First Name',
                'align' => 'center',
            ),
            'lastname' => array(
                'title' => 'Last Name',
                'align' => 'center',
            ),
            'email' => array(
                'title' => 'Email',
                'align' => 'center',
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
        // Define the form structure
        $this->fields_form = array(
            'legend' => array(
                'title' => 'Manager Information',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => 'First Name',
                    'name' => 'firstname',
                    'size' => 20,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => 'Last Name',
                    'name' => 'lastname',
                    'size' => 20,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => 'Email',
                    'name' => 'email',
                    'size' => 20,
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => 'Save',
                'class' => 'btn btn-default pull-right'
            )
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddemployee')) {
            $id_employee = (int)Tools::getValue('id_employee');
            $firstname = pSQL(Tools::getValue('firstname'));
            $lastname = pSQL(Tools::getValue('lastname'));
            $email = pSQL(Tools::getValue('email'));

            $employee = new Employee($id_employee);
            $employee->firstname = $firstname;
            $employee->lastname = $lastname;
            $employee->email = $email;

            if ($employee->save()) {
                Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . $this->token);
            } else {
                $this->errors[] = Tools::displayError('An error occurred while saving the manager.');
            }
        }

        return parent::postProcess();
    }
}
