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
 * @copyright 2018 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

require_once dirname(__FILE__) . '/AdminKbPushCoreController.php';
include_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushTemplates.php');

class AdminKbPushTemplatesController extends AdminKbPushCoreController
{
    public $all_languages = array();
    protected $notification_type = array();
    protected $ps_shop = array();

    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->table = 'kb_web_push_template';
        $this->className = 'KbPushTemplates';
        $this->identifier = 'id_template';
        $this->display = 'list';
        $this->context = Context::getContext();
        $this->all_languages = $this->getAllLanguages();

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Push Templates', 'AdminKbPushTemplatesController');
        $this->notification_type[0] = $this->module->l('Custom Notification Alert', 'AdminKbPushTemplatesController');
        $this->notification_type[1] = $this->module->l('Order Status Update Alert', 'AdminKbPushTemplatesController');
        $this->notification_type[2] = $this->module->l('Abandoned Cart Alert', 'AdminKbPushTemplatesController');
        $this->notification_type[3] = $this->module->l('Product Price Alert', 'AdminKbPushTemplatesController');
        $this->notification_type[4] = $this->module->l('Product Back In Stock Alert', 'AdminKbPushTemplatesController');
        $shops = Shop::getShops();
        foreach ($shops as $shop) {
            $this->ps_shop[$shop['id_shop']] = $shop['name'];
        }

