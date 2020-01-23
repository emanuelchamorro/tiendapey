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
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

require_once dirname(__FILE__).'/AdminKbPushCoreController.php';
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushDelay.php');

class AdminKbPushNotificationController extends AdminKbPushCoreController
{
    public $all_languages = array();
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'configuration';
        $this->className = 'Configuration';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Push Notification', 'AdminKbPushNotificationController');
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
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        $templates = DB::getInstance()->executeS('SELECT t.id_template,l.notification_title FROM '._DB_PREFIX_.'kb_web_push_template t'
                . ' INNER JOIN '._DB_PREFIX_.'kb_web_push_template_lang l'
                . ' on (t.id_template=l.id_template AND l.id_lang='.(int)$id_lang
                .' AND l.id_shop='.(int)$id_shop.') INNER JOIN '._DB_PREFIX_.'kb_web_push_template_shop s'
                . ' on (s.id_template=t.id_template AND s.id_shop='.(int)$id_shop.')');
        
        array_unshift($templates, array('id_template' => null, 'notification_title' => $this->module->l('Select Template', 'AdminKbPushNotificationController')));
        
        
        $this->context->smarty->assign(
            array(
                'kb_front_url' => $this->context->link->getPageLink('index', true, Context::getContext()->language->id, array('registerAdmin' => true)),
                'kb_templates' => $templates,
                'loader' => $this->getModuleDirUrl().$this->module->name.'/views/img/loader.gif',
                'kb_send_promotion_url' => $this->context->link->getModuleLink($this->module->name, 'sendpromotionpush', array('action' => 'sendKbPush'), (bool) Configuration::get('PS_SSL_ENABLED')),
                'kb_admin_tempate_url'=> $this->context->link->getAdminLink('AdminKbPushTemplates', true).'&addkb_web_push_template',
                'kb_admin_delay_url' => $this->context->link->getModuleLink($this->module->name, 'cron', array('action' => 'syncdelaypush', 'secure_key' => Configuration::get('KB_WEB_PUSH_CRON_1')), (bool) Configuration::get('PS_SSL_ENABLED')),
                'kb_admin_abd_url' => $this->context->link->getModuleLink($this->module->name, 'cron', array('action' => 'syncabandonedcart', 'secure_key' => Configuration::get('KB_WEB_PUSH_CRON_2')), (bool) Configuration::get('PS_SSL_ENABLED')),
            )
        );
        $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/pushnotification.tpl');
        $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/velovalidation.tpl');
        
        parent::initContent();
    }
    
    /*
     * Prestashop Default Function in AdminController.
     * handles the process input, process Ajax etc. 
     */
    public function postProcess()
    {
        if (Tools::isSubmit('sendkbAllSubscriber')) {
            if (Tools::getValue('send_push_time') == 1) {
                $template = Tools::getValue('template');
                $send_date = trim(Tools::getValue('send_at_time_date'));
                $id_shop = $this->context->shop->id;
                $kbdelay = new KbPushDelay();
                $kbdelay->id_shop = $id_shop;
                $kbdelay->id_template = $template;
                $kbdelay->delay_time = $send_date;
                $kbdelay->is_sent = 0;
                $kbdelay->is_expired = 0;
                $kbdelay->sent_at = '';
                if ($kbdelay->add()) {
                    $this->context->cookie->__set('kb_redirect_success', $this->module->l('Delay Push Notification successfully saved.', 'AdminKbPushNotificationController'));
                    Tools::redirectAdmin($this->context->link->getAdminlink('AdminKbPushNotification'));
                }
            }
        }
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
    
    /*
     * function to render page header toolbar
     * return array
     */
    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['delay_push_cron'] = array(
            'href' => $this->context->link->getModuleLink($this->module->name, 'cron', array('action' => 'syncdelaypush', 'secure_key' => Configuration::get('KB_WEB_PUSH_CRON_1')), (bool) Configuration::get('PS_SSL_ENABLED')),
            'desc' => $this->module->l('Push Delay Notification', 'AdminKbPushNotificationController'),
            'icon' => 'process-icon-refresh',
            'target' => 'blank'
        );
        $this->page_header_toolbar_btn['cart_push_cron'] = array(
            'href' => $this->context->link->getModuleLink($this->module->name, 'cron', array('action' => 'syncabandonedcart', 'secure_key' => Configuration::get('KB_WEB_PUSH_CRON_2')), (bool) Configuration::get('PS_SSL_ENABLED')),
            'desc' => $this->module->l('Push Abandoned Cart Notification', 'AdminKbPushNotificationController'),
            'icon' => 'process-icon-refresh',
            'target' => 'blank'
        );
        parent::initPageHeaderToolbar();
    }
}
