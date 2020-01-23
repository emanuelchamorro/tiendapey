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
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushPushes.php');

class AdminKbPushHistoryController extends AdminKbPushCoreController
{
    public $all_languages = array();
    protected $is_sent = array();
    protected $notification_type = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'kb_web_push_pushes';
        $this->className = 'KbPushPushes';
        $this->identifier = 'id_push';
        $this->display = 'list';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Push History', 'AdminKbPushHistoryController');
        $this->is_sent[0] = $this->module->l('No', 'AdminKbPushHistoryController');
        $this->is_sent[1] = $this->module->l('Yes', 'AdminKbPushHistoryController');
        
        $this->notification_type[0] = $this->module->l('Custom Notification Alert', 'AdminKbPushHistoryController');
        $this->notification_type[1] = $this->module->l('Order Status Update Alert', 'AdminKbPushHistoryController');
        $this->notification_type[2] = $this->module->l('Abandoned Cart Alert', 'AdminKbPushHistoryController');
        $this->notification_type[3] = $this->module->l('Product Price Alert', 'AdminKbPushHistoryController');
        $this->notification_type[4] = $this->module->l('Product Back In Stock Alert', 'AdminKbPushHistoryController');
        $this->fields_list = array(
            'id_push' => array(
                'title' => $this->module->l('ID', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
            ),
            'title' => array(
                'title' => $this->module->l('Title', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
            ),
            'primary_url' => array(
                'title' => $this->module->l('Primary Link', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
            ),
            'is_sent' => array(
                'title' => $this->module->l('Is Sent', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
                'type'=> 'select',
                'list' => $this->is_sent,
                'callback' => 'psIsSent',
                'filter_key' => 'a!is_sent'
            ),
            'is_clicked' => array(
                'title' => $this->module->l('Is Clicked', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
                'type'=> 'select',
                'list' => $this->is_sent,
                'callback' => 'psIsSent',
                'filter_key' => 'a!is_clicked'
            ),
            'type' => array(
                'title' => $this->module->l('Type', 'AdminKbPushHistoryController'),
                'align' => 'text-center',
                'filter_key' => 'type',
                'type' => 'select',
                'list' => $this->notification_type,
                'callback' => 'notificationTypeList'
            ),
            'date_add' => array(
                'title' => $this->module->l('Date Created', 'AdminKbPushHistoryController'),
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
        );
        
        $this->_select = 'l.title';
        $this->_join = ' INNER JOIN '._DB_PREFIX_.'kb_web_push_pushes_lang l on (a.id_push=l.id_push AND l.id_lang='.(int)  Context::getContext()->language->id.')';
        $this->_orderWay = 'DESC';
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
    public function psIsSent($echo, $tr)
    {
        unset($tr);
        return $this->is_sent[$echo];
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
