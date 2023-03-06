<?php
class BlocksModel extends ObjectModel {
    public static $definition = array(
        'table' => 'mdn_blocks',
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

            'content' =>            array('type' => self::TYPE_HTML,    'validate' => 'isString', 'required' => false, 'lang' => true),
            'template' =>         array('type' => self::TYPE_STRING,  'validate' => 'isString', 'required' => false),

            'active_block' =>       array('type' => self::TYPE_BOOL,    'validate' => 'isUnsignedInt', 'required' => false),
        ),
    );

    public $id;
    public $technical_id;
    public $label;
    public $class;
    public $content;

    public $template;
    public $active_block;

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
            `template` VARCHAR(256) NOT NULL, 
            `active_block` int(1) NOT NULL, 
            PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        $sq3 = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang`(
            `id` int(10) unsigned NOT NULL auto_increment,
            `id_shop` int(10) unsigned NOT NULL,
            `id_lang` int(10) NOT NULL, 
            `content` text NOT NULL,
            PRIMARY KEY (`id`, `id_lang`) 
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        $result = Db::getInstance()->execute($sq1)
            && Db::getInstance()->execute($sq3);

        return $result;
    }
}
