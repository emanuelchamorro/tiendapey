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
class KbPushSubscriberMapping extends ObjectModel
{
    public $id_mapping;
    public $id_push;
    public $id_shop;
    public $reg_id;
    public $date_add;
    
//    const TABLE_NAME = 'kb_web_push_subscribers';
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kb_web_push_subscriber_mapping',
        'primary' => 'id_mapping',
        'fields' => array(
            'id_mapping' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_push' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'reg_id' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHTML'
            ),
            'date_add' => array(
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
}
