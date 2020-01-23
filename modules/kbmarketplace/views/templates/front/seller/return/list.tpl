<script type="text/javascript" src='{$tiny_mce_js_file}' ></script>{*Variable contains css and html content, escape not required*}
<script>
    var kb_editor_lang = "{$editor_lang}";
    var empty_comment = "{l s='No Comments added by customer.' mod='kbmarketplace'}";
    var return_approve_success = "{l s='Return Request approved Successfully.' mod='kbmarketplace'}";
    var return_disapprove_success = "{l s='Return Request disapproved successfully..' mod='kbmarketplace'}";
    var return_status_update_success = "{l s='Return status updated successfully.' mod='kbmarketplace'}";
    var return_complete_success = "{l s='Return Request marked as complete successfully..' mod='kbmarketplace'}";
    var notification_title = "{l s='Notification.' mod='kbmarketplace'}";
</script>
<style>
    .kb-modal {
    position: absolute;
    top: 0%;
    /* left: 6%; */
    z-index: 99999;
    background-color: #fff;
    border: 1px solid #777;
    width: 90%;
}
.kb-modal-header .kb-modal-close {
     margin-top: -4%;
}
    </style>
<div class="kb-content">
    <div class="kb-content-header">
        <h1>{l s='Return List' mod='kbmarketplace'}</h1>
        <div class="clearfix"></div>
    </div>
    
    {if isset($kbfilter)}
        {$kbfilter nofilter} {* Variable contains HTML/CSS/JSON, escape not required *}

    {/if}
    
    {if isset($kbmutiaction)}
        {$kbmutiaction nofilter} {* Variable contains HTML/CSS/JSON, escape not required *}

    {/if}
    
    {if isset($kblist)}
        <div class="kb-vspacer5"></div>
        {$kblist nofilter} {* Variable contains HTML/CSS/JSON, escape not required *}

    {/if}
</div>
{* Return approve modal *}
    <div id="kb-return_approve-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    {*<div id="return_approve-loader" class="kb-modal loading-block" style="left:0%;"><div class="loader128"></div></div>*}
    <div id="return_approve-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_approve_form_title'>{l s='Are You sure.You want to approve this return request ?' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_approve-modal-form">X</span>
</div>
        <div class="kb-modal-footer" style="text-align: center;">
            <div id="return_approve-updating-progress" class="input-loader" style="display: none; vertical-align: middle;"></div>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success" id="rm_yes_approve" name="rm_yes_approve" data-id="0">{l s='Approve' mod='kbmarketplace'}</button>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success cancel-button" id="rm_yes_cancel" data-modal="kb-return_approve-modal-form" name="rm_yes_cancel" data-id="0">{l s='Cancel' mod='kbmarketplace'}</button>
        </div>
        </div>
        </div>
{* approve moda over *}


{* return deny modal *}
  <div id="kb-return_deny-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    {*<div id="return_approv-loader" class="kb-modal loading-block" style="left:0%;"><div class="loader128"></div></div>*}
    <div id="return_deny-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_deny_form_title'>{l s='Are You sure, You want to disapprove this return request ?' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_deny-modal-form">X</span>
