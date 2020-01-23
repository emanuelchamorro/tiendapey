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
 * @copyright 2015 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Kbreviewincentives extends Module
{
    public function __construct()
    {
        $this->name = 'kbreviewincentives';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'Knowband';
        $this->need_instance = 0;
        $this->module_key = '28ba98f4793eec7c1486c0f1f9e70685';
        $this->author_address = '0x2C366b113bd378672D4Ee91B75dC727E857A54A6';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        parent::__construct(); /* Calling the parent constuctor method */
        $this->displayName = $this->l('Product Review Reminder And Incentives');
        $this->description = $this->l('This module allows product reviews and based on reviews admin can provide incentives to the customers.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES')) {
            $this->warning = $this->l('No name provided');
        }
    }
    
    /* Default Prestashop function for Installation*/
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        $this->installTabs();   //Install admin tabs
        if (!parent::install() ||
                !$this->registerHook('leftColumn') ||
                !$this->registerHook('header') ||
                !$this->registerHook('displayProductButtons') ||
                !$this->registerHook('displayFooterProduct') ||
                !$this->registerHook('actionValidateOrder') ||
                !$this->registerHook('actionDeleteGDPRCustomer') ||
                !$this->registerHook('actionExportGDPRData') ||
                !$this->registerHook('displayProductListReviews')
        ) {
            return false;
        }
        
        //Create language directory in mail folder according to available language
        $mail_dir = dirname(__FILE__) . '/mails/en';
        foreach (Language::getLanguages(false) as $lang) {
            if ($lang['iso_code'] != 'en') {
                $new_dir = dirname(__FILE__) . '/mails/' . $lang['iso_code'];
                $this->copyfolder($mail_dir, $new_dir);
            }
        }

        //Create table to save product and orders to check order status
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_order_status_check` (
                    `id_order_check` int(10) NOT NULL auto_increment,
                    `customer_id` int(10) NOT NULL,
                    `id_order` int(10) NOT NULL,
                    `product_id` text NOT NULL,
                    `order_status` text NOT NULL,
                    `reminder_profile_id` int(10) NOT NULL,
                    `date_added` datetime NOT NULL,
                    `date_updated` datetime NOT NULL,
                    PRIMARY KEY (`id_order_check`),
                    INDEX (  `id_order` )
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Create table to save schedules
        $query = "CREATE TABLE IF NOT EXISTS`" . _DB_PREFIX_ . "velsof_review_reminder_schedule` (
                    `reminder_id` int(10) NOT NULL auto_increment,
                    `customer_id` int(10) NOT NULL,
                    `product_id` text NOT NULL,
                    `order_id` int(10) NOT NULL,
                    `schedule_type` varchar(50) NOT NULL,
                    `schedule_date` datetime NOT NULL,
                    `email_subject` varchar(255) NOT NULL,
                    `text_content` text NOT NULL,
                    `body` text NOT NULL,
                    `id_lang` int(10) NOT NULL,
                    `id_shop` int(10) NOT NULL,
                    `is_send` enum('1','0') NOT NULL,
                    `date_added` datetime DEFAULT NULL,
                    `date_updated` datetime DEFAULT NULL,
                    PRIMARY KEY (`reminder_id`),
                    INDEX (  `schedule_date` )
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Create email templates table
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_review_incentive_emails` (
			`id_template` int(10) NOT NULL auto_increment,
			`id_lang` int(10) NOT NULL,
			`id_shop` INT(11) NOT NULL DEFAULT  "0",
			`iso_code` char(4) NOT NULL,
			`template_name` varchar(255) NOT NULL,
			`text_content` text NOT NULL,
			`subject` varchar(255) NOT NULL,
			`body` text NOT NULL,
			`date_added` DATETIME NULL,
			`date_updated` DATETIME NULL,
			PRIMARY KEY (`id_template`),
			INDEX (  `id_lang` ),
                        INDEX (  `template_name` )
			) CHARACTER SET utf8 COLLATE utf8_general_ci';
        Db::getInstance()->execute($query);

        //Create table for saving product,customer and their reviews
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_product_reviews` (
                    `review_id` int(10) NOT NULL auto_increment,
                    `id_lang` int(10) NOT NULL,
                    `id_shop` int(10) NOT NULL,
                    `id_currency` int(10) NOT NULL,
                    `review_title` varchar(255) NOT NULL,
                    `author` varchar(255) NOT NULL,
                    `description` text NOT NULL,
                    `product_id` int(10) NOT NULL,
                    `category_id` int(10) NOT NULL,
                    `manufacturer_id` int(10) NOT NULL,
                    `customer_id` int(10) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `current_status` enum('1','0','3') NOT NULL,
                    `incentive_amount` int(10) NOT NULL,
                    `ratings` int(5) NOT NULL,
                    `certified_buyer` enum('1','0') NOT NULL,
                    `enable_incentive` enum('1','0') NOT NULL,
                    `helpful_votes` int(10) NOT NULL,
                    `not_helpful_votes` int(10) NOT NULL,
                    `date_add` datetime NOT NULL,
                    `date_updated` datetime NOT NULL,
                     PRIMARY KEY (`review_id`),
                     INDEX (  `product_id` ),
                     INDEX (  `customer_id` ),
                     INDEX (  `ratings` )
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Create table for coupon mapping with customer
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_incentive_coupon` (
                    `coupon_id` int(10) NOT NULL auto_increment,
                    `code` varchar(50) NOT NULL,
                    `review_id` varchar(50) NOT NULL,
                    `customer_id` int(10) NOT NULL,
                    `date_add` datetime NOT NULL,
                    `date_update` datetime NOT NULL,
                    PRIMARY KEY (`coupon_id`),
                    INDEX (  `customer_id` ),
                    INDEX (  `review_id` )
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Creating table for storing product id on which review is not allowed
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_products_review_incentive` (
                     `kbrc_product_id` int(10) NOT null auto_increment,
                     `product_id` int(10) NOT null,
                     `product_name` varchar(255) NOT NULL,
                     `date_add` datetime DEFAULT null,
                     `date_updated` datetime DEFAULT null,
                     PRIMARY KEY (`kbrc_product_id`),
                     INDEX(`product_id`)
                     ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Creating table for storing category id on which review is not allowed
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_categories_review_incentive` (
                     `kbrc_category_id` int(10) NOT null auto_increment,
                     `category_id` int(10) NOT null,
                     `date_added` datetime DEFAULT null,
                     `date_updated` datetime DEFAULT null,
                     PRIMARY KEY (`kbrc_category_id`),
                     INDEX(`category_id`)
                     ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Creating table for storing reminder profile
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_reminder_profile` (
                    `reminder_profile_id` int(10) NOT NULL auto_increment,
                    `select_type` varchar(50) NOT NULL,
                    `no_of_days_after` int(10) NOT NULL,
                    `active` enum('1','0') NOT NULL,
                    `enable_order_create_reminder` enum('1','0') NOT NULL,
                    `date_add` datetime NOT NULL,
                    `date_updated` datetime NOT NULL,
                    PRIMARY KEY (`reminder_profile_id`),
                    INDEX(`select_type`)
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);

        //Create email templates table for reminder profile
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_reminder_profile_templates` (
			`id_template_profile` int(10) NOT NULL auto_increment,
                        `reminder_profile_id` int(10) NOT NULL,
			`id_lang` int(10) NOT NULL,
			`id_shop` INT(11) NOT NULL DEFAULT  "0",
			`iso_code` char(4) NOT NULL,
			`template_name` varchar(255) NOT NULL,
			`text_content` text NOT NULL,
			`subject` varchar(255) NOT NULL,
			`body` text NOT NULL,
			`date_added` DATETIME NULL,
			`date_updated` DATETIME NULL,
			PRIMARY KEY (`id_template_profile`),
			INDEX (  `id_lang` ),
                        INDEX (  `template_name` ),
                        INDEX (  `reminder_profile_id` )
			) CHARACTER SET utf8 COLLATE utf8_general_ci';
        Db::getInstance()->execute($query);

        //Create table for audit log
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "velsof_incentive_audit_log` (
                        `id_audit_log` int(11) NOT NULL AUTO_INCREMENT,
                        `log_entry` text NOT NULL,
                        `log_user` varchar(255) NOT NULL,
                        `log_class_method` varchar(255) NOT NULL,
                        `log_type` varchar(255) NOT NULL,
                        `log_action` varchar(255) NOT NULL,
                        `log_time` datetime NOT NULL,
                        PRIMARY KEY (`id_audit_log`)
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);
        //Create table for audit log
        $query = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "kbrc_vote_data` (
                        `vote_id` int(10) NOT NULL auto_increment,
                        `review_id` int(10) NOT NULL,
                        `email` varchar(255) NOT NULL,
                        `vote_type` enum('Yes','No') NOT NULL,
                        `date_added` datetime NOT NULL,
                        `date_updated` datetime NOT NULL,
                    PRIMARY KEY (`vote_id`),
                    INDEX(`review_id`),
                    INDEX(`email`)
                  ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=UTF8;";
        Db::getInstance()->execute($query);
        //Store default email data(language default)
        $sql = 'SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'velsof_review_incentive_emails';
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
        if (!($res['COUNT(*)'] > 0)) {
            $reminder_temp = $this->getDefaultReminderEmail();
            $this->insertEmailDefaultData($reminder_temp);
            $new_review_post = $this->getDefaultNewReviewEmail();
            $this->insertEmailDefaultData($new_review_post);
            $review_dis = $this->getDefaultReviewDisEmail();
            $this->insertEmailDefaultData($review_dis);
            $without_coupon_temp = $this->getDefaultWithoutCouponEmail();
            $this->insertEmailDefaultData($without_coupon_temp);
            $with_coupon_temp = $this->getDefaultWithCouponEmail();
            $this->insertEmailDefaultData($with_coupon_temp);
        }
        Configuration::updateValue('KBRC_SECURE_KEY', '371ba1fff6ddfb23ca63ad2043abcdc1');
        return true;
    }
    /*
     * Function to add template in database while installing the module
     */
    protected function getDefaultReminderEmail()
    {
        $template_html = array();
        $template_html['name'] = 'reminder_temp';
        $template_html['body'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/email_templates/reminder_temp.tpl');
        $template_html['subject'] = $this->l('Review these products and get amazing incentives.');
        $template_html['text_content'] = strip_tags($template_html['body']);
        return $template_html;
    }
    /*
     * Function to add template in database while installing the module
     */
    protected function getDefaultNewReviewEmail()
    {
        $template_html = array();
        $template_html['name'] = 'new_review_post';
        $template_html['body'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/email_templates/new_review_post.tpl');
        $template_html['subject'] = $this->l('New review is posted on your Shop.');
        $template_html['text_content'] = strip_tags($template_html['body']);
        return $template_html;
    }
    /*
     * Function to add template in database while installing the module
     */
    protected function getDefaultReviewDisEmail()
    {
        $template_html = array();
        $template_html['name'] = 'review_dis';
        $template_html['body'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/email_templates/review_dis.tpl');
        $template_html['subject'] = $this->l('Your review has been disapproved.');
        $template_html['text_content'] = strip_tags($template_html['body']);
        return $template_html;
    }
    /*
     * Function to add template in database while installing the module
     */
    protected function getDefaultWithoutCouponEmail()
    {
        $template_html = array();
        $template_html['name'] = 'without_coupon_temp';
        $template_html['body'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/email_templates/without_coupon_temp.tpl');
        $template_html['subject'] = $this->l('Congratulations! Your Review has been approved.');
        $template_html['text_content'] = strip_tags($template_html['body']);
        return $template_html;
    }
    /*
     * Function to add template in database while installing the module
     */
    protected function getDefaultWithCouponEmail()
    {
        $template_html = array();
        $template_html['name'] = 'with_coupon_temp';
        $template_html['body'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/email_templates/with_coupon_temp.tpl');
        $template_html['subject'] = $this->l('Congratulation! You got incentives for reviewing products on our shop.');
        $template_html['text_content'] = strip_tags($template_html['body']);
        return $template_html;
    }
    
    
    
    /*Default Prestashop Function for Uninstallation*/
    public function uninstall()
    {
        //Uninstall tabs
        $this->uninstallTabs();
        //Unregister hooks
        if (!parent::uninstall() ||
                !$this->unregisterHook('leftColumn') ||
                !$this->unregisterHook('header') ||
                !$this->unregisterHook('displayProductButtons') ||
                !$this->unregisterHook('displayFooterProduct') ||
                !$this->unregisterHook('actionValidateOrder') ||
                !$this->unregisterHook('displayProductListReviews') ||
                !$this->unregisterHook('actionExportGDPRData') ||
                !$this->unregisterHook('actionDeleteGDPRCustomer') ||
                !Configuration::deleteByName('KBRC_PRODUCT_REVIEW_INCENTIVES') ||
                !Configuration::deleteByName('KBRC_SECURE_KEY')
        ) {
            return false;
        }
        return true;
    }
    
    /*
     * Function to export the customer data from this module for the GDPR compliant plugin.
     */
    public function hookActionExportGDPRData($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            if (Module::isInstalled($this->name)) {
                $config = Tools::unSerialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
                if ($config['enable']) {
                    $existing_customer = Db::getInstance()->executeS('SELECT * from ' . _DB_PREFIX_ . 'velsof_product_reviews where email="' . pSQL($customer['email']) . '"');
                    $gdpr_data = array();
                    $id_lang = Context::getContext()->language->id;
                    if (!empty($existing_customer)) {
                        foreach ($existing_customer as $key => $existing_cust) {
                            $review_content = array();
                            $product = new Product($existing_cust['product_id'], true, $id_lang);
                            $current_status = $this->l('Disapproved');
                            if ($existing_cust['current_status'] == 0) {
                                $current_status = $this->l('Disapproved');
                            } elseif ($existing_cust['current_status'] == 1) {
                                $current_status = $this->l('Approved');
                            } elseif ($existing_cust['current_status'] == 3) {
                                $current_status = $this->l('Pending');
                            }
                            $gdpr_data[] = array(
                                $this->l('Customer Email') => $existing_cust['email'],
                                $this->l('Name') => $existing_cust['author'],
                                $this->l('Product Name') => $product->name,
                                $this->l('Review Title') => $existing_cust['review_title'],
                                $this->l('Review Description') => Tools::htmlentitiesDecodeUTF8($existing_cust['description']),
                                $this->l('Rating') => $existing_cust['ratings'],
                                $this->l('Status') => $current_status,
                                $this->l('Certified Buyer') => ($existing_cust['certified_buyer'])?$this->l('Yes'):$this->l('No'),
                                $this->l('Helpful Votes') => $existing_cust['helpful_votes'],
                                $this->l('Not Helpful Votes') => $existing_cust['not_helpful_votes'],
                                $this->l('Incentive Amount') => Tools::displayPrice($existing_cust['incentive_amount'], (int) $existing_cust['id_currency']),
                            );
                        }
                    }
                    if (!empty($gdpr_data) && count($gdpr_data) > 0) {
                          return json_encode($gdpr_data);
                    }
                    
                    return json_encode($this->l('Review Incentives: No user found with this email.'));
                }
            }
        }
    }
    
    /*
     * Function to delete the customer data from this module for the GDPR compliant plugin.
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            if (Module::isInstalled($this->name)) {
                $config = Tools::unSerialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
                if ($config['enable'] && $config['GDPR_compatibility_status']) {
                    $existing_customer = Db::getInstance()->executeS('SELECT id_customer from ' . _DB_PREFIX_ . 'customer where email="' . pSQL($customer['email']) . '"');
                    DB::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'velsof_product_reviews where email="'.pSQL($customer['email']).'"');
                    if (!empty($existing_customer)) {
                        foreach ($existing_customer as $existing_cust) {
                            $id_customer = $existing_cust['id_customer'];
                            if (!empty($id_customer)) {
                                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'velsof_incentive_coupon where id_customer='.($id_customer));
                                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'velsof_review_reminder_schedule where customer_id='.($id_customer));
                            }
                        }
                        return json_encode(true);
                    }
                }
                return json_encode($this->l('Review Incentives: No user found with this email.'));
            }
        }
    }
     
    /*
     * Function to show review list on product page
     */
    public function hookDisplayFooterProduct()
    {
        $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        if ($module_settings['enable'] == 1) {
            $ratings_data = array();
            $this->context->smarty->assign('read_review', '');
            //Add CSS for review block
            $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/front/kbrc_front.css');
            if (Tools::getValue('st')) {
                $this->context->smarty->assign('read_review', Tools::getValue('st'));
            }

            // Get write a review link
            $product_id = Tools::getValue('id_product');
            $link = $this->context->link->getModuleLink(
                'kbreviewincentives',
                'kbwritenewreview',
                array('product_id' => $product_id),
                (bool) Configuration::get('PS_SSL_ENABLED')
            );
            $sql = "SELECT name FROM " . _DB_PREFIX_ . "product_lang WHERE id_lang = '" . (int) $this->context->language->id . "' AND id_product = '" . (int) $product_id . "'";
            $product_data = Db::getInstance()->getRow($sql);
            $this->context->smarty->assign('product_name', $product_data['name']);
            $this->context->smarty->assign('write_new_review_link', $link);
            $this->context->smarty->assign('product_cont_path', $link);

            //Query to fetch review on this product

            $query = "SELECT * FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE product_id = '" . (int) $product_id . "' AND current_status = '1' AND id_Shop='".(int) $this->context->shop->id."'";
            $review_result = Db::getInstance()->executeS($query);
            if (empty($review_result)) {
                return $this->display(__FILE__, 'no_review_found.tpl');
            } else {
                if ($this->context->customer->isLogged()) {
                    $is_logged_in = $this->context->customer->id;
                } else {
                    $is_logged_in = 0;
                }
                $this->context->smarty->assign('is_logged', $is_logged_in);
                //Add JS for review block
                $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/front/kbrc_front.js');
                $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/front/kbrc_chart.js');
                $this->context->controller->addJS('https://www.google.com/jsapi');
                //Query to find ratings data
                $query = "SELECT ratings, COUNT(ratings) as rating_count, SUM(ratings) as rating_sum  FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE product_id = '" . (int) $product_id . "' AND current_status = '1' GROUP BY ratings";
                $rating_result = Db::getInstance()->executeS($query);
                $rating_count_tot = 0;
                $rating_sum = 0;
                foreach ($rating_result as $ratings) {
                    $rating = $ratings['ratings'];
                    $ratings_data[$rating . '_star'] = $ratings['rating_count'];
                    $rating_count_tot = $rating_count_tot + $ratings['rating_count'];
                    $rating_sum = $rating_sum + $ratings['rating_sum'];
                }
                $this->context->smarty->assign('total_ratings', $rating_count_tot);

                $avg_rating = (int) $rating_sum / (int) $rating_count_tot;
                $this->context->smarty->assign('avg_rating', ROUND($avg_rating, 1));
                $this->context->smarty->assign('rating_result', Tools::jsonEncode($ratings_data));
                $this->context->smarty->assign('image_path', $this->getModuleDirUrl() . $this->name . '/views/img/front/');
                $this->context->smarty->assign('reviews_data', $review_result);
                return $this->display(__FILE__, 'product_review_list.tpl');
            }
        }
    }
    /*
     * Function to fetch email templates
     */
    protected function loadEmailTemplate($language, $template_name)
    {
        $fetch_template_query = 'select * from ' . _DB_PREFIX_ . 'velsof_review_incentive_emails where id_lang=' . (int) $language .
                ' and id_shop=' . (int) $this->context->shop->id . ' and template_name="' . pSQL($template_name) . '"';
        $template_data = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($fetch_template_query);
        if ($template_data) {
            $template_data['body'] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
            $template_data['subject'] = Tools::htmlentitiesDecodeUTF8($template_data['subject']);
            $template_data['text_content'] = Tools::htmlentitiesDecodeUTF8($template_data['text_content']);
            return $template_data;
        } else {
            $template_data = array();
            return $template_data;
        }
    }
    /*
     * Function to get template directory
     */
    protected function getTemplateDir()
    {
        $lang_id = Configuration::get('PS_LANG_DEFAULT');
        $iso = Language::getIsoById((int) $lang_id);
        return _PS_MODULE_DIR_ . $this->name . '/mails/' . $iso . '/';
    }
    /*
     * Function to replace images in email templates
     */
    public function replaceEmailImage($template_data)
    {
        $template_data['body'] = str_replace('{minimal_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/minimal6.png', $template_data['body']);
        $template_data['body'] = str_replace('{disapprove_icon_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/rejected.png', $template_data['body']);
        $template_data['body'] = str_replace('{approve_icon_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/accepted.png', $template_data['body']);
        $template_data['body'] = str_replace('{fb_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/FB.png', $template_data['body']);
        $template_data['body'] = str_replace('{tumbler_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/TUMBLER.png', $template_data['body']);
        $template_data['body'] = str_replace('{pininterest_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/PINTEREST.png', $template_data['body']);
        $template_data['body'] = str_replace('{twitter_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/TWITTER.png', $template_data['body']);
        $template_data['body'] = str_replace('{reminder_icon_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/reminder.png', $template_data['body']);
        $template_data['body'] = str_replace('{icon_img_path}', $this->getModuleDirUrl() . $this->name . '/views/img/admin/email/ICON.png', $template_data['body']);

        return $template_data;
    }
    /*
     * Function to send email to admin and customers
     */
    public function sendNotificationEmail($email_template, $review_data, $lang_id, $review_description = null, $pro_obj = null, $coupon_details = array())
    {
        $template_data = $this->loadEmailTemplate($lang_id, $email_template);
        $template_data = $this->replaceEmailImage($template_data);
        $directory = $this->getTemplateDir();
        if (is_writable($directory)) {
            $html_template = $email_template . '.html';
            $txt_template = $email_template . '.txt';

            $base_html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/base_email_temp.tpl');

            $template_html = str_replace('{template_content}', $template_data['body'], $base_html);

            $file = fopen($directory . $html_template, 'w+');
            fwrite($file, $template_html);
            fclose($file);

            $file = fopen($directory . $txt_template, 'w+');
            fwrite($file, strip_tags($template_html));
            fclose($file);
            $shop_url_obj = new ShopUrl($this->context->shop->id);
            $shop_url = $shop_url_obj->getUrl((bool) Configuration::get('PS_SSL_ENABLED'));
            if ($email_template == 'new_review_post') {
                $email = Configuration::get('PS_SHOP_EMAIL');
                $name = Configuration::get('PS_SHOP_NAME');
            } else {
                if ($review_data['customer_id'] != 0) {
                    $customer = new Customer((int) $review_data['customer_id']);
                    $email = $customer->email;
                    $name = $customer->firstname . ' ' . $customer->lastname;
                } else {
                    $email = $review_data['email'];
                    $name = $review_data['author'];
                }
            }
            $link_obj = new Link();
            switch ($email_template) {
                case 'new_review_post':
                    $template_vars = array(
                        '{customer_name}' => $name,
                        '{review_content}' => $review_description,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                        '{product_name}' => $pro_obj->name,
                        '{shop_url}' => $shop_url
                    );
                    break;

                case 'review_dis':
                    $template_vars = array(
                        '{customer_name}' => $name,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                        '{shop_url}' => $shop_url,
                        '{shop_email}' => Configuration::get('PS_SHOP_EMAIL'),
                    );
                    break;

                case 'without_coupon_temp':
                    $template_vars = array(
                        '{customer_name}' => $name,
                        '{product_link}' => $link_obj->getProductLink($pro_obj) . '?st=1',
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                        '{shop_email}' => Configuration::get('PS_SHOP_EMAIL'),
                        '{product_name}' => $pro_obj->name,
                        '{shop_url}' => $shop_url
                    );
                    break;

                case 'with_coupon_temp':
                    $template_vars = array(
                        '{customer_name}' => $name,
                        '{coupon_code}' => $coupon_details['code'],
                        '{amount}' => $coupon_details['coupon_value'],
                        '{shop_email}' => Configuration::get('PS_SHOP_EMAIL'),
                        '{shop_url}' => $shop_url,
                        '{product_name}' => $pro_obj->name,
                        '{review_link}' => $link_obj->getProductLink($pro_obj) . '?st=1',
                    );
                    break;
            }
            unset($link_obj);

            $is_mail_send = Mail::Send(
                $lang_id,
                $email_template,
                $template_data['subject'],
                $template_vars,
                $email,
                $name,
                Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('PS_SHOP_NAME'),
                null,
                null,
                _PS_MODULE_DIR_ . $this->name.'/mails/',
                false,
                $this->context->shop->id
            );
            return $is_mail_send;
        } else {
            return false;
        }
    }
    
    
    /*
     * Function to save product in schedule list for sending Reminder email to buyers
     */
    public function hookActionValidateOrder($params)
    {
        $product_to_send_reminder = array();
        if ($this->checkModuleEnable()) {
            $order_id = $params['order']->id;
            $products = Context::getContext()->cart->getProducts();
            foreach ($products as $product) {
                $cat_result = $this->checkCategory($product['id_category_default']);
                if ($cat_result == false) {         //This product category not blacklisted
                    //Check if product is black listed or not
                    $pro_result = $this->checkProduct($product['id_product']);
                    if ($pro_result == false) {
                        $product_to_send_reminder[] = $product['id_product'];
                    }
                }
            }
            $this->scheduleReminder($product_to_send_reminder, $order_id);
        }
    }
 
    /*
     * Function to schedule reminder if order is placed and reminders are available
     */
    public function scheduleReminder($product_to_send_reminder, $order_id)
    {
        //Fix a schedule according to reminder profile
        $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_reminder_profile rp INNER JOIN " . _DB_PREFIX_ . "velsof_reminder_profile_templates rpt ON "
                . " rp.reminder_profile_id = rpt.reminder_profile_id AND id_lang = '" . (int) $this->context->language->id . "'"
                . " AND id_shop = '" . (int) $this->context->shop->id . "' ORDER BY rp.no_of_days_after";
        $reminder_profiles = Db::getInstance()->executeS($sql);
        foreach ($reminder_profiles as $reminder) {
            if ($reminder['active'] == 1) {
                if ($reminder['enable_order_create_reminder'] == 1) {
                    if ($this->insertScheduleReminder($reminder, $product_to_send_reminder, $order_id)) {
                        $this->addLogEntry('Success', 'Reminder Scheduled', 'A new reminder is scheduled for customer having customer ID ' . $this->context->customer->id . '', 'hookActionValidateOrder($params)', '');
                    } else {
                        $this->addLogEntry('Error', 'Reminder Schedule Failed', 'Data could not be inserted in DB', 'hookActionValidateOrder($params)', '');
                    }
                } elseif ($reminder['enable_order_create_reminder'] == 0) {
                    $products = serialize($product_to_send_reminder);
                    $sql = "INSERT INTO " . _DB_PREFIX_ . "velsof_order_status_check VALUES('','" . (int) $this->context->customer->id . "','" . (int) $order_id . "','" . pSQL($products) . "',"
                            . " '" . pSQL($reminder['select_type']) . "','" . (int) $reminder['reminder_profile_id'] . "', now(), now())";
                    $res = Db::getInstance()->execute($sql);
                    if ($res) {
                        $this->addLogEntry('Success', 'Send order for status check', 'A new order is sent for checking order status through cron', 'hookActionValidateOrder($params)', '');
                    } else {
                        $this->addLogEntry('Error', 'Order can not be sent for status check.', 'Data could not be stored in table.', 'hookActionValidateOrder($params)', '');
                    }
                }
            }
        }
    }
    
    /*
     * Function to create reminder or insert reminder details into DB
     */
    public function insertScheduleReminder($reminder, $product_to_send_reminder, $order_id)
    {
        $schedule_type = 'Reminder';
        $schedule_date = date('Y-m-d H:i:s', strtotime('+' . $reminder['no_of_days_after'] . ' days'));
        $products = serialize($product_to_send_reminder);
        $sql = "INSERT INTO " . _DB_PREFIX_ . "velsof_review_reminder_schedule VALUES('','" . (int) $this->context->customer->id . "', '" . pSQL($products) . "', '" . (int) $order_id . "', '" . pSQL($schedule_type) . "',"
                . " '" . pSQL($schedule_date) . "', '" . pSQL($reminder['subject']) . "', '" . pSQL($reminder['text_content']) . "',"
                . " '" . pSQL($reminder['body']) . "', '" . (int) $this->context->language->id . "', '" . (int) $this->context->shop->id . "','0', now(), now())";
        $res = Db::getInstance()->execute($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
     /*
     * Function to check module is enable or not
     */
    public function checkModuleEnable()
    {
        $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        if ($module_settings['enable'] == 1) {
            return true;
        } else {
            return false;
        }
    }
            
     /*
     * Function to check category of current product is blocked or not for reminder
     */
    public function checkCategory($category_id)
    {
        $query = "SELECT * FROM " . _DB_PREFIX_ . "velsof_categories_review_incentive WHERE category_id='" . (int) $category_id . "'";
        $res = Db::getInstance()->getRow($query);
        if (!empty($res)) {
            $category_show = true;
        } else {
            $res = $this->getParentId($category_id);
            $category_show = $res;
        }
        return $category_show;
    }
    /*
     * Function to fetch parent category ID and check for restriction
     */
    public function getParentId($category_id)
    {
        $query = "SELECT id_parent FROM " . _DB_PREFIX_ . "category WHERE id_category=" . (int) $category_id;
        $result = Db::getInstance()->getRow($query);
        if ($result['id_parent'] != 0) {
            $query = "SELECT * FROM " . _DB_PREFIX_ . "velsof_categories_review_incentive WHERE category_id='" . (int) $result['id_parent'] . "'";
            $res = Db::getInstance()->getRow($query);
            if (!empty($res)) {
                return true;
            } else {
                return $this->getParentId($result['id_parent']);
            }
        } else {
            return false;
        }
    }
     /*
     * Function to check current product is blocked or not for reminder
     */
    public function checkProduct($product_id)
    {
        $query = "SELECT * FROM " . _DB_PREFIX_ . "velsof_products_review_incentive WHERE product_id='" . (int) $product_id . "'";
        $res = Db::getInstance()->getRow($query);
        if (!empty($res)) {
            $product_show = true;
        } else {
            $product_show = false;
        }
        return $product_show;
    }
    
    public function hookDisplayHeader()
    {
        $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        $page_name = $this->context->controller->php_self;
        if ($module_settings['enable'] == 1 && $page_name == 'product') {
            $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/front/kbrc_front.css');
            $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/front/kbrc_front.js');
            $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/front/kbrc_chart.js');
            $this->context->controller->addJS('https://www.google.com/jsapi');
        }
    }

    /*
     * Function to show read and write review option on product page
     */
    public function hookDisplayProductButtons()
    {
        $module_settings = Tools::Unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        if ($module_settings['enable'] == 1) {
            //Add CSS for review block
            $product_id = Tools::getValue('id_product');
            $link = $this->context->link->getModuleLink(
                'kbreviewincentives',
                'kbwritenewreview',
                array('product_id' => $product_id),
                (bool) Configuration::get('PS_SSL_ENABLED')
            );
            $this->context->smarty->assign('write_new_review_link', $link);
            return $this->display(__FILE__, 'post_read_review_option.tpl');
        }
    }

    /*
     * Function removes module tabs to the admin panel
     */
    private function uninstallTabs()
    {
        foreach ($this->moduleTabsToAdd() as $tab) {
            $tab_obj = new Tab(Tab::getIdFromClassName($tab[0]));
            $tab_obj->delete();
        }
    }
     /*
     * Function adds module tabs to the admin panel
     */
    private function installTabs()
    {
        foreach ($this->moduleTabsToAdd() as $tab) {
            $tab_obj = new Tab();
            $tab_obj->name = array();
            foreach (Language::getLanguages(false) as $lang) {
                $tab_obj->name[$lang['id_lang']] = $this->l($tab[1]);
            }
            $tab_obj->class_name = $tab[0];
            $tab_obj->module = $this->name;
            $tab_obj->active = 0;
            $tab_obj->id_parent = Tab::getIdFromClassName($tab[0]);
            $tab_obj->add();
            unset($tab_obj);
        }
    }

    /*
     * Function returns the tabs that will be added to the admin panel for this module
     */
    private function moduleTabsToAdd()
    {
        $tabs_to_add = array();
        $tabs_to_add[] = array(
            'AdminKbrcReminderProfiles',
            'Reminder Profiles'
        );
        $tabs_to_add[] = array(
            'AdminKbrcReviews',
            'Customer Reviews'
        );
        $tabs_to_add[] = array(
            'AdminKbrcCriteria',
            'Criteria Settings'
        );
        $tabs_to_add[] = array(
            'AdminKbrcAuditLog',
            'Audit Log'
        );
        $tabs_to_add[] = array(
            'AdminKbrcReports',
            'Reports'
        );
        return $tabs_to_add;
    }
    /*
     * Function for adding text to translation files
     */
    private function addTextsToTranslations()
    {
        $this->l('Reminder Profiles');
        $this->l('Criteria Settings');
        $this->l('Customer Reviews');
        $this->l('Audit Log');
        $this->l('Reports');
    }
    
    /*
     * Adding back media CSS & JS
     */

    public function addBackOfficeMedia()
    {
        /* CSS files */
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/kbrc_admin.css');
//        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/font-awesome.css');
//        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/font-awesome.min.css');
        /* JS files */
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/admin/kbrc_admin.js');
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/velovalidation.js');
//        $this->context->controller->addJS($this->_path . 'views/js/velovalidation.js'); 
    }
    /*
    * Function to insert email templates in DB and assigning variables to email templates
    * 
    *  @param    Array template_data    Contains template data which is to be inserted
    *  @param    boolean return    True if email is inserted
    */
    protected function insertEmailDefaultData($template_data)
    {
        $all_shops = Shop::getShops(false);
        foreach ($all_shops as $shop) {
            foreach (Language::getLanguages(false) as $lang) {
                $qry = 'INSERT into ' . _DB_PREFIX_ . 'velsof_review_incentive_emails values ("", ' .
                    (int) $lang['id_lang'] . ',
                ' . (int) $shop['id_shop'] . ', "' .
                    pSQL($lang['iso_code']) . '",
                "' . pSQL($template_data['name']) . '", "' .
                    pSQL(Tools::htmlentitiesUTF8($template_data['text_content'])) . '",
                "' . pSQL(Tools::htmlentitiesUTF8($template_data['subject'])) . '", "' .
                    pSQL(Tools::htmlentitiesUTF8($template_data['body'])) . '",
                now(), now())';
                $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($qry);
            }
        }
        return $res;
    }
    /*
    * Function to update email templates in DB and to update email html and text files in mails folder
    * 
    *  @param    Array template_data    Contains template data which is to be updated
    *  @param    boolean return    True if email is updated otherwise returns False
    */
    protected function updateEmailTemplate($template_data)
    {
        $tem_name = $template_data['name'];
        $qry = 'UPDATE ' . _DB_PREFIX_ . 'velsof_review_incentive_emails set subject = "' . pSQL(Tools::htmlentitiesUTF8($template_data['subject'])) . '",
				body="' . pSQL(Tools::htmlentitiesUTF8($template_data['body'])) . '", text_content = "' . pSQL($template_data['text_content']) . '", date_updated=now() WHERE
				template_name = "' . pSQL($tem_name) . '" and id_lang=' . (int) $template_data['template_lang'].' and id_shop = '. (int)$template_data['id_shop'];
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($qry);
        return $res;
    }
     /*
    * Function to get email data for selected shop, email template name and language
    * 
    *  @param    Array template_data    Contains template data which is to be updated
    *  @param    boolean return    True if email is updated otherwise returns False
    */
    protected function getEmailData($template_data)
    {
        $fetch_template_query = 'select * from ' . _DB_PREFIX_ . 'velsof_review_incentive_emails where id_lang=' . (int) $template_data['template_lang'] .
                ' and id_shop=' . (int) $this->context->shop->id . ' and template_name="' . pSQL($template_data['name']) . '"';
        $template_data = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($fetch_template_query);
        return $template_data;
    }
    /*
     * Function to show ratings on product list
     */
//    public function hookDisplayProductListReviews($params)
//    {
//        $product_id = $params['product']['id_product'];
//        $query = "SELECT ratings, COUNT(ratings) as rating_count, SUM(ratings) as rating_sum  FROM " . _DB_PREFIX_ . "velsof_product_reviews WHERE product_id = '" . (int) $product_id . "' AND current_status = '1' GROUP BY ratings";
//        $rating_result = Db::getInstance()->executeS($query);
//        if (!empty($rating_result)) {
//            $rating_count_tot = 0;
//            $rating_sum = 0;
//            foreach ($rating_result as $ratings) {
//                $rating_count_tot = $rating_count_tot + $ratings['rating_count'];
//                $rating_sum = $rating_sum + $ratings['rating_sum'];
//            }
//            if ($rating_count_tot != 0) {
//                $avg_rating = (int) $rating_sum / (int) $rating_count_tot;
//            } else {
//                $avg_rating = 0;
//            }
//            $link = $this->context->link->getProductLink($product_id);
//            $this->context->smarty->assign('rating_link', $link . '?st=1');
//            $this->context->smarty->assign('rating', round($avg_rating));
//            $this->context->smarty->assign('rating_total', $rating_count_tot);
//            $this->context->smarty->assign('path', $this->getModuleDirUrl() . $this->name . '/views/img/front/');
//            return $this->display(__FILE__, 'kbrc_rating_display.tpl');
//        }
//    }
//    public function hookDisplayProductTab()
//    {
//           return $this->display(__FILE__, 'demo.tpl');
//    }
//    public function hookDisplayProductTabContent()
//    {
//        return $this->display(__FILE__, 'demo.tpl');
//    }
    /*
     * Default prestashop main function
     */
    public function getContent()
    {
//        $html = $this->adminDisplayWarning(
//            $this->l('Note:: If you clicks on SYNC REVIEWS button then all reviews from default review module will be transferred to this module.')
//        );
//        $this->initPageHeaderToolbar();
        $this->registerHook('displayProductButtons');
        $languages = Language::getLanguages(false);
        $output = null;
        if (Tools::isSubmit('submit' . $this->name)) {
            $module_config = Tools::getValue('kbreviewincentives');
            foreach ($languages as $lang) {
                $email_subject = Tools::getValue(
                    'WITH_COUPON_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_content = Tools::getValue(
                    'WITH_COUPON_EMAIL_TEMP_' . $lang['id_lang']
                );
                $template_data = array();
                $template_data['template_lang'] = $lang['id_lang'];
                $template_data['name'] = 'with_coupon_temp';
                $result = $this->getEmailData($template_data);
                if (!empty($result)) {
                    $template_data['name'] = 'with_coupon_temp';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->updateEmailTemplate($template_data);
                } else {
                    $template_data['name'] = 'with_coupon_temp';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->insertEmailDefaultData($template_data);
                }
            }
            foreach ($languages as $lang) {
                $email_subject = Tools::getValue(
                    'WITHOUT_COUPON_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_content = Tools::getValue(
                    'WITHOUT_COUPON_EMAIL_TEMP_' . $lang['id_lang']
                );
                $template_data = array();
                $template_data['template_lang'] = $lang['id_lang'];
                $template_data['name'] = 'without_coupon_temp';
                $result = $this->getEmailData($template_data);
                if (!empty($result)) {
                    $template_data['name'] = 'without_coupon_temp';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $this->updateEmailTemplate($template_data);
                } else {
                    $template_data['name'] = 'without_coupon_temp';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->insertEmailDefaultData($template_data);
                }
            }
            foreach ($languages as $lang) {
                $email_subject = Tools::getValue(
                    'REJECT_REVIEW_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_content = Tools::getValue(
                    'REJECT_REVIEW_EMAIL_TEMP_' . $lang['id_lang']
                );
                $template_data = array();
                $template_data['template_lang'] = $lang['id_lang'];
                $template_data['name'] = 'review_dis';
                $result = $this->getEmailData($template_data);
                if (!empty($result)) {
                    $template_data['name'] = 'review_dis';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->updateEmailTemplate($template_data);
                } else {
                    $template_data['name'] = 'review_dis';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->insertEmailDefaultData($template_data);
                }
            }
            $gdpr_policy_text = array();
            $gdpr_policy_url = array();
            foreach ($languages as $lang) {
                $email_subject = Tools::getValue(
                    'ADMIN_EMAIL_SUBJECT_' . $lang['id_lang']
                );
                $email_content = Tools::getValue(
                    'ADMIN_EMAIL_TEMP_' . $lang['id_lang']
                );
                $template_data = array();
                $template_data['template_lang'] = $lang['id_lang'];
                $template_data['name'] = 'new_review_post';
                $result = $this->getEmailData($template_data);
                if (!empty($result)) {
                    $template_data['name'] = 'new_review_post';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->updateEmailTemplate($template_data);
                } else {
                    $template_data['name'] = 'new_review_post';
                    $template_data['subject'] = $email_subject;
                    $template_data['template_lang'] = $lang['id_lang'];
                    $template_data['id_shop'] = (int) $this->context->shop->id;
                    $template_data['body'] = $email_content;
                    $template_data['text_content'] = strip_tags($email_content);
                    $this->insertEmailDefaultData($template_data);
                }
                $gdpr_policy_text[$lang['id_lang']] = Tools::getValue('gdpr_policy_text_'.$lang['id_lang']);
                $gdpr_policy_url[$lang['id_lang']] = Tools::getValue('gdpr_policy_url_'.$lang['id_lang']);
            }
            $module_config['gdpr_policy_text'] = $gdpr_policy_text;
            $module_config['gdpr_policy_url'] = $gdpr_policy_url;
            /* Updating form values in configuration table */
            Configuration::updateValue('KBRC_PRODUCT_REVIEW_INCENTIVES', serialize($module_config));
            $output .= $this->displayConfirmation($this->l('Settings have been saved successfully.'));
        }
        /* Add back office Media */
        $this->addBackOfficeMedia();
        $link_report = $this->context->link->getAdminLink('AdminKbrcReports', true);
        $link_reminder = $this->context->link->getAdminLink('AdminKbrcReminderProfiles', true);
        $link_criteria = $this->context->link->getAdminLink('AdminKbrcCriteria', true);
        $link_review = $this->context->link->getAdminLink('AdminKbrcReviews', true);
        $link_audit_log = $this->context->link->getAdminLink('AdminKbrcAuditLog', true);
        $default_link = $this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($this->name).'&tab_module='.$this->tab.'&module_name='.urlencode($this->name);
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
        $this->context->smarty->assign(
            'audit_log_link',
            $link_audit_log
        );
        $this->context->smarty->assign('selected_nav', 'config');
        $this->context->smarty->assign('method', '');
        $this->context->smarty->assign(
            'top_tabs_kbreviewincentives',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->name.'/views/templates/admin/top_tabs_kbreviewincentive.tpl'
            )
        );
//        $audit_link_box = $this->context->smarty->fetch(
//            _PS_MODULE_DIR_ .$this->name. '/views/templates/admin/audit_link.tpl'
//        );
        $this->context->smarty->assign('lang_id', $this->context->language->id);

//        $this->context->smarty->assign('module_tabs', $module_tabs);
        $this->context->smarty->assign('firstCall', false);
        $this->context->smarty->assign('controller_path', '');
        $this->context->smarty->assign(
            'kb_velovalidation',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->name.'/views/templates/admin/kb_velovalidation.tpl'
            )
        );
        
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        /* Getting form fields */
        $this->fields_form = $this->getGeneralSettingsTabFields();
        $module_settings = Tools::unserialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
        $field_value = array(
            'kbreviewincentives[enable]' => isset($module_settings['enable']) ? $module_settings['enable'] : 0,
            'kbreviewincentives[GDPR_compatibility_status]' => isset($module_settings['GDPR_compatibility_status']) ? $module_settings['GDPR_compatibility_status'] : 0,
            'kbreviewincentives[enable_gdpr_policy]' => isset($module_settings['enable_gdpr_policy']) ? $module_settings['enable_gdpr_policy'] : 0,
            'kbreviewincentives[incentive_enable]' => isset($module_settings['incentive_enable']) ? $module_settings['incentive_enable'] : 0,
            'kbreviewincentives[incentive_amount]' => isset($module_settings['incentive_amount']) ? $module_settings['incentive_amount'] : 0,
            'kbreviewincentives[incentive_criteria]' => isset($module_settings['incentive_criteria']) ? $module_settings['incentive_criteria'] : 0,
            'kbreviewincentives[moderation]' => isset($module_settings['moderation']) ? $module_settings['moderation'] : 0,
        );
        /* Persist multilang fields email subjects */
        /* Persist multilang fields email templates */
        foreach ($languages as $lang) {
            $template_data['name'] = 'with_coupon_temp';
            $template_data['template_lang'] = $lang['id_lang'];
            $template_data['']=(int) $this->context->shop->id;
//            print_r($template_data);
//            die;
            $template_data = $this->getEmailData($template_data);
           
            if (!empty($template_data)) {
                $template_data = $this->replaceEmailImage($template_data);
                $field_value['WITH_COUPON_EMAIL_SUBJECT'][$lang['id_lang']] = $template_data['subject'];
                $field_value['WITH_COUPON_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
            } else {
                $field_value['WITH_COUPON_EMAIL_SUBJECT'][$lang['id_lang']] = $this->l("Yipeeee!!!");
                $field_value['WITH_COUPON_EMAIL_TEMP'][$lang['id_lang']] = $this->l("Yipeeee!!!");
            }
        }
        foreach ($languages as $lang) {
            $template_data['name'] = 'without_coupon_temp';
            $template_data['template_lang'] = $lang['id_lang'];
            $template_data = $this->getEmailData($template_data);

            if (!empty($template_data)) {
                $template_data = $this->replaceEmailImage($template_data);
                $field_value['WITHOUT_COUPON_EMAIL_SUBJECT'][$lang['id_lang']] = $template_data['subject'];
                $field_value['WITHOUT_COUPON_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
            } else {
                $field_value['WITHOUT_COUPON_EMAIL_SUBJECT'][$lang['id_lang']] = $this->l("Yipeeee!!!");
                $field_value['WITHOUT_COUPON_EMAIL_TEMP'][$lang['id_lang']] = $this->l("Yipeeee!!!");
            }
            
            $field_value['gdpr_policy_text'][$lang['id_lang']] = (isset($module_settings['gdpr_policy_text']) && !empty($module_settings['gdpr_policy_text'][$lang['id_lang']])) ? $module_settings['gdpr_policy_text'][$lang['id_lang']] : $this->l('I agree to the terms and conditions.');
            $field_value['gdpr_policy_url'][$lang['id_lang']] = (isset($module_settings['gdpr_policy_url']) && !empty($module_settings['gdpr_policy_url'][$lang['id_lang']])) ? $module_settings['gdpr_policy_url'][$lang['id_lang']] : '';
        }
        foreach ($languages as $lang) {
            $template_data['name'] = 'review_dis';
            $template_data['template_lang'] = $lang['id_lang'];
            $template_data = $this->getEmailData($template_data);

            if (!empty($template_data)) {
                $template_data = $this->replaceEmailImage($template_data);
                $field_value['REJECT_REVIEW_EMAIL_SUBJECT'][$lang['id_lang']] = $template_data['subject'];
                $field_value['REJECT_REVIEW_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
            } else {
                $field_value['REJECT_REVIEW_EMAIL_SUBJECT'][$lang['id_lang']] = $this->l("Yipeeee!!!");
                $field_value['REJECT_REVIEW_EMAIL_TEMP'][$lang['id_lang']] = $this->l("Yipeeee!!!");
            }
        }
        foreach ($languages as $lang) {
            $template_data['name'] = 'new_review_post';
            $template_data['template_lang'] = $lang['id_lang'];
            $template_data = $this->getEmailData($template_data);

            if (!empty($template_data)) {
                $template_data = $this->replaceEmailImage($template_data);
                $field_value['ADMIN_EMAIL_SUBJECT'][$lang['id_lang']] = $template_data['subject'];
                $field_value['ADMIN_EMAIL_TEMP'][$lang['id_lang']] = Tools::htmlentitiesDecodeUTF8($template_data['body']);
            } else {
                $field_value['ADMIN_EMAIL_SUBJECT'][$lang['id_lang']] = $this->l("Yipeeee!!!");
                $field_value['ADMIN_EMAIL_TEMP'][$lang['id_lang']] = $this->l("Yipeeee!!!");
            }
        }

        $action = AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules');
        $form = $this->getFormHtml($this->fields_form, $languages, $field_value, 'kbrc_general_settings', $action);
        $link = $this->context->link->getModuleLink(
            'kbreviewincentives',
            'cron'
        );
        $this->context->smarty->assign('cron_url', $link . '?secure_key=' . Configuration::get('KBRC_SECURE_KEY'));
        $this->context->smarty->assign('form', $form);
        $this->context->smarty->assign('form1', '');
        /* Attached tpl to helper form */
        $helper = new HelperView();
        $helper->module = $this;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->current = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->override_folder = 'helpers/';
        $helper->base_folder = 'view/';
        $helper->base_tpl = 'page_custom.tpl';
        $view = $helper->generateView();
        $this->context->smarty->assign('view', $view);
        $tpl = 'Form_custom.tpl';
        $helper = new Helper();
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->override_folder = 'helpers/';
        $helper->base_folder = 'form/';
        $helper->setTpl($tpl);
        $tpl = $helper->generate();
        $output = $output . $tpl;
        return $output;
    }
    /*
     * Fetching fields in General settings tab
     */
    public function getGeneralSettingsTabFields()
    {
        $criteria_groups = array(
            array(
                'id' => 1,
                'name' => $this->l('Buyer')
            ),
            array(
                'id' => 2,
                'name' => $this->l('Any Customer')
            ),
        );
        $mod_opt = array(
            array(
                'id' => 1,
                'name' => $this->l('Automatic')
            ),
            array(
                'id' => 2,
                'name' => $this->l('Manual')
            ),
        );
        $form_fields = array(
            'form' => array(
                'id_form' => 'kbrc_general_settings',
                'legend' => array(
                    'title' => $this->l('General Settings'),
                    'icon' => 'icon-gears'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable the module'),
                        'type' => 'switch',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable or Disable the plugin functionality'),
                    ),
                    array(
                        'label' => $this->l('Delete Customer Data On Delete Request for GDPR'),
                        'type' => 'switch',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[GDPR_compatibility_status]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('Enable/Disable to delete customer data on GDPR module delete request.'),
                        'hint' => $this->l('Enable this to delete customer data on GDPR module delete request.'),
                    ),
                    array(
                        'label' => $this->l('Enable/Disable Privacy Policy'),
                        'type' => 'switch',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[enable_gdpr_policy]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable/Disable to display consent checkbox from frontend.'),
                    ),
                    array(
                        'label' => $this->l('Privacy Policy Text'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Enter the privacy policy text'),
                        'desc' => $this->l('This text will be displayed when user writes new review'),
                        'name' => 'gdpr_policy_text',
                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Privacy Policy Page URL'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Enter the URL of the privacy policy page'),
                        'desc' => $this->l('Enter the URL of the page where you have define privacy policy.'),
                        'name' => 'gdpr_policy_url',
//                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Enable Incentives'),
                        'type' => 'switch',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[incentive_enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('If enable then allows incentives to customer.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Incentive Amount'),
                        'hint' => $this->l('Default incentive amount to customer whose reviews has been accepted.'),
                        'name' => 'kbreviewincentives[incentive_amount]',
                        'col' => 2,
                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Incentive Criteria'),
                        'type' => 'select',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[incentive_criteria]',
                        'hint' => $this->l('Allow incentives and reminder to selected group.'),
                        'is_bool' => true,
                        'options' => array(
                            'query' => $criteria_groups,
                            'id' => 'id',
                            'name' => 'name',
                        ),
                        'required' => false,
                    ),
                    array(
                        'label' => $this->l('Moderation'),
                        'type' => 'select',
                        'class' => 'general_setting_tab',
                        'name' => 'kbreviewincentives[moderation]',
                        'hint' => $this->l('Evaluate review before posting on product page.'),
                        'desc' => $this->l('If automatic then reviews will be automatically approved and if manual then admin will approve or disapprove it.'),
                        'is_bool' => true,
                        'options' => array(
                            'query' => $mod_opt,
                            'id' => 'id',
                            'name' => 'name',
                        ),
                        'required' => false,
                    ),
                    array(
                        'label' => $this->l('Email Subject (When review is accepted and incentives are included)'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Subject of email which will be sent to customers When review is accepted and incentives are included.'),
                        'name' => 'WITH_COUPON_EMAIL_SUBJECT',
                        'required' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email Template (When review is accepted and incentives are included)'),
                        'name' => 'WITH_COUPON_EMAIL_TEMP',
                        'id' => 'WITH_COUPON_EMAIL_TEMP',
                        'required' => true,
                        'cols' => '9',
                        'rows' => '20',
                        'class' => 'col-lg-9',
                        'lang' => true,
                        'autoload_rte' => true,
                        'desc' => $this->l('Do not remove {amount}, {customer_name}, {coupon_code}, {shop_url}, {shop_email} tags in this email template. ')
                    ),
                    array(
                        'label' => $this->l('Email Subject (When review is accepted and incentives are excluded)'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Subject of email which will be sent to customers When review is accepted and incentives are not allowed.'),
                        'name' => 'WITHOUT_COUPON_EMAIL_SUBJECT',
                        'required' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email Template (When review is accepted and incentives are excluded)'),
                        'name' => 'WITHOUT_COUPON_EMAIL_TEMP',
                        'id' => 'WITHOUT_COUPON_EMAIL_TEMP',
                        'required' => true,
                        'cols' => '9',
                        'rows' => '20',
                        'class' => 'col-lg-9',
                        'lang' => true,
                        'autoload_rte' => true,
                        'desc' => $this->l('Do not remove {shop_name}, {customer_name}, {product_name}, {shop_url}, {shop_email}, {product_link} tags in this email template. ')
                    ),
                    array(
                        'label' => $this->l('Email Subject (When admin disapproves a review.)'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Subject of email which will be sent to customers When admin disapproves a review.'),
                        'name' => 'REJECT_REVIEW_EMAIL_SUBJECT',
                        'required' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email Template (When admin disapproves a review.)'),
                        'name' => 'REJECT_REVIEW_EMAIL_TEMP',
                        'id' => 'REJECT_REVIEW_EMAIL_TEMP',
                        'required' => true,
                        'cols' => '9',
                        'rows' => '20',
                        'class' => 'col-lg-9',
                        'lang' => true,
                        'autoload_rte' => true,
                        'desc' => $this->l('Do not remove {shop_name}, {customer_name}, {shop_url}, {shop_email} tags in this email template. ')
                    ),
                    array(
                        'label' => $this->l('Email Subject (Inform to admin when a new review is posted)'),
                        'type' => 'text',
                        'lang' => true,
                        'hint' => $this->l('Subject of email which will be sent to admin when a new review will be posted.'),
                        'class' => 'optn_email_subject',
                        'name' => 'ADMIN_EMAIL_SUBJECT',
                        'required' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email Template (Inform to admin when a new review is posted)'),
                        'name' => 'ADMIN_EMAIL_TEMP',
                        'id' => 'ADMIN_EMAIL_TEMP',
                        'required' => true,
                        'cols' => '9',
                        'rows' => '20',
                        'class' => 'col-lg-9',
                        'lang' => true,
                        'autoload_rte' => true,
                        'desc' => $this->l('Do not remove {shop_name}, {shop_url}, {shop_email}, {product_name}, {review_content} tags in this email template. ')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right kbrc_general_settings_btn'
                ),
            ),
        );

        return $form_fields;
    }

    /*
     * Function for returning the HTML for a Helper Form
     */
    public function getFormHtml($field_form, $languages, $field_value, $id, $action)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->fields_value = $field_value;
        $helper->name_controller = $this->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $this->displayName;
        if ($id == 'kbrc_general_settings') {
            $helper->show_toolbar = true;
        } else {
            $helper->show_toolbar = false;
        }
        $helper->table = 'configuration';
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = false;
        $helper->submit_action = 'submit' . $this->name;
        return $helper->generateForm(array('form' => $field_form));
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
    /*
     * Function definition to add an entry of Review Incentive Audit Log into Database
     * 
     * $auditLogEntryString = 'Log Message';
     * $auditMethodName = 'ClassName::FunctionName()';
     * KbMailChimpCore::addLogEntry('synchronization', 'info', $auditLogEntryString, $auditMethodName);
     */
    public static function addLogEntry($auditLogType = '', $auditLogAction = '', $auditLogEntry = '', $auditMethodName = '', $auditLogUser = '')
    {
        $auditLogTime = date("Y-m-d H:i:s");
        if (empty($auditLogUser)) {
            if (!empty(Context::getContext()->employee->firstname) && !empty(Context::getContext()->employee->lastname)) {
                $auditLogUser = Context::getContext()->employee->firstname . ' ' . Context::getContext()->employee->lastname;
            } else {
                $auditLogUser = 'Customer';
            }
        }

        if (!empty($auditLogEntry) && !empty($auditLogUser) && !empty($auditMethodName) && !empty($auditLogTime)) {
            $auditLogSQL = "INSERT INTO " . _DB_PREFIX_ . "velsof_incentive_audit_log VALUES (NULL, '" . pSQL($auditLogEntry, true) . "', '" . pSQL($auditLogUser) . "', '" . pSQL($auditMethodName) . "', '" . pSQL($auditLogType) . "', '" . pSQL($auditLogAction) . "', '" . pSQL($auditLogTime) . "')";
            if (Db::getInstance()->execute($auditLogSQL)) {
                /*
                 * Add log entry to Log File
                 */
                $handle = fopen(_PS_MODULE_DIR_ . 'kbreviewincentives/' . 'log/review_incentive.log', 'a+');
                $message = $auditMethodName . "\t\t" . $auditLogAction . "\t\t" . $auditLogTime . "\t\t" . $auditLogType . "\t\t" . $auditLogEntry . "\n";
                fwrite($handle, $message);
                fclose($handle);
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    /*
     * Function for returning the absolute path of the module directory
     */
    protected function getKbModuleDir()
    {
        return _PS_MODULE_DIR_.$this->name.'/';
    }
    
    /*
     * Function to create coupon
     */
    
    public function createCoupon($review_data)
    {
        $coupon_details = array();
        $coupon_details['code'] = $this->generateCouponCode();
        $is_used_partial = 1;
        $fixed_reduction = $review_data['incentive_amount'];
        $coupon_details['coupon_value'] = Tools::displayPrice($review_data['incentive_amount'], (int) $review_data['id_currency']);
        $percent_reduction = 0;
        $free_shipping = 0;
        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'velsof_incentive_coupon
                                    SET review_id = "' . (int) $review_data['review_id'] . '",code = "' . pSQL($coupon_details['code']) . '", 
                                    customer_id = ' . (int) $review_data['customer_id'] . ', 
                                    date_add = "' . pSQL(date('Y-m-d H:i:s')) . '", date_update = "' . pSQL(date('Y-m-d H:i:s')) . '"';
        Db::getInstance()->execute($sql);
        $coupon_name = 'KnowbandReviewIncentives';
        $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule  SET
                                    id_customer = ' . (int) $review_data['customer_id'] . ',
                                    date_from = "' . pSQL(date('Y-m-d H:i:s')) . '",
                                    date_to = "' . pSQL(date('Y-m-d 23:23:59', strtotime('1 day'))) . '",
                                    description = "' . pSQL($coupon_name) . '",
                                    quantity = 1, quantity_per_user = 1, priority = 1, partial_use = ' . (int) $is_used_partial . ',
                                    code = "' . pSQL($coupon_details['code']) . '", minimum_amount = 0, minimum_amount_tax = 0, 
                                    minimum_amount_currency = ' . (int) $review_data['id_currency'] . ', minimum_amount_shipping = 0,
                                    country_restriction = 0, carrier_restriction = 0, group_restriction = 0, cart_rule_restriction = 0, 
                                    product_restriction = 0, shop_restriction = 1, 
                                    free_shipping = ' . (int) $free_shipping . ',
                                    reduction_percent = ' . (int) $percent_reduction . ', reduction_amount = ' . (int) $fixed_reduction . ', 
                                    reduction_tax = 1, reduction_currency = ' . (int) $review_data['id_currency'] . ', 
                                    reduction_product = 0, gift_product = 0, gift_product_attribute = 0,
                                    highlight = 0, active = 1, 
                                    date_add = "' . pSQL(date('Y-m-d H:i:s')) . '", date_upd = "' . pSQL(date('Y-m-d H:i:s')) . '"';

        Db::getInstance()->execute($sql);

        $cart_rule_id = Db::getInstance()->Insert_ID();

        Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_shop
                    set id_cart_rule = ' . (int) $cart_rule_id . ', id_shop = ' . (int) $this->context->shop->id);
        $languages = Language::getLanguages(true);
        foreach ($languages as $lang) {
            Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_lang
                set id_cart_rule = ' . (int) $cart_rule_id . ', id_lang = ' . (int) $lang['id_lang'] . ', 
                name = "' . pSQL($coupon_name) . '"');
        }
        return $coupon_details;
    }
    
     /*
     * Function to get random coupons and contains a probability logic to 
     * select slice number and according to that slice number it returns coupon data
     * 
     * @params    Array return   Coupon data of selected slice number if coupon value is not zero
     */
    private function generateCouponCode()
    {
        $length = 8;
        $code = '';
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789';
        $maxlength = Tools::strlen($chars);
        if ($length > $maxlength) {
            $length = $maxlength;
        }
        $i = 0;
        while ($i < $length) {
            $char = Tools::substr($chars, mt_rand(0, $maxlength - 1), 1);
            if (!strstr($code, $char)) {
                $code .= $char;
                $i++;
            }
        }
        //
        // Check if coupon code alredy exist or not
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'cart_rule where code = "' . pSQL($code) . '"';
        $result = Db::getInstance()->executeS($sql);
        if (count($result) == 0) {
            return $code;
        }

        return $this->generateCouponCode();
    }
    /*
     * Function to check customer comes under incentive criteria or not
     */
    public function checkIncentiveCriteria($incentive_criteria, $review_data)
    {
        if ($incentive_criteria == 1) {   //Buyer
            $customer_id = $review_data['customer_id'];
            $product_id = $review_data['product_id'];
            $sql = "SELECT od.id_order "
                    . "FROM " . _DB_PREFIX_ . "order_detail od "
                    . "INNER JOIN " . _DB_PREFIX_ . "orders o "
                    . "WHERE o.id_customer = '" . (int) $customer_id . "' "
                    . "AND od.product_id = '" . (int) $product_id . "'";
            $order_delivered = Db::getInstance()->getRow($sql);
            if (!empty($order_delivered)) {
                return true;
            } else {
                return false;
            }
        } else if ($incentive_criteria == 2) {  //Any Customer
            $sql = "SELECT * FROM " . _DB_PREFIX_ . "customer WHERE id_customer = '" . (int) $review_data['customer_id'] . "'";
            $res = Db::getInstance()->getRow($sql);
            if (empty($res)) {
                return false;
            } else {
                return true;
            }
        }
    }
    
    /*
     * Function to files from one directory to other
     */

    protected function copyfolder($source, $destination)
    {
        $directory = opendir($source);
        mkdir($destination);
        while (($file = readdir($directory)) != false) {
            Tools::copy($source . '/' . $file, $destination . '/' . $file);
        }
        closedir($directory);
    }
}
