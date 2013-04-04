{strip}
<ul>
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'user_bookmarks' ) and $gBitUser->hasPermission( 'p_tidbits_create_bookmarks' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}bookmarks.php">{booticon iname="icon-bookmark" iexplain="Links to my favourite pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'feature_tasks' ) and $gBitUser->hasPermission( 'p_tidbits_use_tasks' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}tasks.php">{booticon iname="icon-check" iexplain="Tasks" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'usermenu' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}menu.php">{booticon iname="icon-sitemap"   iexplain="User Mneu" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
