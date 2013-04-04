<div class="admin box">
	<h3>{tr}User files{/tr}</h3>
	<div class="boxcontent">
		<form action="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=userfiles" method="post">
		<table class="panel">
		<tr><td>{tr}Quota (Mb){/tr}</td><td>
		<input type="text" name="userfiles_quota" value="{$gBitSystem->getConfig('userfiles_quota')|escape}" size="5" />
		</td></tr>
		<tr><td>{tr}Use database to store userfiles{/tr}:</td><td><input type="radio" name="users_uf_use_db" value="y" {if $gBitSystem->isFeatureActive('users_uf_use_db')}checked="checked"{/if}/></td></tr>
		<tr><td>{tr}Use a directory to store userfiles{/tr}:</td><td><input type="radio" name="users_uf_use_db" value="n" {if $gBitSystem->isFeatureActive('users_uf_use_db')}checked="checked"{/if}/></td></tr>
		<tr><td align="right">{tr}Path{/tr}:</td><td><input type="text" name="tidbits_userfiles_use_dir" value="{$tidbits_userfiles_use_dir|escape}" size="50" /> </td></tr>
		<tr class="panelsubmitrow"><td colspan="2"><input type="submit" class="btn" name="userfilesprefs" value="{tr}Change preferences{/tr}" /></td></tr>
		</table>
		</form>
	</div>
</div>
