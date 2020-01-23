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
class KbPushTemplates extends ObjectModel
{
    public $id_template;
    public $notification_type;
    public $notify_icon;
    public $notify_icon_path;
    public $action_button_link1;
    public $action_button_link2;
    public $primary_url;
    public $notification_title;
    public $notification_message;
    public $action_button1;
    public $action_button2;
    public $active;
    public $date_add;
    public $date_upd;
    
    const TABLE_NAME = 'kb_web_push_template';
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => self::TABLE_NAME,
        'primary' => 'id_template',
        'multilang' => true,
        'multishop' => true,
        'multilang_shop' => true,
        'fields' => array(
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
            'notification_type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ),
            'notification_title' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'lang' => true,
            ),
            'notification_message' => array(
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
            'active' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
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
    
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation(self::TABLE_NAME, array('type' => 'shop'));
        parent::__construct($id, $id_lang, $id_shop);
    }
    
    /*
     * function to get listing of push templates 
     * 
     * @return array
     */
    public static function getNotificationTemplates()
    {
        return DB::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'kb_web_push_template p');
    }
    
    /*
     * function to get template id by template type
     * 
     * @param notification_type
     * @return boolean or integer
     */
    public static function getNotificationTemplateIDByType($type = null)
    {
        if ($type == null || empty($type)) {
            return;
        }
        return DB::getInstance()->getValue('SELECT id_template FROM '._DB_PREFIX_.'kb_web_push_template p WHERE notification_type="'.pSQL($type).'"');
    }
}