        $this->fields_list = array(
            'id_template' => array(
                'title' => $this->module->l('ID', 'AdminKbPushTemplatesController'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'notification_title' => array(
                'filter_key' => 'notification_title',
                'title' => $this->module->l('Template Title', 'AdminKbPushTemplatesController'),
                'align' => 'text-center',
            ),
            'notification_type' => array(
                'filter_key' => 'notification_type',
                'type' => 'select',
                'list' => $this->notification_type,
                'title' => $this->module->l('Template Type', 'AdminKbPushTemplatesController'),
                'align' => 'text-center',
                'callback' => 'notificationTypeList'
            ),
            'id_shop' => array(
                'filter_key' => 'id_shop',
                'type' => 'select',
                'list' => $this->ps_shop,
                'title' => $this->module->l('Shop', 'AdminKbPushTemplatesController'),
                'callback' => 'psShopList'
            ),
            'date_add' => array(
                'title' => $this->module->l('Date Added', 'AdminKbPushTemplatesController'),
                'type' => 'date',
                'filter_key' => 'a!date_add'
            ),
        );

        $this->_select = 'l.notification_title,s.id_shop';
        $this->_join = ' INNER JOIN `' . _DB_PREFIX_ . $this->table . '_lang` l on (a.id_template=l.id_template AND l.id_lang='
                .(int)Context::getContext()->language->id.' AND l.id_shop='.(int)Context::getContext()->shop->id.') ';
        $this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . $this->table . '_shop` s on (a.id_template=s.id_template) ';
        $this->_where = ' AND s.id_shop IN ('.(int)Context::getContext()->shop->id.')';
        
        $array_list = array();
        $notification_list = DB::getInstance()->executeS('SELECT id_template from '._DB_PREFIX_.$this->table.' Where notification_type IN ("3","2","1", "4")');
        if (!empty($notification_list)) {
            foreach ($notification_list as $arr) {
                $array_list[] = $arr['id_template'];
            }
        }
        $this->list_skip_actions['delete'] = (array) $array_list;
        
//        $this->_orderBy  = 'a.id_image';
        $this->_orderWay = 'DESC';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }
    
    public function renderList()
    {
        return parent::renderList();
    }
    
    public function notificationTypeList($echo, $tr)
    {
        unset($tr);
        return $this->notification_type[$echo];
    }
    
    public function psShopList($echo, $tr)
    {
        unset($tr);
        return $this->ps_shop[$echo];
    }
    
    /**
     * Function used to render the form for this controller
     *
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function renderForm()
    {
        $this->table = 'kb_web_push_template';
        $this->className = 'KbPushTemplates';
        $obj_type = '';
        $obj = $this->object;
        if (!empty($obj->notification_type)) {
            $obj_type = $obj->notification_type;
        }
        $time = time();
        $notify_image = $this->getModuleDirUrl() . $this->module->name . '/views/img/welcome_default.jpg?time=' . $time;
        if (!empty($obj->notify_icon)) {
            $notify_image = $obj->notify_icon.'?time='.$time;
        }
        $notify_img_url = "<img id='kbslmarker' class='img img-thumbnail'  src='" . $notify_image . "' width='100px;' height='100px;'>";
        
        $this->fields_form[0]['form'] = array(
            'id_form' => 'kbpush_add_notification_form',
            'legend' => array(
                'title' => $this->module->l('Knowband Push Templates', 'AdminKbPushTemplatesController'),
                'icon' => 'icon-bell'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Notification Title', 'AdminKbPushTemplatesController'),
                    'name' => 'notification_title',
                    'lang' => true,
                    'hint' => $this->module->l('This field is used to display the title in the push notification', 'AdminKbPushTemplatesController'),
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Message', 'AdminKbPushTemplatesController'),
                    'name' => 'notification_message',
                    'lang' => true,
                    'hint' => $this->module->l('This field is used to display the message in the push notification', 'AdminKbPushTemplatesController'),
                    'required' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->module->l('Upload Icon', 'AdminKbPushTemplatesController'),
                    'name' => 'notification_icon',
                    'required' => false,
                    'image' => $notify_img_url ? $notify_img_url : false,
                    'desc' => $this->module->l('For the best view, upload 192 x 192 pixel PNG image', 'AdminKbPushTemplatesController'),
                    'display_image' => true,
                    'hint' => $this->module->l('Upload image to display in push notification', 'AdminKbPushTemplatesController')
                ),
            ),
            'submit' => array(
                'title' => $this->module->l('Save', 'AdminKbPushTemplatesController'),
                'class' => 'btn btn-default pull-right form_kb-push-admin'
            ),
        );


        $shortcodes = '';
        $this->fields_form[0]['form']['input'][] = array(
            'type' => 'text',
            'label' => $this->module->l('Primary Link', 'AdminKbPushTemplatesController'),
            'name' => 'primary_url',
            'hint' => $this->module->l('This field is used to provide the link in the push notification', 'AdminKbPushTemplatesController'),
        );
        if ($obj_type == KbPushnotification::KBPN_PRICE_ALERT) {
            $this->context->smarty->assign('notification_type', 'price');
        } elseif ($obj_type == KbPushnotification::KBPN_ABANDONED_CART_ALERT) {
            $this->context->smarty->assign('notification_type', 'cart');
        } elseif ($obj_type == KbPushnotification::KBPN_BACK_IN_STOCK_ALERT) {
            $this->context->smarty->assign('notification_type', 'stock');
        } elseif ($obj_type == KbPushnotification::KBPN_ORDER_STATUS_UPDATE) {
            $this->context->smarty->assign('notification_type', 'orderstatus');
        }

        $shortcodes = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/shortcodes.tpl');
        $this->multiple_fieldsets = true;
        $this->fields_form = array_values($this->fields_form);
        $this->context->smarty->assign('id_lang', $this->context->language->id);
        $this->context->smarty->assign('languages', $this->all_languages);
        $this->context->smarty->assign('form_data', $obj);
        $pushbtn = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/pushbuttons.tpl');
        $validation = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/velovalidation.tpl');
        return $validation.parent::renderForm().$pushbtn.$shortcodes;
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
        $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/velovalidation.tpl');

        parent::initContent();
    }
    
    /*
     * function to process add template
     */
    public function processAdd()
    {
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            $languages = $this->all_languages;
            $notification_title = array();
            $notification_message = array();
            foreach ($languages as $lang) {
                $notification_title[$lang['id_lang']] = trim(Tools::getValue('notification_title_' . $lang['id_lang']));
                $notification_message[$lang['id_lang']] = trim(Tools::getValue('notification_message_' . $lang['id_lang']));
            }

            $primary_link = trim(Tools::getValue('primary_url'));
            $action_button1 = array();
            $action_button2 = array();
            foreach ($languages as $lang) {
                $action_button1[$lang['id_lang']] = (array)(!empty(Tools::getValue('action_button1_' . $lang['id_lang']))) ? trim(Tools::getValue('action_button1_' . $lang['id_lang'])) : '';
                $action_button2[$lang['id_lang']] = (array)(!empty(Tools::getValue('action_button2_' . $lang['id_lang']))) ? trim(Tools::getValue('action_button2_' . $lang['id_lang'])) : '';
            }
            $action_button_link2 = (!empty(Tools::getValue('action_button_link2'))) ? trim(Tools::getValue('action_button_link2')) : '';
            $action_button_link1 = (!empty(Tools::getValue('action_button_link1'))) ? trim(Tools::getValue('action_button_link1')) : '';

            $kbnotify = new KbPushTemplates();
            $kbnotify->notification_message = $notification_message;
            $kbnotify->notification_title = $notification_title;
            $kbnotify->primary_url = $primary_link;
            $kbnotify->action_button1 = $action_button1;
            $kbnotify->action_button2 = $action_button2;
            $kbnotify->action_button_link1 = $action_button_link1;
            $kbnotify->action_button_link2 = $action_button_link2;
            $kbnotify->notify_icon = $this->getModuleDirUrl() . $this->module->name . '/views/img/welcome_custom.jpg';
            $kbnotify->notify_icon_path = _PS_MODULE_DIR_ . $this->module->name . '/views/img/welcome_custom.jpg';
            $kbnotify->active = 1;
            $kbnotify->notification_type = KbPushnotification::KBPN_CUSTOM_NOTIFY_ALERT;
            
            if ($kbnotify->add()) {
                $id_template = $kbnotify->id;
                //image upload
                if (!empty($_FILES)) {
                    if ($_FILES['notification_icon']['error'] == 0 && $_FILES['notification_icon']['name'] != '' && $_FILES['notification_icon']['size'] > 0) {
                        $file_extension = pathinfo($_FILES['notification_icon']['name'], PATHINFO_EXTENSION);
                        $path = _PS_MODULE_DIR_ . $this->module->name . '/views/img/notify_' . $id_template . '.' . $file_extension;
                        $mask = _PS_MODULE_DIR_ . $this->module->name . '/views/img/notify_'.$id_template.'.*';
                        $matches = glob($mask);
                        if (count($matches) > 0) {
                            array_map('unlink', $matches);
                        }
                        $upload = move_uploaded_file(
                            $_FILES['notification_icon']['tmp_name'],
                            $path
                        );
                        chmod($path, 0777);
                        if ($upload) {
                            $kbnotify->notify_icon_path = $path;
                            $kbnotify->notify_icon = $this->getModuleDirUrl() . $this->module->name . '/views/img/notify_' . $id_template . '.' . $file_extension;
                        }
                    }
                }
                $kbnotify->update();
                $this->context->cookie->__set('kb_redirect_success', $this->module->l('Template successfully created.', 'AdminKbPushTemplatesController'));
                Tools::redirectAdmin($this->context->link->getAdminlink('AdminKbPushTemplates'));
            }
        }
    }
    
