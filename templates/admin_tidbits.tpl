{strip}
{form legend="User Settings"}
	<input type="hidden" name="page" value="{$page}" />
	{foreach from=$formFeatures key=feature item=output}
		<div class="row">
			{formlabel label=`$output.label` for=$feature}
			{forminput}
				{html_checkboxes name="settings[$feature]" values="y" checked=`$gBitSystemPrefs.$feature` labels=false id=$feature}
				{formhelp note=`$output.note` page=`$output.link`}
			{/forminput}
		</div>
	{/foreach}

	<div class="row submit">
		<input type="submit" name="features" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
