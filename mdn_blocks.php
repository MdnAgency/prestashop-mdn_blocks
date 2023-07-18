<?php
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksModel.php';
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksImageModel.php';
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksCategoryModel.php';
require_once _PS_MODULE_DIR_ . '/mdn_blocks/classes/BlocksProductModel.php';
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

if (!defined('_PS_VERSION_')) {
    exit;
}
class Mdn_Blocks extends Module implements WidgetInterface {
    /**
     * Menu definition
     * @var array[]
     */
    public $tabs = [
        [
            'name' => 'Blocs de contenu',
            'class_name' => 'AdminBlocks',
            'visible' => true,
            'parent_class_name' => 'IMPROVE',
            'icon' => 'desktop_mac'
        ],
        [
            'name' => 'Blocs de contenu',
            'class_name' => 'AdminBlocks',
            'visible' => true,
            'parent_class_name' => 'AdminBlocks',
            'icon' => 'desktop_mac'
        ],
        [
            'name' => 'Blocs image',
            'class_name' => 'AdminBlocksImage',
            'visible' => true,
            'parent_class_name' => 'AdminBlocks',
            'icon' => 'desktop_mac'
        ],
        [
            'name' => 'Blocs catégories',
            'class_name' => 'AdminBlocksCategory',
            'visible' => true,
            'parent_class_name' => 'AdminBlocks',
            'icon' => 'desktop_mac'
        ],
        [
            'name' => 'Blocs produits',
            'class_name' => 'AdminBlocksProduct',
            'visible' => true,
            'parent_class_name' => 'AdminBlocks',
            'icon' => 'desktop_mac'
        ]
    ];


    public function __construct()
    {
        $this->name = 'mdn_blocks';
        $this->author = 'Maison du Net - Loris';
        $this->tab = 'front_office_features';
        $this->version = '1.4.0';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
        $this->displayName = "Blocs éditables";
        $this->description = "Gérer vos blocs de contenu";
        parent::__construct();

    }


    /**
     * Register Models & Hooks
     * @return bool
     */
    public function install()
    {
        BlocksModel::createContentTable();
        BlocksImageModel::createContentTable();
        BlocksCategoryModel::createContentTable();
        BlocksProductModel::createContentTable();

        return parent::install() && $this->registerHook("ActionRegisterBlock");
    }

