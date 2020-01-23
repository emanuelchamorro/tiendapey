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

class KbreviewincentivesCronModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        $kbrc_obj = new Kbreviewincentives();
        if (Tools::getValue('secure_key')) { //get secure key
            $secure_key = Configuration::get('KBRC_SECURE_KEY');
            if ($secure_key == Tools::getValue('secure_key')) {
                $module_config = Tools::unSerialize(Configuration::get('KBRC_PRODUCT_REVIEW_INCENTIVES'));
                if (isset($module_config['enable']) && $module_config['enable'] == 1) { // check module is enable or not
                    $email_count = 0;
                    $this->checkCurrentOrderStatus();
                    //To check scheduled reminder time and send email
                    $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_review_reminder_schedule WHERE is_send = '0'";
                    $reminder_data = Db::getInstance()->executeS($sql);
                    foreach ($reminder_data as $reminder) {
                        if (strtotime(date('Y-m-d', strtotime($reminder['schedule_date']))) <= strtotime(date('Y-m-d'))) {
                            $template_data = array();
                            $template_data['subject'] = Tools::htmlentitiesDecodeUTF8($reminder['email_subject']);
                            $template_data['text_content'] = Tools::htmlentitiesDecodeUTF8($reminder['text_content']);
                            $template_data['body'] = Tools::htmlentitiesDecodeUTF8($reminder['body']);
                            $product_ids = array();
                            $product_ids = Tools::unserialize($reminder['product_id']);
                            //send email to customer
                            if ($this->sendNotificationEmail($template_data, $reminder['id_lang'], $product_ids, 'reminder_temp', $reminder['customer_id'])) {
                                $email_count++;
                                $customer = new Customer($reminder['customer_id']);
                                $this->module->addLogEntry('Success', 'Reminder Email has been sent.', 'A reminder has been sent to ' . $customer->firstname . '' . $customer->lastname . ' having email id ' . $customer->email, 'sendNotificationEmail()', '');
                                $sql = "UPDATE " . _DB_PREFIX_ . "velsof_review_reminder_schedule SET is_send = 1 WHERE reminder_id = '" . (int) $reminder['reminder_id'] . "'";
                                Db::getInstance()->execute($sql);
                            } else {
                                $customer = new Customer($reminder['customer_id']);
                                $this->module->addLogEntry('Error', 'Reminder Email could not send.', 'Reminder could not send to ' . $customer->firstname . '' . $customer->lastname . ' having email id ' . $customer->email, 'sendNotificationEmail()', '');
                                echo $this->module->l('Some problem ocurred. Email could not be sent.', 'cron');
                            }
                        }
                    }
                    echo $this->module->l('Cron has been executed successfully.', 'cron');
                    echo $this->module->l('Total number of sent email is .', 'cron').''.$email_count;
                    die;
                }
            } else {
                echo $this->module->l('You are not authorized to access this page', 'cron');
                die;
            }
        } else {
            echo $this->module->l('You are not authorized to access this page', 'cron');
            die;
        }
    }

    /*
     * Function to check current order status
     */
    public function checkCurrentOrderStatus()
    {
        //Check order status, calculate days and create a schedule
        $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_order_status_check";
        $order_check_data = Db::getInstance()->executeS($sql);
        foreach ($order_check_data as $order_check) {
            $order_status = array();
            $order_status = Tools::unSerialize($order_check['order_status']);
            $sql = "SELECT current_state FROM " . _DB_PREFIX_ . "orders WHERE id_order = '" . (int) $order_check['id_order'] . "'";
            $order_state = Db::getInstance()->getRow($sql);
            if (in_array($order_state['current_state'], $order_status)) {
                $sql = "SELECT * FROM " . _DB_PREFIX_ . "velsof_reminder_profile rp INNER JOIN " . _DB_PREFIX_ . "velsof_reminder_profile_templates rpt ON "
                        . " rp.reminder_profile_id = rpt.reminder_profile_id AND id_lang = '" . (int) $this->context->language->id . "'"
                        . " AND id_shop = '" . (int) $this->context->shop->id . "' AND rp.reminder_profile_id = '" . (int) $order_check['reminder_profile_id'] . "'  ORDER BY rp.no_of_days_after";
                $reminder = Db::getInstance()->getRow($sql);
                $schedule_type = 'Reminder';
                $schedule_date = date('Y-m-d H:i:s', strtotime('+' . $reminder['no_of_days_after'] . ' days'));
                $products = $order_check['product_id'];
                $sql = "INSERT INTO " . _DB_PREFIX_ . "velsof_review_reminder_schedule VALUES('','" . (int) $this->context->customer->id . "', '" . pSQL($products) . "', '" . (int) $order_check['id_order'] . "', '" . pSQL($schedule_type) . "',"
                        . " '" . pSQL($schedule_date) . "', '" . pSQL($reminder['subject']) . "', '" . pSQL($reminder['text_content']) . "',"
                        . " '" . pSQL($reminder['body']) . "', '" . (int) $this->context->language->id . "', '" . (int) $this->context->shop->id . "','0', now(), now())";
                $res = Db::getInstance()->execute($sql);
                if ($res) {
                    $order = new Order($order_check['id_order']);
                    $this->module->addLogEntry('Success', 'A new Reminder is scheduled.', 'A new reminder is scheduled for order reference ' . $order->reference, 'checkCurrentOrderStatus()', '');
                }
                $sql = "DELETE FROM " . _DB_PREFIX_ . "velsof_order_status_check WHERE id_order_check = '" . (int) $order_check['id_order_check'] . "'";
                Db::getInstance()->execute($sql);
            }
        }
    }

    /*
     * Function to send email to admin and customers
     */
    public function sendNotificationEmail($template_data, $lang_id, $product_ids, $email_template, $customer_id)
    {
        $directory = $this->getTemplateDir();
        $template_data = $this->module->replaceEmailImage($template_data);
        $link_obj = new Link();
        $link = $this->context->link->getModuleLink(
            'kbreviewincentives',
            'kbwritenewreview'
        );
        $product_data = array();
        foreach ($product_ids as $value) {
            $id_image = Product::getCover($value);
            $pro_obj = new Product((int) $value);
            $image = new Image($id_image['id_image']);
            $img_path = $this->getImgDirUrl() . _THEME_PROD_DIR_ . $image->getExistingImgPath() . '.jpg';
            $product_data[$value]['image'] = $img_path;
            $product_data[$value]['name'] = $pro_obj->name[(int) $this->context->language->id];
            $product_data[$value]['link'] = $link . '?product_id=' . $value;
            $product_data[$value]['price'] = Tools::displayPrice((float) $pro_obj->price);
        }
        $this->context->smarty->assign('product_data', $product_data);
        $product_html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/email_templates/product_review_link.tpl');
        $template_data['body'] = str_replace('{product_content}', $product_html, $template_data['body']);
        if (is_writable($directory)) {
            $html_template = $email_template . '.html';
            $txt_template = $email_template . '.txt';

            $base_html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/base_email_temp.tpl');

            $template_html = str_replace('{template_content}', $template_data['body'], $base_html);

            $file = fopen($directory . $html_template, 'w+');
            fwrite($file, $template_html);
            fclose($file);

            $file = fopen($directory . $txt_template, 'w+');
            fwrite($file, strip_tags($template_html));
            fclose($file);

            $customer = new Customer((int) $customer_id);
            $shop_url_obj = new ShopUrl($this->context->shop->id);
            $shop_url = $shop_url_obj->getUrl((bool) Configuration::get('PS_SSL_ENABLED'));
            switch ($email_template) {
                case 'reminder_temp':
                    $template_vars = array(
                        '{customer_name}' => $customer->firstname . ' ' . $customer->lastname,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                        '{shop_email}' => Configuration::get('PS_SHOP_EMAIL'),
                        '{shop_url}' => $shop_url
                    );
                    break;
            }
            unset($link_obj);

            $is_mail_send = Mail::Send(
                $lang_id,
                $email_template,
                $template_data['subject'],
                $template_vars,
                $customer->email,
                $customer->firstname.' '.$customer->lastname,
                Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('PS_SHOP_NAME'),
                null,
                null,
                _PS_MODULE_DIR_ . $this->module->name.'/mails/',
                false,
                $this->context->shop->id
            );
            return $is_mail_send;
        } else {
            return false;
        }
    }
    /*
     * Function to get email template directory path
     */
    protected function getTemplateDir()
    {
        $lang_id = Configuration::get('PS_LANG_DEFAULT');
        $iso = Language::getIsoById((int) $lang_id);
        return _PS_MODULE_DIR_ . $this->module->name . '/mails/' . $iso . '/';
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
