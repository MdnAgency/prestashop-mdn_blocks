<?php
class ModuleAdminBlockController extends ModuleAdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->lang = true;
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
                'callback'=>'dumpShortCode'
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

    public function getAvailableTemplates($type = "text") {
        return array_unique(
            array_merge(
                array_map(function ($v) {
                    $l = explode(DIRECTORY_SEPARATOR, $v);
                    return str_replace(".tpl", "", end($l));
                }, glob(__DIR__."/../../views/templates/widget/$type/*.tpl")),
                array_map(function ($v) {
                    $l = explode(DIRECTORY_SEPARATOR, $v);
                    return str_replace(".tpl", "", end($l));
                }, glob(_PS_THEME_DIR_."/modules/mdn_blocks/views/templates/widget/$type/*.tpl"))
            )
        );
    }

    public function getCommonFields($type) {
        return [
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
            array(
                'type' => 'select',
                'label' => $this->l('Template'),
                'name' => 'template',
                'required' => true,
                'options' => array(
                    'query' => array_map(function ($v) {
                        return [
                            'id_option' => $v,
                            'name' => $v,
                        ];
                    }, $this->getAvailableTemplates($type)),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
            ),
        ];
    }


    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'mdn_blocks/admin/js/admin.js?v=1');
    }


    public function display() {
        parent::display();
    }

    public function renderList() {
        return parent::renderList();
    }
}