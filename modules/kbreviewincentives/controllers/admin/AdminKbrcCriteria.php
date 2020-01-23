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

include_once(_PS_MODULE_DIR_.'kbreviewincentives/classes/admin/criteria.php');
class AdminKbrcCriteriaController extends ModuleAdminController
{
    protected $kb_module_name = 'kbreviewincentives';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->allow_export = true;
        $this->context = Context::getContext();
        $this->list_no_link = true;
        $this->className = 'Criteria';
        $this->kb_smarty = new Smarty();
        $this->kb_smarty->registerPlugin('function', 'l', 'smartyTranslate');
        $this->kb_smarty->setTemplateDir(_PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/');
        $this->table = 'velsof_products_review_incentive';
        $this->identifier = 'kbrc_product_id';
        $this->lang = false;
        $this->display = 'list';
        parent::__construct();

        $this->toolbar_title = $this->module->l('Product Criteria', 'AdminKbrcCriteria');

        if (Tools::getValue('kbrc_product_id')) {
            $this->toolbar_title = $this->module->l('Edit Product', 'AdminKbrcReminderProfiles');
        } elseif (Tools::isSubmit('add' . $this->table)) {
            $this->toolbar_title = $this->module->l('Add Product', 'AdminKbrcReminderProfiles');
        } else {
            $this->toolbar_title = $this->module->l('Product Criteria', 'AdminKbrcReminderProfiles');
        }

        $this->fields_list = array(
            'product_id' => array(
                'title' => $this->module->l('ID', 'AdminKbrcCriteria'),
                'search' => false,
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->module->l('Product Name', 'AdminKbrcCriteria'),
                'havingFilter' => true,
                'search' => true,
                'type' => 'text',
                'align' => 'text-center'
            ),
        );
        $this->bulk_actions = array(
            $this->module->l('delete', 'AdminKbrcCriteria') => array(
                'text' => $this->module->l('Delete selected', 'AdminKbrcCriteria'),
                'confirm' => $this->module->l('Delete selected testimonial(s)?', 'AdminKbrcCriteria'),
                'icon' => 'icon-trash'
            )
        );
        $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "product_lang` pl ON (pl.`id_product` = a.`product_id`) AND pl.id_lang = '" . (int) $this->context->language->id . "'";
        $this->_select = 'pl.*';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
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
     * Default admin controller function for rendering form
     */
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $products = array();
        $query = "SELECT pl.id_product, pl.name FROM " . _DB_PREFIX_ . "product p INNER JOIN " . _DB_PREFIX_ . "product_lang pl ON pl.id_product = p.id_product"
                . " AND pl.`id_lang` = '" . (int) $this->context->language->id . "' AND pl.id_shop = '" . (int) $this->context->shop->id . "'";
        $pro_data = Db::getInstance()->executeS($query);
        foreach ($pro_data as $product_data) {
            $products[] = array(
                'id_option' => $product_data['id_product'],
                'name' => $product_data['name']
            );
        }
        $this->fields_form = array(
            'id_form' => 'kbrc_criteria_settings',
            'legend' => array(
                'title' => $this->module->l('Add Products', 'AdminKbrcCriteria'),
                'icon' => 'icon-envelope'
            ),
            'input' => array(
                array(
                    'label' => $this->module->l('Enter the products name', 'AdminKbrcCriteria'),
                    'type' => 'text',
                    'hint' => $this->module->l('Select the products on which you want to disable reminders and incentives.', 'AdminKbrcCriteria'),
                    'class' => 'ac_input',
                    'name' => 'kbrc_product',
                    'autocomplete' => false,
                    'required' => true
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'product_id',
                ),
//                array(
//                    'type' => 'select',
//                    'label' => $this->module->l('Products', 'AdminKbrcCriteria'),
//                    'multiple' => true,
//                    'name' => 'kb_product[]',
//                    'hint' => $this->module->l('Select the products on which you want to disable reminders and incentives.', 'AdminKbrcCriteria'),
//                    'desc' => $this->module->l('Hold CTRL to select multiple', 'AdminKbrcCriteria'),
//                    'is_bool' => true,
//                    'options' => array(
//                        'query' => $products,
//                        'id' => 'id_option',
//                        'name' => 'name',
//                    ),
//                    'size' => 14
//                ),
            ),
            'buttons' => array(
                array(
                    'title' => $this->module->l('Save', 'AdminKbrcCriteria'),
                    'type' => 'submit',
                    'icon' => 'process-icon-save',
                    'class' => 'pull-right velsof_banned_products_incentives',
                    'id' => 'submit_add',
                    'name' => 'submitAdd' . $this->table,
                ),
            )
        );
        $this->fields_value = array(
            'kbrc_product' => $obj->product_name,
            'product_id' => $obj->product_id,
        );
        return parent::renderForm();
    }
    /*
     * Default Function (Used here to handle updating a new Product to remove incentive and reminder features on it)
     * Default admin controller function to add
     */
    public function processUpdate()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            if (Tools::isSubmit('submitAdd' . $this->table)) {
                $this->obj = $this->loadObject(Tools::getValue('kbrc_product_id'));
                $this->obj->product_id = Tools::getValue('product_id');
                $this->obj->product_name = Tools::getValue('kbrc_product');
                $this->obj->date_updated = date('Y-m-d H:i:s');
                $res = $this->obj->save();
                if ($res) {
                    $this->context->cookie->__set(
                        'kb_redirect_success',
                        $this->module->l('Product name has been updated successfully.', 'AdminKbrcReminderProfiles')
                    );
                } else {
                    $this->context->cookie->__set(
                        'kb_redirect_error',
                        $this->module->l('Unable to update product. Technical Error', 'AdminKbrcReminderProfiles')
                    );
                }
            }
        }
    }
    /*
     * Default Function (Used here to handle adding a new Product to remove incentive and reminder features on it)
     * Default admin controller function to add
     */
    public function processAdd()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $this->obj = new Criteria();
            $this->obj->product_id = Tools::getValue('product_id');
            $this->obj->product_name = Tools::getValue('kbrc_product');
            $this->obj->date_updated = date('Y-m-d H:i:s');
            $res = $this->obj->save();
            if ($res) {
                $this->context->cookie->__set(
                    'kb_redirect_success',
                    $this->module->l('Product name has been added successfully.', 'AdminKbrcReminderProfiles')
                );
            } else {
                $this->context->cookie->__set(
                    'kb_redirect_error',
                    $this->module->l('Unable to add product. Technical Error', 'AdminKbrcReminderProfiles')
                );
            }
        }
    }
    /*
     * Default function, used here to set required smarty variables
     */
    public function initContent()
    {
        if (Tools::getvalue('ajaxproductaction')) {
            echo $this->ajaxproductlist();
            die;
        }
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        $link_reminder = $this->context->link->getAdminLink('AdminKbrcReminderProfiles', true);
        $link_criteria = $this->context->link->getAdminLink('AdminKbrcCriteria', true);
        $link_review = $this->context->link->getAdminLink('AdminKbrcReviews', true);
        $link_audit_log = $this->context->link->getAdminLink('AdminKbrcAuditLog', true);
        $link_report = $this->context->link->getAdminLink('AdminKbrcReports', true);
        $default_link = $this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($this->module->name).'&tab_module='.$this->module->tab.'&module_name='.urlencode($this->module->name);
        $this->context->smarty->assign(
            'admin_configure_controller',
            $default_link
        );
        
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
        
        $this->context->smarty->assign('selected_nav', 'exclude');
        $this->context->smarty->assign(
            'audit_log_link',
            $link_audit_log
        );
        $this->context->smarty->assign('form', '');
        $this->context->smarty->assign('controller_path', $link_criteria.'&ajaxproductaction=true');
        $this->context->smarty->assign('lang_id', $this->context->language->id);
        $this->context->smarty->assign('firstCall', false);
        $this->context->smarty->assign('method', '');
        $this->available_tabs_lang = array(
            'ProductCondition' => $this->l('Product Condition'),
            'CategoryCondition' => $this->l('Category Condition'),
        );

        
        $this->available_tabs = array(
            array('ProductCondition', 'icon-gears'),
            array('CategoryCondition', 'icon-gears')
        );

        $this->tab_display = 'ProductCondition';
        $module_tabs = array();

        foreach ($this->available_tabs as $tab) {
            $module_tabs[$tab[0]] = array(
                'id' => $tab[0],
                'selected' => (Tools::strtolower($tab[0]) == Tools::strtolower($this->tab_display) ||
                (isset($this->tab_display_module) && 'module' . $this->tab_display_module == Tools::strtolower($tab[0]))),
                'name' => $this->available_tabs_lang[$tab[0]],
                'href' => '',
                'icon' => $tab[1],
            );
        }
        $this->fields_form1 = $this->getCategoryTabFields();
        $form1 = $this->getFormHtml($this->fields_form1, $languages, 'category_set');
        $this->context->smarty->assign('form', '');
        $this->context->smarty->assign('form1', $form1);
        $this->context->smarty->assign('lang_id', $this->context->language->id);
        $this->context->smarty->assign('module_tabs', $module_tabs);
        $this->context->smarty->assign('firstCall', false);
        $module_tabs_exclu = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/module_tabs_exclude.tpl'
        );
        $tabs = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/top_tabs_kbreviewincentive.tpl'
        );
        $kb_velovalidation_variables = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/kb_velovalidation.tpl'
        );
