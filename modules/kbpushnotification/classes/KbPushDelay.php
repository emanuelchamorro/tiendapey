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
class KbPushDelay extends ObjectModel
{
    public $id_delay;
    public $id_template;
    public $id_shop;
    public $delay_time;
    public $is_sent;
    public $is_expired;
    public $sent_at;
    public $date_add;
    public $date_upd;
    
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kb_web_push_delay',
        'primary' => 'id_delay',
        'fields' => array(
            'id_delay' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_template' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_sent' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'is_expired' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'delay_time' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
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
    
    public function __construct($id_subscriber = null)
    {
        parent::__construct($id_subscriber);
    }
    
    /*
     * function to get the list of delay notification
     * return array
     */
    public static function getDelayPushWtSend($id_shop = null)
    {
        $str = '';
        if (!empty($id_shop)) {
            $str .= ' AND id_shop='.(int)$id_shop;
        }
        return DB::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'kb_web_push_delay WHERE is_sent=0 AND is_expired=0 '.$str);
    }
}
