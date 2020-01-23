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

require_once dirname(__FILE__).'/AdminKbPushCoreController.php';
//Include Class to inherit some common functions and callbacks
require_once(_PS_MODULE_DIR_ . 'kbpushnotification/classes/KbPushSubscribers.php');


class AdminKbPushStatisticsController extends AdminKbPushCoreController
{

    //Class Constructor
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;

        parent::__construct();
        $this->toolbar_title = $this->module->l('Knowband Web Push Statistics', 'AdminKbPushStatisticsController');
        $this->display = 'view';
    }

    //Set JS and CSS
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJS($this->getModuleDirUrl() . $this->module->name . '/views/js/admin/morries/morris.min.js');
        $this->addCSS($this->getModuleDirUrl() . $this->module->name . '/views/css/admin/morris.css');
        $this->addJS($this->getModuleDirUrl() . $this->module->name . '/views/js/admin/raphael.min.js');
        $this->addJQueryPlugin('flot');
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = $this->module->l('Knowband Web Push Statistics', 'AdminKbPushStatisticsController');
        parent::initPageHeaderToolbar();
    }

    public function renderView()
    {
        $this->context->smarty->assign(
            array(
                'controller_path' => $this->context->link->getAdminLink('AdminKbPushStatistics'),
                'loader' => $this->getModuleDirUrl().$this->module->name.'/views/img/loader.gif',
            )
        );
//        d($this->getRecordData());
        $record_data = $this->getRecordData();
        
        $kpi_details = $this->renderKPIData($record_data);
        
//        $tpl = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/list/list.tpl');
        
        $large_kpi_icon = 'no';
        if (version_compare(_PS_VERSION_, '1.6.1.1', '<') && version_compare(_PS_VERSION_, '1.6.0.1', '>')) {
            //Large icon of KPI
            $large_kpi_icon = 'yes';
        }

        $this->context->smarty->assign(array(
            'kpi_details' => $kpi_details,
            'large_kpi_icon' => $large_kpi_icon,
            'total_subscribers' => $record_data['allSubscribers'],
            'chrome_subscribers' => $record_data['chromeSubscribers'],
            'firefox_subscribers' => $record_data['firfoxSubscribers'],
            'module_path' => $this->context->link->getAdminLink('AdminKbPushStatistics', true),
            'loader' => $this->getModuleDirUrl().$this->module->name.'/views/img/loader.gif',
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name.'/views/templates/admin/statistics.tpl');
    }
    
    /*
     * function to render KPI data 
     * return KPI
     */
    public function renderKPIData($data)
    {
        $kpis = array();
        $helper_kpi = new HelperKpi();
        $helper_kpi->id = 'total-subscribers';
        $helper_kpi->icon = 'icon-users';
        $helper_kpi->color = 'color3';
        $helper_kpi->title = $this->module->l('Total Subscribers', 'AdminKbPushStatisticsController');
        $helper_kpi->value = $data['allSubscribers'];
        $kpis[] = $helper_kpi->generate();
        $helper_kpi->id = 'total-campaign';
        $helper_kpi->icon = 'icon-bullhorn';
        $helper_kpi->color = 'color1';
        $helper_kpi->title = $this->module->l('Total Campaigns', 'AdminKbPushStatisticsController');
        $helper_kpi->value = $data['allCampaings'];
        $kpis[] = $helper_kpi->generate();
        $helper_kpi->id = 'total-desktop-user';
        $helper_kpi->icon = 'icon-desktop';
        $helper_kpi->color = 'color2';
        $helper_kpi->title = $this->module->l('Total Desktop Subscribers', 'AdminKbPushStatisticsController');
        $helper_kpi->value = $data['desktopSubscribers'];
        $kpis[] = $helper_kpi->generate();
        $helper_kpi->id = 'total-mobile-user';
        $helper_kpi->icon = 'icon-mobile';
        $helper_kpi->color = 'color4';
        $helper_kpi->title = $this->module->l('Total Mobile Subscribers', 'AdminKbPushStatisticsController');
        $helper_kpi->value = $data['mobileSubscribers'];
        $kpis[] = $helper_kpi->generate();
        $helper = new HelperKpiRow();
        $helper->kpis = $kpis;
        return $helper->generate();
    }
    
    /*
     * function to get all statistics data of push notifications
     * 
     * return array
     */
    public function getRecordData()
    {
        $allCampaigns = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_pushes');
        $allSubscribers = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_subscribers');
        $desktopSubscribers = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_subscribers where device="Desktop"');
        $mobileSubscribers = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_subscribers where device="Mobile"');
        $chromeSubscribers = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_subscribers where browser="Chrome"');
        $firfoxSubscribers = Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'kb_web_push_subscribers where browser="Firefox"');
        $data = array(
            'allCampaings' => $allCampaigns,
            'allSubscribers' => $allSubscribers,
            'desktopSubscribers' => $desktopSubscribers,
            'mobileSubscribers' => $mobileSubscribers,
            'chromeSubscribers' => $chromeSubscribers,
            'firfoxSubscribers' => $firfoxSubscribers,
        );
        
        return $data;
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('ajax')) {
            if (Tools::isSubmit('getChart')) {
                $start_date = Tools::getValue('start');
                $end_date = Tools::getValue('end');
                $groupby = Tools::getValue('groupby');
                $json = $this->displayChartData($start_date, $end_date, $groupby);
                $data = array();
                $data['graph'] = $json['data_graph'];
                header('Content-Type: application/json', true);
                echo Tools::jsonEncode($data);
                die;
            }
            die;
        }
        return parent::postProcess();
    }
    
    /*
     * function to get bar graph data based on the filters
     * 
     * @param from date, to date and group by
     * @return array
     */
    public function displayChartData($from, $to, $groupby)
    {
        $range = '';
        if ($groupby == 'days') {
            $range = 'day';
        } elseif ($groupby == 'years') {
            $range = 'year';
        } elseif ($groupby == 'months') {
            $range = 'month';
        }
        $orderDataArray = array();
        $filter_string = '';
        $ticks = array();
        switch ($range) {
            case 'day':
                $ticks = array();
                $filter_string = ' and date(date_add) >="' . pSQL($from) . '" 
					and date(date_add) <="' . pSQL($to) . '" group by YEAR(date_add), MONTH(date_add), DAY(date_add)';
                $push_records = $this->getPushBeasedOnFilters($filter_string);
                $subscriber_record = $this->getSubscribersBeasedOnFilters($filter_string);
                $push_recordArray = array();
                $found = false;
                $date_diff = abs(strtotime($to) - strtotime($from));
                $days = (int) floor($date_diff / (60 * 60 * 24));
                for ($i = $days; $i >= 0; $i--) {
                    $date = date("Y-m-d", strtotime("- " . $i . " days", strtotime($to)));
                    foreach ($push_records as $push_record) {
                        if ($date == date("Y-m-d", strtotime($push_record['date_add']))) {
                            $push_recordArray['push_totals'] = $push_record['push_count'];
                            $push_recordArray['subscription_totals'] = $subscriber_record;
                            $push_recordArray['time'] = date("d-M-Y", strtotime($date));
                            $orderDataArray[$date] = $push_recordArray;
                            $found = true;
                        }
                    }
                    if ($found == false) {
                        $orderDataArray[$date] = array("push_totals" => 0, "subscription_totals" => 0, 'time' => date("d-M-Y", strtotime($date)));
                    }
                    $ticks[] = date("d M", strtotime($date));
                }
                $data = $orderDataArray;
                break;

            case 'month':
                $filter_string = 'and date(date_add) >="' . pSQL($from) . '" AND date(date_add) <= "' .
                        pSQL($to) . '" group by YEAR(date_add),MONTH(date_add)';
                $push_records = $this->getPushBeasedOnFilters($filter_string);
                $subscriber_record = $this->getSubscribersBeasedOnFilters($filter_string);
                $push_recordArray = array();
                $found = false;
                $start_date_month = (int) date("m", strtotime($from));
                $end_date_month = (int) date("m", strtotime($to));
                $start_date_year = (int) date("Y", strtotime($from));
                $end_date_year = (int) date("Y", strtotime($to));

                $date_arr = array();
                for ($i = $start_date_year; $i <= $end_date_year; $i++) {
                    if ($i == $start_date_year) {
                        $k = $start_date_month;
                    } else {
                        $k = 1;
                    }

                    if ($i == $end_date_year) {
                        $l = $end_date_month;
                    } else {
                        $l = 12;
                    }

                    for ($j = $k; $j <= $l; $j++) {
                        $date_arr[] = date("Y-m", strtotime($i . "-" . $j));
                    }
                }
                foreach ($date_arr as $date) {
                    $found = false;
                    $ticks = array();
                    foreach ($push_records as $push_record) {
                        if ($date == date("Y-m", strtotime($push_record['date_add']))) {
                            $push_recordArray['push_totals'] = $push_record['push_count'];
                            $push_recordArray['subscription_totals'] = $subscriber_record;
                            $push_recordArray['time'] = date("M-Y", strtotime($date));
                            $orderDataArray[$date] = $push_recordArray;
                            $found = true;
                        }
                    }
                    if ($found == false) {
                        $orderDataArray[$date] = array("push_totals" => 0, "subscription_totals" => 0, 'time' => date("M-Y", strtotime($date)));
                    }
                }
                    $data = $orderDataArray;
                break;
            case 'year':
                $filter_string = 'and  date(date_add) >= "' . pSQL($from) . '" 
					and date(date_add) <="' . pSQL($to) . '" group by YEAR(date_add)';
                $push_records = $this->getPushBeasedOnFilters($filter_string);
                $subscriber_record = $this->getSubscribersBeasedOnFilters($filter_string);
                $start_date_year = (int) date("Y", strtotime($from));
                $end_date_year = (int) date("Y", strtotime($to));

                $date_arr = array();
                for ($i = $start_date_year; $i <= $end_date_year; $i++) {
                    $date_arr[] = (string) $i;
                }
                $ticks = array();
                $push_recordArray = array();
                foreach ($date_arr as $date) {
                    $found = false;
                    foreach ($push_records as $push_record) {
                        if ($date == date("Y", strtotime($push_record['date_add']))) {
                             $push_recordArray['push_totals'] = $push_record['push_count'];
                            $push_recordArray['subscription_totals'] = $subscriber_record;
                            $push_recordArray['time'] = $date;
                            $orderDataArray[$date] = $push_recordArray;
                            $found = true;
                        }
                    }
                    if ($found == false) {
                        $orderDataArray[$date] = array("push_totals" => 0, "subscription_totals" => 0, 'time' => $date);
                    }
                    $ticks[] = $date;
                }
//                $ticks;
                $data = $orderDataArray;
                break;
        }
        $data = $orderDataArray;
        $item = array();
        $item['data_graph'] = $orderDataArray;
        return $item;
    }
    
    /*
     * function to get push count based on the filters
     * 
     * @return array
     */
    public function getPushBeasedOnFilters($filters_string = '')
    {
        $query = 'SELECT count(id_push) as push_count,date_add FROM '._DB_PREFIX_.'kb_web_push_pushes p where 1 '.$filters_string;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }
    
    /*
     * function to get subscriber count based on the filters
     * 
     * @return array
     */
    public function getSubscribersBeasedOnFilters($filters_string = '')
    {
        $query = 'SELECT count(id_subscriber) as subscriber_count FROM '._DB_PREFIX_.'kb_web_push_subscribers p where 1 '.$filters_string;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }
}
