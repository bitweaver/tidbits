{strip}
<ul class="dropdown-menu sub-menu">
	{* deprecated <li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=menus" title="{tr}Custom Menus{/tr}" >{tr}Custom Menus{/tr}</a></li> *}
	{*if $gBitUser->hasPermission( 'p_tidbits_edit_fortune_cookies' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}admin/admin_cookies.php">{tr}Cookies{/tr}</a></li>
	{/if*}
	{if $gBitSystem->isFeatureActive( 'tidbits_banning' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}admin/admin_banning.php">{tr}Banning{/tr}</a></li>
	{/if}
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=tidbits">{tr}Tidbits Settings{/tr}</a></li>
</ul>
{/strip}
