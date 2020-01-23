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
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

class KbreviewincentivesKbwritenewreviewModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        $this->display_column_left = false;
        parent::__construct();
    }

    /*
     * Function to submit data on submitting a new review
     */
    public function submitData()
    {
        $review_data = array();
        $this->json = array();
        $review_title = Tools::getValue('review_title');
        $review_description = Tools::getValue('review_description');
        $review_rating = Tools::getValue('ratings');
        $review_name = Tools::getValue('review_name');
        $review_email = Tools::getValue('review_email');
        $product_id = Tools::getValue('review_product_id');
        if ($this->context->customer->isLogged() && $this->context->customer->id) {
            $customer_id = $this->context->customer->id;
        } else {
            $customer_id = 0;
        }
        $review_data['email'] = $review_email;
        $review_data['author'] = $review_name;
        $review_data['customer_id'] = $customer_id;
        $review_data['product_id'] = $product_id;
        $link = $this->context->link->getProductLink($product_id);
        $pro_obj = new Product($product_id);
        $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        if ($module_settings['incentive_enable'] == 1 && $this->module->checkIncentiveCriteria($module_settings['incentive_criteria'], $review_data)) {
            $cat_result = $this->module->checkCategory($pro_obj->id_category_default);
            if ($cat_result == false) {         //This product category not blacklisted
                //Check if product is black listed or not
                $pro_result = $this->module->checkProduct($product_id);
                if ($pro_result == false) {
                    $incentive_amount = $module_settings['incentive_amount'];
                } else {
                    $incentive_amount = 0;
                }
            } else {
                $incentive_amount = 0;
            }
            $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_incentive_coupon vic INNER JOIN " . _DB_PREFIX_ . "velsof_product_reviews vpr ON vic.review_id = vpr.review_id  WHERE vpr.product_id = '" . (int) $review_data['product_id'] . "' AND vpr.customer_id = '" . (int) $review_data['customer_id'] . "'";
            $coupon_data = Db::getInstance()->getRow($sql);
            if (!empty($coupon_data)) {
                $incentive_amount = 0;
            }
        } else {
            $incentive_amount = 0;
        }
        if ($module_settings['moderation'] == 1) {
            $current_status = '1';
        } else {
            $current_status = '3';
        }

        $enable_incentive = $module_settings['incentive_enable'];

        $query = "INSERT INTO " . _DB_PREFIX_ . "velsof_product_reviews VALUES('','" . (int) $this->context->language->id . "','" . (int) $this->context->shop->id . "','" . (int) $this->context->currency->id . "','" . pSQL($review_title) . "','" . pSQL($review_name) . "','" . pSQL($review_description) . "',"
                . "'" . (int) $product_id . "','" . (int) $pro_obj->id_category_default . "','" . (int) $pro_obj->id_manufacturer . "', '" . (int) $customer_id . "', '" . pSQL($review_email) . "', '" . (int) $current_status . "', '" . (int) $incentive_amount . "', '" . (int) $review_rating . "',"
                . "'0', '" . (int) $enable_incentive . "', '0', '0', now(), now())";

        $res = Db::getInstance()->execute($query);
        $review_id = Db::getInstance()->Insert_ID();
        if ($res) {
            if ($module_settings['moderation'] == 1) {
                $this->module->addLogEntry('Success', 'New review posted.', 'A new review has been posted', 'KbreviewincentivesKbwritenewreviewModuleFrontController::submitData()', '');
                $success_message = $this->module->l('Your review has been posted.You can check your review on following link.', 'kbwritenewreview');
                $this->json['msg'] = $success_message;
                $this->json['res'] = true;
                $this->json['link'] = $link . '?st=1';
                if ($this->sendMailToAdmin($review_description, $review_data, $pro_obj)) {
                    $this->module->addLogEntry('Success', 'An email has been sent to admin to inform about a new posted review.', 'An email has been sent to admin to inform about a new posted review.', 'sendNotificationEmail()', '');
                    $this->json['mail_status'] = true;
                } else {
                    $this->json['mail_status'] = false;
                }
                if ($module_settings['incentive_enable'] == 1) {
                    $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE review_id = '" . (int) $review_id . "'";
                    $review_data = Db::getInstance()->getRow($sql);
                    $cat_result = $this->module->checkCategory($review_data['category_id']);
                    if ($cat_result == false) {         //This product category not blacklisted
                        //Check if product is black listed or not
                        $pro_result = $this->module->checkProduct($review_data['product_id']);
                        if ($pro_result == false) {
                            $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_incentive_coupon vic INNER JOIN " . _DB_PREFIX_ . "velsof_product_reviews vpr ON vic.review_id = vpr.review_id  WHERE vpr.product_id = '" . (int) $review_data['product_id'] . "'";
                            $coupon_data = Db::getInstance()->getRow($sql);
                            if (empty($coupon_data)) {
                                $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
                                if ($this->module->checkIncentiveCriteria($module_settings['incentive_criteria'], $review_data)) {
                                    $coupon_details = array();
                                    $coupon_details = $this->module->createCoupon($review_data);  //Function to get coupon
                                    if ($this->module->sendNotificationEmail('with_coupon_temp', $review_data, (int) $this->context->language->id, null, $pro_obj, $coupon_details)) {
                                        $this->module->addLogEntry('Success', 'A coupon email has been sent.', 'Incentive has been sent to customer having customer id' . $customer_id['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
                                        $this->context->cookie->__set(
                                            'kb_redirect_success',
                                            $this->module->l('Email has been sent to customer successfully.', 'AdminKbrcReviews')
                                        );
                                    } else {
                                        $this->module->addLogEntry('Error', 'A coupon email could not send.', 'Incentive could not be send to customer having customer id' . $customer_id['customer_id'], 'AdminKbrcReviewsController::processUpdate()', '');
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
            } else {
                $this->module->addLogEntry('Success', 'A new review has been sent for approval.', 'A new review has been sent for approval.', 'KbreviewincentivesKbwritenewreviewModuleFrontController::submitData()', '');
                $success_message = $this->module->l('Review has been sent for review to admin. Review will be visible on the site once approved. you will be notified when the review will be approved OR rejected.', 'kbwritenewreview');
                $this->json['msg'] = $success_message;
                $this->json['res'] = true;
                $this->json['link'] = '';
                if ($this->sendMailToAdmin($review_description, $review_data, $pro_obj)) {
                    $this->module->addLogEntry('Success', 'An email has been sent to admin to inform about a new posted review.', 'An email has been sent to admin to inform about a new posted review.', 'sendNotificationEmail()', '');
                    $this->json['mail_status'] = true;
                } else {
                    $this->json['mail_status'] = false;
                }
            }
        } else {
            $error_message = $this->module->l('Unable to save your review.Please try again.');
            $this->json['msg'] = $error_message;
            $this->json['res'] = false;
        }
        header('Content-Type: application/json', true);
        echo Tools::jsonEncode($this->json);
        /* Changes started by rishabh jain on 5th sep 2018
         * Changes done for adding compatibility with marketplace module
         */
        $mp_review_data = $review_data;
        $mp_review_data['review_id'] = $review_id;
        if (Module::isInstalled('kbmarketplace')) {
            Hook::exec(
                'actionKbProductReviewAddAfter',
                array(
                    'object' => $mp_review_data
                )
            );
            /* Changes over */
        }
        die;
    }

    /*
     * Default Prestashop function for post processes
     */
    public function postProcess()
    {
        if (Tools::isSubmit('review_submit')) {
            $this->submitData();
        }
        if (Tools::isSubmit('ajax')) {
            $this->insertCheckVote();
        }
    }
    /*
     * Function to insert vote details in DB called through AJAX
     */
    public function insertCheckVote()
    {
        $res1 = '';
        $res2 = '';
        $this->json = array();
        $vote = Tools::getValue('vote');
        $review_id = Tools::getValue('review_id');
        $customer_id = Tools::getValue('customer_id');
        $sql = "SELECT email FROM " . _DB_PREFIX_ . "customer WHERE id_customer = '" . (int) $customer_id . "'";
        $email = Db::getInstance()->getRow($sql);
        $sql = "SELECT * FROM " . _DB_PREFIX_ . "kbrc_vote_data WHERE review_id = '" . (int) $review_id . "' AND email = '" . pSQL($email['email']) . "'";
        $vote_data = Db::getInstance()->getRow($sql);
        if (empty($vote_data)) {
            $sql = "SELECT helpful_votes, not_helpful_votes FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE review_id = '" . (int) $review_id . "'";
            $helpful_vote = Db::getInstance()->getRow($sql);
            if ($vote == 1) {
                $vote_type = 'Yes';
                $count_yes = (int) $helpful_vote['helpful_votes'] + 1;
                $count_no = $helpful_vote['not_helpful_votes'];
            } elseif ($vote == 0) {
                $vote_type = 'No';
                $count_yes = $helpful_vote['helpful_votes'];
                $count_no = (int) $helpful_vote['not_helpful_votes'] + 1;
            }
            $sql = "UPDATE " . _DB_PREFIX_ . "velsof_product_reviews SET helpful_votes = '" . (int) $count_yes . "', not_helpful_votes = '" . (int) $count_no . "' WHERE review_id = '" . (int) $review_id . "'";
            $res1 = Db::getInstance()->execute($sql);
            $sql = "INSERT INTO " . _DB_PREFIX_ . "kbrc_vote_data SET review_id = '" . (int) $review_id . "',email = '" . pSQL($email['email']) . "', vote_type = '" . pSQL($vote_type) . "', "
                    . " date_added = now(), date_updated = now()";
            $res2 = Db::getInstance()->execute($sql);
            $sql = "SELECT helpful_votes, not_helpful_votes FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE review_id = '" . (int) $review_id . "'";
            $vote_data_ajax = Db::getInstance()->getRow($sql);
            if ($res1 && $res2) {
                $this->json['msg'] = $this->module->l('Thanks for vote.', 'kbwritenewreview');
                $this->json['success'] = true;
                $this->json['review_id'] = $review_id;
                $this->json['yes'] = $vote_data_ajax['helpful_votes'];
                $this->json['sum'] = $vote_data_ajax['not_helpful_votes'] + $vote_data_ajax['helpful_votes'];
            } else {
                $this->json['msg'] = $this->module->l('Your vote could not be added.', 'kbwritenewreview');
                $this->json['success'] = '3';
                $this->json['review_id'] = $review_id;
            }
        } else {
            $this->json['msg'] = $this->module->l('You have voted already.', 'kbwritenewreview');
            $this->json['success'] = false;
            $this->json['review_id'] = $review_id;
        }
        header('Content-Type: application/json', true);
        echo Tools::jsonEncode($this->json);
        die;
    }

    /*
     * Function to send email to admin
     */
    public function sendMailToAdmin($review_description, $review_data, $pro_obj)
    {
        return $this->module->sendNotificationEmail('new_review_post', $review_data, (int) $this->context->language->id, $review_description, $pro_obj);
    }
    
    /*
     * Default PrestaShop function
     */
    public function initContent()
    {
        parent::initContent();
        $config = unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        if (!empty($config) && $config['enable']) {
            if (Tools::getValue('product_id')) {
                $id = Tools::getValue('product_id');
                $link = $this->context->link->getProductLink($id);
                $this->context->smarty->assign('product_link', $link);
                $this->context->smarty->assign('product_id', $id);
                $this->context->smarty->assign('front_cont_link', $this->context->link->getModuleLink('kbreviewincentives', 'kbwritenewreview'));
                $this->context->smarty->assign('module_path', $this->getModuleDirUrl() . $this->module->name . '/views/img/front/');
                if ($this->context->customer->isLogged() && $this->context->customer->id) {
                    $customer_id = $this->context->customer->id;
                    $email = $this->context->customer->email;
                } else {
                    $customer_id = 0;
                    $email = '';
                }
                $pro_obj = new Product((int) $id);
                $id_image = Product::getCover($id);
                $image = new Image($id_image['id_image']);
                $id_lang = Context::getContext()->language->id;
                $img_path = $this->getImgDirUrl() . _THEME_PROD_DIR_ . $image->getExistingImgPath() . '.jpg';
                $this->context->smarty->assign('product_image', $img_path);
                $this->context->smarty->assign('customer_id', (int) $customer_id);
                $this->context->smarty->assign('customer_email', $email);
                $this->context->smarty->assign('product_name', $pro_obj->name[$this->context->language->id]);
                $this->context->smarty->assign(
                    array(
                        'GDPR_compatibility_status' => $config['GDPR_compatibility_status'],
                        'enable_gdpr_policy' => $config['enable_gdpr_policy'],
                        'gdpr_policy_text' => isset($config['gdpr_policy_text'][$id_lang]) ? $config['gdpr_policy_text'][$id_lang] : '',
                        'gdpr_policy_url' => isset($config['gdpr_policy_url'][$id_lang]) ? $config['gdpr_policy_url'][$id_lang] : '',
                    )
                );
                $this->setTemplate('module:kbreviewincentives/views/templates/front/kb_write_new_review.tpl');
            }
        } else {
            Tools::redirect($this->context->link->getPageLink('index'));
        }
    }

    /*
     * Function to get module directory
     */
    private function getImgDirUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_;
        } else {
            $module_dir = _PS_BASE_URL_;
        }
        return $module_dir;
    }

    /*
     * Default function, used here to include JS/CSS files for the module.
     */
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryPlugin('fancybox');
        $this->addCSS($this->getModuleDirUrl() . $this->module->name . '/views/css/front/kbrc_front.css');
        $this->addJS($this->getModuleDirUrl() . $this->module->name . '/views/js/front/kbrc_front.js');
        $this->addJS($this->getModuleDirUrl() . $this->module->name . '/views/js/velovalidation.js');
        $this->addCSS(_PS_MODULE_DIR_ . $this->module->name . '/views/css/front/font-awesome.css');
        $this->addCSS(_PS_MODULE_DIR_ . $this->module->name . '/views/css/front/font-awesome.min.css');
//        $this->addCSS(_PS_MODULE_DIR_.$this->module->name.'/views/css/front/kbrc_front.css');
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
