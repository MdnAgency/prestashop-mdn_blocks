<?php
class BlocksImageModel extends ObjectModel {
    public static $definition = array(
        'table' => 'mdn_blocks_image',
        'primary' => 'id',
        // 'multishop' => true,
        // 'multilang' => true,
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'id' =>                 array('type' => self::TYPE_INT,     'validate' => 'isUnsignedInt', 'required' => false),
            'technical_id' =>        array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => false),
            'label' =>        array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => true),
            'class' =>         array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => false),
            'image' =>            array('type' => self::TYPE_STRING,    'validate' => 'isString', 'required' => false, 'lang' => true),
            'image_alt' =>         array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => false,  'lang' => true),
            'active_image' =>       array('type' => self::TYPE_BOOL,    'validate' => 'isUnsignedInt', 'required' => false),
        ),
    );

    public $id;
    public $technical_id;
    public $label;
    public $image_alt;
    public $class;

    public $image;

    public $active_image;

    public function __construct($id_primario = null, $id_lang = null)
    {
        parent::__construct($id_primario, $id_lang);
    }

    public static function createContentTable()   {


        $sq1 = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'`(
            `id` int(10) unsigned NOT NULL auto_increment,
            `id_shop` int(10) unsigned NOT NULL  ,
            `technical_id` VARCHAR(256) NOT NULL,
            `label` VARCHAR(256) NOT NULL,
            `class` VARCHAR(256) NOT NULL, 
            `active_image` int(1) NOT NULL, 
            PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        $sq3 = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang`(
            `id` int(10) unsigned NOT NULL auto_increment,
            `id_shop` int(10) unsigned NOT NULL,
            `id_lang` int(10) NOT NULL, 
            `image` VARCHAR(256) NOT NULL,
            `image_alt` VARCHAR(256) NOT NULL,
            PRIMARY KEY (`id`, `id_lang`) 
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        $result = Db::getInstance()->execute($sq1)
            && Db::getInstance()->execute($sq3);

        return $result;
    }
}
