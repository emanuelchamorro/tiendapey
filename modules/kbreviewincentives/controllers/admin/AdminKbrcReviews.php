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
*/

include_once(_PS_MODULE_DIR_.'kbreviewincentives/classes/admin/reviews.php');
class AdminKbrcReviewsController extends ModuleAdminController
{
    protected $kb_module_name = 'kbreviewincentives';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->list_no_link = true;
        $this->className = 'Reviews';
        $this->kb_smarty = new Smarty();
        /* changes by rishabh jain */
        $this->kb_smarty->setCompileDir(_PS_CACHE_DIR_ . 'smarty/compile');
        $this->kb_smarty->setCacheDir(_PS_CACHE_DIR_ . 'smarty/cache');
        $this->kb_smarty->use_sub_dirs = true;
        $this->kb_smarty->setConfigDir(_PS_SMARTY_DIR_ . 'configs');
        $this->kb_smarty->caching = false;
        
        $this->kb_smarty->registerPlugin('function', 'l', 'smartyTranslate');
        $this->kb_smarty->setTemplateDir(_PS_MODULE_DIR_ . $this->kb_module_name . '/views/templates/admin/');
        $this->table = 'velsof_product_reviews';
        $this->identifier = 'review_id';
        $this->lang = false;
        $this->display = 'list';

        parent::__construct();
        if (Tools::getValue('review_id')) {
            $this->toolbar_title = $this->module->l('Edit Review Information', 'AdminKbrcReviews');
        } elseif (Tools::isSubmit('addpayment_method_cod_fee')) {
            $this->toolbar_title = $this->module->l('Add Review Information', 'AdminKbrcReviews');
        } else {
            $this->toolbar_title = $this->module->l('Product Reviews', 'AdminKbrcReviews');
        }

        $this->fields_list = array(
            'review_id' => array(
                'title' => $this->module->l('ID', 'AdminKbrcReviews'),
                'search' => false,
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'email' => array(
                'title' => $this->module->l('Customer Email', 'AdminKbrcReviews'),
                'havingFilter' => true,
                'search' => true,
                'type' => 'text',
                'align' => 'text-center'
            ),
            'author' => array(
                'title' => $this->module->l('Author', 'AdminKbrcReviews'),
                'havingFilter' => true,
                'search' => true,
                'type' => 'text',
                'align' => 'text-center'
            ),
            'name' => array(
                'title' => $this->module->l('Product Name', 'AdminKbrcReviews'),
                'havingFilter' => true,
                'search' => true,
                'type' => 'text',
                'align' => 'text-center'
            ),
            'ratings' => array(
                'title' => $this->module->l('Ratings', 'AdminKbrcReviews'),
                'callback' => 'getRatingImage',
                'havingFilter' => true,
                'search' => true,
                'type' => 'select',
                'filter_key' => 'ratings',
                'list' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'),
                'align' => 'text-center'
            ),
            'current_status' => array(
                'title' => $this->module->l('Approve/Disapprove', 'AdminKbrcReviews'),
                'callback' => 'getCurrentStatus',
                'align' => 'text-center',
                'type' => 'select',
                'filter_key' => 'current_status',
                'list' => array('1' => $this->module->l('Approved', 'AdminKbrcReviews'), '0' => $this->module->l('Disapproved', 'AdminKbrcReviews'),
                    '3' => $this->module->l('Pending', 'AdminKbrcReviews')),
                'search' => true
            ),
            'date_add' => array(
                'title' => $this->module->l('Date Added', 'AdminKbrcReviews'),
                'align' => 'text-center',
                'type' => 'datetime',
                'search' => true
            )
        );
        $this->bulk_actions = array(
            $this->module->l('delete', 'AdminKbrcReviews') => array(
                'text' => $this->module->l('Delete selected', 'AdminKbrcReviews'),
                'confirm' => $this->module->l('Delete selected testimonial(s)?', 'AdminKbrcReviews'),
                'icon' => 'icon-trash'
            )
        );
        $this->_join = "INNER JOIN `" . _DB_PREFIX_ . "product_lang` pl ON (pl.`id_product` = a.`product_id`) AND pl.id_lang = '" . (int) $this->context->language->id . "'"
                . " AND pl.id_shop = '" . (int) $this->context->shop->id . "' AND a.id_shop = '" . (int) $this->context->shop->id . "'";
        $this->_select = 'pl.*';
        $this->_orderBy = 'a.date_add';
        $this->_orderWay = 'DESC';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }
    /*
     * Function to get current status
     */
    public function getCurrentStatus($row_data, $tr)
    {
        $tpl = $this->kb_smarty->createTemplate('current_status.tpl');
        if ($row_data == 1) {
            $current_status = '1';
        } else if ($row_data == 0) {
            $current_status = '0';
        } else if ($row_data == 3) {
            $current_status = '3';
        }
        $tpl->assign(array(
            'current_status' => $current_status,
        ));
        return $tpl->fetch();
    }
    /*
     * Function to get ratings image
     */
    public function getRatingImage($row_data, $tr)
    {
        $tpl = $this->kb_smarty->createTemplate('ratings_image.tpl');
        if ($row_data == 1) {
            $star = '1';
        } else if ($row_data == 2) {
            $star = '2';
        } else if ($row_data == 3) {
            $star = '3';
        } else if ($row_data == 4) {
            $star = '4';
        } else if ($row_data == 5) {
            $star = '5';
        }
        $tpl->assign(array(
            'image_path' => $this->getModuleDirUrl() . $this->module->name . '/views/img/front/stars-' . $star . '.png',
        ));
        return $tpl->fetch();
    }
    /*
     * Default prestashop function to update reviews
     */

