<style>
    #kbrc_rating {
        margin-bottom: 18px;
    }
    #kbrc_rating_text {
        margin-top: -10px;
    margin-bottom: 10px;
    color: blue;
    }
    .kbrc_rating_block{
        display: inline-block;
    }
</style>
<div class='kbrc_rating_block'><img src='{$path nofilter}stars-{$rating}.png' alt='rating' id='kbrc_rating'/><div id='kbrc_rating_text'><a href='{$rating_link nofilter}' id='kbrc_link' style="font-weight: bold;color:blue" target="_blank" title="{l s='Total Reviews' mod='kbreviewincentives'}"> ({if $rating_total eq 1}{$rating_total} {l s='Review' mod='kbreviewincentives'} {else} {$rating_total} {l s='Reviews' mod='kbreviewincentives'}{/if})</a></div>{* Variable contains URL, can not escape this *}
    <script>
        {*document.addEventListener("DOMContentLoaded", function(event) {
        $('.kbrc_rating_block').insertBefore($('.product-title'));
    });*}
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
* @copyright 2016 knowband
* @license   see file: LICENSE.txt
*}