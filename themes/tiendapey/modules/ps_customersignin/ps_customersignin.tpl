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
<div id="_desktop_user_info">
  <div class="user-info">
    {if $logged}
      {*<a
        class="logout hidden-sm-down"
        href="{$logout_url}"
        rel="nofollow"
      >*}
        {*<i class="material-icons">&#xE7FF;</i>*}
        {*<i class="material-icons">rotate_left</i>*}
       {* {l s='Salir' d='Shop.Theme.Actions'}
      </a>*}
      <div class="dropdown show">
		<a class="account dropdown-toggle" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <span>{$customer.firstname}</span>
		</a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
      <div class="row">
        <div class="col-xs-5">
          <div class="avatar">
            <i class="material-icons">person</i>
          </div>
          <p>¡Hola, {$customer.firstname}!</p>
          <p><a href="{$my_account_url}" class="btn btn-info btn-lg d-block">{l s='Mi cuenta' d='Shop.Theme.Customeraccount'}</a></p>
        </div>
        <div class="col-xs-7">
          <div class="user-menu__shortcuts">
            <ul>
              <li><a href="{$urls.pages.identity}">Mis Datos</a></li>
              <li><a href="{$urls.pages.history}">Compras</a></li>
              {if $customer.addresses|count}
              <li><a href="{$urls.pages.addresses}">Direcciones</a></li>
              {else}
              <li><a href="{$urls.pages.address}">Direcciones</a></li>
              {/if}
              {$seller_menus}
              <li><a href="{$urls.actions.logout}">{l s='Salir' d='Shop.Theme.Actions'}</a></li>
            </ul>
        </div>
      </div>
    </div>
  </div>
      {*<a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >*}
        {*<i class="material-icons">person</i>*}
        {*<span class="hidden-sm-down">{$customerName}</span>*}
        {*<span class="hidden-sm-down">Mi cuenta</span>*}
      </a>
    {else}
      <a
        href="{$my_account_url}?create_account=1"
        title="{l s='Creá tu cuenta' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        {*<i class="material-icons">&#xE7FF;</i>*}
        <span class="hidden-sm-down">{l s='Creá tu cuenta' d='Shop.Theme.Actions'}</span>
      </a>
      <a
        href="{$my_account_url}"
        title="{l s='Log in to your account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        {*<i class="material-icons">&#xE7FF;</i>*}
        <span class="hidden-sm-down">{l s='Ingresá' d='Shop.Theme.Actions'}</span>
      </a>
    {/if}
  </div>
</div>
