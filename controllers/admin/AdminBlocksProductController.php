<?php
require_once "ModuleAdminBlockController.php";

use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksProductModel.php';


class AdminBlocksProductController extends ModuleAdminBlockController
{

    public function __construct() {
        $this->table = BlocksProductModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksProductModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksProductModel::class; //Classe de l'objet

        parent::__construct();
    }


    /**
     * Callback dans le listing
     * @param int $value
     * @param array $row
     * @return string
     */
    public function dumpShortCode($value,$row){
        return '{widget name="mdn_blocks" type="product" hook="'.$value.'"}';
    }

    public function postProcess() {
        //$_POST['categories'] = implode(",", $_POST['categories']);

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
                $this->getCommonFields("product"),
                [
                    array(
                        'type' => 'select',
                        'label' => $this->l('Type de sélecteur'),
                        'name' => 'selector_type',
                        'required' => true,
                        'options' => array(
                            'query' => $options = array(
                                array(
                                    'id_option' => 'category',       // The value of the 'value' attribute of the <option> tag.
                                    'name' => 'Id de Catégorie',    // The value of the text content of the  <option> tag.
                                ),
                                /*array(
                                    'id_option' => 'product_id',
                                    'name' => 'Id Produits',
                                ),*/
                                array(
                                    'id_option' => 'new',
                                    'name' => 'Nouveaux produits',
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),
                    [
                        'type' => 'text',
                        'label' => $this->module->l('ID de la catégorie'),
                        'name' => 'products',
                        'size' => 255,
                        'required' => false,
                        'lang' => true
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Nombre de produit max'),
                        'name' => 'product_limit',
                        'size' => 10,
                        'required' => false,
                        'lang' => false
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