    /**
     * Unregister Hooks (we keep models)
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall() && $this->unregisterHook("ActionRegisterBlock");
    }


    /**
     * Render widget
     * @param $hookName
     * @param array $configuration
     * @return false|string|null
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->active) {
            return null;
        }

        $vars = $this->getWidgetVariables($hookName, $configuration);

        $this->smarty->assign($vars);


        if (count($vars['results']) >= 1 && !empty($vars['results'][0]['template'])) {
            if (!empty($configuration['type']))
                return $this->display(__FILE__, 'views/templates/widget/' . $configuration['type'] . '/' . $vars['results'][0]['template'] . '.tpl');

            return $this->display(__FILE__, 'views/templates/widget/text/' . $vars['results'][0]['template'] . '.tpl');
        } else {
            $this->smarty->assign([
                'dev' => _PS_MODE_DEV_ || true,
                'type' => $configuration['type'],
                'hook' => $configuration['hook']
            ]);
            return $this->display(__FILE__, 'views/templates/widget/empty.tpl');
        }
    }


    /**
     * Get widget variable based on inputed configuration
     * @param $hookName
     * @param array $configuration
     * @return array|void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ReflectionException
     */
    public function getWidgetVariables($hookName, array $configuration)
    {
        $lang = $this->context->language->id ?? Configuration::get('PS_LANG_DEFAULT');

        $model = $this->getModelFromType($configuration['type']);

        $Collection = new PrestaShopCollection($model);
        $results = $Collection
            ->where('active_block', '=', '1')
            ->where('technical_id', '=', ($hookName))
            ->getAll();

        switch ($configuration['type']) {
            case "image":
                return ['results' => array_map(function ($v) use ($lang) {
                    return [
                        'template' => $v->template,
                        'image_alt' => $v->image_alt[$lang],
                        'image' => $v->image[$lang],
                        'class' => $v->class
                    ];
                }, $results->getResults()), 'lang' => $lang];
            case "category":
                return ['results' => array_map(function ($v) use ($lang, $configuration) {
                    $listing_category = !empty($configuration['categories']) ? $configuration['categories'] : $v->categories[$lang];

                    if($listing_category != "") {
                        $categories = explode(",", $listing_category);
                        $categories = array_map(function ($v) use ($lang) {
                            return new Category($v, $lang);
                        }, $categories);
                        $categories = array_filter($categories, function ($v) { return $v->id !== null; });
                    }
                    else {
                        $categories = [];
                    }
                    return [
                        'template' => $v->template,
                        'categories' => $categories,
                        'class' => $v->class
                    ];
                }, $results->getResults()), 'lang' => $lang];
            case "text":
                return ['results' => array_map(function ($v) use($lang) {
                    return [
                        'template' => $v->template,
                        'content' => $v->content[$lang],
                        'class' => $v->class
                    ];
                }, $results->getResults()), 'lang' => $lang];
            case "product":
                return ['results' => array_map(function ($v) use ($lang) {
                    $products = [];
                    if($v->selector_type == "category") {
                        $products = (new Category($v->products[$lang], $lang))->getProducts($lang, 0, $v->product_limit);
                    }
                    else if($v->selector_type == "new") {
                        $products = Product::getNewProducts($lang, 0, $v->product_limit);
                    }
                    else {
                        $products = [];
                    }

                    $assembler = new ProductAssembler($this->context);

                    $presenterFactory = new ProductPresenterFactory($this->context);
                    $presentationSettings = $presenterFactory->getPresentationSettings();
                    $presenter = new ProductListingPresenter(
                        new ImageRetriever(
                            $this->context->link
                        ),
                        $this->context->link,
                        new PriceFormatter(),
                        new ProductColorsRetriever(),
                        $this->context->getTranslator()
                    );

                    $products_for_template = array();

                    if (is_array($products)) {
                        foreach ($products as $rawProduct) {
                            $products_for_template[] = $presenter->present(
                                $presentationSettings,
                                $assembler->assembleProduct($rawProduct),
                                $this->context->language
                            );
                        }
                    }

                    return [
                        'template' => $v->template,
                        'products' => $products_for_template,
                        'class' => $v->class
                    ];
                }, $results->getResults()), 'lang' => $lang];
        }
    }

    /**
     * Register Block for compatibility with PRETTYBLOCKS module
     * @return array
     * @throws PrestaShopException
     */
    public function hookActionRegisterBlock()
    {
        $blocks = [];

        foreach (['text', 'image', 'category', 'product'] as $item) {
            $model = $this->getModelFromType($item);

            // get all of collection
            $Collection = new PrestaShopCollection($model);
            $results = $Collection
                ->getAll();

            // if collection is empty we don't want any block to be display
            if(count($results) == 0)
                continue;

            // work around for the module unless they fix
            $choices = [
                'default' => 'default',
            ];

            // add any line
            foreach ($results as $result) {
                $choices[$result->technical_id] = $result->technical_id;
            }

            $blocks[] =  [
                'name' => "@mdn_blocks - " .$item,
                'description' => "Support for MDN Blocks Widget Injection ".$item,
                'code' => 'mdn_blocks_'.$item,
                'tab' => 'general',
                'icon' => 'ArchiveBoxIcon',
                'need_reload' => true,
                'templates' => [
                    // dynamic template
                    'default' => 'module:'.$this->name.'/views/templates/support/prettyblocks/' . $item .  '.tpl'
                ],
                'config' => [
                    'fields' => [
                        'key' => [
                            'type' => 'select', // type of field
                            'label' => 'Choose a value', // label to display
                            'default' => count($choices) >= 1 ? array_values($choices)[0] : null, // default value based on first choice
                            'choices' => $choices
                        ],
                    ],
                ],
            ];
        }

        // get block listing
        return $blocks;
    }

    /**
     * Transform a type into a model
     * @param $type
     * @return string|null
     */
    public function getModelFromType($type) {
        $model = null;

        switch ($type) {
            case "image":
                $model = 'BlocksImageModel';
                break;
            case "category":
                $model = 'BlocksCategoryModel';
                break;
            case "text":
                $model = 'BlocksModel';
                break;
            case "product":
                $model = 'BlocksProductModel';
                break;
        }

        return $model;
    }
}