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
				<li><a id="address-link" href="{$urls.pages.address}"><i class="material-icons">person_pin</i> {l s='Add first address' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="history-link" href="{$urls.pages.history}" class="active"><i class="material-icons">wrap_text</i> {l s='Order history and details' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="order-slips-link" href="{$urls.pages.order_slip}"><i class="material-icons">list_alt</i> {l s='Pedidos cancelados' d='Shop.Theme.Customeraccount'}</a></li>
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
			  {l s='Order history' d='Shop.Theme.Customeraccount'}
			{/block}
			<h6>{l s='Here are the orders you\'ve placed since your account was created.' d='Shop.Theme.Customeraccount'}</h6>
			{if $orders}
				<table class="table table-responsive table-striped table-bordered table-labeled hidden-sm-down table-responsive">
				  <thead class="thead-default">
					<tr>
					  <th>{l s='Order reference' d='Shop.Theme.Checkout'}</th>
					  <th>{l s='Date' d='Shop.Theme.Checkout'}</th>
					  <th>{l s='Total price' d='Shop.Theme.Checkout'}</th>
					  <th class="hidden-md-down">{l s='Payment' d='Shop.Theme.Checkout'}</th>
					  <th class="hidden-md-down">{l s='Status' d='Shop.Theme.Checkout'}</th>
					  <th>{l s='Invoice' d='Shop.Theme.Checkout'}</th>
					  <th>&nbsp;</th>
					</tr>
				  </thead>
				  <tbody>
					{foreach from=$orders item=order}
					  <tr>
						<th scope="row">{$order.details.reference}</th>
						<td>{$order.details.order_date}</td>
						<td class="text-xs-right">{$order.totals.total.value}</td>
						<td class="hidden-md-down">{$order.details.payment}</td>
						<td>
						  <span
							class="label label-pill {$order.history.current.contrast}"
							style="background-color:{$order.history.current.color}"
						  >
							{$order.history.current.ostate_name}
						  </span>
						</td>
						<td class="text-sm-center hidden-md-down">
						  {if $order.details.invoice_url}
							<a href="{$order.details.invoice_url}"><i class="material-icons">&#xE415;</i></a>
						  {else}
							-
						  {/if}
						</td>
						<td class="text-sm-center order-actions">
						  <a href="{$order.details.details_url}" data-link-action="view-order-details">
							{l s='Details' d='Shop.Theme.Customeraccount'}
						  </a>
						  {if $order.details.reorder_url}
							<a href="{$order.details.reorder_url}">{l s='Reorder' d='Shop.Theme.Actions'}</a>
						  {/if}
						</td>
					  </tr>
					{/foreach}
				  </tbody>
				</table>

				<div class="orders hidden-md-up">
				  {foreach from=$orders item=order}
					<div class="order">
					  <div class="row">
						<div class="col-xs-10">
						  <a href="{$order.details.details_url}"><h3>{$order.details.reference}</h3></a>
						  <div class="date">{$order.details.order_date}</div>
						  <div class="total">{$order.totals.total.value}</div>
						  <div class="status">
							<span
							  class="label label-pill {$order.history.current.contrast}"
							  style="background-color:{$order.history.current.color}"
							>
							  {$order.history.current.ostate_name}
							</span>
						  </div>
						</div>
						<div class="col-xs-2 text-xs-right">
							<div>
							  <a href="{$order.details.details_url}" data-link-action="view-order-details" title="{l s='Details' d='Shop.Theme.Customeraccount'}">
								<i class="material-icons">&#xE8B6;</i>
							  </a>
							</div>
							{if $order.details.reorder_url}
							  <div>
								<a href="{$order.details.reorder_url}" title="{l s='Reorder' d='Shop.Theme.Actions'}">
								  <i class="material-icons">&#xE863;</i>
								</a>
							  </div>
							{/if}
						</div>
					  </div>
					</div>
				  {/foreach}
				</div>
			{else}
				<article class="alert alert-warning" role="alert" data-alert="warning">
				  <ul>
					<li>No ha realizado ningún pedido.</li>
				  </ul>
				</article>
			{/if}
		</div>
	</div>

  
{/block}
