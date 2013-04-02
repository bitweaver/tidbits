{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin tidbits">
	<div class="header">
		<h1>{tr}Banning System{/tr}</h1>
	</div>

	<div class="body">
		{form title="Add / Edit Rule"}
			<input type="hidden" name="page" value="{$page}" />
			<div class="control-group">
				{formlabel label="Rule Title" for="banning-title"}
				{forminput}
					<input type="text" name="title" id="banning-title" value="{$info.title|escape}" />
					{formhelp note=""}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Username regex" for="banning-userregex"}
				{forminput}
					<input type="radio" name="mode" value="user" {if $info.mode eq 'user'}checked="checked"{/if} />
					<input type="text" name="user" id="banning-userregex" value="{$info.user|escape}" />
					{formhelp note="Ban users matching this regular expression."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="IP regex" for="banning-ipregex"}
				{forminput}
					<input type="radio" name="mode" value="ip" {if $info.mode eq 'ip'}checked="checked"{/if} />
					<input type="text" name="ip1" id="banning-ipregex" value="{$info.ip1|escape}" size="3" />.<input type="text" name="ip2" value="{$info.ip2|escape}" size="3" />.<input type="text" name="ip3" value="{$info.ip3|escape}" size="3" />.<input type="text" name="ip4" value="{$info.ip4|escape}" size="3" />
					{formhelp note="Ban computers matching this regular expression."}
				{/forminput}
			</div>

{*
			<div class="control-group">
				{formlabel label="Ban from Package" for=""}
				{forminput}
		<table><tr>
		{section name=ix loop=$sections}
        <td>
			<input type="checkbox" name="section[{$sections[ix]}]" id="tidbits_banning-section" {if in_array($sections[ix],$info.sections)}checked="checked"{/if} /> <label for="tidbits_banning-section">{$sections[ix]}</label>
        </td>
        {if not ($smarty.section.ix.rownum mod 3)}
                {if not $smarty.section.ix.last}
                        </tr><tr>
                {/if}
        {/if}
        {if $smarty.section.ix.last}
                {math equation = "n - a % n" n=3 a=$data|@count assign="cells"}
                {if $cells ne $cols}
                {section name=pad loop=$cells}
                        <td>&nbsp;</td>
                {/section}
                {/if}
                </tr>
        {/if}
    	{/section}
		</table>
				{/forminput}
			</div>
*}

			<div class="control-group">
				{formlabel label="Date Restrictions" for="banning-actdates"}
				{forminput}
					<label><input type="checkbox" name="use_dates" id="banning-actdates" {if $info.use_dates eq 'y'}checked="checked"{/if} /> {tr}Use Date Restrictions{/tr}</label>
					<br /> {html_select_date prefix="date_from" time="$info.date_from"}
					<br /> {html_select_date prefix="date_to" time="$info.date_to"}
					{formhelp note="Only apply this banning rule between these dates."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Message" for="banning-message"}
				{forminput}
					<textarea rows="4" cols="50" id="banning-message" name="message">{$info.message|escape}</textarea>
					{formhelp note="Custom message the banned user will see when accessing the site."}
				{/forminput}
			</div>

			<div class="control-group submit">
				<input type="submit" name="save" value="{tr}Store Settings{/tr}" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .tidbits -->
{/strip}

{minifind}

{form}
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="offset" value="{$offset|escape}" />
	<input type="hidden" name="find" value="{$find|escape}" />
	<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />

	<table>
		<caption>{tr}Existing Banning Rules{/tr}</caption>
		<tr>
			<th style="width:1px"><input type="submit" name="del" value="{tr}Delete{/tr} " /></th>
			<th>{tr}Title{/tr}</th>
			<th>{tr}User/IP{/tr}</th>
			<th>{tr}Sections{/tr}</th>
			<th>{tr}Action{/tr}</th>
		</tr>

		{section name=user loop=$items}
			<tr class="{cycle values="even,odd" print=false}">
				<td><input type="checkbox" name="delsec[{$items[user].ban_id}]" /></td>
				<td><a href="{$smarty.const.KERNEL_PKG_URL}admin/admin_banning.php?ban_id={$items[user].ban_id}">{$items[user].title|escape}</a></td>
				<td>
					{if $items[user].mode eq 'user'}
						{$items[user].user}
					{else}
						{$items[user].ip1}.{$items[user].ip2}.{$items[user].ip3}.{$items[user].ip4}
					{/if}
				</td>
				<td>
					{section name=ix loop=$items[user].sections}
						{$items[user].sections[ix].section}{if not $smarty.section.ix.last},{/if}
					{/section}
				</td>
				<td>
					<a href="{$smarty.const.KERNEL_PKG_URL}admin/admin_banning.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;find={$find}&amp;remove={$items[user].ban_id}" onclick="return confirm('{tr}Are you sure you want to delete this rule?{/tr}')" title="Delete this rule">{biticon ipackage="icons" iname="edit-delete" iexplain="remove"}</a>&nbsp;&nbsp;
				</td>
			</tr>
		{sectionelse}
			<tr class="norecords"><td colspan="5">{tr}No records found{/tr}</td></tr>
		{/section}
	</table>
{/form}

{pagination}
