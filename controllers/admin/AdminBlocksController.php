<?php

require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksModel.php';


class AdminBlocksController extends ModuleAdminController
{

    public function __construct() {
        $this->bootstrap = true;
        $this->lang = true;

        $this->table = BlocksModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksModel::class; //Classe de l'objet

        parent::__construct();


        //Liste des champs de l'objet à afficher dans la liste
        $this->fields_list = [
            'label' => [
                'title' => $this->module->l('Label'),
                'lang' => true,
                'align' => 'left',
            ],
            'id' => [
                'title' => $this->module->l('Shortcode'),
                'lang' => true,
                'align' => 'left',
                'callback'=>'ceciestuntest'
            ],
            'active_block' => [
                'title' => $this->module->l('Actif ?'),
                'lang' => true,
                'align' => 'left',
            ]
        ];

        //Ajout d'actions sur chaque ligne
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }


    /**
     * Callback dans le listing
     * @param int $value
     * @param array $row
     * @return string
     */
    public function ceciestuntest($value,$row){
        return '{widget name="mdn_blocks" hook="'.$value.'"}';
    }

    public function postProcess() {
        parent::postProcess();
    }


    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
    }

    public function display() {

        parent::display();
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
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->module->l('Label (uniquement back-office)'),
                    'name' => 'label',
                    'size' => 200,
                    'required' => false,
                    'lang' => false
                ],
                [
                    'type' => 'text',
                    'label' => $this->module->l('Classes CSS (front-office)'),
                    'name' => 'class',
                    'size' => 200,
                    'required' => false,
                    'lang' => false
                ],
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

                array(
                    'type' => 'select',
                    'label' => $this->l('Actif'),
                    'name' => 'active_block',
                    'required' => true,
                    'options' => array(
                        'query' => $options = array(
                            array(
                                'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
                                'name' => 'Oui',    // The value of the text content of the  <option> tag.
                            ),
                            array(
                                'id_option' => 0,
                                'name' => 'Non',
                            ),
                        ),
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
            ],
            //Boutton de soumission
            'submit' => [
                'name' => 'slider',
                'title' => $this->l('Save'), //On garde volontairement la traduction de l'admin par défaut
            ]
        ];
        return parent::renderForm();
    }

    public function renderList() {
        return parent::renderList();
    }
}