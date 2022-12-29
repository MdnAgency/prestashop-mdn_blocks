<?php
require_once "ModuleAdminBlockController.php";
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksModel.php';


class AdminBlocksController extends ModuleAdminBlockController
{
    static $TYPE = "text";

    public function __construct() {
        $this->table = BlocksModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksModel::class; //Classe de l'objet

        parent::__construct();
    }


    /**
     * Callback dans le listing
     * @param int $value
     * @param array $row
     * @return string
     */
    public function dumpShortCode($value,$row){
        return '{widget name="mdn_blocks" type="text" hook="'.$value.'"}';
    }

    public function postProcess() {
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
            'input' => array_merge($this->getCommonFields("text") , [
                [
                    'label' => $this->module->l('Contenu'),
                    'type' => 'textarea',
                    'name' => 'content',
                    'required' => true,
                    'lang' => true, //Flag pour utilisation des langues
                    'rows' => 5,
                    'cols' => 40,
                    'autoload_rte' => true,
                ],
            ]),
            //Boutton de soumission
            'submit' => [
                'name' => 'slider',
                'title' => $this->l('Save'), //On garde volontairement la traduction de l'admin par défaut
            ]
        ];
        return parent::renderForm();
    }
}