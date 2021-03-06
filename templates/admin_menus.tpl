{strip}
{jstabs}
	{if $menu_id > 0}
		{assign var=title value="Edit Menu"}
	{else}
		{assign var=title value="Create Menu"}
	{/if}
	{jstab title=$title}
	{if $menu_id > 0}
		<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}">{tr}Create new Menu{/tr}</a>
	{/if}
		{form legend="Edit/Create new Menu"}
			<input type="hidden" name="page" value="{$page}" />
			<input type="hidden" name="menu_id" value="{$menu_id|escape}" />
			<div class="form-group">
				{formlabel label="Name" for="menus_name"}
				{forminput}
					<input type="text" name="name" id="menus_name" value="{$name|escape}" />
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Description" for="menus_desc"}
				{forminput}
					<textarea name="description" id="menus_desc" rows="4" cols="50">{$description|escape}</textarea>
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Type" for="menus_type"}
				{forminput}
					<select name="type" id="menus_type">
						<option value="d" {if $type eq 'd'}selected="selected"{/if}>{tr}dynamic collapsed{/tr}</option>
						<option value="e" {if $type eq 'e'}selected="selected"{/if}>{tr}dynamic extended{/tr}</option>
						<option value="f" {if $type eq 'f'}selected="selected"{/if}>{tr}fixed{/tr}</option>
					</select>
					{formhelp note="<dl><dt>dynamic collapsed</dt><dd>When accessing the site for the first time, the menus will be dynamic and collapsed.</dd><dt>dynamic extended</dt><dd>When accessing the site for the first time, the menus will be dynamic and expanded with all links visible.</dd><dt>fixed</dt><dd>The menu will not be dynamic. this means that all links are visible and cannot be collapsed/hidden.</dd></dl>"}
				{/forminput}
			</div>

			<div class="form-group submit">
				<input type="submit" class="btn btn-default" name="save" value="{tr}Save{/tr}" />
			</div>
		{/form}

		{minifind sort_mode=$sort_mode page=$page}

		<table class="table data">
			<caption>{tr}List of configured menus{/tr}</caption>
			<tr>
				<th><a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'menu_id_asc'}menu_id_desc{else}menu_id_asc{/if}">{tr}ID{/tr}</a></th>
				<th><a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_asc'}name_desc{else}name_asc{/if}">{tr}Name{/tr}</a></th>
				<th><a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'description_asc'}description_desc{else}description_asc{/if}">{tr}Description{/tr}</a></th>
				<th><a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'type_asc'}type_desc{else}type_asc{/if}">{tr}Type{/tr}</a></th>
				<th>{tr}Options{/tr}</th>
				<th>{tr}Action{/tr}</th>
			</tr>

			{cycle values="even,odd" print=false}
			{section name=user loop=$menus}
				<tr class="{cycle}">
					<td>{$menus[user].menu_id}</td>
					<td>{$menus[user].name}</td>
					<td>{$menus[user].description}</td>
					<td>{$menus[user].type}</td>
					<td>{$menus[user].options}</td>
					<td class="actionicon">
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;menu_id={$menus[user].menu_id}" title="{tr}Edit this menu{/tr}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit"}</a>
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=menu_options&amp;menu_id={$menus[user].menu_id}" title="{tr}Configure this menu{/tr}">{booticon iname="icon-file"  ipackage="icons"  iexplain="configure"}</a>
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$menus[user].menu_id}" 
							onclick="return confirm('{tr}Are you sure you want to delete this menu?{/tr}')" title="{tr}Delete this menu{/tr}">{booticon iname="icon-trash" ipackage="icons" iexplain="remove"}</a>
					</td>
				</tr>
			{sectionelse}
				<tr class="norecords"><td colspan="6">{tr}No records found{/tr}</td></tr>
			{/section}
		</table>

		{pagination page=$page}

	{/jstab}

	{jstab title="Global Menu Settings"}
		{form legend="Global Menu Settings"}
			<input type="hidden" name="page" value="{$page}" />
			{foreach from=$formMenuFeatures key=feature item=output}
				<div class="form-group">
					{formlabel label=$output.label for=$feature}
					{forminput}
						{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
						{formhelp note=$output.note page=$output.page}
					{/forminput}
				</div>
			{/foreach}
			<div class="form-group submit">
				<input type="submit" class="btn btn-default" name="menu_features" value="{tr}Change preferences{/tr}" />
			</div>
		{/form}
	{/jstab}
{/jstabs}
{/strip}