    public function processUpdate()
    {
        if ('submitAdd' . $this->table) {
            $review_id = Tools::getValue('review_id');
            $current_status = Tools::getValue('current_status');
//            print_r($review_id);
//            print_R($current_status);
//            die;
            if ($current_status == 0) {
                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE review_id = '" . (int) $review_id . "' AND current_status!='0'";
                $review_data = Db::getInstance()->getRow($sql);
                if (!empty($review_data)) {
                    if ($this->module->sendNotificationEmail('review_dis', $review_data, (int) $this->context->language->id)) {
                        $this->module->addLogEntry('Success', 'Email has been sent for rejecting a review.', 'A review is disapproved of a customer having customer id ' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                        $this->context->cookie->__set(
                            'kb_redirect_success',
                            $this->module->l('Email has been sent to customer successfully.', 'AdminKbrcReviews')
                        );
                    } else {
                        $this->module->addLogEntry('Error', 'Email could not be send for rejecting a review.', 'A review is disapproved of a customer having customer id ' . $review_data['customer_id'] . ' But email could not be sent.', 'AdminKbrcReviewsController::processUpdate()', '');
                        $this->context->cookie->__set(
                            'kb_redirect_error',
                            $this->module->l('Email could not sent to customer.', 'AdminKbrcReviews')
                        );
                    }
                }
            } elseif ($current_status == 1) {
                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE review_id = '" . (int) $review_id . "' AND current_status!='1'";
                $review_data = Db::getInstance()->getRow($sql);
                if (!empty($review_data)) {
                    $pro_obj = new Product($review_data['product_id']);
                    if ($review_data['incentive_amount'] == 0) {
                        if ($this->module->sendNotificationEmail('without_coupon_temp', $review_data, (int) $this->context->language->id, null, $pro_obj)) {
                            $this->module->addLogEntry('Success', 'An email has been sent for review approval information.', 'An email is sent for approving a review of customer having customer id ' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                            $this->context->cookie->__set(
                                'kb_redirect_success',
                                $this->module->l('Email has been sent to customer successfully.', 'AdminKbrcReviews')
                            );
                        } else {
                            $this->module->addLogEntry('Error', 'An email could not be send for review approval information.', 'An email send get failed for approving a review of customer having customer id ' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                            $this->context->cookie->__set(
                                'kb_redirect_error',
                                $this->module->l('Email could not sent to customer.', 'AdminKbrcReviews')
                            );
                        }
                    } elseif ($review_data['incentive_amount'] != 0) {
                        $cat_result = $this->module->checkCategory($review_data['category_id']);
                        if ($cat_result == false) {         //This product category not blacklisted
                            //Check if product is black listed or not
                            $pro_result = $this->module->checkProduct($review_data['product_id']);
                            if ($pro_result == false) {
                                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_incentive_coupon vic INNER JOIN " . _DB_PREFIX_ . "velsof_product_reviews vpr ON vic.review_id = vpr.review_id  WHERE vpr.product_id = '" . (int) $review_data['product_id'] . "' AND vpr.customer_id = '" . (int) $review_data['customer_id'] . "'";
                                $coupon_data = Db::getInstance()->getRow($sql);
                                if (empty($coupon_data)) {
                                    $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
                                    if ($this->module->checkIncentiveCriteria($module_settings['incentive_criteria'], $review_data)) {
                                        $coupon_details = array();
                                        $coupon_details = $this->module->createCoupon($review_data);  //Function to get coupon
                                    }
                                    if ($this->module->sendNotificationEmail('with_coupon_temp', $review_data, (int) $this->context->language->id, null, $pro_obj, $coupon_details)) {
                                        $this->module->addLogEntry('Success', 'A coupon email has been sent.', 'Incentive has been sent to customer having customer id ' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                                        $this->context->cookie->__set(
                                            'kb_redirect_success',
                                            $this->module->l('Email has been sent to customer successfully.', 'AdminKbrcReviews')
                                        );
                                    } else {
                                        $this->module->addLogEntry('Error', 'A coupon email could not send.', 'Incentive could not be send to customer having customer id ' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                                        $this->context->cookie->__set(
                                            'kb_redirect_error',
                                            $this->module->l('Email could not sent to customer.', 'AdminKbrcReviews')
                                        );
                                    }
                                } else {
                                    if ($this->module->sendNotificationEmail('without_coupon_temp', $review_data, (int) $this->context->language->id, null, $pro_obj)) {
                                        $this->module->addLogEntry('Success', 'An email has been sent for review approval information.', 'An email is sent for approving a review of customer having customer id' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                                        $this->context->cookie->__set(
                                            'kb_redirect_success',
                                            $this->module->l('Email has been sent to customer successfully.', 'AdminKbrcReviews')
                                        );
                                    } else {
                                        $this->module->addLogEntry('Error', 'An email could not be send for review approval information.', 'An email send get failed for approving a review of customer having customer id' . $review_data['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                                        $this->context->cookie->__set(
                                            'kb_redirect_error',
                                            $this->module->l('Email could not sent to customer.', 'AdminKbrcReviews')
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        parent::processUpdate();
    }
    
    /* changes by rishabh jain on 6th sep 2018
     * to add compatibility with marketplace module
     */
    public function processDelete()
    {
        $review_id = Tools::getValue('review_id');
        /* Changes started by rishabh jain on 5th sep 2018
         * Changes done for adding compatibility with marketplace module
         */
        if (Module::isInstalled('kbmarketplace')) {
            $mp_config = Tools::unserialize(Configuration::get('KB_MARKETPLACE_CONFIG'));
            if (isset($mp_config['kbmp_enable_product_review_compatibility']) && $mp_config['kbmp_enable_product_review_compatibility'] == 1) {
                Hook::exec(
                    'actionKbProductReviewDeleteAfter',
                    array(
                        'review_id' => $review_id
                    )
                );
            }
        }
        parent::processDelete();
    }

    /*
     * Default admin controller function for rendering form
     */
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        $current_status = array(
            array(
                'id' => '1',
                'name' => $this->module->l('Approved', 'AdminKbrcReviews')
            ),
            array(
                'id' => '0',
                'name' => $this->module->l('Disapproved', 'AdminKbrcReviews')
            ),
            array(
                'id' => '3',
                'name' => $this->module->l('Pending', 'AdminKbrcReviews')
            ),
        );
        $rating = array(
            array(
                'id' => '1',
                'name' => '1'
            ),
            array(
                'id' => '2',
                'name' => '2'
            ),
            array(
                'id' => '3',
                'name' => '3'
            ),
            array(
                'id' => '4',
                'name' => '4'
            ),
            array(
                'id' => '5',
                'name' => '5'
            ),
        );
//        $this->context->smarty->assign('image_path', $this->getModuleDirUrl().$this->module->name.'/views/img/front/stars-'.$ratings.'.png');
        $this->fields_form = array(
            'id_form' => 'kbrc_reviews',
            'legend' => array(
                'title' => $this->module->l('Edit Product Reviews', 'AdminKbrcReviews'),
                'icon' => 'icon-envelope'
            ),
            'input' => array(
                array(
                    'label' => $this->module->l('Change Status', 'AdminKbrcReviews'),
                    'type' => 'select',
                    'class' => 'general_setting_tab',
                    'name' => 'current_status',
                    'hint' => $this->module->l('If approved then review will be post if disapproved then an email will be sent to customer that review has been disapproved.', 'AdminKbrcReviews'),
                    'is_bool' => true,
                    'options' => array(
                        'query' => $current_status,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'required' => false,
                ),
                array(
                    'label' => $this->module->l('Product', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'product_name',
                    'hint' => $this->module->l('Product name on which review is posted.', 'AdminKbrcReviews'),
                    'col' => 7
                ),
                array(
                    'label' => $this->module->l('Review Title', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'review_title',
                    'hint' => $this->module->l('Review Title of posted review.', 'AdminKbrcReviews'),
                    'col' => 7
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Review Description', 'AdminKbrcReviews'),
                    'hint' => $this->module->l('Content of posted review.', 'AdminKbrcReviews'),
                    'name' => 'description',
                    'required' => true,
                    'cols' => '2',
                    'rows' => '10',
                    'class' => 'col-lg-9',
                ),
                array(
                    'label' => $this->module->l('Ratings', 'AdminKbrcReviews'),
                    'type' => 'select',
                    'name' => 'ratings',
                    'hint' => $this->module->l('Ratings to product given by this author.', 'AdminKbrcReviews'),
                    'options' => array(
                        'query' => $rating,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'required' => false,
                ),
//                array(
//                    'type' => 'html',
//                    'html_content' => $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/ratings_image_edit.tpl'),
//                    'name' => '',
//                    'label' => $this->module->l('Ratings', 'AdminKbrcReviews'),
//                    'hint' => $this->module->l('Ratings to product given by this author.', 'AdminKbrcReviews'),
//                ),
                array(
                    'label' => $this->module->l('Total Helpful Votes', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'helpful_votes',
                    'hint' => $this->module->l('Total helpful votes on this review.', 'AdminKbrcReviews'),
                    'col' => 2,
                ),
                array(
                    'label' => $this->module->l('Total Not Helpful Votes', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'not_helpful_votes',
                    'hint' => $this->module->l('Total not helpful votes on this review.', 'AdminKbrcReviews'),
                    'col' => 2
                ),
//                array(
//                    'label' => $this->module->l('Enable Incentive', 'AdminKbrcReviews'),
//                    'type' => 'switch',
//                    'name' => 'enable_incentive',
//                    'values' => array(
//                        array(
//                            'value' => 1,
//                        ),
//                        array(
//                            'value' => 0,
//                        ),
//                    ),
//                    'hint' => $this->module->l('Enable/Disable this Incentive', 'AdminKbrcReviews'),
//                ),
                array(
                    'label' => $this->module->l('Incentive Amount', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'incentive_amount',
                    'hint' => $this->module->l('Incentive amount for this customer.', 'AdminKbrcReviews'),
                    'col' => 2
                ),
                array(
                    'label' => $this->module->l('Author', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'author',
                    'col' => 7
                ),
                array(
                    'label' => $this->module->l('Author Email', 'AdminKbrcReviews'),
                    'type' => 'text',
                    'required' => true,
                    'name' => 'email',
                    'id' => 'kb_review_author_email',
                    'col' => 7
                ),
                array(
                    'label' => $this->module->l('Certified Buyer', 'AdminKbrcReviews'),
                    'type' => 'switch',
                    'name' => 'certified_buyer',
                    'values' => array(
                        array(
                            'value' => 1,
                        ),
                        array(
                            'value' => 0,
                        ),
                    ),
                    'hint' => $this->module->l('Enable/Disable certified buyer badage on customer name.', 'AdminKbrcReviews'),
                ),
            ),
            'buttons' => array(
                array(
                    'title' => $this->module->l('Save', 'AdminKbrcReviews'),
                    'type' => 'submit',
                    'icon' => 'process-icon-save',
                    'class' => 'pull-right velsof_review_incentives',
                    'id' => 'submit_add',
                    'name' => 'submitAdd' . $this->table,
                ),
            )
        );
        $this->fields_value['ratings'] = $obj->ratings;
        if (Tools::getIsset('review_id')) {
            $review_id = Tools::getValue('review_id');
            $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_product_reviews a INNER JOIN `" . _DB_PREFIX_ . "product_lang` pl ON (pl.`id_product` = a.`product_id`) AND pl.id_lang = '" . (int) $this->context->language->id . "'"
                    . " AND pl.id_shop = '" . (int) $this->context->shop->id . "' AND a.review_id = '" . (int) $review_id . "'";
            $res = Db::getInstance()->getRow($sql);
            $this->fields_value['product_name'] = $res['name'];
        }
        return parent::renderForm();
    }
    /*
     * Default function, used here to set required smarty variables
     */
    public function initContent()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
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
            'audit_log_link',
            $link_audit_log
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
        $this->context->smarty->assign('method', '');
        $this->context->smarty->assign('selected_nav', 'reviews');
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
     * Function to remove add new btn from toolbar
     */
    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
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
     * Function to get module directory
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
     * Function to check SSL
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
}
