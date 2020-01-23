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

require_once(_PS_ROOT_DIR_ . '/init.php');
//Include Walmart Module Class to inherit some common functions and callbacks
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/kbpushnotification.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushDelay.php');
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushSubscribers.php');

class KbpushnotificationCronModuleFrontController extends ModuleFrontController
{

    public $php_self = 'cron';
    protected $Walmart = '';
    

    //function defination to execute first
    public function init()
    {
//        parent::init();
        if (!Tools::isEmpty(trim(Tools::getValue('action')))) {
            $action = Tools::getValue('action');
            switch ($action) {
                case 'syncdelaypush':
                    $this->syncDelayPush();
                    break;
                case 'syncabandonedcart':
                    $this->syncAbandonedCartPush();
                    break;
            }
        }
        die;
    }
    
    /*
     * function to send Abandoned Cart push notification
     */
    public function syncAbandonedCartPush()
    {
        if (Tools::getValue('secure_key') === Configuration::get('KB_WEB_PUSH_CRON_2')) {
            $config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
            if (!empty($config) && isset($config['module_config']['enable'])) {
                if ($config['module_config']['enable_abandoned_cart']) {
                    if ($this->syncAbandonedCart()) {
                        echo $this->module->l('Success', 'cron');
                    }
                }
            } else {
                echo $this->module->l('Please enable the Abandoned Cart Push from Module Configuration', 'cron');
            }
        } else {
            echo $this->module->l('Security Token is invalid or expired', 'cron');
        }

        die;
    }
    
    /*
     * function to send delay push notification
     */
    public function syncDelayPush()
    {
        if (Tools::getValue('secure_key') === Configuration::get('KB_WEB_PUSH_CRON_1')) {
            if ($this->syncDelayWebPush()) {
                echo $this->module->l('Success', 'cron');
            }
        } else {
            echo $this->module->l('Security Token is invalid or expired', 'cron');
        }

        die;
    }
    
