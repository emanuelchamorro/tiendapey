<div class='productTabs col-lg-2 col-md-3'>
            <div class='list-group'>
                {$i=1}
                {foreach $module_tabs key=numStep item=tab}
                    <a class='list-group-item {if $tab.selected}active{/if}' id='link-{$tab.id}' onclick='switchModuleTabs(this, {$i});'>
                        <i class="{$tab.icon}"></i> {$tab.name}
                        <i class="icon-exclamation-circle" style="display:none;"></i>
                    </a>
                    {$i=$i+1}
                {/foreach}
            </div>
        </div>
            <div class="alert alert-info col-lg-10">
                {l s='Please note : All products and categories which will be selected here, will not be eligible for incentives and reminder will not be sent for review to customer who purchases these products.' mod='kbreviewincentives'}
            </div>
            <div id='velsof_review_incentive_category' class='col-lg-10 col-md-9'>
                {$form1 nofilter}{*Variable contains html content, escape not required*}
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
* @copyright 2016 knowband
* @license   see file: LICENSE.txt
*}    