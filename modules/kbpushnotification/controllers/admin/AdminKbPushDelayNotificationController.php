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
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushDelay.php');

class AdminKbPushDelayNotificationController extends AdminKbPushCoreController
{
    public $all_languages = array();
    protected $notification_type = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'kb_web_push_delay';
        $this->className = 'KbPushDelay';
        $this->identifier = 'id_delay';
        $this->display = 'list';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Push Delay Notification', 'AdminKbPushDelayNotificationController');
        
        $template_type = array();
        $this->notification_type[0] = $this->module->l('Custom Notification Alert', 'AdminKbPushDelayNotificationController');
        $this->notification_type[1] = $this->module->l('Order Status Update Alert', 'AdminKbPushDelayNotificationController');
        $this->notification_type[2] = $this->module->l('Abandoned Cart Alert', 'AdminKbPushDelayNotificationController');
        $this->notification_type[3] = $this->module->l('Product Price Alert', 'AdminKbPushDelayNotificationController');
        $this->notification_type[4] = $this->module->l('Product Back In Stock Alert', 'AdminKbPushDelayNotificationController');
        
        $this->fields_list = array(
            'id_delay' => array(
                'title' => $this->module->l('ID', 'AdminKbPushDelayNotificationController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'notification_title' => array(
                'title' => $this->module->l('Template', 'AdminKbPushDelayNotificationController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
                'filter_key' => 'l!notification_title'
            ),
            'notification_type' => array(
                'title' => $this->module->l('Template Type', 'AdminKbPushDelayNotificationController'),
                'search' => false,
                 'callback' => 'notificationTypeList'
            ),
            'delay_time' => array(
                'title' => $this->module->l('Sent On', 'AdminKbPushDelayNotificationController'),
                 'type' => 'datetime',
                'filter_key' => 'a!delay_time'
            ),
            'is_sent' => array(
                'title' => $this->module->l('Sent On', 'AdminKbPushDelayNotificationController'),
                'search' => false,
                 'callback' => 'notificationIsSent'
            ),
            'date_add' => array(
                'title' => $this->module->l('Added On', 'AdminKbPushDelayNotificationController'),
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
        );

        $this->_select = 't.notification_type, l.notification_title';
        $this->_join = ' INNER JOIN `' . _DB_PREFIX_.'kb_web_push_template` t on (a.id_template=t.id_template)';
        $this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . 'kb_web_push_template_lang` l on (a.id_template=l.id_template AND l.id_lang='
                .(int)Context::getContext()->language->id.' AND l.id_shop='.(int)Context::getContext()->shop->id.') ';
        $this->_orderWay = 'DESC';
        
        $this->addRowAction('delete');
    }
    
    public function renderList()
    {
        return parent::renderList();
    }
    
    /*
     * Callback function to return notification type
     * @return string
     */
    public function notificationTypeList($echo, $tr)
    {
        unset($tr);
        return $this->notification_type[$echo];
    }
    
    /*
     * Callback function to return whether notification is sent or not
     * @return string
     */
    public function notificationIsSent($echo, $tr)
    {
        unset($tr);
        if ($echo) {
            return $this->module->l('Yes', 'AdminKbPushDelayNotificationController');
        } else {
            return $this->module->l('No', 'AdminKbPushDelayNotificationController');
        }
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
