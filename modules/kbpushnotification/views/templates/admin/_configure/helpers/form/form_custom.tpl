{extends file='helpers/form/form.tpl'}

{block name='defaultForm'}
    <script>
        var ajax_action = "{$ajax_action}";{*Variable contains URL content, escape not required*}
    </script>
    <div class='row'>
        <div class='productTabs col-lg-2 col-md-3'>
            <div class='list-group'>
                {$i=1}
                {foreach $module_tabs key=numStep item=tab}
                    <a class='list-group-item {if $tab.selected|escape:'htmlall':'UTF-8'}active{/if}' id='link-{$tab.id|escape:'htmlall':'UTF-8'}' onclick='switchModuleTabs(this, {$i|escape:'htmlall':'UTF-8'});'>
                        <i class="{$tab.icon|escape:'htmlall':'UTF-8'}"></i>{$tab.name|escape:'htmlall':'UTF-8'}
                        <i class="icon-exclamation-circle" style="display:none;position: absolute;right: 0;"></i>
                    </a>
                    {$i=$i+1}
                {/foreach}
            </div>
        </div>
        {$form} {*Variable contains html content, escape not required*} 
        {$form1} {*Variable contains html content, escape not required*} 
        {$form2} {*Variable contains html content, escape not required*} 
    </div>
{/block}

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

