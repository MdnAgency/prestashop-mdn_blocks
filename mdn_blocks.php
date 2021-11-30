<?php
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksModel.php';
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}
class Mdn_Blocks extends Module implements WidgetInterface {
    public $tabs = [
        [
            'name' => 'Blocs',
            'class_name' => 'AdminBlocks',
            'visible' => true,
            'parent_class_name' => 'IMPROVE',
            'icon' => 'desktop_mac'
        ]
    ];

    const SLIDER_CONTROLLER = "AdminBlocks";

    public function __construct()
    {
        $this->name = 'mdn_blocks';
        $this->author = 'Maison du Net - Loris';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
        parent::__construct();

    }


    public function install()
    {
        BlocksModel::createContentTable();

        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }


    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->active) {
            return;
        }

        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->display(__FILE__, 'views/templates/widget/blocks.tpl');
    }


    public function getWidgetVariables($hookName, array $configuration)
    {
        $Slider =  new PrestaShopCollection('BlocksModel');
        $results = $Slider
            ->where('active_block', '=', '1')
            ->where('id', '=', strtoupper($hookName))
            ->getAll();

        $lang = Configuration::get('PS_LANG_DEFAULT');


        return ['results' => array_map(function ($v) use($lang) {
            return [
                'content' => $v->content[$lang],
                'class' => $v->class
            ];
        }, $results->getResults()), 'lang' => $lang];
    }
}