<style>
    {*.graph_lines{
    width:1000px;
    height: 400px;
    }
    .flot_graph {
    width:900px;
    height:300px;
    }*}
</style>
{$kpi_details}{*Escape not required as contains html*}
<div class="kb-donut-graph">
    <!--more statistics box start-->
    <div class="panel deep-purple-box">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-7 col-sm-7 col-xs-7">
                    <div id="graph-donut" class="revenue-graph" style="height: 278px"></div>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-5">
                    <ul class="bar-legend">
                        <li><span class="purple"></span> {l s='Chrome Subscribers' mod='kbpushnotification'}</li>
                        <li><span class="blue"></span> {l s='Firefox Subscribers' mod='kbpushnotification'}</li>
                        <li><span class="red"></span> {l s='Others' mod='kbpushnotification'}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="panel">
        <div class="panel-heading">
            {l s='Statistics' mod='kbpushnotification'}
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="control-label required">
                            {l s='Start Date' mod='kbpushnotification'}
                        </label>
                        <div class="input-group">
                            <input type="text" name="start_date" class="form-control start_date input-medium" value="{'-2 month'|date_format:"%Y-%m-%d"|escape:'htmlall':'UTF-8'}">
                            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="control-label required">
                            {l s='End Date' mod='kbpushnotification'}
                        </label>
                        <div class="input-group">
                            <input type="text" name="end_date" class="form-control input-medium end_date" value="{$smarty.now|date_format:"%Y-%m-%d"|escape:'htmlall':'UTF-8'}">
                            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="control-label">
                            {l s='Group by' mod='kbpushnotification'}
                        </label>
                        <select name="groupby" class="form-control" id="group_by">
                            <option value="days" selected="selected">{l s='Days' mod='kbpushnotification'}</option>
                            <option value="months">{l s='Month' mod='kbpushnotification'}</option>
                            <option value="years">{l s='Year' mod='kbpushnotification'}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group" style="margin-top: 13%;">
                        <button type="button" class="btn btn-primary filtersalereport">{l s='Filter' mod='kbpushnotification'}</button>
                    </div>
                </div>

            </div>
            <div class="row salereportgraph" style="display: none;">
                <div class="graph_lines"> 
                    <div id="flot-placeholder" class="flot_graph" style="height: 235px"></div>      
                    <div id="graph_loader_legend" style="padding-left: 10px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.filtersalereport').trigger('click');
            $('.salereportgraph').show();
        });
    </script>
    <script>
        var large_kpi_icon = "{$large_kpi_icon|escape:'htmlall':'UTF-8'}";
        var currentText = '{l s='Now'  mod='kbpushnotification' js=1}';
        var closeText = '{l s='Done'  mod='kbpushnotification' js=1}';
        var timeOnlyTitle = '{l s='Choose Time'  mod='kbpushnotification' js=1}';
        var timeText = '{l s='Time' mod='kbpushnotification' js=1}';
        var hourText = '{l s='Hour' mod='kbpushnotification' js=1}';
        var minuteText = '{l s='Minute' mod='kbpushnotification' js=1}';
        var end_date_error = "{l s='End date cannot be previous to start date.' mod='kbpushnotification'}";
        var module_path = "{$module_path|escape:'quotes':'UTF-8'}";
        var technical_error = "{l s='There is some technical error' mod='kbpushnotification'}";
        var subscriber_label = "{l s='Total Subscribers' mod='kbpushnotification'}";
        var push_label = "{l s='Total Pushes' mod='kbpushnotification'}";


        var totalSubscribers = '{$total_subscribers|escape:'htmlall':'UTF-8'}';
        var chromeSubscribers = '{$chrome_subscribers|escape:'htmlall':'UTF-8'}';
        var firefoxSubscribers = '{$firefox_subscribers|escape:'htmlall':'UTF-8'}';
        var otherSubscribers = '{$total_subscribers - ($chrome_subscribers + $firefox_subscribers)|escape:'htmlall':'UTF-8'}';
        var chrome_label = "{l s='Chrome Subscribers' mod='kbpushnotification'}";
        var firefox_label = "{l s='Firefox Subscribers' mod='kbpushnotification'}";
        var other_label = "{l s='Others' mod='kbpushnotification'}";
        var out_of_label = "{l s='out of' mod='kbpushnotification'}";
        
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
    <div class="modal"></div>
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
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin Velovalidation tpl file
*}