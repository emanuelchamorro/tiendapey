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

class AdminKbrcReportsController extends ModuleAdminController
{

    protected $kb_module_name = 'kbreviewincentives';
    protected $kb_velsof_method_name = 'kbreviewincentives';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->list_no_link = true;
        $this->className = '';
        $this->kb_smarty = new Smarty();
        $this->kb_smarty->registerPlugin('function', 'l', 'smartyTranslate');
        $this->kb_smarty->setTemplateDir(_PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/');
        $this->table = 'velsof_product_reviews';
        $this->identifier = 'review_id';
        $this->lang = false;
        $this->display = 'list';
        parent::__construct();
        /* Ajax */
        if (Tools::isSubmit('kb_report')) {
            $method = Tools::getValue('kbrc_report_option');
            $this->context->smarty->assign('method', $method);
        } else {
            if (Tools::isSubmit('submitFiltervelsof_product_reviews')) {
                $method = Tools::getValue('velsof_method_name');
                $this->context->smarty->assign('method', $method);
            } else {
                $method = '1';
                $this->context->smarty->assign('method', $method);
            }
        }
        $context = Context::getContext();
        if ($method != "") {
            $context->cookie->__set('velsof_method_name', $method);
        }

        if ($method == "") {
            $method = $this->context->cookie->velsof_method_name;
        }

        if ($method == '1') {
            $this->toolbar_title = $this->module->l('Product Report', 'AdminKbrcReports');
            $this->fields_list = array(
                'name' => array(
                    'title' => $this->module->l('Products', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_reviews' => array(
                    'title' => $this->module->l('Total Reviews', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_customer' => array(
                    'title' => $this->module->l('Total Customers', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_incentives' => array(
                    'title' => $this->module->l('Total Incentives', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'last_date' => array(
                    'title' => $this->module->l('Last Reviewed On', 'AdminKbrcReports'),
                    'align' => 'text-center',
                    'type' => 'datetime',
                    'filter_key' => 'a!date_add',
                    'search' => true,
                )
            );
            $this->_select = "pl.name as name, MAX(a.date_add) as last_date, count(a.product_id) as total_reviews, count(DISTINCT(a.customer_id)) as total_customer, sum(a.incentive_amount) as total_incentives";
            $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "product_lang` pl ON (pl.`id_product` = a.`product_id`) AND pl.id_lang = '" . (int) $this->context->language->id . "'";
            $this->_group = 'GROUP BY a.product_id';
        } elseif ($method == '2') {
            $this->toolbar_title = $this->module->l('Category Report', 'AdminKbrcReports');
            $this->fields_list = array(
                'name' => array(
                    'title' => $this->module->l('Category', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_reviews' => array(
                    'title' => $this->module->l('Total Reviews', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_customer' => array(
                    'title' => $this->module->l('Total Customers', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_incentives' => array(
                    'title' => $this->module->l('Total Incentives', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'last_date' => array(
                    'title' => $this->module->l('Last Reviewed On', 'AdminKbrcReports'),
                    'align' => 'text-center',
                    'type' => 'datetime',
                    'search' => true
                )
            );
            $this->_select = "cl.name as name, MAX(a.date_add) as last_date, count(a.category_id) as total_reviews, count(DISTINCT(a.customer_id)) as total_customer, sum(a.incentive_amount) as total_incentives";
            $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "category_lang` cl ON (cl.`id_category` = a.`category_id`) AND cl.id_lang = '" . (int) $this->context->language->id . "'";
            $this->_group = 'GROUP BY a.category_id';
        } elseif ($method == '3') {
            $this->toolbar_title = $this->module->l('Manufacturer Report', 'AdminKbrcReports');
            $this->fields_list = array(
                'name' => array(
                    'title' => $this->module->l('Manufacturers', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_reviews' => array(
                    'title' => $this->module->l('Total Reviews', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_customer' => array(
                    'title' => $this->module->l('Total Customers', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_incentives' => array(
                    'title' => $this->module->l('Total Incentives', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'last_date' => array(
                    'title' => $this->module->l('Last Reviewed On', 'AdminKbrcReports'),
                    'align' => 'text-center',
                    'type' => 'datetime',
                    'search' => true
                )
            );
            $this->_select = "m.name as name, MAX(a.date_add) as last_date, count(a.manufacturer_id) as total_reviews, count(DISTINCT(a.customer_id)) as total_customer, sum(a.incentive_amount) as total_incentives";
            $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "manufacturer` m ON (m.`id_manufacturer` = a.`manufacturer_id`)";
            $this->_group = 'GROUP BY a.manufacturer_id';
        } elseif ($method == '4') {
            $this->toolbar_title = $this->module->l('Customers Report', 'AdminKbrcReports');
            $this->fields_list = array(
                'first_name' => array(
                    'title' => $this->module->l('First Name', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'last_name' => array(
                    'title' => $this->module->l('Last Name', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'email' => array(
                    'title' => $this->module->l('Email-Id', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_products' => array(
                    'title' => $this->module->l('Total Products', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'total_incentives' => array(
                    'title' => $this->module->l('Total Incentives Earned', 'AdminKbrcReports'),
                    'havingFilter' => true,
                    'search' => true,
                    'type' => 'text',
                    'align' => 'text-center'
                ),
                'last_date' => array(
                    'title' => $this->module->l('Last Reviewed On', 'AdminKbrcReports'),
                    'align' => 'text-center',
                    'type' => 'datetime',
                    'search' => true
                )
            );
            $this->_select = "c.firstname as first_name,c.lastname as last_name,MAX(a.date_add) as last_date, c.email as email, count(DISTINCT(a.product_id)) as total_products, sum(a.incentive_amount) as total_incentives";
            $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "customer` c ON (c.`id_customer` = a.`customer_id`)";
            $this->_group = 'GROUP BY a.customer_id';
        }
    }

    /*
     * Function for returning the HTML of Helper Form
     */

    public function renderGenericForm($fields_form, $fields_value, $admin_token)
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->fields_value = $fields_value;
        $helper->name_controller = $this->module->name;
        $helper->languages = $languages;
        $helper->token = $admin_token;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->show_toolbar = true;
        $helper->table = 'velsof_show_report';
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'kb_report';
        return $helper->generateForm(array($fields_form));
    }

    /*
     * Function to fetch show report drop down
     */

    public function getReportHTML()
    {
        $report_options = array(
            array(
                'id_report' => 1,
                'name' => $this->module->l('Product', 'AdminKbrcReports')
            ),
            array(
                'id_report' => 2,
                'name' => $this->module->l('Category', 'AdminKbrcReports')
            ),
            array(
                'id_report' => 3,
                'name' => $this->module->l('Manufacturer', 'AdminKbrcReports')
            ),
            array(
                'id_report' => 4,
                'name' => $this->module->l('Customer', 'AdminKbrcReports')
            )
        );
        $this->fields_form = array('form' => array(
                'id_form' => 'kbrc_Report',
                'legend' => array(
                    'title' => $this->module->l('Reports', 'AdminKbrcReports'),
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->module->l('Select Report', 'AdminKbrcReports'),
                        'name' => 'kbrc_report_option',
                        'hint' => $this->module->l('Select data to filter results.', 'AdminKbrcReports'),
                        'options' => array(
                            'query' => $report_options,
                            'id' => 'id_report',
                            'name' => 'name',
                        ),
                    ),
//                    array(
//                        'type' => 'hidden',
//                        'name' => 'submitFiltervelsof_product_reviews',
//                    )
                ),
                'buttons' => array(
                    array(
                        'title' => $this->module->l('Show Reports', 'AdminKbrcReports'),
                        'type' => 'submit',
                        'icon' => 'process-icon-save',
                        'class' => 'btn btn-default pull-right kbrc_show_report',
                        'id' => 'submit_add',
                        'name' => 'kb_report',
                    ),
                ),
            ),
        );
        return $this->fields_form;
    }

    /*
     * Default function, used here to set required smarty variables
     */

    public function initContent()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        $this->fields_form = $this->getReportHTML();

        $method = $this->context->cookie->velsof_method_name;
        $fields_value = array('kbrc_report_option' => $method);
        $this->context->smarty->assign('method', $method);

        /** Change the Paging value to 1, if report type is changed */
        if (Tools::isSubmit('kb_report')) {
//            $fields_value['submitFiltervelsof_product_reviews'] = 1;
        } else {
//            $fields_value['submitFiltervelsof_product_reviews'] = '';
        }

        $show_report_form = $this->renderGenericForm(
            $this->fields_form,
            $fields_value,
            Tools::getAdminTokenLite('AdminKbrcReportsController')
        );

        $link_review = $this->context->link->getAdminLink('AdminKbrcReviews', true);
        $link_reminder = $this->context->link->getAdminLink('AdminKbrcReminderProfiles', true);
        $link_criteria = $this->context->link->getAdminLink('AdminKbrcCriteria', true);
        $link_audit_log = $this->context->link->getAdminLink('AdminKbrcAuditLog', true);
        $link_report = $this->context->link->getAdminLink('AdminKbrcReports', true);
        $default_link = $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . urlencode($this->module->name) . '&tab_module=' . $this->module->tab . '&module_name=' . urlencode($this->module->name);
        $this->context->smarty->assign('admin_configure_controller', $default_link);
        $this->context->smarty->assign('audit_log_link', $link_audit_log);
        $this->context->smarty->assign('reminder_profile_link', $link_reminder);
        $this->context->smarty->assign('exclude_condition_link', $link_criteria);
        $this->context->smarty->assign('product_review_link', $link_review);
        $this->context->smarty->assign('review_report_link', $link_report);
        $this->context->smarty->assign('selected_nav', 'Reports');
        $tabs = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/top_tabs_kbreviewincentive.tpl'
        );
        $this->context->smarty->assign('form', '');
        $this->context->smarty->assign('form1', '');
        $this->context->smarty->assign('lang_id', $this->context->language->id);
        $this->context->smarty->assign('controller_path', '');
        $this->context->smarty->assign('firstCall', false);
        $kb_velovalidation_variables = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/kb_velovalidation.tpl'
        );
        $this->content .= $tabs;
        $this->content .= $kb_velovalidation_variables;
        $this->content .= $show_report_form;


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
     * Function to add back and audit log link on top toolbar
     */

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['back_url'] = array(
            'href' => 'javascript: window.history.back();',
            'desc' => $this->module->l('Back', 'AdminKbrcCriteria'),
            'icon' => 'process-icon-back'
        );
        $this->page_header_toolbar_btn['new_template'] = array(
            'href' => $this->context->link->getAdminLink('AdminKbrcAuditLog', true),
            'desc' => $this->module->l('Audit Log'),
            'icon' => 'process-icon-stats'
        );
        parent::initPageHeaderToolbar();
    }

    /*
     * Function to remove add new btn from toolbar
     */

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    /*
     * Function for returning the absolute path of the module directory
     */

    protected function getKbModuleDir()
    {
        return _PS_MODULE_DIR_ . $this->kb_module_name . '/';
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
    }
}