    /*
     * function defined to send abandoned cart notification to the subscribers
     */
    protected function syncAbandonedCart()
    {
        $abandoned_cart_data = $this->getAbandonedCart();

        if (!empty($abandoned_cart_data)) {
            $reg_data = array();
            foreach ($abandoned_cart_data as $abd_cart_data) {
                $id_cart = $abd_cart_data['id_cart'];
                $cart = new Cart($id_cart);
                if ($cart->nbProducts() > 0) {
                    if (!empty($abd_cart_data['id_customer'])) {
                        $id_guest = Db::getInstance()->getValue('SELECT id_guest FROM ' . _DB_PREFIX_ . 'guest WHERE id_customer=' . (int) $abd_cart_data['id_customer']);
                    } else {
                        $id_guest = $abd_cart_data['id_guest'];
                    }
                    
                    $cart_total = Cart::getTotalCart($id_cart, true, Cart::BOTH_WITHOUT_SHIPPING);
                    $subscriber = KbPushSubscribers::getPushSubscriber($id_guest);
                    if (!empty($subscriber)) {
                        $id_shop = $subscriber['id_shop'];
                        $id_lang = $subscriber['id_lang'];
                        $reg_id = $subscriber['reg_id'];
                        if ($id_shop == $abd_cart_data['id_shop'] && !empty($reg_id)) {
                            $fcm_setting = Tools::jsonDecode(Configuration::get('KB_PUSH_FCM_SERVER_SETTING'), true);
                            if (!empty($fcm_setting)) {
                                $fcm_server_key = $fcm_setting['server_key'];
                                $headers = array(
                                    'Authorization:key=' . $fcm_server_key,
                                    'Content-Type:application/json'
                                );
                                $id_template = KbPushTemplates::getNotificationTemplateIDByType(KbPushnotification::KBPN_ABANDONED_CART_ALERT);
                                if (!empty($id_template)) {
                                    $fields = array();
                                    $kbpushnotification = new KbPushnotification();
                                    $fields = $kbpushnotification->getNotificationPushData($id_template, $id_lang, $id_shop);
                                    if (!empty($fields)) {
                                        $kbTemplate = new KbPushTemplates($id_template, false, $id_shop);
                                        $message = '';
                                        if (isset($fields['data']['body'])) {
                                            $message = $fields['data']['body'];
                                            $message = str_replace('{{kb_cart_amount}}', Tools::displayPrice($cart_total), $message);
                                            $fields['data']['body'] = $message;
                                        }
                                        $fields['to'] = $reg_id;
                                        $fields["data"]["base_url"] = $this->getBaseUrl();
                                        $fields["data"]["click_url"] = $this->context->link->getModuleLink($this->module->name, 'serviceworker', array('action' => 'updateClickPush'), (bool) Configuration::get('PS_SSL_ENABLED'));

                                        $is_sent = 1;
                                        $push_id = $kbpushnotification->savePushNotification($kbTemplate, $is_sent, array($reg_id));
                                        if (!empty($push_id)) {
                                            $result = $kbpushnotification->sendPushRequestToFCM($headers, $fields);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
    
    /*
     * function to get abandoned cart list
     * return array
     */
    protected function getAbandonedCart()
    {
        $query = Db::getInstance()->executeS(
            'SELECT  max(c.id_cart) as id_cart, c.id_guest,c.id_shop '
            . 'FROM ' . _DB_PREFIX_ . 'cart c INNER JOIN '
            . _DB_PREFIX_ . 'kb_web_push_subscribers s on (c.id_guest=s.id_guest) '
            . 'WHERE TIME_TO_SEC(TIMEDIFF(\'' . pSQL(date('Y-m-d H:i:00', time()))
            . '\', c.`date_add`)) > 86400 AND NOT EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'orders o WHERE o.`id_cart` = c.`id_cart`)'
            . ' group by c.id_guest  ORDER BY c.`date_add` desc'
        );
        return $query;
    }
    
    
    /*
     * function defined to send push notification
     *  when time is less than or equal to current time
     */
    protected function syncDelayWebPush()
    {
        $delayPushDataWtSend = KbPushDelay::getDelayPushWtSend();
        if (!empty($delayPushDataWtSend)) {
            $subscribers = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'kb_web_push_subscribers WHERE is_admin=0');
            $reg_data = array();
            if (!empty($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    $id_lang = $subscriber['id_lang'];
                    $id_guest = $subscriber['id_guest'];
                    $id_shop = $subscriber['id_shop'];
                    $reg_data[$id_shop][$id_lang][] = $subscriber['reg_id'];
                }
                if (!empty($reg_data)) {
                    foreach ($delayPushDataWtSend as $delayPushWtSend) {
                        $now = time();
                        $delay = strtotime($delayPushWtSend['delay_time']);
                        if ($delay <= $now) {
                            $id_template = $delayPushWtSend['id_template'];
                            $id_shop = $delayPushWtSend['id_shop'];
                            $reg_ids = array();
                            $kbpushnotification = new KbPushnotification();
                            $fcm_setting = Tools::jsonDecode(Configuration::get('KB_PUSH_FCM_SERVER_SETTING'), true);
                            if (!empty($fcm_setting)) {
                                $fcm_server_key = $fcm_setting['server_key'];
                                $headers = array(
                                    'Authorization:key=' . $fcm_server_key,
                                    'Content-Type:application/json'
                                );
                                foreach ($reg_data as $key => $reg_info) {
                                    if ($key == $id_shop) {
                                        foreach ($reg_info as $key1 => $s_reg_id) {
                                            $fields = array();
                                            $fields = $kbpushnotification->getNotificationPushData($id_template, $key1, $key);
                                            if (!empty($fields)) {
                                                $fields['registration_ids'] = $s_reg_id;
                                                $fields["data"]["base_url"] = $this->getBaseUrl();
                                                $result = $kbpushnotification->sendPushRequestToFCM($headers, $fields);
                                                if (isset($result["success"]) && $result["success"]) {
                                                    $kbdelay = new KbPushDelay($delayPushWtSend['id_delay']);
                                                    $kbdelay->is_sent = 1;
                                                    $kbdelay->sent_at = date('Y-m-d H:i:s');
                                                    if ($kbdelay->update()) {
                                                        $kbTemplate = new KbPushTemplates($id_template, false, $key);
                                                        $kbpushnotification->savePushNotification($kbTemplate, $result["success"], $s_reg_id);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
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
     * Function to get the URL of the store,
     * this function also checks if the store
     * is a secure store or not and returns the URL accordingly
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
}
