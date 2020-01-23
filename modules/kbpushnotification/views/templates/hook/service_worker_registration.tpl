<script data-keepinline>
    var kb_display_custom_notif = 0;
    {if !empty($welcome_setting) && isset($welcome_setting['enable'])}
        {if $welcome_setting['enable']}
    kb_display_custom_notif = 1;
        {/if}
    {/if}
    var dashboard_worker = '{$dashboard_worker}{*escape not required as contain URL*}';
    var kb_service_worker_front_url = '{$kb_service_worker_front_url|escape:'quotes':'UTF-8'}';
    var kb_registed_success = "{l s='Registered Successfully' mod='kbpushnotification'}";
    var kb_registed_error = "{l s='Error in registrated as admin' mod='kbpushnotification'}";
</script>
{if !empty($welcome_setting) && isset($welcome_setting['enable'])}
    {if $welcome_setting['enable']}
        <div class="kb-cs-notify-container" style="display:none;">
            <div class="kb-cs-notify-overlay"></div>
            <div class="kb-cs-notify-block">
                <div class="kb-cs-notify-box"> 
                    {if !empty($welcome_setting) && isset($welcome_setting['display_logo']) && $welcome_setting['display_logo']}
                        <div class="kb-cs-notify-img">
                            <img width="65" height="65" style="border-radius:5px;" src="{$welcome_setting['logo']|escape:'quotes':'UTF-8'}">
                        </div>

                    {/if}
                    <div class="kb-cs-notify-content">
                        <div class="kb-cs-notify-subcontent">
                            {if !empty($welcome_setting) && isset($welcome_setting['action_message'][$id_lang])}
                                {$welcome_setting['action_message'][$id_lang]|escape:'htmlall':'UTF-8'}
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="kb-cs-notify-btn-block">
                    <span id="kb-cs-msg" style="color:red;font-size:12px;"></span>
                    <a class=" kb-cs-notify-disapprove" onclick="" href="javascript:void(0);">
                        {if !empty($welcome_setting) && isset($welcome_setting['action_cancel_text'][$id_lang])}
                            {$welcome_setting['action_cancel_text'][$id_lang]|escape:'htmlall':'UTF-8'}
                        {/if}
                    </a>
                    <a class="btn btn-primary kb-cs-notify-approve" href="javascript:void(0);">
                        {if !empty($welcome_setting) && isset($welcome_setting['action_btn_text'][$id_lang])}
                            {$welcome_setting['action_btn_text'][$id_lang]|escape:'htmlall':'UTF-8'}
                        {/if}
                    </a>
                </div>
            </div>
        </div>
    {/if}
{/if}
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2018 Knowband
* @license   see file: LICENSE.txt
*}