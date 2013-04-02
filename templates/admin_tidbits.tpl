{strip}
{form legend="User Settings"}
	<input type="hidden" name="page" value="{$page}" />
	{foreach from=$formFeatures key=feature item=output}
		<div class="control-group">
			{formlabel label=`$output.label` for=$feature}
			{forminput}
				{html_checkboxes name="settings[$feature]" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=`$output.note` page=`$output.link`}
			{/forminput}
		</div>
	{/foreach}

	<div class="control-group submit">
		<input type="submit" name="features" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
