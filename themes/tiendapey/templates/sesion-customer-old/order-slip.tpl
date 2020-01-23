{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{extends file='customer/page.tpl'}

{block name='page_content'}
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<p class="titleAccount">
			<span>Mi cuenta</span>
			<span>¡hola {$customer.firstname}!</span>
			</p>
			<ul class="linksAccount">
			<li><a id="identity-link" href="{$urls.pages.identity}"><i class="material-icons">assignment_ind</i> {l s='Information' d='Shop.Theme.Customeraccount'}</a></li>
			{if $customer.addresses|count}
				<li><a id="addresses-link" href="{$urls.pages.addresses}"><i class="material-icons">person_pin</i> {l s='Addresses' d='Shop.Theme.Customeraccount'}</a></li>
			{else}
				<li><a id="address-link" href="{$urls.pages.address}" class="active"><i class="material-icons">person_pin</i> {l s='Add first address' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="history-link" href="{$urls.pages.history}"><i class="material-icons">wrap_text</i> {l s='Order history and details' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="order-slips-link" href="{$urls.pages.order_slip}" class="active"><i class="material-icons">list_alt</i> {l s='Pedidos cancelados' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if $configuration.voucher_enabled && !$configuration.is_catalog}
				<li><a id="discounts-link" href="{$urls.pages.discount}"><i class="material-icons">monetization_on</i> {l s='Vouchers' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if $configuration.return_enabled && !$configuration.is_catalog}
				<li><a id="returns-link" href="{$urls.pages.order_follow}"><i class="material-icons">flip_camera_android</i> {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			<li><a id="discount-link" href="{$urls.pages.discount}"><i class="material-icons">local_activity</i> {l s='Vouchers' d='Shop.Theme.Customeraccount'}</a></li>
			{hook h='displayMyAccountBlock'}
			</ul>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			{block name='page_title'}
			  {l s='Credit slips' d='Shop.Theme.Customeraccount'}
			{/block}
			<h6>{l s='Credit slips you have received after canceled orders.' d='Shop.Theme.Customeraccount'}</h6>
			  {if $credit_slips}
				<table class="table table-striped table-bordered hidden-sm-down table-responsive">
				  <thead class="thead-default">
					<tr>
					  <th>{l s='Order' d='Shop.Theme.Customeraccount'}</th>
					  <th>{l s='Credit slip' d='Shop.Theme.Customeraccount'}</th>
					  <th>{l s='Date issued' d='Shop.Theme.Customeraccount'}</th>
					  <th>{l s='View credit slip' d='Shop.Theme.Customeraccount'}</th>
					</tr>
				  </thead>
				  <tbody>
					{foreach from=$credit_slips item=slip}
					  <tr>
						<td><a href="{$slip.order_url_details}" data-link-action="view-order-details">{$slip.order_reference}</a></td>
						<td scope="row">{$slip.credit_slip_number}</td>
						<td>{$slip.credit_slip_date}</td>
						<td class="text-sm-center">
						  <a href="{$slip.url}"><i class="material-icons">&#xE415;</i></a>
						</td>
					  </tr>
					{/foreach}
				  </tbody>
				</table>
				<div class="credit-slips hidden-md-up">
				  {foreach from=$credit_slips item=slip}
					<div class="credit-slip">
					  <ul>
						<li>
						  <strong>{l s='Order' d='Shop.Theme.Customeraccount'}</strong>
						  <a href="{$slip.order_url_details}" data-link-action="view-order-details">{$slip.order_reference}</a>
						</li>
						<li>
						  <strong>{l s='Credit slip' d='Shop.Theme.Customeraccount'}</strong>
						  {$slip.credit_slip_number}
						</li>
						<li>
						  <strong>{l s='Date issued' d='Shop.Theme.Customeraccount'}</strong>
						  {$slip.credit_slip_date}
						</li>
						<li>
						  <a href="{$slip.url}">{l s='View credit slip' d='Shop.Theme.Customeraccount'}</a>
						</li>
					  </ul>
					</div>
				  {/foreach}
				</div>
				{else}
				<article class="alert alert-warning" role="alert" data-alert="warning">
					  <ul>
						<li>No ha recibido ninguna nota de crédito por pedidos cancelados.</li>
					  </ul>
					</article>
			  {/if}
	  </div>
  </div>
{/block}
