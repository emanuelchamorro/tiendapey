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
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushSubscribers.php');

class AdminKbPushSubscribersController extends AdminKbPushCoreController
{
    public $all_languages = array();
    protected $kb_country = array();
    protected $kb_is_admin = array();
    protected $kb_devices = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'kb_web_push_subscribers';
        $this->className = 'KbPushSubscribers';
        $this->identifier = 'id_subscriber';
        $this->display = 'list';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Push Subscribers', 'AdminKbPushSubscribersController');
        $this->kb_is_admin[0] = $this->module->l('No', 'AdminKbPushSubscribersController');
        $this->kb_is_admin[1] = $this->module->l('Yes', 'AdminKbPushSubscribersController');
        
        $this->kb_devices['Tablet'] = $this->module->l('Tablet', 'AdminKbPushSubscribersController');
        $this->kb_devices['Mobile'] = $this->module->l('Mobile', 'AdminKbPushSubscribersController');
        $this->kb_devices['Desktop'] = $this->module->l('Desktop', 'AdminKbPushSubscribersController');

        $countries = Country::getCountries($this->context->language->id);

        foreach ($countries as $country) {
            $this->kb_country[$country['id_country']] = $country['name'];
        }
        $this->kb_country[0] = $this->module->l('Unknown Country', 'AdminKbPushSubscribersController');
        $this->fields_list = array(
            'id_subscriber' => array(
                'title' => $this->module->l('ID', 'AdminKbPushSubscribersController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'browser' => array(
                'filter_key' => 'browser',
                'title' => $this->module->l('Browser', 'AdminKbPushSubscribersController'),
                'align' => 'text-center',
            ),
            'browser_version' => array(
                'filter_key' => 'browser_version',
                'title' => $this->module->l('Browser Version', 'AdminKbPushSubscribersController'),
                'align' => 'text-center',
            ),
            'platform' => array(
                'filter_key' => 'platform',
                'title' => $this->module->l('Platform', 'AdminKbPushSubscribersController'),
            ),
            'device' => array(
                'type' => 'select',
                'list' => $this->kb_devices,
                'callback' => 'getKbDevice',
                'filter_key' => 'a!device',
                'title' => $this->module->l('Device', 'AdminKbPushSubscribersController'),
            ),
            'is_admin' => array(
                'title' => $this->module->l('Is Admin', 'AdminKbPushSubscribersController'),
                'filter_key' => 'a!is_admin',
                'list' => $this->kb_is_admin,
                'callback' => 'getIsAdmin',
                 'type' => 'select',
            ),
            'id_country' => array(
                'type' => 'select',
                'list' => $this->kb_country,
                'callback' => 'getKbCountry',
                'filter_key' => 'a!id_country',
                'title' => $this->module->l('Country', 'AdminKbPushSubscribersController'),
            ),
            'date_add' => array(
                'title' => $this->module->l('Subscriber at', 'AdminKbPushSubscribersController'),
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
        );

        $this->_orderWay = 'DESC';
    }

    public function renderList()
    {
        return parent::renderList();
    }

    public function getKbCountry($echo, $tr)
    {
        unset($tr);
        return $this->kb_country[$echo];
    }
    
    public function getIsAdmin($echo, $tr)
    {
        unset($tr);
        return $this->kb_is_admin[$echo];
    }
    
    public function getKbDevice($echo, $tr)
    {
        unset($tr);
        return $this->kb_devices[$echo];
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
