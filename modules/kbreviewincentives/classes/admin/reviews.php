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

class Reviews extends ObjectModel
{
    public $review_id;
    public $review_title;
    public $author;
    public $description;
    public $product_id;
    public $customer_id;
    public $email;
    public $current_status;
    public $incentive_amount;
    public $ratings;
    public $certified_buyer;
    public $enable_incentive;
    public $helpful_votes;
    public $not_helpful_votes;
    public $date_add;
    public $date_updated;
    
    const TABLE_NAME = 'velsof_product_reviews';
    public static $definition = array(
        'table' => 'velsof_product_reviews',
        'primary' => 'review_id',
        'fields' => array(
            'review_id' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'review_title' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'author' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName'
            ),
            'description' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'email' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'current_status' => array(
                'type' => self::TYPE_INT,
            ),
            'incentive_amount' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isPrice'
            ),
            'ratings' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'not_helpful_votes' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'helpful_votes' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'certified_buyer' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'enable_incentive' => array(
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