//        $information_block = $this->context->smarty->fetch(
//                        _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/kb_description.tpl'
//                    );
        $this->content .= $tabs;
        $this->content .= $module_tabs_exclu;
        $this->content .= $kb_velovalidation_variables;
//        $this->content .= $information_block;

        
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
     * Default PrestaShop function to perform post processes
     */
    public function postProcess()
    {
        if (Tools::isSubmit('velsof_category_set')) {
            if (Tools::getValue('kbrc_reviewincentive_category')) {
                $cat_id_pro = Tools::getValue('kbrc_reviewincentive_category');
                $query = "DELETE  FROM " . _DB_PREFIX_ . "velsof_categories_review_incentive";
                Db::getInstance()->execute($query);
                if (!empty($cat_id_pro)) {
                    for ($i = 0; $i <= count($cat_id_pro) - 1; $i++) {
                        $query = "INSERT INTO " . _DB_PREFIX_ . "velsof_categories_review_incentive VALUES('','" . (int) $cat_id_pro[$i] . "',now(),now())";
                        Db::getInstance()->execute($query);
                    }
                }
            } else {
                $query = "DELETE  FROM " . _DB_PREFIX_ . "velsof_categories_review_incentive";
                Db::getInstance()->execute($query);
            }
        }
        parent::PostProcess();
    }
    /*
     * Fetching fields in categories tab
     */
    public function getCategoryTabFields()
    {
        $selected_store_cat = array();
        $query = "SELECT * FROM " . _DB_PREFIX_ . "velsof_categories_review_incentive";
        $res = Db::getInstance()->executeS($query);
        if (!empty($res)) {
            foreach ($res as $category) {
                $selected_store_cat[] = $category['category_id'];
            }
        } else {
            $selected_store_cat = array();
        }
        //Get Store root category
        $root = Category::getRootCategory();
        //Generating the tree for the first column
        $tree = new HelperTreeCategories('prestashop_category');
        $tree->setUseCheckBox(true)
                ->setAttribute('is_category_filter', $root->id)
                ->setRootCategory($root->id)
                ->setSelectedCategories($selected_store_cat)
                ->setInputName('kbrc_reviewincentive_category');

        $categoryTreePresta = $tree->render();
        $form_fields = array(
            'form' => array(
                'id_form' => 'category_set',
                'legend' => array(
                    'title' => $this->module->l('Categories Criteria', 'AdminKbrcCriteria'),
                    'icon' => 'icon-check'
                ),
                'input' => array(
                    array(
                        'type' => 'categories_select',
                        'label' => $this->l('Categories'),
                        'hint' => $this->module->l('Select category of products on which reminder and incentives will not be allowed.', 'AdminKbrcCriteria'),
                        'name' => 'kbrc_reviewincentive_category',
                        'required' => false,
                        'category_tree' => $categoryTreePresta,
                        'class' => 'optn_cat'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right category_set_save'
                ),
            ),
        );

        return $form_fields;
    }
    /*
     * Function for returning the HTML for a Helper Form
     */
    public function getFormHtml($field_form, $languages, $id)
    {
        $helper = new HelperForm();
        $helper->module = $this->module;
//        $helper->fields_value = $field_value;
        $helper->name_controller = $this->module->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
//        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->module->name;
        $helper->title = $this->module->displayName;
        $helper->table = '';
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = false;
        $helper->submit_action = 'velsof_category_set';
        return $helper->generateForm(array('form' => $field_form));
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
        $this->addCSS($this->getKbModuleDir().'views/css/admin/jquery.autocomplete.css');
    }
    /*
     * Function to fetch product list
     */
    public function ajaxproductlist()
    {
        $query = Tools::getValue('q', false);
        if (!$query or $query == '' or Tools::strlen($query) < 1) {
            die();
        }

        /*
         * In the SQL request the "q" param is used entirely to match result in database.
         * In this way if string:"(ref : #ref_pattern#)" is displayed on the return list, 
         * they are no return values just because string:"(ref : #ref_pattern#)" 
         * is not write in the name field of the product.
         * So the ref pattern will be cut for the search request.
         */
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }

// Excluding downloadable products from packs because download from pack is not supported
        $excludeVirtuals = (bool) Tools::getValue('excludeVirtuals', false);
        $exclude_packs = (bool) Tools::getValue('exclude_packs', false);

        $sql = 'SELECT p.`id_product`, `reference`, pl.name
		FROM `' . _DB_PREFIX_ . 'product` p
		LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = '
                . 'p.id_product AND pl.id_lang = '
                . '' . (int) Context::getContext()->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
		WHERE (pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\')' .
                (!empty($excludeIds) ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
                ($excludeVirtuals ? 'AND p.id_product NOT IN (SELECT pd.id_product FROM '
                        . '`' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
                ($exclude_packs ? 'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '');

        $items = Db::getInstance()->executeS($sql);

        if ($items) {
            foreach ($items as $item) {
                echo trim($item['name']) . (!empty($item['reference']) ?
                        ' (ref: ' . $item['reference'] . ')' : '') .
                '|' . (int) ($item['id_product']) . "\n";
            }
        }
    }
}