</div>
        <div class="kb-modal-footer" style="text-align: center;">
            <div id="return_deny-updating-progress" class="input-loader" style="display: none; vertical-align: middle;"></div>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success" id="rm_yes_deny" name="rm_yes_deny" data-id="0">{l s='Deny' mod='kbmarketplace'}</button>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success cancel-button" id="rm_yes_cancel" data-modal="kb-return_deny-modal-form" name="rm_yes_cancel" data-id="0">{l s='Cancel' mod='kbmarketplace'}</button>
        </div>
        </div>
        </div>
{* chnages over *}
{* Complete return *}
    <div id="kb-return_complete-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    <div id="return_complete-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_complete_form_title'>{l s='Are You sure, You want to mark this return request complete ?' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_complete-modal-form">X</span>
        </div>
        <div class="kb-modal-footer" style="text-align: center;">
            <div id="return_complete-updating-progress" class="input-loader" style="display: none; vertical-align: middle;"></div>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success" id="rm_yes_complete" name="rm_yes_complete" data-id="0">{l s='Mark as Complete' mod='kbmarketplace'}</button>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success cancel-button" id="rm_yes_cancel" data-modal="kb-return_complete-modal-form" name="rm_yes_cancel" data-id="0">{l s='Cancel' mod='kbmarketplace'}</button>
        </div>
        </div>
        </div>
{* changes over *}
{* update return status modal *}
<div id="kb-return_change_status-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    <div id="return_change_status-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_change_status_form_title'>{l s='Change Return Status' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_change_status-modal-form">X</span>
        </div>
            <div class="kb-modal-content">
            <div id="new-return_approve-form-msg" class="kbalert"></div>
            <div id="new-return_approve-form" class="new_return_approve_form kb-form" style="padding:0;">
                <ul class="kb-form-list">
                    <li class="kb-form-fwidth">
                        <div class="kb-form-label-block">
                            <span class="kblabel ">{l s='Choose Return Status' mod='kbmarketplace'}</span>
                        </div>
                        <div class="kb-form-field-block">
                            <select id="rm_change_return_status" name="rm_change_return_status" class="form-control select2-hidden-accessible" data-toggle="select2" tabindex="-1" aria-hidden="true">
                               {foreach $status_return as $key => $status_data}
                                    <option value="{$status_data['return_data_id']|intval}">{$status_data['value']|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                           </select>
                        </div>
                    </li>
                </ul>
            </div>
                
            </div>
            
        <div class="kb-modal-footer" style="text-align: center;">
            <div id="return_change_status-updating-progress" class="input-loader" style="display: none; vertical-align: middle;"></div>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success" id="rm_yes_change_status" name="rm_yes_change_status" data-id="0">{l s='Update Status' mod='kbmarketplace'}</button>
            <button type="button" style="margin: 2%;" class="kbbtn-big kbbtn-success cancel-button" id="rm_yes_cancel" data-modal="kb-return_change_status-modal-form" name="rm_yes_cancel" data-id="0">{l s='Cancel' mod='kbmarketplace'}</button>
        </div>
        </div>
        </div>

{* changes over *}

{* return history modal *}
<div class="modal fade" id="rm_return_history_modal"  tab-index="-1" aria-hidden="true" aria-labelledby="modal-remove">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='kbmarketplace'}</span></button>
                <h4 class="modal-title velsof_modal_title" id="modal-policy" >{l s='Return History' mod='kbmarketplace'}</h4>
            </div>
            <div class="modal-body" id='rm_return_history'></div>
            <div class="modal-footer">
                <button type="button" onclick="rmCloseModal('rm_return_history_modal')"  class="btn btn-warning">{l s='Close' mod='kbmarketplace'}</button>
            </div>
        </div>
    </div>
</div>
                
{* return commentts modal *}
<div id="kb-return_comment-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    <div id="return_comment-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_comment_form_title'>{l s='Return Reason' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_comment-modal-form">X</span>
    </div>
    <div class="kb-modal-footer" style="text-align: center;">
    </div>
