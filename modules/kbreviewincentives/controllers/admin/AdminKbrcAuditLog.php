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

class AdminKbrcAuditLogController extends ModuleAdminController
{
    protected $kb_module_name = 'kbreviewincentives';
    /*
     * Default function used here to define columns in the helper list of Audit Log helper list
     */
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'velsof_incentive_audit_log';

        parent::__construct();
        $this->fields_list = array(
            'id_audit_log' => array(
                'title' => $this->module->l('Log ID', 'AdminKbMailChimpAuditLogController'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'log_type' => array(
                'title' => $this->module->l('Action Type', 'AdminKbMailChimpAuditLogController')
            ),
            'log_action' => array(
                'title' => $this->module->l('Action', 'AdminKbMailChimpAuditLogController')
            ),
            'log_entry' => array(
                'title' => $this->module->l('Description', 'AdminKbMailChimpAuditLogController'),
                'float' => true
            ),
            'log_user' => array(
                'title' => $this->module->l('Action User', 'AdminKbMailChimpAuditLogController'),
                'align' => 'center'
            ),
            'log_class_method' => array(
                'title' => $this->module->l('Function Called', 'AdminKbMailChimpAuditLogController')
            ),
            'log_time' => array(
                'title' => $this->module->l('Time of Action', 'AdminKbMailChimpAuditLogController'),
                'type' => 'datetime'
            )
        );

        $this->_orderBy = 'id_audit_log';
        $this->_orderWay = 'DESC';

        $this->list_no_link = true;
    }

    /*
     * Default function used here to render the Audit Log helper list
     */
    public function renderList()
    {
        return parent::renderList();
    }

    /*
     * Default function used here to remove the 'Add New' button
     */
    public function initToolbar()
    {
        parent::initToolbar();

        unset($this->toolbar_btn['new']);
    }

    /*
     * Default function, used here to set required smarty variables
     */
    public function initContent()
    {
        $link_reminder = $this->context->link->getAdminLink('AdminKbrcReminderProfiles', true);
        $link_criteria = $this->context->link->getAdminLink('AdminKbrcCriteria', true);
        $link_review = $this->context->link->getAdminLink('AdminKbrcReviews', true);
        $link_report = $this->context->link->getAdminLink('AdminKbrcReports', true);
        $default_link = $this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($this->module->name).'&tab_module='.$this->module->tab.'&module_name='.urlencode($this->module->name);
        $this->context->smarty->assign(
            'admin_configure_controller',
            $default_link
        );
        $this->context->smarty->assign('method', '');
        $this->context->smarty->assign(
            'reminder_profile_link',
            $link_reminder
        );
        
        $this->context->smarty->assign(
            'exclude_condition_link',
            $link_criteria
        );
        
        $this->context->smarty->assign(
            'product_review_link',
            $link_review
        );
        
        $this->context->smarty->assign(
            'review_report_link',
            $link_report
        );
        $this->context->smarty->assign('form', '');
        $this->context->smarty->assign('form1', '');
        $this->context->smarty->assign('controller_path', '');
        $this->context->smarty->assign('lang_id', $this->context->language->id);
        $this->context->smarty->assign('selected_nav', '');
        $this->context->smarty->assign('audit_log_link', '');
        $tabs = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/top_tabs_kbreviewincentive.tpl'
        );
        $kb_velovalidation_variables = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/kb_velovalidation.tpl'
        );
        $this->content .= $tabs;
        $this->content .= $kb_velovalidation_variables;
        
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
        $this->addCSS($this->getKbModuleDir().'views/css/admin/kbrc_admin.css');
        $this->addJS($this->getKbModuleDir().'views/js/admin/kbrc_admin.js');
        $this->addJS($this->getKbModuleDir().'views/js/velovalidation.js');
        $this->addJs($this->getKbModuleDir() . 'views/js/admin/jquery.autocomplete.js');
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
//        $this->page_header_toolbar_btn['new_template'] = array(
//               'href' => $this->context->link->getAdminLink('AdminKbrcAuditLog', true),
//               'desc' => $this->module->l('Audit Log'),
//               'icon' => 'process-icon-anchor'
//           );
        parent::initPageHeaderToolbar();
    }
}
