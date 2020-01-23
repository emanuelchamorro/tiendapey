<div class="alert alert-danger kb-error" style="display:none;"></div>
<div class="alert alert-success kb-success" style="display:none;"></div>

<div class="alert alert-warning">
    <h4 style="color:red;">{l s='Instructions' mod='kbpushnotification'}</h4>
    <p>{l s='To register for Send Test, click to the button below' mod='kbpushnotification'}</p>
    <p></p>
    <a href="{$kb_front_url|escape:'quotes':'UTF-8'}" target="_blank" type="submit" class="btn btn-warning">{l s='Register for Send Test' mod='kbpushnotification'}</a>
    <p></p>
    <p style="color:red;">* {l s='In case of Promotional Notification Admin can not track the push notification clicks.' mod='kbpushnotification'}</p>
</div>


<form action="#" method="post" class="defaultForm form-horizontal kb_push_notification_form">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-bell"></i>
            {l s='Knowband Push Notification' mod='kbpushnotification'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="{l s='Choose the template for the push notification' mod='kbpushnotification'}">
                        {l s='Select Template' mod='kbpushnotification'}
                    </span>
                </label>
                <div class="col-lg-9">
                    <select name="template" class=" fixed-width-xl" id="template">
                        {if !empty($kb_templates)}
                            {foreach $kb_templates as $template}
                                <option value="{$template['id_template']|escape:'htmlall':'UTF-8'}">{$template['notification_title']|escape:'htmlall':'UTF-8'}</option>
                            {/foreach}
                        {/if}
                    </select>

                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2"></label>
                <div class="col-lg-9">
                    <div class="stepconfig-row col-lg-2"></div>
                    <div class="stepconfig-row setup-panel col-lg-3" style="width: 21%;">
                        <div class="stepconfig-step">
                            <span class="stepconfig-btn">{l s='OR' mod='kbpushnotification'}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"></label>
                <div class="col-lg-9">
                    <a href="{$kb_admin_tempate_url|escape:'quotes':'UTF-8'}"class="btn btn-warning">{l s='Create Template' mod='kbpushnotification'}</a>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="{l s='Choose the option for the time to send push notification' mod='kbpushnotification'}">
                        {l s='Send Notification' mod='kbpushnotification'}
                    </span>
                </label>
                <div class="col-lg-9">
                    <div class="kb-radio-field">
                        <div class="radio">
                            <label for="send_immediately">
                                <input type="radio" name="send_push_time" id="send_immediately" value="0">
                                {l s='Send Immediately' mod='kbpushnotification'}
                            </label>
                        </div>
                        <div class="radio">
                            <label for="send_at_time">
                                <input type="radio" name="send_push_time" id="send_at_time" value="1">
                                {l s='Send at a particular time' mod='kbpushnotification'}
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="" name="send_at_time_date" id="send_at_time_date" value readonly="readonly" style="display:none;margin-top:14px; ">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right" name="sendkbAllSubscriber" value="1">
                <i class="process-icon-refresh"></i> {l s='Send to All Subscriber' mod='kbpushnotification'}</button>
            <button type="submit" class="btn btn-default pull-right" name="sendkbTest" value="1">
                <i class="process-icon-refresh"></i> {l s='Send Test' mod='kbpushnotification'}</button>
        </div>

    </div>
</form>
        
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    {l s='Cron Instructions' mod='kbpushnotification'}
                </div>
                <div class="row">
                    <p>
                    {l s='Add the cron to your store via control panel/putty to send push notification to your subscribers. Please find the instructions to setup crons below -' mod='kbpushnotification'}
                </p>
                <br/>
                <p>
                    <b>{l s='URLs to Add to Cron via Control Panel' mod='kbpushnotification'}</b>
                </p>
                <p>1. <b>{l s='Push Delay Notification' mod='kbpushnotification'}</b> - {$kb_admin_delay_url|escape:'quotes':'UTF-8'}</p>
                <p>1. <b>{l s='Push Abandoned Cart Notification' mod='kbpushnotification'}</b> - {$kb_admin_abd_url|escape:'quotes':'UTF-8'}</p>
                 <p>
                    <b>{l s='Cron setup via SSH' mod='kbpushnotification'}</b>
                </p>
                <p>1. <b>{l s='Push Delay Notification' mod='kbpushnotification'}</b> - 40 * * * * curl -O /dev/null '{$kb_admin_delay_url|escape:'htmlall':'UTF-8'}'</p>
                <p>2. <b>{l s='Push Abandoned Cart Notification' mod='kbpushnotification'}</b> - 10 * * * * curl -O /dev/null '{$kb_admin_abd_url|escape:'htmlall':'UTF-8'}'</p>
                </div>
            </div>
        </div>

<script>
    var kbcurrentToken = window.localStorage.getItem('reg_id');
    var kb_push_admin = true;
    var kb_select_tempate = "{l s='Please select tempate.' mod='kbpushnotification'}";
    var kb_send_promotion_url = "{$kb_send_promotion_url|escape:'quotes':'UTF-8'}";
    var currentText = '{l s='Now'  mod='kbpushnotification'}';
    var closeText = '{l s='Done'  mod='kbpushnotification'}';
    var timeOnlyTitle = '{l s='Choose Time'  mod='kbpushnotification'}';
    var timeText = '{l s='Time' mod='kbpushnotification'}';
    var hourText = '{l s='Hour' mod='kbpushnotification'}';
    var minuteText = '{l s='Minute' mod='kbpushnotification'}'
</script>

<style>
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 ) 
            url('{$loader|escape:'quotes':'UTF-8'}') 
            50% 50% 
            no-repeat;
    }

    /* When the body has the loading class, we turn
       the scrollbar off with overflow:hidden */
    body.loading {
        overflow: hidden;   
    }

    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.loading .modal {
        display: block;
    }
</style>
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