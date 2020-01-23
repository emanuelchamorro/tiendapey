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
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushTemplates.php');
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushSubscribers.php');
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushPushes.php');
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushSubscriberMapping.php');
include_once(_PS_MODULE_DIR_.'kbpushnotification/classes/KbPushProductSubscribers.php');

class KbPushnotification extends Module
{

    const MODEL_FILE = 'model.sql';
    const MODEL_DATA_FILE  = 'data.sql';
    const KBPN_CUSTOM_NOTIFY_ALERT = 0;
    const KBPN_ORDER_STATUS_UPDATE = 1;
    const KBPN_ABANDONED_CART_ALERT = 2;
    const KBPN_PRICE_ALERT = 3;
    const KBPN_BACK_IN_STOCK_ALERT = 4;
    const PARENT_TAB_CLASS = 'AdminKbPushConfiguration';
    const SELL_CLASS_NAME = 'SELL';
    public $custom_errors = array();
    
    public function __construct()
    {
        $this->name = 'kbpushnotification';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'knowband';
        $this->need_instance = 1;
        $this->module_key = '96945081030e37a71168f50cf9b0a6f7';
        $this->author_address = '0x2C366b113bd378672D4Ee91B75dC727E857A54A6';
        $this->lang = true;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Knowband Web Push Notifications');
        $this->description = $this->l('The Web push notification helps to boost the sales and customer engagement by sending unlimited push notification to the customer.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        /*
         * Create Database table and if there is some problem then display error message
         */
        if (!$this->installModel()) {
            $this->custom_errors[] = $this->l('Error occurred while installing/upgrading modal.');
            return false;
        }

        /*
         * Register various hook functions
         */
        if (!parent::install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('actionOrderStatusUpdate') ||
            !$this->registerHook('displayFooterProduct') ||
            !$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('actionBeforeAuthentication') ||
            !$this->registerHook('actionAuthentication') ||
            !$this->registerHook('actionBeforeSubmitAccount') ||
            !$this->registerHook('actionCustomerAccountAdd') ||
            !$this->registerHook('actionCustomerLogoutBefore') ||
            !$this->registerHook('actionCustomerLogoutAfter') ||
            !$this->registerHook('displayBackOfficeHeader')) {
            return false;
        }
        
        //Create Admin tabs
        $this->installKbTabs();
        
        //FCM key update
        $setting = array(
            'apiKey' => "AIzaSyA750xlMtIuePD6SsGx7FDlihk55qIkQhw",
            'authDomain' => "propane-fusion-156206.firebaseapp.com",
            'databaseURL' => "https://propane-fusion-156206.firebaseio.com",
            'projectId' => "propane-fusion-156206",
            'storageBucket' => "propane-fusion-156206.appspot.com",
            'messagingSenderId' => "310977195083",
            'server_key' => "AAAASGevbEs:APA91bHydR2XnMLZFkrQhQU33Vp3N_koFmjikYlP9AATJ4L3RIsX73m6AZwzZeJVLQVuor-yY13EJ1j6-H8-qPA2hireV6-Ti4N0tZ8LY1jabx_E7pchXhxoH0TWQc7-xQYZhNbf1qjVZIy2DMD5I6gqS8U1XXrnCQ",

        );
        Configuration::updateValue('KB_PUSH_FCM_SERVER_SETTING', Tools::jsonEncode($setting));

        if (!Configuration::get('KB_WEB_PUSH_CRON_1')) {
            Configuration::updateValue('KB_WEB_PUSH_CRON_1', $this->kbKeyGenerator());
        }
        if (!Configuration::get('KB_WEB_PUSH_CRON_2')) {
            Configuration::updateValue('KB_WEB_PUSH_CRON_2', $this->kbKeyGenerator());
        }
        
        $existing_notification = KbPushTemplates::getNotificationTemplates();
        if (empty($existing_notification) && count($existing_notification) <= 0) {
            //for order update
            $this->createUpdateStatus();
            $this->createAbandandAlert();
            $this->createPriceAlertUpdate();
            $this->createBackStockAlertUpdate();
        }
                
        return true;
    }
    
    protected function createAbandandAlert()
    {
        $title = $this->l('Buy Now');
        $message = $this->l('Hello, Your cart still waiting for you to checkout');
        $image_url = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_cart.jpg';
        $image_path = _PS_MODULE_DIR_ . $this->name . '/views/img/welcome_cart.jpg';
        $kbnotify_abd = new KbPushTemplates();
        $kbnotify_abd->notify_icon = $image_url;
        $kbnotify_abd->notify_icon_path = $image_path;
        $kbnotify_abd->notification_type = self::KBPN_ABANDONED_CART_ALERT;
        $kbnotify_abd->notification_title = $title;
        $kbnotify_abd->notification_message = $message;
        $kbnotify_abd->active = 1;
        if ($kbnotify_abd->add()) {
            return true;
        }
        return false;
    }
    
    protected function createUpdateStatus()
    {
        $title = $this->l('Update Order');
        $message = $this->l('Hello, Your order is updated');
        $image_url = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_order.jpg';
        $image_path = _PS_MODULE_DIR_ . $this->name . '/views/img/welcome_order.jpg';
        $kbnotify = new KbPushTemplates();
        $kbnotify->notify_icon = $image_url;
        $kbnotify->notify_icon_path = $image_path;
        $kbnotify->notification_type = self::KBPN_ORDER_STATUS_UPDATE;
        $kbnotify->notification_title = $title;
        $kbnotify->notification_message = $message;
        $kbnotify->active = 1;
        if ($kbnotify->add()) {
            return true;
        }
        return false;
    }
    
    protected function createPriceAlertUpdate()
    {
        $title = $this->l('Product Price Alert');
        $message = $this->l('Product').' {{kb_item_name}}'. $this->l(' updated. Now Price ').'{{kb_item_current_price}}'.$this->l('. Before Price ').'{{kb_item_old_price}}';
        $image_url = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_price.jpg';
        $image_path = _PS_MODULE_DIR_ . $this->name . '/views/img/welcome_price.jpg';
        $kbnotify = new KbPushTemplates();
        $kbnotify->notify_icon = $image_url;
        $kbnotify->notify_icon_path = $image_path;
        $kbnotify->notification_type = self::KBPN_PRICE_ALERT;
        $kbnotify->notification_title = $title;
        $kbnotify->notification_message = $message;
        $kbnotify->active = 1;
        if ($kbnotify->add()) {
            return true;
        }
        return false;
    }
    
    protected function createBackStockAlertUpdate()
    {
        $title = $this->l('Product Back In Stock Alert');
        $message = $this->l('Product').' {{kb_item_name}}'. $this->l(' is now back in stock. You can now buy the product.');
        $image_url = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_stock.jpg';
        $image_path = _PS_MODULE_DIR_ . $this->name . '/views/img/welcome_stock.jpg';
        $kbnotify = new KbPushTemplates();
        $kbnotify->notify_icon = $image_url;
        $kbnotify->notify_icon_path = $image_path;
        $kbnotify->notification_type = self::KBPN_BACK_IN_STOCK_ALERT;
        $kbnotify->notification_title = $title;
        $kbnotify->notification_message = $message;
        $kbnotify->active = 1;
        if ($kbnotify->add()) {
            return true;
        }
        return false;
    }
    
    //Function definition to install module tabs
    public function installKbTabs()
    {
        $parentTab = new Tab();
        $parentTab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $parentTab->name[$lang['id_lang']] = $this->l('Knowband Web Push Notification');
        }

        $parentTab->class_name = self::PARENT_TAB_CLASS;
        $parentTab->module = $this->name;
        $parentTab->active = 1;
        $parentTab->id_parent = Tab::getIdFromClassName(self::SELL_CLASS_NAME);
        $parentTab->icon = 'notifications';
        $parentTab->add();

        $id_parent_tab = (int) Tab::getIdFromClassName(self::PARENT_TAB_CLASS);
        $admin_menus = $this->adminSubMenus();

        foreach ($admin_menus as $menu) {
            $tab = new Tab();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $this->l($menu['name']);
            }

            $tab->class_name = $menu['class_name'];
            $tab->module = $this->name;
            $tab->active = $menu['active'];
            $tab->id_parent = $id_parent_tab;
            $tab->add($this->id);
        }
        return true;
    }
    
    
    /*
     * Function definition to create submenus list
     */
    public function adminSubMenus()
    {
        $subMenu = array(
            array(
                'class_name' => 'AdminKbPushSettings',
                'name' => $this->l('General Settings'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushTemplates',
                'name' => $this->l('Templates'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushNotification',
                'name' => $this->l('Push Notification'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushDelayNotification',
                'name' => $this->l('Delay Push Notification'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushSubscribers',
                'name' => $this->l('Subscribers'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushProductSubscribers',
                'name' => $this->l('Product Subscribers'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushHistory',
                'name' => $this->l('History'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
            array(
                'class_name' => 'AdminKbPushStatistics',
                'name' => $this->l('Statistics'),
                'active' => 1,
//                'parent_id' => Tab::getIdFromClassName('AdminKbPushConfiguration')
            ),
        );

        return $subMenu;
    }
    
    /*
     * Function to uninstall the module with 
     * unregister various hook and 
     * also delete the configuration setting
     */

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->unregisterHook('actionOrderStatusUpdate') ||
            !$this->unregisterHook('displayFooterProduct') ||
            !$this->unregisterHook('actionProductUpdate') ||
            !$this->unregisterHook('actionAuthentication') ||
            !$this->unregisterHook('actionBeforeAuthentication') ||
            !$this->unregisterHook('actionCustomerLogoutAfter') ||
            !$this->unregisterHook('actionCustomerLogoutBefore') ||
            !$this->unregisterHook('actionCustomerAccountAdd') ||
            !$this->unregisterHook('actionBeforeSubmitAccount') ||
            !$this->unregisterHook('displayHeader')) {
            return false;
        }
        
        $this->unInstallKbTabs();
      
        return true;
    }
    
    /*
     * Function removes module tabs to the admin panel
     */
    public function unInstallKbTabs()
    {
        $parentTab = new Tab(Tab::getIdFromClassName(self::PARENT_TAB_CLASS));
        $parentTab->delete();

        $admin_menus = $this->adminSubMenus();

        foreach ($admin_menus as $menu) {
            $sql = 'SELECT id_tab FROM `' . _DB_PREFIX_ . 'tab` Where class_name = "' . pSQL($menu['class_name']) . '" 
				AND module = "' . pSQL($this->name) . '"';
            $id_tab = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        return true;
    }
    
    
    /*
     * To install Database Table during install of the module
     */
    protected function installModel()
    {
        $installation_error = false;
        if (!file_exists(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file not found.');
            $installation_error = true;
        } elseif (!is_readable(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file is not readable.');
            $installation_error = true;
        } elseif (!$sql = Tools::file_get_contents(_PS_MODULE_DIR_ . $this->name . '/' . self::MODEL_FILE)) {
            $this->custom_errors[] = $this->l('Model installation file is empty.');
            $installation_error = true;
        }

        if (!$installation_error) {
            /*
             * Replace _PREFIX_ and ENGINE_TYPE with default Prestashop values
             */
            $sql = str_replace(
                array('_PREFIX_', 'ENGINE_TYPE'),
                array(_DB_PREFIX_, _MYSQL_ENGINE_),
                $sql
            );
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
            foreach ($sql as $query) {
                if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(trim($query))) {
                    $installation_error = true;
                }
            }
        }
        
        
        if ($installation_error) {
            return false;
        } else {
            return true;
        }
    }
    
    /*
     * Function for including the media files in the admin panel
     */
    protected function addBackOfficeMedia()
    {
        /* CSS files */
        $this->context->controller->addCSS($this->_path . 'views/css/admin/kb_admin.css');
        
        /* JS files */
        $this->context->controller->addJS($this->_path . 'views/js/velovalidation.js');
        $this->context->controller->addJS($this->_path . 'views/js/admin/kb_admin.js');
        $this->context->controller->addJS($this->_path . 'views/js/admin/validation_admin.js');
    }
    
    /*
     * function to display the module configuration page
     */
    public function getContent()
    {
//        echo $this->registerHook('actionProductUpdate');
        $errors = array();
        $this->addBackOfficeMedia();
        /*
         * loop to fetch all language with default language in an array
         */
        $languages = Language::getLanguages(false);
        /*
         * Function to submit the configuration setting values,
         * first by validating the form data and then save into the DB
         */
        if (Tools::isSubmit('kbConfigSubmit')) {
            $db_data = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
            $config_form = Tools::getValue('kbpushnotification');
            $welcome_form = Tools::getValue('kbwelcomenotify');
            $kbproductsignup_form = Tools::getValue('kbproductsignup');
            foreach ($languages as $lang) {
                $welcome_form['action_message'][$lang['id_lang']] = trim(Tools::getValue('kbwelcomenotify_action_message_'.$lang['id_lang']));
                $welcome_form['action_btn_text'][$lang['id_lang']] = trim(Tools::getValue('kbwelcomenotify_action_btn_text_'.$lang['id_lang']));
                $welcome_form['action_cancel_text'][$lang['id_lang']] = trim(Tools::getValue('kbwelcomenotify_action_cancel_text_'.$lang['id_lang']));
                $kbproductsignup_form['kbsignup_price_heading'][$lang['id_lang']] = trim(Tools::getValue('kbsignup_price_heading_'.$lang['id_lang']));
                $kbproductsignup_form['kbsignup_price_message'][$lang['id_lang']] = trim(Tools::getValue('kbsignup_price_message_'.$lang['id_lang']));
                $kbproductsignup_form['kbsignup_stock_heading'][$lang['id_lang']] = trim(Tools::getValue('kbsignup_stock_heading_'.$lang['id_lang']));
                $kbproductsignup_form['kbsignup_stock_message'][$lang['id_lang']] = trim(Tools::getValue('kbsignup_stock_message_'.$lang['id_lang']));
                $kbproductsignup_form['kbsignup_button_text'][$lang['id_lang']] = trim(Tools::getValue('kbsignup_button_text_'.$lang['id_lang']));
            }
            $welcome_form['logo_path'] = _PS_MODULE_DIR_ . $this->name . '/views/img/welcome_default.jpg';
            $welcome_form['logo'] = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_default.jpg';
            $is_img_upload = false;
            if (!empty($_FILES)) {
                if ($_FILES['kb_welcome_logo']['error'] == 0 && $_FILES['kb_welcome_logo']['name'] != '' && $_FILES['kb_welcome_logo']['size'] > 0) {
                    $file_extension = pathinfo($_FILES['kb_welcome_logo']['name'], PATHINFO_EXTENSION);
                    $path = _PS_MODULE_DIR_ . $this->name . '/views/img/kb_welcome_logo.' . $file_extension;
                    $mask = _PS_MODULE_DIR_ . $this->name . '/views/img/kb_welcome_logo.*';
                    $matches = glob($mask);
                    if (count($matches) > 0) {
                        array_map('unlink', $matches);
                    }
                    $upload = move_uploaded_file(
                        $_FILES['kb_welcome_logo']['tmp_name'],
                        $path
                    );
                    chmod($path, 0777);
                    if ($upload) {
                        $is_img_upload = true;
                        $welcome_form['logo_path'] = $path;
                        $welcome_form['logo'] = $this->getModuleDirUrl() . $this->name . '/views/img/kb_welcome_logo.' . $file_extension;
                    }
                }
            }
            if (!$is_img_upload) {
                if (!empty($db_data) && isset($db_data['welcome_setting'])) {
                    $welcome_form['logo_path'] = $db_data['welcome_setting']['logo_path'];
                    $welcome_form['logo'] = $db_data['welcome_setting']['logo'];
                }
            }
            $data = array(
                'module_config' => $config_form,
                'welcome_setting' => $welcome_form,
                'product_signup_setting' => $kbproductsignup_form,
            );
            Configuration::updateValue('KB_PUSH_NOTIFICATION', Tools::jsonEncode($data));
            $this->context->cookie->__set('kb_redirect_success', $this->l('Configuration successfully updated.'));
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        }
        
        $output = '';
        if (isset($this->context->cookie->kb_redirect_success)) {
            $output .= $this->displayConfirmation($this->context->cookie->kb_redirect_success);
            unset($this->context->cookie->kb_redirect_success);
        }
        
        $this->available_tabs_lang = array(
            'ModuleConfiguration' => $this->l('Configuration'),
            'ProductUpdateSignUp' => $this->l('Product Sign Up Box'),
            'WelcomeNotificationSettings' => $this->l('Welcome Notification Settings'),
        );
        
        $this->available_tabs = array(
            array('ModuleConfiguration', 'icon-wrench'),
            array('ProductUpdateSignUp', 'icon-sign-in'),
            array('WelcomeNotificationSettings', 'icon-bell'),
        );
        
        $this->tab_display = 'ModuleConfiguration';
        $module_tabs = array();
        foreach ($this->available_tabs as $tab) {
            $module_tabs[$tab[0]] = array(
                'id' => $tab[0],
                'selected' => (Tools::strtolower($tab[0]) == Tools::strtolower($this->tab_display) ||
                (isset($this->tab_display_module) && 'module' . $this->tab_display_module == Tools::strtolower($tab[0]))),
                'name' => $this->available_tabs_lang[$tab[0]],
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'icon' => $tab[1],
            );
        }

        /*
         * Fetch configuration settings from the Database and convert them into array
         */
        $this->kb_push_notify = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
        /*
         * Persistence the configuration setting form data
         */
        
        $config_form_data = '';
        $welcome_form_data = '';
        $product_sign_form_data = '';
        if (!empty($this->kb_push_notify) && isset($this->kb_push_notify['module_config'])) {
            $config_form_data = $this->kb_push_notify['module_config'];
        }
        if (!empty($this->kb_push_notify) && isset($this->kb_push_notify['welcome_setting'])) {
            $welcome_form_data = $this->kb_push_notify['welcome_setting'];
        }
        if (!empty($this->kb_push_notify) && isset($this->kb_push_notify['product_signup_setting'])) {
            $product_sign_form_data = $this->kb_push_notify['product_signup_setting'];
        }
        
        $config_field_value = array(
            'kbpushnotification[enable]' => (!empty($config_form_data)) ? $config_form_data['enable'] : 0,
            'kbpushnotification[enable_order_status]' => (!empty($config_form_data)) ? $config_form_data['enable_order_status'] : 0,
            'kbpushnotification[enable_abandoned_cart]' => (!empty($config_form_data)) ? $config_form_data['enable_abandoned_cart'] : 0,
            'kbpushnotification[enable_product_price_alert]' => (!empty($config_form_data)) ? $config_form_data['enable_product_price_alert'] : 0,
            'kbpushnotification[enable_product_stock_alert]' => (!empty($config_form_data)) ? $config_form_data['enable_product_stock_alert'] : 0,
        );
        $welcome_config_value = array(
            'kbwelcomenotify[enable]' => (!empty($welcome_form_data)) ? $welcome_form_data['enable']:0,
            'kbwelcomenotify[display_logo]' => (!empty($welcome_form_data)) ? $welcome_form_data['display_logo']:0,
        );
        
        $signup_config_value = array(
            'kbproductsignup[enable]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['enable'] : 0,
            'kbproductsignup[heading_bk_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['heading_bk_color'] : '#f08080',
            'kbproductsignup[heading_font_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['heading_font_color'] : '#ffffff',
            'kbproductsignup[content_bk_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['content_bk_color'] : '#ffffff',
            'kbproductsignup[content_font_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['content_font_color'] : '#000000',
            'kbproductsignup[button_bk_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['button_bk_color'] : '#fd8222',
            'kbproductsignup[block_border_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['block_border_color'] : '#f08080',
            'kbproductsignup[button_font_color]' => (!empty($product_sign_form_data)) ? $product_sign_form_data['button_font_color'] : '#ffffff',
        );
        
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
            $welcome_config_value['kbwelcomenotify_action_message'][$language['id_lang']] = (!empty($welcome_form_data) && isset($welcome_form_data['action_message'][$language['id_lang']])) ? $welcome_form_data['action_message'][$language['id_lang']] : $this->l('We\'d like to show you notifications for the latest news and updates. ');
            $welcome_config_value['kbwelcomenotify_action_btn_text'][$language['id_lang']] = (!empty($welcome_form_data) && isset($welcome_form_data['action_btn_text'][$language['id_lang']])) ? $welcome_form_data['action_btn_text'][$language['id_lang']] : $this->l('Approve');
            $welcome_config_value['kbwelcomenotify_action_cancel_text'][$language['id_lang']] = (!empty($welcome_form_data) && isset($welcome_form_data['action_cancel_text'][$language['id_lang']])) ? $welcome_form_data['action_cancel_text'][$language['id_lang']] : $this->l('No Thanks');
            $signup_config_value['kbsignup_price_heading'][$language['id_lang']] = (!empty($product_sign_form_data) && isset($product_sign_form_data['kbsignup_price_heading'][$language['id_lang']])) ? $product_sign_form_data['kbsignup_price_heading'][$language['id_lang']] : $this->l('Subscribe for Price Alert');
            $signup_config_value['kbsignup_price_message'][$language['id_lang']] = (!empty($product_sign_form_data) && isset($product_sign_form_data['kbsignup_price_message'][$language['id_lang']])) ? $product_sign_form_data['kbsignup_price_message'][$language['id_lang']] : $this->l('Subscribe for Price Alert');
            $signup_config_value['kbsignup_stock_heading'][$language['id_lang']] = (!empty($product_sign_form_data) && isset($product_sign_form_data['kbsignup_stock_heading'][$language['id_lang']])) ? $product_sign_form_data['kbsignup_stock_heading'][$language['id_lang']] : $this->l('Subscribe for Back In Stock Alert');
            $signup_config_value['kbsignup_stock_message'][$language['id_lang']] = (!empty($product_sign_form_data) && isset($product_sign_form_data['kbsignup_stock_message'][$language['id_lang']])) ? $product_sign_form_data['kbsignup_stock_message'][$language['id_lang']] : $this->l('Subscribe for Back In Stock Alert');
            $signup_config_value['kbsignup_button_text'][$language['id_lang']] = (!empty($product_sign_form_data) && isset($product_sign_form_data['kbsignup_button_text'][$language['id_lang']])) ? $product_sign_form_data['kbsignup_button_text'][$language['id_lang']] : $this->l('Sign Up');
        }

        $this->fields_form = $this->getConfigurationForm();
        $this->fields_form1 = $this->getProductSignupForm();
        $this->fields_form2 = $this->getWelcomeNotificationForm();
        
        $action = AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules');
        
        /*
         * Create helper form for configuration setting form
         */
        $form = $this->getform(
            $this->fields_form,
            $languages,
            $this->l('Configuration'),
            false,
            $config_field_value,
            'module_config',
            $action
        );
        $form1 = $this->getform(
            $this->fields_form1,
            $languages,
            $this->l('Product Signup Setting'),
            false,
            $signup_config_value,
            'product_signup_setting',
            $action
        );
        $form2 = $this->getform(
            $this->fields_form2,
            $languages,
            $this->l('Welcome Notification Setting'),
            false,
            $welcome_config_value,
            'welcome_notification_setting',
            $action
        );
        
        $this->context->smarty->assign('module_tabs', $module_tabs);
        $this->context->smarty->assign('form', $form);
        $this->context->smarty->assign('form1', $form1);
        $this->context->smarty->assign('form2', $form2);
        $this->context->smarty->assign('firstCall', false);
        $this->context->smarty->assign(
            'ajax_action',
            'index.php?controller=AdminModules&token=' . Tools::getAdminTokenLite('AdminModules') . '&configure=' . $this->name
        );
        
        $tpl = 'form_custom.tpl';
        $helper = new Helper();
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
        ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->override_folder = 'helpers/';
        $helper->base_folder = 'form/';
        $helper->setTpl($tpl);
        $tpl = $helper->generate();
        
        $this->context->smarty->assign('is_shortcodes', true);
        $velovalidation = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name.'/views/templates/admin/velovalidation.tpl');
        $output = $output . $tpl;
        return $velovalidation.$output;
    }
    
    /*
     * Function to create welcome notification form
     */
    private function getProductSignupForm()
    {
        $form = array(
            'form' => array(
                'id_form' => 'product_signup_setting',
                'legend' => array(
                    'title' => $this->l('Product Signup Setting'),
                    'icon' => 'icon-sign-in'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable'),
                        'type' => 'switch',
                        'name' => 'kbproductsignup[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the product sign up')
                    ),
                    array(
                        'label' => $this->l('Sign up Price Alert Heading'),
                        'type' => 'text',
                        'name' => 'kbsignup_price_heading',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text for the heading displaying in the signup form')
                    ),
                    array(
                        'label' => $this->l('Price Alert Message'),
                        'type' => 'textarea',
                        'name' => 'kbsignup_price_message',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text message to display for the price alert signup form')
                    ),
                    array(
                        'label' => $this->l('Sign up Back In Stock Alert Heading'),
                        'type' => 'text',
                        'name' => 'kbsignup_stock_heading',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text for the heading displaying in the signup form')
                    ),
                    array(
                        'label' => $this->l('Back In Stock Alert Message'),
                        'type' => 'textarea',
                        'name' => 'kbsignup_stock_message',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text message to display for the back in stock alert signup form')
                    ),
                    array(
                        'label' => $this->l('Sign up Button Text'),
                        'type' => 'text',
                        'name' => 'kbsignup_button_text',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text for the signup button displaying in the signup form')
                    ),
                    
                    array(
                        'label' => $this->l('Sign up Heading Background color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[heading_bk_color]',
                        'hint' => $this->l('Choose the background color to display in the heading of the sign up form')
                    ),
                    array(
                        'label' => $this->l('Sign up Heading Font color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[heading_font_color]',
                        'hint' => $this->l('Choose the font color to display in the heading of the sign up form')
                    ),
                    array(
                        'label' => $this->l('Sign up Content Background color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[content_bk_color]',
                        'hint' => $this->l('Choose the background color to display in the content of the sign up form')
                    ),
                    array(
                        'label' => $this->l('Sign up Content Font color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[content_font_color]',
                        'hint' => $this->l('Choose the font color to display in the content of the sign up form')
                    ),
                    array(
                        'label' => $this->l('Sign up Block Border color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[block_border_color]',
                        'hint' => $this->l('Choose the color to display in the border of the sign up block')
                    ),
                    array(
                        'label' => $this->l('Sign up Button Background color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[button_bk_color]',
                        'hint' => $this->l('Choose the background color for the sign up button')
                    ),
                    array(
                        'label' => $this->l('Sign up Button Font color'),
                        'type' => 'color',
                        'required' => true,
                        'name' => 'kbproductsignup[button_font_color]',
                        'hint' => $this->l('Choose the font color for the sign up button')
                    ),
                    
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right kbph_product_sign_setting_btn'
                ),
            ),
        );
        return $form;
    }
    
    /*
     * Function to create welcome notification form
     */
    private function getWelcomeNotificationForm()
    {
        $time = time();
        $notify_image = $this->getModuleDirUrl() . $this->name . '/views/img/welcome_default.jpg?time=' . $time;
        
        $kb_config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
        /*
         * Persistence the configuration setting form data
         */
        if (!empty($kb_config) && isset($kb_config['welcome_setting'])) {
            if (isset($kb_config['welcome_setting']['logo']) && !empty($kb_config['welcome_setting']['logo'])) {
                $notify_image = $kb_config['welcome_setting']['logo'].'?time='.$time;
            }
        }
        $notify_img_url = "<img id='kbslmarker' class='img img-thumbnail'  src='" . $notify_image . "' width='100px;' height='100px;'>";
        $form = array(
            'form' => array(
                'id_form' => 'welcome_notification_setting',
                'legend' => array(
                    'title' => $this->l('Welcome Notification Setting'),
                    'icon' => 'icon-bell'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable'),
                        'type' => 'switch',
                        'name' => 'kbwelcomenotify[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the welcome notification')
                    ),
                    array(
                        'label' => $this->l('Action Message'),
                        'type' => 'text',
                        'name' => 'kbwelcomenotify_action_message',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text to display the message in the welcome notification popup')
                    ),
                    array(
                        'label' => $this->l('Action Button Text'),
                        'type' => 'text',
                        'name' => 'kbwelcomenotify_action_btn_text',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text to display in the action button for the welcome notification popup')
                    ),
                    array(
                        'label' => $this->l('Action Cancel Text'),
                        'type' => 'text',
                        'name' => 'kbwelcomenotify_action_cancel_text',
                        'required' => true,
                        'lang' => true,
                        'hint' => $this->l('Enter the text to display in the cancel button for the welcome notification popup')
                    ),
                    array(
                        'label' => $this->l('Display Logo'),
                        'type' => 'switch',
                        'name' => 'kbwelcomenotify[display_logo]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable to display logo in the welcome notification')
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Upload Logo'),
                        'name' => 'kb_welcome_logo',
                        'required' => false,
                        'image' => $notify_img_url ? $notify_img_url : false,
                        'desc' => $this->l('For the best view, upload 192 x 192 pixel PNG image'),
                        'display_image' => true,
                        'hint' => $this->l('Upload image to display in push notification')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right kbph_welcome_setting_btn'
                ),
            ),
        );
        return $form;
    }
    
    /*
     * Function to create configuration setting form
     */
    private function getConfigurationForm()
    {
        $form = array(
            'form' => array(
                'id_form' => 'module_config',
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable'),
                        'type' => 'switch',
                        'name' => 'kbpushnotification[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the plugin')
                    ),
                    array(
                        'label' => $this->l('Enable/Disable Order Status Update'),
                        'type' => 'switch',
                        'name' => 'kbpushnotification[enable_order_status]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the order status update')
                    ),
                    array(
                        'label' => $this->l('Enable/Disable Abandoned Cart alert'),
                        'type' => 'switch',
                        'name' => 'kbpushnotification[enable_abandoned_cart]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the abandoned cart alert')
                    ),
                    array(
                        'label' => $this->l('Enable/Disable Product Price alert'),
                        'type' => 'switch',
                        'name' => 'kbpushnotification[enable_product_price_alert]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the product price alert')
                    ),
                    array(
                        'label' => $this->l('Enable/Disable Product back in stock alert'),
                        'type' => 'switch',
                        'name' => 'kbpushnotification[enable_product_stock_alert]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable the product back in stock alert')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right kbph_general_btn'
                ),
            ),
        );
        return $form;
    }
    
    /*
     * Function to create Helper Form
     */

    public function getform($field_form, $languages, $title, $show_cancel_button, $field_value, $id, $action)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->fields_value = $field_value;
        $helper->name_controller = $this->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $title;
        if ($id == 'module_config') {
            $helper->show_toolbar = true;
        } else {
            $helper->show_toolbar = false;
        }
        $helper->table = $id;
        $helper->firstCall = true;
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = $show_cancel_button;
        $helper->submit_action = $action;
        return $helper->generateForm(array('form' => $field_form));
    }
    
//    //Hook to add content on Back Office Header
//    public function hookDisplayBackOfficeHeader()
//    {
//        $this->context->controller->addCSS($this->_path . 'views/css/admin/tab.css');
//    }
    
    
    public function hookDisplayHeader()
    {
        $config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
        if (!empty($config) && isset($config['module_config']['enable'])) {
            $module_config = $config['module_config'];
            if ($module_config['enable']) {
                $this->kbSetMedia();
                $this->context->smarty->assign(
                    array(
                        
                        'id_lang' => Context::getContext()->language->id,
                        'welcome_setting' => $config['welcome_setting'],
                        'dashboard_worker' => $this->getModuleDirUrl().$this->name.'/views/js/worker_dashboard.js',
                        'kb_service_worker_front_url' => $this->context->link->getModuleLink($this->name, 'serviceworker', array('action' => 'registerServiceWorker'), (bool) Configuration::get('PS_SSL_ENABLED')),
                    )
                );
                return  $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/hook/service_worker_registration.tpl');
            }
        }
    }
    
    public function hookActionBeforeAuthentication($params)
    {
        $id_guest = $this->context->cookie->id_guest;
        $this->context->cookie->__set('kb_push_guest_id', $id_guest);
    }
    
    public function hookActionAuthentication($params)
    {
        $this->updateCustomerGuestId();
    }
    
    public function hookActionBeforeSubmitAccount()
    {
        $id_guest = $this->context->cookie->id_guest;
        $this->context->cookie->__set('kb_push_guest_id', $id_guest);
    }
    
    public function hookActionCustomerAccountAdd()
    {
         $this->updateCustomerGuestId();
    }
    
    /*
     * function to update customer guest id if token is updated
     */
    protected function updateCustomerGuestId()
    {
        if (isset($this->context->cookie->kb_push_guest_id)) {
            $id_guest = $this->context->cookie->kb_push_guest_id;
            $updated_id_guest = $this->context->cookie->id_guest;
            $guestSubscriber = KbPushSubscribers::getPushSubscriber($id_guest);
            if (!empty($guestSubscriber)) {
                $kbsubscriber = new KbPushSubscribers($guestSubscriber['id_subscriber']);
                if (!empty($kbsubscriber->id_subscriber)) {
                    if (!empty($updated_id_guest) && $updated_id_guest != 0) {
                        $kbsubscriber->id_guest = $updated_id_guest;
                        if ($kbsubscriber->update()) {
                            DB::getInstance()->execute('UPDATE '._DB_PREFIX_.'kb_web_push_product_subscriber_mapping set id_guest='.(int)$updated_id_guest.' WHERE id_guest='.(int)$id_guest);
                        }
                    }
                    unset($this->context->cookie->kb_push_guest_id);
                }
            }
        }
        
        return true;
    }

    /*
     * hook function to display sign up box to register
     * for notification in Product page
     */
    public function hookDisplayFooterProduct($params)
    {
        $config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
        if (!empty($config) && isset($config['module_config']['enable'])) {
            $product_config = $config['product_signup_setting'];
            if (!empty($product_config) && isset($product_config['enable']) && $product_config['enable']) {
//                $product = $params['product'];
//                Tools::dieObject($product);
                $id_lang = Context::getContext()->language->id;
                $product = new Product($params['product']['id'], true, $id_lang);
                $product_image = Image::getCover($product->id);
                $id_image = 0;
                if (!empty($product_image)) {
                    $id_image = $product_image['id_image'];
                }
                $id_guest = Context::getContext()->cookie->id_guest;
                $id_shop = Context::getContext()->shop->id;
                $id_lang = Context::getContext()->language->id;
                
                $getSubscriber = KbPushSubscribers::getSubscriberRegIDs($id_guest, $id_shop);
                $priceDisplay = Product::getTaxCalculationMethod((int)$this->context->cookie->id_customer);
                $productPrice = Product::getPriceStatic($product->id, false, null, 6);
                if (!$priceDisplay ||$priceDisplay == 2) {
                    $productPrice = Product::getPriceStatic($product->id, true, null, 6);
                }
                $reg_id = '';
                if (!empty($getSubscriber) && count($getSubscriber) > 0) {
                    $reg_id = $getSubscriber[0]['reg_id'];
                }
                $product_price = Tools::displayPrice($productPrice);
                $product_img = $this->context->link->getImageLink($product->link_rewrite, $id_image, ImageType::getFormatedName('medium'));
                
                $ecotax_rate = (float) Tax::getProductEcotaxRate(
                    $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}
                );
                $id_group = (int) Group::getCurrent()->id;
                $group_reduction = GroupReduction::getValueForProduct($product->id, $id_group);
                if ($group_reduction === false) {
                    $group_reduction = Group::getReduction((int) $this->context->cookie->id_customer) / 100;
                }
                
                $is_registered = KbPushProductSubscribers::getSubscriberByProductANDRegID($product->id, $reg_id, $id_guest);
                
                $product_price_message = '';
                if (isset($product_config['kbsignup_price_message'][$id_lang]) &&
                        !empty($product_config['kbsignup_price_message'][$id_lang])) {
                    $product_price_message = $product_config['kbsignup_price_message'][$id_lang];
                    $product_price_message = str_replace('{{kb_item_name}}', $product->name, $product_price_message);
                    $product_price_message = str_replace('{{kb_item_current_price}}', $product_price, $product_price_message);
                    $product_price_message = str_replace('{{kb_item_reference}}', $product->reference, $product_price_message);
                }
                $product_stock_message = '';
                if (isset($product_config['kbsignup_stock_message'][$id_lang]) &&
                        !empty($product_config['kbsignup_stock_message'][$id_lang])) {
                    $product_stock_message = $product_config['kbsignup_stock_message'][$id_lang];
                    $product_stock_message = str_replace('{{kb_item_name}}', $product->name, $product_stock_message);
                    $product_stock_message = str_replace('{{kb_item_current_price}}', $product_price, $product_stock_message);
                    $product_stock_message = str_replace('{{kb_item_reference}}', $product->reference, $product_stock_message);
                }
                $price_info = array(
                    'heading' => (isset($product_config['kbsignup_price_heading'][$id_lang]) && !empty($product_config['kbsignup_price_heading'][$id_lang])) ? $product_config['kbsignup_price_heading'][$id_lang] : $this->l('Set Price Alert'),
                    'message' => $product_price_message,
                );
                $stock_info = array(
                    'heading' => (isset($product_config['kbsignup_stock_heading'][$id_lang]) && !empty($product_config['kbsignup_stock_heading'][$id_lang])) ? $product_config['kbsignup_stock_heading'][$id_lang] : $this->l('Set Product Stock Alert'),
                    'message' => $product_stock_message,
                );
                
                $this->context->smarty->assign(array(
                    'product_signup' => $product_config,
                    'product_price' => $product_price,
                    'product_img' => $product_img,
                    'price_info' => json_encode($price_info),
                    'stock_info' => json_encode($stock_info),
                    'reg_id' => $reg_id,
                    'product_price_message' => $product_price_message,
                    'product_stock_message' => $product_stock_message,
                    'id_product' => $product->id,
                    'allow_oosp' => $product->isAvailableWhenOutOfStock((int)$product->out_of_stock),
                    'id_guest' => $id_guest,
                    'PS_CATALOG_MODE'     => (bool)Configuration::get('PS_CATALOG_MODE') || (Group::isFeatureActive() && !(bool)Group::getCurrent()->show_prices),
                    'id_shop' => $id_shop,
                    'product_price_wt_sign' => $productPrice,
                    'currency_code' => $this->context->currency->iso_code,
                    'product' => $product,
                    'ecotax_rate' => $ecotax_rate,
                    'group_reduction' => $group_reduction,
                    'id_lang' => Context::getContext()->language->id,
                    'loader' => $this->getModuleDirUrl() . $this->name . '/views/img/popup_loader.svg',
                    'kb_push_signup_url' => $this->context->link->getModuleLink($this->name, 'productalert', array('action' => 'signup'), (bool) Configuration::get('PS_SSL_ENABLED')),
                ));
                return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/hook/signup_product.tpl');
            }
        }
    }
    
    /*
     * function defined to send product update notification
     *  to the subscriber if there is an change in price or stock
     */
    public function hookActionProductUpdate($params)
    {
        $config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
        
        if (!empty($config) && isset($config['module_config']['enable'])) {
            $module_config = $config['module_config'];
            if (!empty($params) && isset($params['id_product'])) {
                $product = $params['product'];
                $id_product = $params['id_product'];
                $subscribed_products = DB::getInstance()->executeS(
                    'SELECT sm.*,s.reg_id as id_reg FROM ' . _DB_PREFIX_ . 'kb_web_push_product_subscriber_mapping sm INNER JOIN '._DB_PREFIX_.'kb_web_push_subscribers s on (s.id_subscriber=sm.id_subscriber AND s.id_shop='.(int)$this->context->shop->id.') '
                    . ' where sm.id_shop='.(int)$this->context->shop->id.' AND sm.id_product=' . (int) $id_product
                );
                $stock_reg_ids = array();
                $price_reg_ids = array();
                $fcm_setting = Tools::jsonDecode(Configuration::get('KB_PUSH_FCM_SERVER_SETTING'), true);
                if (!empty($fcm_setting)) {
                    $fcm_server_key = $fcm_setting['server_key'];
                    $headers = array(
                        'Authorization:key=' . $fcm_server_key,
                        'Content-Type:application/json'
                    );
                    if (!empty($subscribed_products)) {
                        foreach ($subscribed_products as $sub_product) {
                            $id_lang = $sub_product['id_lang'];
                            $id_shop = $sub_product['id_shop'];
                            $product_list = new Product($id_product, $id_lang, $id_shop);
                            
                            $id_customer = Db::getInstance()->getValue('SELECT id_customer FROM ' . _DB_PREFIX_ . 'guest where id_guest=' . (int) $sub_product['id_guest']);
                            $id_product_attribute = $sub_product['id_product_attribute'];
                            $priceDisplay = Product::getTaxCalculationMethod($id_customer);
                            $productPrice = Product::getPriceStatic($id_product, false, $id_product_attribute, 6);
                            if (!$priceDisplay || $priceDisplay == 2) {
                                $productPrice = Product::getPriceStatic($id_product, true, $id_product_attribute, 6);
                            }
                            $subscriber_usr = KbPushSubscribers::getSubscriberRegIDs($sub_product['id_guest'], $id_shop);
                            if ($module_config['enable_product_stock_alert']) {
                                if (($sub_product['subscribe_type'] == 'stock') && ($sub_product['is_sent'] == 0)) {
                                    $stock_quantity = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute, $id_shop);
                                    if ($stock_quantity > 0) {
                                        $reg_id = $sub_product['id_reg'];
//                                        if (!empty($subscriber_usr)) {
//                                            $reg_id = $subscriber_usr[count($subscriber_usr)-1]['reg_id'];
//                                        }
//                                        d($reg_id);
                                        $kbProduct = new KbPushProductSubscribers($sub_product['id_mapping']);
                                        $kbProduct->is_sent = 1;
                                        $kbProduct->sent_at = date('Y-m-d H:i:s');
                                        $kbProduct->update();
                                        $id_template = KbPushTemplates::getNotificationTemplateIDByType(self::KBPN_BACK_IN_STOCK_ALERT);
                                        if (!empty($id_template)) {
                                            $fields = array();
                                            $productURL = $this->context->link->getProductLink($id_product);
                                            $fields = $this->getNotificationPushData($id_template, $id_lang, $id_shop, $productURL);
                                            if (!empty($fields) && !empty($reg_id)) {
                                                $message = '';
                                                if (isset($fields['data']['body'])) {
                                                    $message = $fields['data']['body'];
                                                    $message = str_replace('{{kb_item_name}}', $product_list->name, $message);
                                                    $message = str_replace('{{kb_item_current_price}}', Tools::displayPrice($productPrice), $message);
                                                    $fields['data']['body'] = $message;
                                                }
                                                $fields['to'] = $reg_id;
                                                $fields["data"]["base_url"] = $this->getBaseUrl();
                                                $fields["data"]["click_url"] = $this->context->link->getModuleLink($this->name, 'serviceworker', array('action' => 'updateClickPush'), (bool) Configuration::get('PS_SSL_ENABLED'));
                                                $is_sent = 1;
                                                $kbTemplate = new KbPushTemplates($id_template, false, $id_shop);
                                                if (!empty($kbTemplate) && !empty($kbTemplate->id)) {
                                                    $push_id = $this->savePushNotification($kbTemplate, $is_sent, array($reg_id));
                                                    if (!empty($push_id)) {
                                                        $fields["data"]["push_id"] = $push_id;
                                                        $result = $this->sendPushRequestToFCM($headers, $fields);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if ($module_config['enable_product_price_alert']) {
                                if (($sub_product['subscribe_type'] == 'price') && ($sub_product['is_sent'] == 0)) {
                                    if ($productPrice < $sub_product['product_price']) {
                                        $reg_id = $sub_product['id_reg'];
                                        //d($reg_id);
//                                        d($subscriber_usr);
//                                        if (!empty($subscriber_usr)) {
//                                            $reg_id = $subscriber_usr[count($subscriber_usr)-1]['reg_id'];
//                                        }
                                        $kbProduct = new KbPushProductSubscribers($sub_product['id_mapping']);
                                        $kbProduct->product_price = $productPrice;
                                        $kbProduct->is_sent = 0;
                                        $kbProduct->sent_at = date('Y-m-d H:i:s');
                                        $kbProduct->update();
                                        $id_template = KbPushTemplates::getNotificationTemplateIDByType(self::KBPN_PRICE_ALERT);
                                        if (!empty($id_template)) {
                                            $fields = array();
                                            $productURL = $this->context->link->getProductLink($id_product);
                                            $fields = $this->getNotificationPushData($id_template, $id_lang, $id_shop, $productURL);
                                            if (!empty($fields) && !empty($reg_id)) {
                                                $message = '';
                                                if (isset($fields['data']['body'])) {
                                                    $message = $fields['data']['body'];
                                                    $message = str_replace('{{kb_item_name}}', $product_list->name, $message);
                                                    $message = str_replace('{{kb_item_current_price}}', Tools::displayPrice($productPrice), $message);
                                                    $message = str_replace('{{kb_item_old_price}}', Tools::displayPrice($sub_product['product_price']), $message);
                                                    $fields['data']['body'] = $message;
                                                }
                                                $fields['to'] = $reg_id;
                                                $fields["data"]["base_url"] = $this->getBaseUrl();
                                                $fields["data"]["click_url"] = $this->context->link->getModuleLink($this->name, 'serviceworker', array('action' => 'updateClickPush'), (bool) Configuration::get('PS_SSL_ENABLED'));
                                                $is_sent = 1;
                                                $kbTemplate = new KbPushTemplates($id_template, false, $id_shop);
                                                $push_id = $this->savePushNotification($kbTemplate, $is_sent, array($reg_id));
                                                if (!empty($push_id)) {
                                                    $fields["data"]["push_id"] = $push_id;
                                                    $result = $this->sendPushRequestToFCM($headers, $fields);
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
     * Hook to check if order status get updated
     *  and then send notification to the subscribers
     */
    public function hookActionOrderStatusUpdate($params)
    {
        if (!empty($params['id_order'])) {
            $lastOrderStatus = OrderHistory::getLastOrderState($params['id_order']);
            $config = Tools::jsonDecode(Configuration::get('KB_PUSH_NOTIFICATION'), true);
            if (!empty($config) && isset($config['module_config']['enable'])) {
                if (!empty($lastOrderStatus)) {
                    $order = new Order($params['id_order']);
                    $current_order_status = $params['newOrderStatus']->id;
                    $old_order_status = $order->current_state;
                    $module_config = $config['module_config'];
                    $id_shop = $params['cart']->id_shop;
                    $id_lang = $params['cart']->id_lang;
                    if ($module_config['enable'] && $module_config['enable_order_status']) {
                        $orderStatus_old = new OrderState($old_order_status, $id_lang);
                        $orderStatus_new = new OrderState($current_order_status, $id_lang);
                        $current_status = $orderStatus_new->name;
                        $old_status = $orderStatus_old->name;
                        $id_guest = Db::getInstance()->getValue('SELECT id_guest FROM `'._DB_PREFIX_.'guest` WHERE `id_customer` ='.(int)$params['cart']->id_customer);
                        $reg_id = KbPushSubscribers::getSubscriberRegIDs($id_guest, $id_shop);

                        if (empty($reg_id)) {
                            $id_guest = $params['cart']->id_guest;
                            $reg_id = KbPushSubscribers::getSubscriberRegIDs($id_guest, $id_shop);
                        }
                        if (!empty($reg_id) && count($reg_id) > 0) {
                            $reg_id = $reg_id[count($reg_id)-1]['reg_id'];
                            $fcm_setting = Tools::jsonDecode(Configuration::get('KB_PUSH_FCM_SERVER_SETTING'), true);
                            if (!empty($fcm_setting)) {
                                $fcm_server_key = $fcm_setting['server_key'];
                                $headers = array(
                                    'Authorization:key=' . $fcm_server_key,
                                    'Content-Type:application/json'
                                );
                                $id_template = KbPushTemplates::getNotificationTemplateIDByType(self::KBPN_ORDER_STATUS_UPDATE);
                                if (!empty($id_template)) {
                                    $fields = array();
                                    $fields = $this->getNotificationPushData($id_template, $id_lang, $id_shop);
                                    if (!empty($fields)) {
                                        $kbTemplate = new KbPushTemplates($id_template, false, $id_shop);
                                        $message = '';
                                        if (isset($fields['data']['body'])) {
                                            $message = $fields['data']['body'];
                                            if ($kbTemplate->notification_type == self::KBPN_ORDER_STATUS_UPDATE) {
                                                $orderTotal = $order->getOrdersTotalPaid();
                                                $orderTotal = Tools::displayPrice($orderTotal);
                                                $message = str_replace('{{kb_order_reference}}', $order->reference, $message);
                                                $message = str_replace('{{kb_order_amount}}', $orderTotal, $message);
                                                $message = str_replace('{{kb_order_before_status}}', $old_status, $message);
                                                $message = str_replace('{{kb_order_after_status}}', $current_status, $message);
                                                $fields['data']['body'] = $message;
                                            }
                                        }
                                        $is_sent = 1;
                                        $push_id = $this->savePushNotification($kbTemplate, $is_sent, array($reg_id));
                                        if (!empty($push_id)) {
                                            $fields['to'] = $reg_id;
                                            $fields["data"]["base_url"] = $this->getBaseUrl();
                                            $fields["data"]["click_url"] = $this->context->link->getModuleLink($this->name, 'serviceworker', array('action' => 'updateClickPush'), (bool) Configuration::get('PS_SSL_ENABLED'));
                                            $fields["data"]['push_id'] = $push_id;
                                            $result = $this->sendPushRequestToFCM($headers, $fields);
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
    
    /*
     * function to load the JS and CSS files
     */
    protected function kbSetMedia()
    {
        /* CSS files */
        $this->context->controller->addCSS($this->_path . 'views/css/front/kb_front.css');
        
        /* JS files */
        $this->context->controller->addJS($this->_path . 'views/js/velovalidation.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase-app.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase-storage.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase-auth.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase-database.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase-messaging.js');
        $this->context->controller->addJS($this->_path . 'views/js/firebase/firebase.js');
        $this->context->controller->addJS($this->_path . 'views/js/service_worker_registeration_template.js');
        $this->context->controller->addJS($this->_path . 'views/js/front/kb_front.js');
    }
    
    public function getNotificationPushData($id_template, $id_lang = null, $id_shop = null, $productURL = null)
    {
        $fields = array();
        if (empty($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }
        if (empty($id_shop)) {
            $id_shop = Context::getContext()->shop->id;
        }
        if (!empty($id_template)) {
            $kbTemplate = new KbPushTemplates($id_template, $id_lang, $id_shop);
            if (!empty($kbTemplate) && !empty($kbTemplate->id)) {
                $fields["data"] = array(
                    "title" => $kbTemplate->notification_title,
                    "action" => $kbTemplate->notification_title,
                    'body' => $kbTemplate->notification_message,
                    "link" => $kbTemplate->primary_url,
                    'icon' => (!empty($kbTemplate->notify_icon)) ? $kbTemplate->notify_icon : '',
                );
                if (!empty($productURL) && $productURL != '') {
                    $fields["data"]['link'] = $productURL;
                }
                $button_img = $this->getModuleDirUrl() . $this->name . '/views/img/cta3.png';

                if (isset($kbTemplate->action_button1) && !empty($kbTemplate->action_button1)) {
                    $title1 = $kbTemplate->action_button1;
                } else {
                    $title1 = '';
                }

                if (isset($kbTemplate->action_button2) && !empty($kbTemplate->action_button2)) {
                    $title2 = $kbTemplate->action_button2;
                } else {
                    $title2 = '';
                }

                if (isset($kbTemplate->action_button_link1) && !empty($kbTemplate->action_button_link1)) {
                    $action_button_link1 = $kbTemplate->action_button_link1;
                } else {
                    $action_button_link1 = '';
                }

                if (isset($kbTemplate->action_button_link2) && !empty($kbTemplate->action_button_link2)) {
                    $action_button_link2 = $kbTemplate->action_button_link2;
                } else {
                    $action_button_link2 = '';
                }

                if ($title1 != '' || $title2 != '') {
                    $fields["data"]["actions"] = array(
                        array("action" => $action_button_link1, "icon" => $button_img, "title" => $title1),
                        array("action" => $action_button_link2, "icon" => $button_img, "title" => $title2)
                    );
                    $fields["data"]["actions_links"] = array(
                        array("cta1" => $action_button_link1),
                        array("cta2" => $action_button_link2)
                    );
                }
            }
        }
        return $fields;
    }
    
    /*
     * function defined to save notification which was sent to the user
     */
    public function savePushNotification($data, $sent_to = 0, $reg_ids = array())
    {
        if (!empty($data)) {
            $id_shop = Context::getContext()->shop->id;
            $kbpushData = new KbPushPushes();
            $kbpushData->title = $data->notification_title;
            $kbpushData->message = $data->notification_message;
            $kbpushData->primary_url = $data->primary_url;
            $kbpushData->notify_icon = $data->notify_icon;
            $kbpushData->type = $data->notification_type;
            if (!empty($data->action_button1)) {
                $kbpushData->action_button1 = $data->action_button1;
            }
            if (!empty($data->action_button2)) {
                $kbpushData->action_button2 = $data->action_button2;
            }
            if (!empty($data->action_button_link1)) {
                $kbpushData->action_button_link1 = $data->action_button_link1;
            }
            if (!empty($data->action_button_link2)) {
                $kbpushData->action_button_link2 = $data->action_button_link2;
            }
            
            $kbpushData->is_active = 1;
            $kbpushData->id_shop = $id_shop;
            $kbpushData->sent_to = $sent_to;
            if ($sent_to) {
                $kbpushData->is_sent = 1;
                $kbpushData->sent_at = date("Y-m-d H:i:s");
            } else {
                $kbpushData->is_sent = 0;
            }
            
            if ($kbpushData->save() && !empty($reg_ids)) {
                foreach ($reg_ids as $reg_id) {
                    $mapping_data = new KbPushSubscriberMapping();
                    $mapping_data->id_push = $kbpushData->id;
                    $mapping_data->reg_id = $reg_id;
                    $mapping_data->id_shop = $id_shop;
                    $mapping_data->save();
                }
                return $kbpushData->id;
            }
            return false;
        }
    }
    
    /*
     * function defined to send push request to FCM
     *  to send notification to the subscribers
     */
    public function sendPushRequestToFCM($headers, $fields = array())
    {
        $payload = json_encode($fields);
        $curl_session = curl_init();
        curl_setopt($curl_session, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
        
        $result = json_decode(curl_exec($curl_session), true);
        return $result;
    }


    public static function getRemoteAddr()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
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
    
    /*
     * function to generate unique key
     */
    protected function kbKeyGenerator($length = 32)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(mt_rand(33, 126));
        }
        return md5($random);
    }
}
