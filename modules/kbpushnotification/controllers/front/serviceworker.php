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
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushPushes.php');

class KbpushnotificationServiceworkerModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    public $display_column_right = false;
    
    public function __construct()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
//        $this->ssl = true;
        parent::__construct();
    }
    
    public function initContent()
    {
        parent::initContent();
    }
    
    public function postProcess()
    {
        /*
         * To update whether subscriber clicked on notification or not
         */
        if (Tools::getValue('action') == 'updateClickPush') {
            $push_id = Tools::getValue('push_id');
            if (!empty($push_id)) {
                $kbpush = new KbPushPushes($push_id);
                $kbpush->is_clicked = (int) $kbpush->is_clicked + 1;
                $kbpush->schedule_at = '';
                if ($kbpush->update()) {
                    echo true;
                }
            }
            die;
        }
         
        /*
         * function to register/update the subscriber after service worker
         */
        if (Tools::getValue('action') == 'registerServiceWorker') {
            $json = array();
            $reg_id = Tools::getValue('reg_id');
            if (!empty($reg_id)) {
                $id_guest = Context::getContext()->cookie->id_guest;
                $check_reg_exist = KbPushSubscribers::getSubscriberbyRegID($reg_id);
                $exist_subscriber = KbPushSubscribers::getPushSubscriber($id_guest);
                $pushSubscriber = new KbPushSubscribers();
                
                if (!empty($check_reg_exist)) {
                    $id_subscriber = $check_reg_exist['id_subscriber'];
                    $pushSubscriber = new KbPushSubscribers($id_subscriber);
                } else {
                    if (!empty($exist_subscriber)) {
                        $id_subscriber = $exist_subscriber['id_subscriber'];
                        $pushSubscriber = new KbPushSubscribers($id_subscriber);
                    }
                }

                $pushSubscriber->id_guest = $id_guest;
                $is_admin = Tools::getValue('isAdmin');
                $id_lang = Context::getContext()->language->id;
                $id_shop = Context::getContext()->shop->id;
                $browser = Tools::getValue('browser');
                $browser_version = Tools::getValue('browser_version');
                $platform = Tools::getValue('platform');
                $pushSubscriber->id_lang = $id_lang;
                $pushSubscriber->id_shop = $id_shop;
                $pushSubscriber->reg_id = $reg_id;
                $pushSubscriber->browser = $browser;
                $pushSubscriber->browser_version = $browser_version;
                $pushSubscriber->platform = $platform;

                $ip_addr = kbPushnotification::getRemoteAddr();
                $pushSubscriber->ip = $ip_addr;
                require_once(_PS_MODULE_DIR_.$this->module->name.'/libraries/ip_location/IpLocation/Ip2.php');
                require_once(_PS_MODULE_DIR_.$this->module->name.'/libraries/ip_location/IpLocation/Service/CsvWebhosting.php');
                require_once(_PS_MODULE_DIR_.$this->module->name.'/libraries/ip_location/IpLocation/Service/GeoIp.php');
                $objIpLocationObject = new \IpLocation_Ip(new \IpLocation_Service_GeoIp());

                $results = $objIpLocationObject->getIpLocation($ip_addr);
                if ($results !== false) {
                    $country_iso_code = ($results->country2Char !== null) ? $results->country2Char : "--";
                } else {
                    $country_iso_code = $this->module->l('Unknown Country', 'serviceworker');
                }
                $pushSubscriber->country = $country_iso_code;

                if (!empty($country_iso_code)) {
                    if (Validate::isLanguageIsoCode($country_iso_code)) {
                        $pushSubscriber->id_country = Country::getByIso($country_iso_code);
                    }
                }

                //is_admin
                $pushSubscriber->is_admin = $is_admin;

                require_once(_PS_VENDOR_DIR_ . 'mobiledetect/mobiledetectlib/Mobile_Detect.php');
                $detect = new Mobile_Detect();
                $device = ($detect->isMobile() ? ($detect->isTablet() ? 'Tablet' : 'Mobile') : 'Desktop');
                $pushSubscriber->device = $device;

                if ($pushSubscriber->save()) {
                    Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'kb_web_push_product_subscriber_mapping set id_guest='.(int)$id_guest.' where reg_id="'.pSQL($reg_id).'"');
                    $subscriber_id = $pushSubscriber->id;
                    $json['success'] = $this->module->l('Subscriber saved successfully.', 'serviceworker');
                    $json['subscriber_id'] = $subscriber_id;
                    echo json_encode($json);
                    die;
                }
            }
            $json['error'] = $this->module->l('Error in retriving token', 'serviceworker');
            echo json_encode($json);
            die;
        }
    }
}
