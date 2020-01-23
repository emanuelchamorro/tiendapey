<div class="kb-push-buttons-form">
{*    {$form_data->action_button1|print_r}*}
    <div class="form-group kb-push-btn-bck" style="{if !empty($form_data) && !empty($form_data->action_button_link1)}display:block;{else}display:none{/if}">
        <label class="control-label col-lg-3"></label>
        <div class="col-lg-9">
            <div class="col-lg-4">
                <label class="control-label required" style="margin-bottom:5px;">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title data-original-title="{l s='Enter the name of button which will be displayed in Push Notification' mod='kbpushnotification'}">
                        {l s='Action Button 1' mod='kbpushnotification'}
                    </span>
                </label>
                <div class="col-lg-9">
                    {if $languages|count > 1}
                        <div class="form-group">
                        {/if}
                        {foreach $languages as $language}
                            {if $languages|count > 1}
                                <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $id_lang}style="display:none"{/if}>
                                    <div class="col-lg-9">
                                    {/if}
                                    <input type="text" name="action_button1_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                           class="kb_action_btn_text" value="{if !empty($form_data) && !empty($form_data->action_button1[$language.id_lang])}{$form_data->action_button1[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}" onkeyup="if (isArrowKey(event))
                                                       return;
                                                   updateFriendlyURL();" required="required">

                                    {if $languages|count > 1}
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                            {$language.iso_code|escape:'htmlall':'UTF-8'}
                                            <i class="icon-caret-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            {foreach from=$languages item=language}
                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                                                {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {/if}

                        {/foreach}
                        {if $languages|count > 1}
                        </div>
                    {/if}
                </div>
                {*                    <input type="text" name="action_button1" id="action_button1" class="kb_action_btn_text" value="{if !empty($kb_form)}{$kb_form['action_button1']}{/if}">*}
            </div>
            <div class="col-lg-4">
                <label class="control-label required" style="margin-bottom:5px;">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title data-original-title="{l s='Enter the link to be opened when click on the button which will be displayed in Push Notification' mod='kbpushnotification'}">
                        {l s='Action Button Link 1' mod='kbpushnotification'}
                    </span>
                </label>
                <input type="text" name="action_button_link1" id="action_button_link1" class="kb_action_btn_link" value="{if !empty($form_data) && !empty($form_data->action_button_link1)}{$form_data->action_button_link1|escape:'htmlall':'UTF-8'}{/if}">
            </div>
        </div>
    </div>
    <div class="form-group kb-push-btn-bck" style="{if !empty($form_data) && !empty($form_data->action_button_link2)}display:block;{else}display:none{/if}">
        <label class="control-label col-lg-3"></label>
        <div class="col-lg-9">
            <div class="col-lg-4">
                <label class="control-label required" style="margin-bottom:5px;">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title data-original-title="{l s='Enter the name of button which will be displayed in Push Notification' mod='kbpushnotification'}">
                        {l s='Action Button 2' mod='kbpushnotification'}
                    </span>
                </label>
                <div class="col-lg-9">
                    {if $languages|count > 1}
                        <div class="form-group">
                        {/if}
                        {foreach $languages as $language}
                            {if $languages|count > 1}
                                <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $id_lang}style="display:none"{/if}>
                                    <div class="col-lg-9">
                                    {/if}
                                    <input type="text" name="action_button2_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                           class="kb_action_btn_text" value="{if !empty($form_data) && !empty($form_data->action_button2[$language.id_lang])}{$form_data->action_button2[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}" onkeyup="if (isArrowKey(event))
                                                       return;
                                                   updateFriendlyURL();" required="required">

                                    {if $languages|count > 1}
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                            {$language.iso_code|escape:'htmlall':'UTF-8'}
                                            <i class="icon-caret-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            {foreach from=$languages item=language}
                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                                                {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {/if}

                        {/foreach}
                        {if $languages|count > 1}
                        </div>
                    {/if}
                </div>
            </div>
            <div class="col-lg-4">
                <label class="control-label required" style="margin-bottom:5px;">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title data-original-title="{l s='Enter the link to be opened when click on the button which will be displayed in Push Notification' mod='kbpushnotification'}">
                        {l s='Action Button Link 2' mod='kbpushnotification'}
                    </span>
                </label>
                <input type="text" name="action_button_link2" id="action_button_link2" class="kb_action_btn_link" value="{if !empty($form_data) && !empty($form_data->action_button_link2)}{$form_data->action_button_link2|escape:'htmlall':'UTF-8'}{/if}">
            </div>
        </div>
    </div>
    <div class="form-group kb-push-add-btn-block">
        <label class="control-label col-lg-3"></label>
        <div class="col-lg-9">
            <button type="button" class="btn btn-primary kb-push-add" style="display: none;">{l s='Add Button' mod='kbpushnotification'}</button>
            <button type="button" class="btn btn-warning kb-push-remove" style="display: none;">{l s='Remove Button' mod='kbpushnotification'}</button>
        </div>
    </div>
</div>
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