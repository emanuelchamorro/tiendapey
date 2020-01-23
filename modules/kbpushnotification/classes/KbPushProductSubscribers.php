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

//Class and its methods to handle 
class KbPushProductSubscribers extends ObjectModel
{
    public $id_mapping;
    public $id_subscriber;
    public $id_lang;
    public $id_shop;
    public $id_guest;
    public $id_product;
    public $id_product_attribute;
    public $currency_iso;
    public $product_price;
    public $subscribe_type;
    public $reg_id;
    public $is_sent;
    public $is_clicked;
    public $browser;
    public $browser_version;
    public $platform;
    public $token_id;
    public $sent_at;
    public $date_add;
    public $date_upd;
    
//    const TABLE_NAME = 'kb_web_push_subscribers';
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kb_web_push_product_subscriber_mapping',
        'primary' => 'id_mapping',
        'fields' => array(
            'id_mapping' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_subscriber' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_lang' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_guest' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_product' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_product_attribute' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_sent' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_clicked' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'currency_iso' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ),
            'product_price' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'reg_id' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'subscribe_type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ),
            'sent_at' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
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
    
    public function __construct($id_mapping = null)
    {
        parent::__construct($id_mapping);
    }
    
    /*
     * function to get the subscriber based on token and guest id
     * return array
     */
    public static function getSubscriberByRegID($reg_id, $id_guest = null)
    {
        $str = '';
        if (!empty($id_guest)) {
            $str .= ' AND id_guest='.(int)$id_guest;
        }
        return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'kb_web_push_product_subscriber_mapping where reg_id="'.pSQL($reg_id).'" '.$str);
    }
    
    /*
     * function to get the product subscriber based on id_product, token
     * 
     * @param  id_product, token, id_guest
     * @return array
     */
    public static function getSubscriberByProductANDRegID($id_product, $reg_id, $id_guest = null, $type = null)
    {
        $str = '';
        if (empty($id_product) || empty($reg_id)) {
            return;
        }
        if (!empty($id_guest)) {
            $str .= ' AND id_guest='.(int)$id_guest;
        }
        if (!empty($type)) {
            $type = trim($type);
            $str .= ' AND subscribe_type="'.pSQL($type).'"';
        }
        return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'kb_web_push_product_subscriber_mapping where id_product='.(int)$id_product.' AND reg_id="'.pSQL($reg_id).'" '.$str);
    }
}
