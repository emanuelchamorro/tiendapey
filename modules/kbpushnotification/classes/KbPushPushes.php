<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future.If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @author    knowband.com <support@knowband.com>
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
* @category  PrestaShop Module
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

//Class and its methods to handle 
class KbPushPushes extends ObjectModel
{
    public $id_push;
//    public $id_lang;
    public $id_shop;
    public $type;
    public $notify_icon;
    public $notify_icon_path;
    public $action_button_link1;
    public $action_button_link2;
    public $primary_url;
    public $title;
    public $message;
    public $action_button1;
    public $action_button2;
    public $is_active;
    public $is_sent;
    public $sent_to;
    public $is_clicked;
    public $schedule_at;
    public $sent_at;
    public $date_add;
    public $date_upd;
    
    const TABLE_NAME = 'kb_web_push_pushes';
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => self::TABLE_NAME,
        'primary' => 'id_push',
        'multilang' => true,
        'fields' => array(
            'id_push' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'notify_icon' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'notify_icon_path' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'primary_url' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'action_button_link1' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'action_button_link2' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ),
            'title' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'lang' => true,
            ),
            'message' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML',
                'lang' => true,
            ),
            'action_button1' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'lang' => true,
            ),
            'action_button2' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'lang' => true,
            ),
            'is_active' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_sent' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'sent_to' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_clicked' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            
            'schedule_at' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ),
            'sent_at' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
            ),
        ),
    );
    
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
}
