{**
 * 2007-2017 PrestaShop
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{foreach from=$results item="block"}
  {assign var="productscount" value=$block.products|count}
  <div class="products products-slick spacing-md-top{if $productscount > 1} products--slickmobile{/if}" data-slick='{strip}
    {ldelim}
    "slidesToShow": 1,
    "slidesToScroll": 1,
    "mobileFirst":true,
    "arrows":true,
    "rows":0,
    "responsive": [
      {ldelim}
        "breakpoint": 992,
        "settings":
        {ldelim}
        "arrows":true,
        "slidesToShow": 4,
        "slidesToScroll": 4,
        "arrows":true
        {rdelim}
      {rdelim},
      {ldelim}
        "breakpoint": 720,
        "settings":
        {ldelim}
        "arrows":true,
        "slidesToShow": 3,
        "slidesToScroll": 3
        {rdelim}
      {rdelim},
      {ldelim}
        "breakpoint": 540,
        "settings":
        {ldelim}
        "arrows":true,
        "slidesToShow": 2,
        "slidesToScroll": 2
        {rdelim}
      {rdelim}
    ]{rdelim}{/strip}'>
    {foreach from=$block.products item="product"}
      {include file="catalog/_partials/miniatures/product.tpl" product=$product}
    {/foreach}
  </div>
{/foreach}