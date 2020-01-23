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
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushPushes.php');

class KbpushnotificationSendpromotionpushModuleFrontController extends ModuleFrontController
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
        if (Tools::getValue('action') == 'sendKbPush') {
            $json = array();
            $id_lang = Context::getContext()->language->id;
            $id_shop = Context::getContext()->shop->id;
            $fcm_setting = Tools::jsonDecode(Configuration::get('KB_PUSH_FCM_SERVER_SETTING'), true);
            if (!empty($fcm_setting)) {
                $fcm_server_key = $fcm_setting['server_key'];
                $reg_id = '';
                $headers = array(
                    'Authorization:key=' . $fcm_server_key,
                    'Content-Type:application/json'
                );
                $id_template = Tools::getValue('id_template');
                $fields = array();
                $time = time();
                $kbpushnotification = new KbPushnotification();
                if (!empty($id_template)) {
                    $fields = $kbpushnotification->getNotificationPushData($id_template, $id_lang, $id_shop);
                }

                if (Tools::getValue('ajax_call') == 'test_push') {
                    if (empty($reg_id)) {
                        $reg_id = Db::getInstance()->getValue('SELECT reg_id FROM '._DB_PREFIX_.'kb_web_push_subscribers WHERE is_admin=1 ORDER BY id_subscriber DESC');
                    }
                    if (!empty($reg_id)) {
                        $fields["to"] = $reg_id;
                    } else {
                        $json["error"] = $this->module->l('Sorry!!Some error occcured. Reg Id not found.', 'sendpromotionpush');
                    }
                }
                if (Tools::getValue('ajax_call') == 'send_campaign') {
                    $reg_ids = array();
                    $reg_tokens = KbPushSubscribers::getSubscriberRegIDs(false, Context::getContext()->shop->id);
                    if (!empty($reg_tokens)) {
                        foreach ($reg_tokens as $r_token) {
                             $reg_ids[] = $r_token['reg_id'];
                        }
                    }
                   
                    $reg_ids = array_chunk($reg_ids, 1000);
//                    $fields["registration_ids"] = $reg_ids;
                }
                
                $base_url = $this->getBaseUrl();
                $fields["data"]["base_url"] = $base_url;
                $fields["data"]["click_url"] = $this->context->link->getModuleLink($this->module->name, 'serviceworker', array('action' => 'updateClickPush'), (bool) Configuration::get('PS_SSL_ENABLED'));
                if (Tools::getValue('ajax_call') == 'send_campaign') {
                    $sent_to_arr = array();
                    if (!empty($reg_ids)) {
                        foreach ($reg_ids as $reg) {
                            $fields["registration_ids"] = $reg;
                            $result = $kbpushnotification->sendPushRequestToFCM($headers, $fields);
                            if (isset($result["success"]) && $result["success"]) {
                                if (isset($reg) && !empty($reg)) {
                                    foreach ($reg as $key => $reg_id) {
                                        if (!isset($result["results"][$key]["error"])) {
                                            $sent_to_arr[] = $reg_id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if (!empty($sent_to_arr)) {
                        $kbtemplate = new KbPushTemplates($id_template, false, $id_shop);
                        $saveresult = $kbpushnotification->savePushNotification($kbtemplate, 1, $sent_to_arr);
                        if ($saveresult) {
                            $json["success"] = $this->module->l('Push Notifications saved and sent successfully.', 'sendpromotionpush');
                        } else {
                            $json["error"] = $this->module->l('Some error occcured while saving.', 'sendpromotionpush');
                        }
                    } else {
                        $json["error"] = $this->module->l('Some error occcured. Please try again reloading the page.', 'sendpromotionpush');
                    }
                } elseif (Tools::getValue('ajax_call') == 'test_push') {
                    $result = $kbpushnotification->sendPushRequestToFCM($headers, $fields);
                    if (isset($result["success"]) && $result["success"]) {
                        $sent_to_arr = array();

                        if (isset($reg_ids) && !empty($reg_ids)) {
                            foreach ($reg_ids as $key => $reg_id) {
                                if (!isset($result["results"][$key]["error"])) {
                                    $sent_to_arr[] = $reg_id;
                                }
                            }
                        }
                        $json["success"] = $this->module->l('Push Notifications sent successfully.', 'sendpromotionpush');
                    } else {
                        $json["error"] = $this->module->l('Some error occcured. Please try again reloading the page.', 'sendpromotionpush');
                    }
                }

                echo json_encode($json);
                die;
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
