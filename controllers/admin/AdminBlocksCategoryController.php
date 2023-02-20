<?php
require_once "ModuleAdminBlockController.php";

use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksCategoryModel.php';


class AdminBlocksCategoryController extends ModuleAdminBlockController
{

    public function __construct() {
        $this->table = BlocksCategoryModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksCategoryModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksCategoryModel::class; //Classe de l'objet

        parent::__construct();
    }


    /**
     * Callback dans le listing
     * @param int $value
     * @param array $row
     * @return string
     */
    public function dumpShortCode($value,$row){
        return '{widget name="mdn_blocks" type="category" hook="'.$value.'"}';
    }

    public function postProcess() {
        if(isset($_POST['categories'])) {
            $_POST['categories'] = implode(",", $_POST['categories']);
        }

       // die();
        parent::postProcess();
    }

    public function renderForm()
    {
        //Définition du formulaire d'édition
        $this->fields_form = [
            //Entête
            'legend' => [
                'title' => $this->module->l('Bloc'),
                'icon' => 'icon-cog'
            ],
            //Champs
            'input' => array_merge(
                $this->getCommonFields("category"),
                [
                    [
                        'type'  => 'categories',
                        'label' => $this->trans('Categories list', [], 'Modules.Mdnfeaturedcategories.Mdnfeaturedcategories'),
                        'name' => 'categories',
                        'lang' => true,
                        'tree' => [
                            'id' => 'categories',
                            'use_checkbox' => true,
                            'selected_categories' =>   (explode(",", $this->object->categories[1]))
                        ]
                    ],
                ]
            ),
            //Boutton de soumission
            'submit' => [
                'name' => 'slider',
                'title' => $this->l('Save'), //On garde volontairement la traduction de l'admin par défaut
            ]
        ];


        return parent::renderForm();
    }
}
