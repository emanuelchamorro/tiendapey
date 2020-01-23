<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55150501" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
    {foreach $product_data as $product}
        <thead>
            <tr>
                <td style="width:25%;border-bottom: 1px solid #eee;"><strong></strong></td>
                <td style="width:25%;border-bottom: 1px solid #eee;"><strong>Product Name</strong></td>
                <td style="width:25%;border-bottom: 1px solid #eee;"><strong>Current Price</strong></td>
                <td style="width:15%;border-bottom: 1px solid #eee;"><strong></strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%;border-bottom: 1px solid #eee;"><img src="{$product['image'] nofilter}" height="100px" width="100px"/></td>{*Variable contains URL content, escape not required*}
                <td style="width:25%;border-bottom: 1px solid #eee;"> {$product['name']}</td>
                <td style="width:25%;border-bottom: 1px solid #eee;">{$product['price']}</td>
                <td style="width:15%;border-bottom: 1px solid #eee;"><a href="{$product['link'] nofilter}">Review Here</a></td>{*Variable contains URL content, escape not required*}
            </tr>
        </tbody>
    {/foreach}
</table>
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