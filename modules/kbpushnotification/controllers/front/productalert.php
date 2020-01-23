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

require_once(_PS_ROOT_DIR_ . '/init.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/kbpushnotification.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushSubscribers.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushSubscriberMapping.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushProductSubscribers.php');

class KbpushnotificationProductalertModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    public $display_column_right = false;
    
    public function __construct()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        parent::__construct();
    }
    
    public function initContent()
    {
        parent::initContent();
    }
    
    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getValue('ajax')) {
            if (Tools::getValue('action') == 'signup') {
                $json = array();
                $reg_id = trim(Tools::getValue('reg_id'));
                $id_product = Tools::getValue('id_product');
                if (!empty($reg_id) && !empty($id_product)) {
                    $id_guest = Context::getContext()->cookie->id_guest;
                    $id_shop = Context::getContext()->shop->id;
                    $id_lang = Context::getContext()->language->id;
                    $id_product_attribute = Tools::getValue('id_product_combination');
                    $product_price = Tools::getValue('actual_price');
                    $type = Tools::getValue('subscribe_type');
                    $exist_subscriber = DB::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'kb_web_push_subscribers WHERE id_guest='.(int)$id_guest.' AND reg_id="'.pSQL($reg_id).'"');
                    if (!empty($exist_subscriber)) {
                        $exist_product_subscriber = DB::getInstance()->getRow(
                            'SELECT * FROM '._DB_PREFIX_.'kb_web_push_product_subscriber_mapping'
                            . ' where id_guest='.(int)$id_guest.' AND id_product='.(int)$id_product
                            .' AND id_product_attribute='.(int)$id_product_attribute.' AND subscribe_type="'.pSQL($type).'"'
                        );
                        $kbProduct = new KbPushProductSubscribers();
                        $is_exist = 0;
                        if (!empty($exist_product_subscriber)) {
                            $kbProduct = new KbPushProductSubscribers($exist_product_subscriber['id_mapping']);
                            if ($kbProduct->subscribe_type != $type) {
                                $kbProduct = new KbPushProductSubscribers();
                            } else {
                                $is_exist = 1;
                            }
                        }
                        $kbProduct->id_guest = $id_guest;
                        $kbProduct->id_lang = $id_lang;
                        $kbProduct->id_shop = $id_shop;
                        $kbProduct->id_subscriber = $exist_subscriber['id_subscriber'];
                        $kbProduct->id_product = $id_product;
                        $kbProduct->id_product_attribute = $id_product_attribute;
                        $kbProduct->product_price = $product_price;
                        $kbProduct->subscribe_type = $type;
                        $kbProduct->reg_id = $reg_id;
                        $kbProduct->sent_at = '';
                        $kbProduct->currency_iso = Context::getContext()->currency->iso_code;
                        if ($kbProduct->save()) {
                            if ($is_exist) {
                                $json['success'] = $this->module->l('You are already registered for the notification.', 'productalert');
                            } else {
                                $json['success'] = $this->module->l('You are successfully registered to get notification.', 'productalert');
                            }
                        } else {
                            $json['error'] = $this->module->l('Sorry, You are not registered to get notification.', 'productalert');
                        }
                    } else {
                        $json['error'] = $this->module->l('You have to subscribe before registering for the notification.', 'productalert');
                    }
                } else {
                    $json['error'] = $this->module->l('You have to subscribe before registering for the notification.', 'productalert');
                }
                echo json_encode($json);
                die();
            }
        }
    }
    
    /*
     * function for Returning the Base URL of the store
     */
    protected function getBaseUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_ ;
        } else {
            $module_dir = _PS_BASE_URL_ ;
        }
        return $module_dir;
    }
    
    /*
     * Function for checking SSL
     */
    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Function to get the URL upto module directory
     */
    private function getModuleDirUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        return $module_dir;
    }
}
