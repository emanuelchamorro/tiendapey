<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
var rating_chart_data = {$rating_result nofilter};{* Variable contains JSON, can not escape this*}
var vote_msg_log_in = "{l s='Please login first to vote.' mod='kbreviewincentives'}";
var product_cont_path = "{$product_cont_path nofilter}";{* Variable contains URL, can not escape this*}
var read_review = "{$read_review}";
var chart_text = "{l s='Rating Chart.' mod='kbreviewincentives'}";
var rating_text = "{l s='Rating.' mod='kbreviewincentives'}";
var review_text = "{l s='Reviews.' mod='kbreviewincentives'}";
var star_text = "{l s='star.' mod='kbreviewincentives'}";
{literal}
    if (typeof google != 'undefined') {
       google.load("visualization", "0", {packages:["corechart"]});
    }
{/literal}
</script>
<section class="page-product-box">
<h3 class="page-product-heading">Opiniones sobre el producto</h3>
{* <h3 class="page-product-heading">{l s='Reviews for ' mod='kbreviewincentives'} {$product_name}</h3> *}
</section>

<div id="tab-review-new" style="padding-top: 10px;">
<fieldset id="review_incentive_fieldset">
<div class="velsofincentive_write_review">
        <div class="velsofincentive_rating_div">
    <div class="velsofincentive_product_review_rating">
            <div class="promedio">
                <span class="numberPromedio">{$avg_rating}.0</span>
            </div>
            <div class="starPromedio">
                <div class="velsofincentive_big_star w-{$avg_rating}"></div>
                <span class="promedioBetween">Promedio entre {$total_ratings} opinión{if ($total_ratings > 1)}opiniones{/if}</span>
            </div>
    </div>
    {* <div id="velsofincentive_bar_graph" style="width: 450px; height: 200px; float: left;"></div>
        <div class="velsofincentive_clear"></div>
    </div> *}
    <div class="velsofincentive_clear"></div>
</div>

<div class="velsofincentive_review_list" id="velsofincentive_review_list">
    {foreach $reviews_data as $user}
        {if $user['current_status'] eq 1}
            <div class="velsofincentive_review">
            <div class="velsofincentive_review_detail">
                <div class="velsofincentive_review_text">
                    <img src="{$image_path nofilter}stars-{$user['ratings']}.png" alt="{$user['ratings']} stars "></div>{* Variable contains HTML, can not escape this *}
                <div class="velsofincentive_review_text">
                    {* <div class="velsofincentive_review_author">
                        <p>{$user['author']} <span class="date">{date('m/d/Y', strtotime($user['date_add']))}</span></p> 
                    </div> *}
                </div>
                <div class="velsofincentive_review_text">
                    
                </div>
                {if ($user['certified_buyer'] == 1)}
                                <div class="velsofincentive_review_text" style="text-align:left;">
                    <img src="{$image_path nofilter}certified_buyer.png" alt="Certified Buyer">{* Variable contains HTML, can not escape this *}
                </div>
                 {/if}
                            </div>
               
            <div class="velsofincentive_review_description">
                <div class="velsofincentive_review_right_title">
                    {$user['review_title']}</div>
                <div class="velsofincentive_review_right_description">
                    {$user['description']}
                </div>
                    <div class="velsofincentive_help_review">
                        <div class="velsofincentive_ask_helpful_review">
                            <div class="velsofincentive_helpful_review">{l s='Was this Review Helpful ?' mod='kbreviewincentives'}</div> 
                            <div class="velsofincentive_review_yes_div"><div class="velsofincentive_review_yes" onclick="vote({$user['review_id']},'1', {$is_logged})">
                                </div><span class="velsofincentive_review_message_bold">{l s='Yes' mod='kbreviewincentives'}</span></div> 
                            <div class="velsofincentive_review_no_div"><div class="velsofincentive_review_no" onclick="vote({$user['review_id']},'0', {$is_logged})">
                                </div><span class="velsofincentive_review_message_bold">{l s='No' mod='kbreviewincentives'}</span></div>
                            <span class="velsofincentive_review_vote" id="velsofincentive_review_vote_{$user['review_id']}">{l s='Thanks for your Vote' mod='kbreviewincentives'}</span> </div>
                        <div class="velsofincentive_vote_helpful_review" id="velsofincentive_vote_helpful_review_{$user['review_id']}">
                            <span class="velsofincentive_review_message_bold" id='kbrc_helpful'>{$user['helpful_votes']}</span> {l s=' out of' mod='kbreviewincentives'}
                            <span class="velsofincentive_review_message_bold" id='kbrc_helpful_tot'>{$user['helpful_votes'] + $user['not_helpful_votes']}</span> {l s='reviews are useful.' mod='kbreviewincentives'}</div>
                    </div>
            </div>
        </div>
                    {/if}
                {/foreach}
        </div>
        <div class="velsofincentive_product_review_write">
            <div class="velsofincentive_message">
                <span>¿Usaste este producto?</span>
                <a target="_blank" href="{$write_new_review_link nofilter}" class="velsofincentive_button">Publica tu Opinión</a>  {* Variable contains HTML, can not escape this *}
            </div>                
    </div>
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