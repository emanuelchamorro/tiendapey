<div class="bootstrap panel col-lg-12 col-md-9" id="cron_instructions"><h3>{l s='Cron Configuration' mod='kbreviewincentives'}</h3>
    {l s='Add the cron to your store via control panel/putty to send reminders automatically.' mod='kbreviewincentives'}
    <br><br><b>{l s='URLs to Add to Cron via Control Panel' mod='kbreviewincentives'}</b>
    <br>{$cron_url nofilter}{*Variable contains url, escape not required*}
    <br><br><b>{l s='Cron setup via SSH' mod='kbreviewincentives'}</b>
    <br>0 22 * * * wget -O /dev/null {$cron_url nofilter}{*Variable contains url, escape not required*}
</div>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin tpl file
*}