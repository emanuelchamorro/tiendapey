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

include_once(_PS_MODULE_DIR_.'kbreviewincentives/classes/admin/ReminderProfile.php');
class AdminKbrcReminderProfilesController extends ModuleAdminController
{
    protected $kb_module_name = 'kbreviewincentives';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->context = Context::getContext();
        $this->list_no_link = true;
        $this->className = 'ReminderProfile';
        $this->kb_smarty = new Smarty();
        $this->kb_smarty->registerPlugin('function', 'l', 'smartyTranslate');
        $this->kb_smarty->setTemplateDir(_PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/');
        $this->table = 'velsof_reminder_profile';
        $this->identifier = 'reminder_profile_id';
        $this->lang = false;
        $this->display = 'list';
        parent::__construct();

        $this->toolbar_title = $this->module->l('Reminder Profiles', 'AdminKbrcReminderProfiles');

        if (Tools::getValue('reminder_profile_id')) {
            $this->toolbar_title = $this->module->l('Edit Reminder Profile', 'AdminKbrcReminderProfiles');
        } elseif (Tools::isSubmit('add' . $this->table)) {
            $this->toolbar_title = $this->module->l('Add Reminder Profile', 'AdminKbrcReminderProfiles');
        } else {
            $this->toolbar_title = $this->module->l('Reminder Profile', 'AdminKbrcReminderProfiles');
        }

        $this->fields_list = array(
            'reminder_profile_id' => array(
                'title' => $this->module->l('ID', 'AdminKbrcReminderProfiles'),
                'search' => false,
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'no_of_days_after' => array(
                'title' => $this->module->l('Days', 'AdminKbrcReminderProfiles'),
                'align' => 'text-center',
                'search' => true,
            ),
            'active' => array(
                'title' => $this->module->l('Active', 'AdminKbrcReminderProfiles'),
                'align' => 'text-center',
                'type' => 'select',
                'filter_key' => 'active',
                'list' => array('1' => $this->module->l('Enable', 'AdminKbrcReminderProfiles'), '0' => $this->module->l('Disable', 'AdminKbrcReminderProfiles')),
                'active' => 'status',
                'search' => true
            ),
            'enable_order_create_reminder' => array(
                'title' => $this->module->l('Created date/Order Status', 'AdminKbrcReminderProfiles'),
                'callback' => 'getYesNoTemp',
                'align' => 'text-center',
                'filter_key' => 'enable_order_create_reminder',
                'list' => array('1' => $this->module->l('Enable', 'AdminKbrcReminderProfiles'), '0' => $this->module->l('Disable', 'AdminKbrcReminderProfiles')),
                'type' => 'select',
//                'active' => 'status',
                'search' => true
            ),
            'date_add' => array(
                'title' => $this->module->l('Date Added', 'AdminKbrcReminderProfiles'),
                'align' => 'text-center',
                'type' => 'datetime',
                'search' => true
            )
        );
        $this->bulk_actions = array(
            $this->module->l('delete', 'AdminKbrcReminderProfiles') => array(
                'text' => $this->module->l('Delete selected', 'AdminKbrcReminderProfiles'),
                'confirm' => $this->module->l('Delete selected testimonial(s)?', 'AdminKbrcReminderProfiles'),
                'icon' => 'icon-trash'
            )
        );
        $this->_join = "INNER JOIN " . _DB_PREFIX_ . "velsof_reminder_profile_templates rpt ON "
                . " a.reminder_profile_id = rpt.reminder_profile_id AND rpt.id_shop = '" . (int) $this->context->shop->id . "' AND rpt.id_lang = ".(int) $this->context->language->id ;
//        $this->_select = 'osl.*';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }
    /*
     * Function to render enable disable HTML with no link
     */
    public function getYesNoTemp($row_data, $tr)
    {
        $tpl = $this->kb_smarty->createTemplate('yes_no_temp.tpl');
        if ($row_data == 1) {
            $tpl->assign(array(
                'renderType' => 1,
            ));
        } else {
            $tpl->assign(array(
                'renderType' => 0,
            ));
        }
        return $tpl->fetch();
    }
    
     /*
     * Default Function (Used here to handle adding a new REMINDER PROFILE)
     * Default admin controller function to add
     */
    public function processAdd()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $language = Language::getLanguages(false);
            $this->obj = new ReminderProfile();
            $this->obj->active = Tools::getValue('active');
            $enable_order_create_reminder = Tools::getValue('enable_order_create_reminder');
            $this->obj->enable_order_create_reminder = $enable_order_create_reminder;
            if ($enable_order_create_reminder == 0) {
                $order_status = Tools::getValue('select_type');
            } else {
                $order_status = array();
            }
            $this->obj->select_type = serialize($order_status);
            $this->obj->no_of_days_after = Tools::getValue('no_of_days_after');
            $this->obj->date_updated = date('Y-m-d H:i:s');
            //Save added data
            $this->obj->save();
            $reminder_profile_id = $this->obj->id;
            foreach ($language as $lang) {
                $email_subject = Tools::getValue(
                    'REMINDER_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_template = Tools::getValue(
                    'REMINDER_EMAIL_TEMP_' . $lang['id_lang']
                );
                $body = $email_template;
                $text_content = strip_tags($email_template);
                $sql = "INSERT INTO " . _DB_PREFIX_ . "velsof_reminder_profile_templates VALUES('','" . (int) $reminder_profile_id . "','" . (int) $lang['id_lang'] . "',"
                        . " '" . (int) $this->context->shop->id . "','" . pSQL($lang['iso_code']) . "', 'reminder', '" . pSQL(Tools::htmlentitiesUTF8($text_content)) . "',"
                        . " '" . pSQL(Tools::htmlentitiesUTF8($email_subject)) . "', '" . pSQL(Tools::htmlentitiesUTF8($body)) . "', now(), now())";

                $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                if ($res) {
                    $this->context->cookie->__set(
                        'kb_redirect_success',
                        $this->module->l('Reminder Profile has been saved successfully.', 'AdminKbrcReminderProfiles')
                    );
                } else {
                    $this->context->cookie->__set(
                        'kb_redirect_error',
                        $this->module->l('Unable to save data. Technical Error', 'AdminKbrcReminderProfiles')
                    );
                }
            }
        }
    }
    /*
     * Default Function (Used here to handle updating a REMINDER PROFILE)
     * Default function of admin controller for update
     */
    public function processUpdate()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $language = Language::getLanguages(false);
            $this->obj = $this->loadObject(Tools::getValue('reminder_profile_id'));
            $this->obj->active = Tools::getValue('active');
            $enable_order_create_reminder = Tools::getValue('enable_order_create_reminder');
            $this->obj->enable_order_create_reminder = $enable_order_create_reminder;
            if ($enable_order_create_reminder == 0) {
                $order_status = Tools::getValue('select_type');
            } else {
                $order_status = array();
            }
            $this->obj->select_type = serialize($order_status);
            $this->obj->no_of_days_after = Tools::getValue('no_of_days_after');
            $this->obj->date_updated = date('Y-m-d H:i:s');
            //Save added data
            $this->obj->save();
            $reminder_profile_id = $this->obj->id;
            foreach ($language as $lang) {
                $email_subject = Tools::getValue(
                    'REMINDER_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_template = Tools::getValue(
                    'REMINDER_EMAIL_TEMP_' . $lang['id_lang']
                );
                $body = $email_template;
                $text_content = strip_tags($email_template);
                $sql = "UPDATE " . _DB_PREFIX_ . "velsof_reminder_profile_templates SET  text_content = '" . pSQL(Tools::htmlentitiesUTF8($text_content)) . "',subject = "
                        . " '" . pSQL(Tools::htmlentitiesUTF8($email_subject)) . "',body = '" . pSQL(Tools::htmlentitiesUTF8($body)) . "', date_updated = now() WHERE reminder_profile_id = '" . (int) $reminder_profile_id . "' AND id_lang = '" . (int) $lang['id_lang'] . "'"
                        . " AND id_shop = '" . (int) $this->context->shop->id . "'";

                $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                if ($res) {
                    $this->context->cookie->__set(
                        'kb_redirect_success',
                        $this->module->l('Reminder Profile has been updated successfully.', 'AdminKbrcReminderProfiles')
                    );
                } else {
                    $this->context->cookie->__set(
                        'kb_redirect_error',
                        $this->module->l('Unable to update data. Technical Error.', 'AdminKbrcReminderProfiles')
                    );
                }
            }
        }
    }
    /*
     * Delete process deleting email template of respective reminder profile
     * Default admin controller function for delete
     */
    public function processDelete()
    {
        if (Tools::isSubmit('delete' . $this->table)) {
            $reminder_profile_id = Tools::getValue('reminder_profile_id');
            $query = "DELETE FROM " . _DB_PREFIX_ . "velsof_reminder_profile WHERE reminder_profile_id = '" . (int) $reminder_profile_id . "'";
            Db::getInstance()->execute($query);
            $query = "DELETE FROM " . _DB_PREFIX_ . "velsof_reminder_profile_templates WHERE reminder_profile_id = '" . (int) $reminder_profile_id . "'";
            Db::getInstance()->execute($query);
            $this->context->cookie->__set(
                'kb_redirect_success',
                $this->module->l('Reminder Profile has been deleted successfully.', 'AdminKbrcReminderProfiles')
            );
        }
    }
    /*
     * Default admin controller function for rendering form
     */
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }


        /* To get order states */
        $order_status = array();
        $sql = "SELECT id_order_state, name FROM " . _DB_PREFIX_ . "order_state_lang WHERE id_lang = '" . (int) $this->context->language->id . "'";
        $order_status_kbcf = Db::getInstance()->executeS($sql);
        foreach ($order_status_kbcf as $order_kbcf) {
            $order_status[] = array(
                'id_option' => $order_kbcf['id_order_state'],
                'name' => $order_kbcf['name']
            );
        }
        $this->fields_form = array(
            'id_form' => 'kbrc_reminder_settings',
            'legend' => array(
                'title' => $this->module->l('Add Reminder Profiles', 'AdminKbrcReminderProfiles'),
                'icon' => 'icon-envelope'
            ),
            'input' => array(
                array(
                    'label' => $this->module->l('Enable/Disable this Reminder', 'AdminKbrcReminderProfiles'),
                    'type' => 'switch',
                    'name' => 'active',
                    'values' => array(
                        array(
                            'value' => 1,
                        ),
                        array(
                            'value' => 0,
                        ),
                    ),
                    'hint' => $this->module->l('Enable/Disable this Reminder', 'AdminKbrcReminderProfiles'),
                ),
                array(
                    'label' => $this->module->l('Enable this if you want to send reminder after order creation date.', 'AdminKbrcReminderProfiles'),
                    'type' => 'switch',
                    'name' => 'enable_order_create_reminder',
                    'values' => array(
                        array(
                            'value' => 1,
                        ),
                        array(
                            'value' => 0,
                        ),
                    ),
                    'desc' => $this->module->l('If enable then reminder will be send to customer, number of days after creating the order. Order status will not be checked here. If disable then number of days after selected order status, reminder will be sent to customer.', 'AdminKbrcReminderProfiles'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->l('Select State', 'AdminKbrcReminderProfiles'),
                    'name' => 'select_type[]',
                    'multiple' => true,
                    'hint' => $this->module->l('Select order status when sending a reminder.', 'AdminKbrcReminderProfiles'),
                    'required' => true,
                    'options' => array(
                        'query' => $order_status,
                        'id' => 'id_option',
                        'name' => 'name'),
                    'size' => 8
                ),
                array(
                    'label' => $this->module->l('Days (How many days after selected state mail should be sent to customer.)', 'AdminKbrcReminderProfiles'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'no_of_days_after',
                    'hint' => $this->module->l('How many days after the selected order status reminder should go to customer.', 'AdminKbrcReminderProfiles'),
                    'suffix' => $this->module->l('Days', 'AdminKbrcReminderProfiles'),
                    'col' => 2
                ),
                array(
                    'label' => $this->module->l('Email Subject', 'AdminKbrcReminderProfiles'),
                    'type' => 'text',
                    'lang' => true,
                    'hint' => $this->module->l('Subject of email for reminder.', 'AdminKbrcReminderProfiles'),
                    'name' => 'REMINDER_EMAIL_SUBJECT',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Email Template', 'AdminKbrcReminderProfiles'),
                    'hint' => $this->l('Content of email template which will be sent to customer as reminder', 'AdminKbrcReminderProfiles'),
                    'name' => 'REMINDER_EMAIL_TEMP',
                    'required' => true,
                    'cols' => '2',
                    'rows' => '10',
                    'class' => 'col-lg-9',
                    'lang' => true,
                    'autoload_rte' => true,
                    'desc' => $this->module->l('Do not remove {shop_name}, {customer_name}, {product_content}, {shop_email} tags from this template.', 'AdminKbrcReminderProfiles')
                ),
            ),
            'buttons' => array(
                array(
                    'title' => $this->module->l('Save', 'AdminKbrcReminderProfiles'),
                    'type' => 'submit',
                    'icon' => 'process-icon-save',
                    'class' => 'pull-right velsof_reminder_profile_btn',
                    'id' => 'submit_add',
                    'name' => 'submitAdd' . $this->table,
                ),
            )
        );

        $this->fields_value = array(
            'active' => $obj->active,
            'enable_order_create_reminder' => $obj->enable_order_create_reminder,
            'no_of_days_after' => $obj->no_of_days_after,
            'select_type[]' => Tools::unSerialize($obj->select_type),
        );
//        d();
        if (Tools::getIsset('reminder_profile_id')) {
            $reminder_profile_id = Tools::getValue('reminder_profile_id');
            $language = Language::getLanguages(false);
            foreach ($language as $lang) {
                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_reminder_profile_templates WHERE reminder_profile_id = '" . (int) $reminder_profile_id . "'"
                        . " AND id_lang = '" . (int) $lang['id_lang'] . "'";
                $reminder_email_data = Db::getInstance()->getRow($sql);
                $this->fields_value['REMINDER_EMAIL_SUBJECT'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($reminder_email_data['subject']);
                $this->fields_value['REMINDER_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($reminder_email_data['body']);
            }
        } else {
            $language = Language::getLanguages(false);
            foreach ($language as $lang) {
                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_review_incentive_emails WHERE template_name = 'reminder_temp' AND id_shop = '" . (int) $this->context->shop->id . "'"
                        . " AND id_lang = '" . (int) $lang['id_lang'] . "'";
                $template_data = Db::getInstance()->getRow($sql);
                $template_data = $this->module->replaceEmailImage($template_data);
                if (isset($template_data['subject']) && isset($template_data['body'])) {
                    $this->fields_value['REMINDER_EMAIL_SUBJECT'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['subject']);
                    $this->fields_value['REMINDER_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
                } else {
                    $this->fields_value['REMINDER_EMAIL_SUBJECT'][$lang['id_lang']] = 'Review these products and get amazing incentives';
                    $this->fields_value['REMINDER_EMAIL_TEMP'][$lang['id_lang']] = '';
                }
            }
        }
        return parent::renderForm();
    }
    /*
     * Default function, used here to set required smarty variables
     */
    public function initContent()
    {
         
        $link_reminder = $this->context->link->getAdminLink('AdminKbrcReminderProfiles', true);
        $link_criteria = $this->context->link->getAdminLink('AdminKbrcCriteria', true);
        $link_review = $this->context->link->getAdminLink('AdminKbrcReviews', true);
        $link_audit_log = $this->context->link->getAdminLink('AdminKbrcAuditLog', true);
        $link_report = $this->context->link->getAdminLink('AdminKbrcReports', true);
        $default_link = $this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($this->module->name).'&tab_module='.$this->module->tab.'&module_name='.urlencode($this->module->name);
        $this->context->smarty->assign('admin_configure_controller', $default_link);
        $this->context->smarty->assign('audit_log_link', $link_audit_log);
        $this->context->smarty->assign('reminder_profile_link', $link_reminder);
        $this->context->smarty->assign('exclude_condition_link', $link_criteria);
        $this->context->smarty->assign('product_review_link', $link_review);
        $this->context->smarty->assign('review_report_link', $link_report);
        $this->context->smarty->assign('method', '');
        $this->context->smarty->assign('selected_nav', 'reminder');
        $tabs = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/top_tabs_kbreviewincentive.tpl'
        );
        $this->context->smarty->assign('form', '');
        $this->context->smarty->assign('form1', '');
        $this->context->smarty->assign('controller_path', '');
        $this->context->smarty->assign('lang_id', $this->context->language->id);
        $this->context->smarty->assign('firstCall', false);
        $kb_velovalidation_variables = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/kb_velovalidation.tpl'
        );
        $this->content .= $tabs;
        $this->content .= $kb_velovalidation_variables;
        
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
     * Function for returning the absolute path of the module directory
     */
    protected function getKbModuleDir()
    {
        return _PS_MODULE_DIR_.$this->kb_module_name.'/';
    }
    /*
     * Default function, used here to include JS/CSS files for the module.
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addCSS($this->getKbModuleDir() . 'views/css/admin/kbrc_admin.css');
        $this->addJS($this->getKbModuleDir() . 'views/js/admin/kbrc_admin.js');
        $this->addJS($this->getKbModuleDir() . 'views/js/velovalidation.js');
        $this->addJs($this->getKbModuleDir() . 'views/js/admin/jquery.autocomplete.js');
        $this->addCSS($this->getKbModuleDir() . 'views/css/admin/jquery.autocomplete.css');
    }
     /*
     * Function to add back and audit log link on top toolbar
     */
    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['back_url'] = array(
            'href' => 'javascript: window.history.back();',
            'desc' => $this->module->l('Back', 'AdminKbrcReminderProfiles'),
            'icon' => 'process-icon-back'
        );
        $this->page_header_toolbar_btn['new_template'] = array(
               'href' => $this->context->link->getAdminLink('AdminKbrcAuditLog', true),
               'desc' => $this->module->l('Audit Log'),
               'icon' => 'process-icon-stats'
           );
        parent::initPageHeaderToolbar();
    }
}
