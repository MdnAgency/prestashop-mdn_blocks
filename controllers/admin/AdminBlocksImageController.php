<?php
require_once "ModuleAdminBlockController.php";
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksImageModel.php';

use Symfony\Component\HttpFoundation\File\UploadedFile;


class AdminBlocksImageController extends ModuleAdminBlockController
{

    public function __construct() {
        $this->table = BlocksImageModel::$definition['table']; //Table de l'objet
        $this->identifier = BlocksImageModel::$definition['primary']; //Clé primaire de l'objet
        $this->className = BlocksImageModel::class; //Classe de l'objet

        parent::__construct();
    }


    /**
     * Callback dans le listing
     * @param int $value
     * @param array $row
     * @return string
     */
    public function dumpShortCode($value,$row){
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
                $this->getCommonFields('image'),
                [
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