    /*
     * function to process update template
     */
    public function processUpdate()
    {
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            $id_template = Tools::getValue('id_template');
            $languages = $this->all_languages;
            $notification_message = array();
            $notification_title = array();
            foreach ($languages as $lang) {
                $notification_title[$lang['id_lang']] = trim(Tools::getValue('notification_title_' . $lang['id_lang']));
                $notification_message[$lang['id_lang']] = trim(Tools::getValue('notification_message_' . $lang['id_lang']));
            }

            $primary_link = trim(Tools::getValue('primary_url'));
            $action_button1 = array();
            $action_button2 = array();
            foreach ($languages as $lang) {
                $action_button1[$lang['id_lang']] = (!empty(Tools::getValue('action_button1_' . $lang['id_lang']))) ? trim(Tools::getValue('action_button1_' . $lang['id_lang'])) : '';
                $action_button2[$lang['id_lang']] = (!empty(Tools::getValue('action_button2_' . $lang['id_lang']))) ? trim(Tools::getValue('action_button2_' . $lang['id_lang'])) : '';
            }
            $action_button_link2 = (!empty(Tools::getValue('action_button_link2'))) ? trim(Tools::getValue('action_button_link2')) : '';
            $action_button_link1 = (!empty(Tools::getValue('action_button_link1'))) ? trim(Tools::getValue('action_button_link1')) : '';
            
            $kbnotify = new KbPushTemplates($id_template);
            //image upload
            if (!empty($_FILES)) {
                if ($_FILES['notification_icon']['error'] == 0 && $_FILES['notification_icon']['name'] != '' && $_FILES['notification_icon']['size'] > 0) {
                    $file_extension = pathinfo($_FILES['notification_icon']['name'], PATHINFO_EXTENSION);
                    $path = _PS_MODULE_DIR_ . $this->module->name . '/views/img/notify_'.$id_template.'.' . $file_extension;
                    $exist_image = $kbnotify->notify_icon_path;
                    $mask = _PS_MODULE_DIR_ . $this->module->name . '/views/img/notify_'.$id_template.'.*';
                    $matches = glob($mask);
                    if (count($matches) > 0) {
                        array_map('unlink', $matches);
                    }
                    $upload = move_uploaded_file(
                        $_FILES['notification_icon']['tmp_name'],
                        $path
                    );
                    chmod($path, 0777);
                    if ($upload) {
                        $kbnotify->notify_icon_path = $path;
                        $kbnotify->notify_icon = $this->getModuleDirUrl().$this->module->name.'/views/img/notify_'.$id_template.'.' . $file_extension;
                    }
                }
            }

            $kbnotify->notification_message = $notification_message;
            $kbnotify->notification_title = $notification_title;
            $kbnotify->primary_url = $primary_link;
            $kbnotify->action_button1 = $action_button1;
            $kbnotify->action_button2 = $action_button2;
            $kbnotify->action_button_link1 = $action_button_link1;
            $kbnotify->action_button_link2 = $action_button_link2;
            if ($kbnotify->update()) {
                $this->context->cookie->__set('kb_redirect_success', $this->module->l('Template successfully updated.', 'AdminKbPushTemplatesController'));
                Tools::redirectAdmin($this->context->link->getAdminlink('AdminKbPushTemplates'));
            }
        }
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
        $this->page_header_toolbar_btn['back_url'] = array(
            'href' => 'javascript: window.history.back();',
            'desc' => $this->module->l('Back', 'AdminKbPushTemplatesController'),
            'icon' => 'process-icon-back'
        );
        if (!Tools::getValue('id_template') && !Tools::isSubmit('add'.$this->table)) {
            $this->page_header_toolbar_btn['new_template'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->module->l('Add new Push Template', 'AdminKbPushTemplatesController'),
                'icon' => 'process-icon-new'
            );
        }
        parent::initPageHeaderToolbar();
    }
}
