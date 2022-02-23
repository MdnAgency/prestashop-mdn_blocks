<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksImageModel.php';


class AdminBlocksImageController extends ModuleAdminController
{

    public function __construct() {
        $this->bootstrap = true;
        $this->lang = true;

        $this->table = BlocksImageModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksImageModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksImageModel::class; //Classe de l'objet

        parent::__construct();


        //Liste des champs de l'objet à afficher dans la liste
        $this->fields_list = [
            'label' => [
                'title' => $this->module->l('Label'),
                'lang' => true,
                'align' => 'left',
            ],
            'technical_id' => [
                'title' => $this->module->l('Shortcode'),
                'lang' => true,
                'align' => 'left',
                'callback'=>'ceciestuntest'
            ],
            'active_image' => [
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
        return '{widget name="mdn_blocks" type="image" hook="'.$value.'"}';
    }

    public function postProcess() {
    //    dump(Tools::getAllValues(), $_FILES);

        foreach (['image'] as $img) {
            if (!empty($_FILES[$img]) && !empty($_FILES[$img]['tmp_name'])) {
                /** @var $uploadedFile UploadedFile */
                $uploadedFile = new UploadedFile($_FILES[$img]['tmp_name'], $_FILES[$img]['name']);
                if (!empty($uploadedFile) && $uploadedFile->getBasename() != "") {
                    if (!$uploadedFile->isValid()) {
                    } else {
                        $dirName = ($uploadedFile->getClientOriginalName());
                        $folder = _PS_CORE_IMG_DIR_ . 'bloc_image' . DIRECTORY_SEPARATOR;

                        $file_name = $dirName;
                        $path = $folder . $file_name;
                        if (!$uploadedFile->move($folder, $file_name)) {
                        }
                    }
                }
            }
        }

       // die();
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
                    'label' => $this->module->l('ID Technique'),
                    'name' => 'technical_id',
                    'size' => 200,
                    'required' => true,
                    'lang' => false,
                    "desc" => "Cet ID est utilisé dans le code pour appeller l'image"
                ],
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
                    'type' => 'file',
                    'label' => $this->module->l('Image (front-office)'),
                    'name' => 'image',
                    'size' => 200,
                    'required' => true,
                    'lang' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->module->l('Description de l\'image (front-office)'),
                    'name' => 'image_alt',
                    'size' => 200,
                    'required' => false,
                    'lang' => true
                ],

                array(
                    'type' => 'select',
                    'label' => $this->l('Actif'),
                    'name' => 'active_image',
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