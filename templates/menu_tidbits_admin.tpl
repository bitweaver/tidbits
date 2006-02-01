{strip}
<ul>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=menus" title="{tr}Custom Menus{/tr}" >{tr}Custom Menus{/tr}</a></li>
    {if $gBitUser->hasPermission( 'bit_p_edit_cookies' )}
        <li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}admin/admin_cookies.php">{tr}Cookies{/tr}</a></li>
    {/if}

</ul>

{/strip}
