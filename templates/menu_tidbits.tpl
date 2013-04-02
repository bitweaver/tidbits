{strip}
<ul>
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'user_bookmarks' ) and $gBitUser->hasPermission( 'p_tidbits_create_bookmarks' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}bookmarks.php">{biticon iname="system-file-manager" iexplain="Links to my favourite pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'feature_tasks' ) and $gBitUser->hasPermission( 'p_tidbits_use_tasks' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}tasks.php">{biticon iname="task-due" iexplain="Tasks" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'tidbits' ) and $gBitSystem->isFeatureActive( 'usermenu' )}
		<li><a class="item" href="{$smarty.const.TIDBITS_PKG_URL}menu.php">{booticon iname="icon-sitemap"   iexplain="User Mneu" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'newsreader' ) and $gBitUser->hasPermission( 'bit_p_newsreader' )}
		<li><a class="item" href="{$smarty.const.NEWSREADER_PKG_URL}index.php">{biticon ipackage=liberty iname=spacer iexplain="Newsreader" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'notepad' ) and $gBitUser->hasPermission( 'bit_p_notepad' )}
		<li><a class="item" href="{$smarty.const.NOTEPAD_PKG_URL}index.php">{biticon ipackage=liberty iname=spacer iexplain="Notepad" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'webmail' ) and $gBitUser->hasPermission( 'bit_p_use_webmail' )}
		<li><a class="item" href="{$smarty.const.WEBMAIL_PKG_URL}index.php">{biticon ipackage=liberty iname=spacer iexplain="Webmail" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
