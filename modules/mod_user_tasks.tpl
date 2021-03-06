{* $Header$ *}
{if $gBitSystem->isFeatureActive( 'feature_tasks' ) and $gBitUser->getUserId() > 0}
	{bitmodule title="$moduleTitle" name="user_tasks"}
		{form action=$ownurl}
			<input type="text" name="modTasksTitle" />
			<input type="submit" name="modTasksSave" value="{tr}add{/tr}" />
		{/form}
		{form action=$ownurl}
			<table class="module box">
				{section name=ix loop=$modTasks}
					<tr><td>
						<input type="checkbox" name="modTasks[{$modTasks[ix].task_id}]" />
						<a {if $modTasks[ix].status eq 'c'}style="text-decoration:line-through;"{/if} href="{$smarty.const.USERS_PKG_URL}tasks.php?task_id={$modTasks[ix].task_id}">{$modTasks[ix].title|escape}</a> ({$modTasks[ix].percentage}%)
					</td></tr>
				{sectionelse}
					<tr><td>&nbsp;</td></tr>
				{/section}
			</table>
			<input type="submit" name="modTasksCom" value="{tr}Done{/tr}" />
			<input type="submit" name="modTasksDel" value="{tr}Delete{/tr}" />
		{/form}
	{/bitmodule}
{/if}
