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

class Criteria extends ObjectModel
{
    public $product_id;
    public $product_name;
    public $date_add;
    public $date_updated;
    
    const TABLE_NAME = 'velsof_products_review_incentive';
    
    public static $definition = array(
        'table' => 'velsof_products_review_incentive',
        'primary' => 'kbrc_product_id',
        'fields' => array(
            'product_id' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName'
            ),
            'product_name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName'
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
