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
 */

require_once dirname(__FILE__) . '/AdminKbPushCoreController.php';
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushProductSubscribers.php');

class AdminKbPushProductSubscribersController extends AdminKbPushCoreController
{
    public $all_languages = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'kb_web_push_product_subscriber_mapping';
        $this->className = 'KbPushProductSubscribers';
        $this->identifier = 'id_subscriber';
        $this->display = 'list';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Product Alert Subscribers', 'AdminKbPushProductSubscribersController');
        

        $this->fields_list = array(
            'id_subscriber' => array(
                'title' => $this->module->l('Subscriber ID', 'AdminKbPushProductSubscribersController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'image' => array(
                'title' => $this->module->l('Image', 'AdminKbPushProductSubscribersController'),
                'align' => 'center',
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'callback' => 'showCoverImage'
            ),
            'name' => array(
                'title' => $this->module->l('Name', 'AdminKbPushProductSubscribersController'),
                'filter_key' => 'pl!name'
            ),
            'reference' => array(
                'title' => $this->module->l('SKU', 'AdminKbPushProductSubscribersController'),
            ),
            'product_price' => array(
                'title' => $this->module->l('Product Price when subscribed', 'AdminKbPushProductSubscribersController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
                'type' => 'price',
                 'currency' => true,
            ),
            'subscribe_type' => array(
                'title' => $this->module->l('Notificaiton Type', 'AdminKbPushProductSubscribersController'),
                'type' => 'select',
                'list' => array('price' => $this->module->l('Price', 'AdminKbPushProductSubscribersController'), 'stock' => $this->module->l('Stock', 'AdminKbPushProductSubscribersController')),
                'filter_key' => 'subscribe_type'
            ),
            'date_add' => array(
                'title' => $this->module->l('Subscribed Date', 'AdminKbPushProductSubscribersController'),
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
        );
        $this->_select .= 'a.id_shop,pl.`name`,p.reference, i.`id_image` as image';
        $this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (a.`id_product` = p.`id_product`) ';
        $this->_join .= ' JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (a.`id_product` = pl.`id_product` AND pl.id_lang = ' . (int)$this->context->language->id.' AND pl.id_Shop='.(int)$this->context->shop->id.')';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` ims ON (a.`id_product` = ims.`id_product` AND ims.`cover` = 1 AND ims.id_shop = ' . (int) $this->context->shop->id . ')';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (ims.`id_image` = i.`id_image`)';
        $this->_where = ' AND a.id_shop='.(int)$this->context->shop->id;

        $this->_orderWay = 'DESC';
    }
    
    public function renderList()
    {
        return parent::renderList();
    }
    
    public function showCoverImage($id_row, $row_data)
    {
        if (!empty($row_data['id_product'])) {
            $product = new Product($row_data['id_product']);
            $coverImage = $product->getCover($row_data['id_product']);

            if (!empty($coverImage)) {
                $path_to_image = _PS_IMG_DIR_ . 'p/' . Image::getImgFolderStatic($coverImage['id_image']) . (int) $coverImage['id_image'] . '.' . $this->imageType;
                return ImageManagerCore::thumbnail($path_to_image, 'product_mini_' . $row_data['id_product'] . '_' . $this->context->shop->id . '.' . $this->imageType, 45, $this->imageType);
            }
        }
    }
    
    public function showAttributes($id_row, $row_data)
    {
        $output = '';
        if (!empty($row_data['id_product_attribute'])) {
            $productAttribute = new Product($row_data['id_product']);
            $attributesList = $productAttribute->getAttributeCombinationsById($row_data['id_product_attribute'], $this->context->employee->id_lang);

            if (!empty($attributesList)) {
                foreach ($attributesList as $attributesList) {
                    if (!empty($output)) {
                        $output .= ' | ';
                    }
                    $output .= '<b>' . $attributesList['group_name'] . ':</b> ' . $attributesList['attribute_name'];
                }
            }
        }
        return $output;
    }
    
    /**
     * Prestashop Default Function in AdminController.
     * Assign smarty variables for all default views, list and form, then call other init functions
     */
    public function initContent()
    {
        if (isset($this->context->cookie->kb_redirect_error)) {
            $this->errors[] = $this->context->cookie->kb_redirect_error;
            unset($this->context->cookie->kb_redirect_error);
        }

        if (isset($this->context->cookie->kb_redirect_success)) {
            $this->confirmations[] = $this->context->cookie->kb_redirect_success;
            unset($this->context->cookie->kb_redirect_success);
        }
        

        parent::initContent();
    }
    

    /*
     * Prestashop Default Function in AdminController.
     * handles the process input, process Ajax etc. 
     */

    public function postProcess()
    {
        parent::postProcess();
    }

    /**
     * Prestashop Default Function in AdminController.
     * Init context and dependencies, handles POST and GET
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * Function used display toolbar in page header
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
    }
    
    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }
}
