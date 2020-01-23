{if $current_status eq 1}
    <span class="label color_field" style="background-color:green;color:white">
        {l s='Approved' mod='kbreviewincentives'}
    </span>
{else if $current_status eq 0}
    <span class="label color_field" style="background-color:red;color:white">
        {l s='Disapproved' mod='kbreviewincentives'}
    </span>
{else if $current_status eq 3}
    <span class="label color_field" style="background-color:#4169E1;color:white">
        {l s='Pending' mod='kbreviewincentives'}
    </span>
{/if}

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