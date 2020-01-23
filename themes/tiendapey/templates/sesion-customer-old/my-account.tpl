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

{block name='page_title'}
  {l s='Your account' d='Shop.Theme.Customeraccount'}
{/block}

{block name='page_content'}
<div class="row">
	<div class="MenuCustomer col-md-3">
		<ul>
			<li><a href="#"><span class="iconoLinks Resumen"></span> Resumen</a></li>
			<li>
				<a class="LinkCuenta" data-toggle="collapse" href="#ResumenCollapse" role="button" aria-expanded="false" aria-controls="ResumenCollapse">
					<span class="iconoLinks Facturacion"></span> Facturación
				</a>
				<div class="collapse" id="ResumenCollapse">
					{if !$configuration.is_catalog}
					<a id="order-slips-link" href="{$urls.pages.order_slip}">
						{l s='Credit slips' d='Shop.Theme.Customeraccount'}
					</a>
					{/if}
				</div>
			</li>
			<li><a href="#"><span class="iconoLinks Reputacion"></span> Reputación</a></li>
			<li>
				<a class="LinkCuenta" data-toggle="collapse" href="#ComprasCollapse" role="button" aria-expanded="false" aria-controls="ComprasCollapse">
					<span class="iconoLinks Compras"></span> Compras
				</a>
				<div class="collapse" id="ComprasCollapse">
					{if !$configuration.is_catalog}
						<a id="history-link" href="{$urls.pages.history}">
							{l s='Order history and details' d='Shop.Theme.Customeraccount'}
						</a>
					{/if}
					{if $configuration.return_enabled && !$configuration.is_catalog}
					<a id="returns-link" href="{$urls.pages.order_follow}">
						{l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
					</a>
				  {/if}
				  {if !$configuration.is_catalog}
					<a id="order-slips-link" href="{$urls.pages.order_slip}">
						{l s='Credit slips' d='Shop.Theme.Customeraccount'}
					</a>
				  {/if}
				  {if $configuration.voucher_enabled && !$configuration.is_catalog}
					<a id="discounts-link" href="{$urls.pages.discount}">
						{l s='Vouchers' d='Shop.Theme.Customeraccount'}
					</a>
				  {/if}
				</div>
			</li>
			<li>
				<a class="LinkCuenta" data-toggle="collapse" href="#VentasCollapse" role="button" aria-expanded="false" aria-controls="VentasCollapse">
					<span class="iconoLinks Ventas"></span> Ventas
				</a>
				<div class="collapse" id="VentasCollapse">
					{block name='display_customer_account'}
						{hook h='displayCustomerAccount'}
					{/block}
				</div>
			</li>
			<li>
				<a class="LinkCuenta" data-toggle="collapse" href="#ConfiguracionCollapse" role="button" aria-expanded="false" aria-controls="ConfiguracionCollapse">
					<span class="iconoLinks Configuracion"></span> Configuración
				</a>
				<div class="collapse" id="ConfiguracionCollapse">
					{if !$configuration.is_catalog}
					<a id="identity-link" href="{$urls.pages.identity}">
						{l s='Mis Datos' d='Shop.Theme.Customeraccount'}
					</a>
					{/if}
					{if $customer.addresses|count}
					<a id="addresses-link" href="{$urls.pages.addresses}">
						{l s='Mis Direcciones' d='Shop.Theme.Customeraccount'}
					</a>
					{else}
					<a id="address-link" href="{$urls.pages.address}">
						{l s='Agregar Dirección' d='Shop.Theme.Customeraccount'}
					</a>
					{/if}
				</div>
			</li>
		</ul>
	</div>
</div>
{/block}


{block name='page_footer'}
  {block name='my_account_links'}
    <div class="text-sm-center">
      <a href="{$logout_url}" >
        {l s='Sign out' d='Shop.Theme.Actions'}
      </a>
    </div>
  {/block}
{/block}
