<div class="kb_custom_tabs kb_custom_panel">
    <span>
        <a class="kb_custom_tab {if $selected_nav == 'config'}kb_active{/if}" title="{l s='General Settings' mod='kbreviewincentives'}" id="kbcf_general_settings" href="{$admin_configure_controller nofilter}">{*Variable contains URL content, escape not required*}
            <i class="icon-gear"></i>
            {l s='General Settings' mod='kbreviewincentives'}
        </a>
    </span>

    <span>
        <a class="kb_custom_tab {if $selected_nav == 'reminder'}kb_active{/if}" title="{l s='Reminder Settings' mod='kbreviewincentives'}" id="kbcf_profile" href="{$reminder_profile_link nofilter}">{*Variable contains URL content, escape not required*}
            <i class="icon-envelope"></i>
            {l s='Reminder Settings' mod='kbreviewincentives'}
        </a>
    </span>

    <span>
        <a class="kb_custom_tab {if $selected_nav == 'exclude'}kb_active{/if}" title="{l s='Exclude Settings' mod='kbreviewincentives'}" id="kbcf_order" href="{$exclude_condition_link nofilter}">{*Variable contains URL content, escape not required*}
            <i class="icon-stop"></i>
            {l s='Exclude Settings' mod='kbreviewincentives'}
        </a>
    </span>
        
        <span>
        <a class="kb_custom_tab {if $selected_nav == 'reviews'}kb_active{/if}" title="{l s='Product Reviews' mod='kbreviewincentives'}" id="kbcf_profile" href="{$product_review_link nofilter}">{*Variable contains URL content, escape not required*}
            <i class="icon-wrench"></i>
            {l s='Product Reviews' mod='kbreviewincentives'}
        </a>
    </span>

    <span>
        <a class="kb_custom_tab {if $selected_nav == 'Reports'}kb_active{/if}" title="{l s='Reports' mod='kbreviewincentives'}" id="kbcf_order" href="{$review_report_link nofilter}">{*Variable contains URL content, escape not required*}
            <i class="icon-ticket"></i>
            {l s='Reports' mod='kbreviewincentives'}
        </a>
    </span>
</div>

        <script>
            var check_for_all = "{l s='Kindly check for all available langauges.' mod='kbreviewincentives'}";
        </script>
            
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
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin tpl file
*}