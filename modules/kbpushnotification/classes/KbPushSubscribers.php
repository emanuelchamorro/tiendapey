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
class KbPushSubscribers extends ObjectModel
{
    public $id_subscriber;
    public $id_lang;
    public $id_shop;
    public $id_guest;
    public $id_country;
    public $country;
    public $reg_id;
    public $is_admin;
    public $ip;
    public $browser;
    public $browser_version;
    public $platform;
    public $device;
    public $token_id;
    public $date_add;
    public $date_upd;
    
//    const TABLE_NAME = 'kb_web_push_subscribers';
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kb_web_push_subscribers',
        'primary' => 'id_subscriber',
        'fields' => array(
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
            'id_country' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_admin' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'reg_id' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'ip' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'browser' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName'
            ),
            'browser_version' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'platform' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'device' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
            ),
            'country' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML',
            ),
            'token_id' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML',
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
    
    public function __construct($id_subscriber = null)
    {
        parent::__construct($id_subscriber);
    }
    
    /*
     * function to get subscriber by guest id
     * @param id_guest
     * @return array
     */
    public static function getPushSubscriber($id_guest, $token = null)
    {
        if (empty($id_guest) && $id_guest == null) {
            return;
        }
        
        return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'kb_web_push_subscribers where id_guest='.(int)$id_guest);
    }
    
    /*
     * function to get listing of registration tokens
     * 
     * @param id_guest, id_shop
     * @return array
     */
    public static function getSubscriberRegIDs($id_guest = null, $id_shop = null)
    {
        $str = '';
        if (!empty($id_guest)) {
            $str .= ' AND id_guest='.(int)$id_guest;
        }
        if (!empty($id_shop)) {
            $str .= ' AND id_shop='.(int)$id_shop;
        }
        $query = Db::getInstance()->executeS('SELECT reg_id FROM '._DB_PREFIX_.'kb_web_push_subscribers Where 1 '.pSQL($str));
        return $query;
    }
    
    /*
     * function to get the subscriber by registation token and id_guest
     * @return array
     */
    public static function getSubscriberbyRegID($reg_id, $id_guest = null)
    {
        $str = '';
        if (!empty($id_guest) && $id_guest != null) {
            $str .= ' AND id_guest='.(int)$id_guest;
            return;
        }
        
        return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'kb_web_push_subscribers where reg_id="'.pSQL($reg_id).'" '.$str);
    }
}
