<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
*/

class ReminderProfile extends ObjectModel
{
    public $reminder_profile_id;
    public $select_type;
    public $no_of_days_after;
    public $active;
    public $enable_order_create_reminder;
    public $date_add;
    public $date_updated;
    
    const TABLE_NAME = 'velsof_reminder_profile';
    
    public static $definition = array(
        'table' => 'velsof_reminder_profile',
        'primary' => 'reminder_profile_id',
        'fields' => array(
            'select_type' => array(
                'type' => self::TYPE_STRING,
            ),
            'no_of_days_after' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'enable_order_create_reminder' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
            ),
             'date_updated' => array(
                'type' => self::TYPE_DATE,
            )
        )
    );
    
    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
    }
}