</div>
</div>
<div id="kb-return_note-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    <div id="return_note-form-content" class="kb-modal" style="display:none;left:0%;">
        <div class="kb-modal-header">
            <h1 id='kb_return_note_form_title'>{l s='Customer Notes' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_note-modal-form">X</span>
    </div>
    <div class="kb-modal-footer" style="text-align: center;">
    </div>
</div>
</div>

{* changes over *}                
{* changes over *}
{*<div id="kb-return_approve-modal-form" style="display:none;">
    <div class="kb-overlay"></div>
    <div id="return_approve-loader" class="kb-modal loading-block"><div class="loader128"></div></div>
    <div id="return_approve-form-content" class="kb-modal" style="display:none">
        <div class="kb-modal-header">
            <h1 id='kb_return_approve_form_title'>{l s='Mark As Approved?' mod='kbmarketplace'}</h1>
            <span class="kb-modal-close" data-modal="kb-return_approve-modal-form">X</span>
</div>
        <div class="kb-modal-content">
            <div id="new-return_approve-form-msg" class="kbalert"></div>
            <div id="new-return_approve-form" class="new_return_approve_form kb-form" style="padding:0;">
                <ul class="kb-form-list">
                                                                                    <li class="kb-form-fwidth">
                        <div class="kb-form-field-block kb-mbtm10">
                            <div class="form-lbl-indis kb-left">
                                <span class="kblabel">{l s='Email Subject' mod='kbmarketplace'}<em>*</em>:</span>
                            </div>
                            <div class="form-field-indis cobmination-field">
                                <input type="text" name="subject_email_allow" id="subject_email_allow" value="" class="add_return_approve_new rm_modal_input"/>
                            </div>    
                        </div>
                    </li>
                    <li class="kb-form-fwidth">
                        <div class="kb-form-field-block kb-mbtm10">
                            <div class="form-lbl-indis kb-left">
                                <span class="kblabel">{l s='Email Content' mod='kbmarketplace'}<em>*</em>:</span>
                            </div>
                            <div class="form-field-indis cobmination-field">
                                <textarea rows="10" class="add_return_approve_new_term rm_modal_input" aria-hidden="true" name="body_email_allow" id="body_email_allow" class="rm_texteditor autoload_rte"></textarea>
                            </div>    
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="kb-modal-footer">
            <button type="button" class="kbbtn-big kbbtn-success" id="rm_yes_approve" name="rm_yes_approve" data-id="0">{l s='Submit' mod='kbmarketplace'}</button>
            <div id="return_approve-updating-progress" class="input-loader" style="display: none; vertical-align: middle;"></div>
        </div>
        </div>
        </div>
        
    <div class="modal fade" id="rm_approve_confirm"  tab-index="-1" aria-hidden="true" aria-labelledby="modal-remove">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content kb-modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='kbmarketplace'}</span></button>
                                                                <h4 class="modal-title velsof_modal_title">{l s='Mark As Approved?' mod='kbmarketplace'}</h4> 

                                                            </div>
                                                            <div class="modal-body kb-form">


                                                                <div class="block">
                                                                    <label class="velsof-help"> {l s='This email will be sent to this customer. If you want to make any changes then you can or send as it is.' mod='kbmarketplace'}</label>

                                                                    
                                                                                                                        <ul class="kb-form-list">
                                                                                    <li class="kb-form-fwidth">
                        <div class="kb-form-field-block kb-mbtm10">
                            <div class="form-lbl-indis kb-tright">
                                <span class="kblabel">{l s='Email Subject' mod='kbmarketplace'}<em>*</em>:</span>
                            </div>
                            <div class="form-field-indis cobmination-field">
                                <input type="text" name="subject_email_allow" id="subject_email_allow" value="" class="add_return_approve_new rm_modal_input"/>
                            </div>    
                        </div>
                    </li>
                    <li class="kb-form-fwidth">
                        <div class="kb-form-field-block kb-mbtm10">
                            <div class="form-lbl-indis kb-tright">
                                <span class="kblabel">{l s='Email Content' mod='kbmarketplace'}<em>*</em>:</span>
                            </div>
                            <div class="form-field-indis cobmination-field">
                                <textarea rows="10" class="add_return_approve_new_term rm_modal_input" aria-hidden="true" name="body_email_allow" id="body_email_allow" class="rm_texteditor"></textarea>
                            </div>    
                        </div>
                    </li>
                </ul>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <img id="rm_approve_return_popup_loader" src="{$path|escape:'quotes':'UTF-8'}returnmanager/views/img/loader_small.gif" />
                                                                <button type="button" onclick="rmCloseModal('rm_approve_confirm')" class="btn btn-warning">{l s='Cancel' mod='kbmarketplace'}</button>
                                                                <button type="button" id="rm_yes_approve" class="btn btn-success">{l s='Submit' mod='kbmarketplace'}</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>*}
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
