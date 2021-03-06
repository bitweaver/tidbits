{strip}
{form legend="User Settings"}
	<input type="hidden" name="page" value="{$page}" />
	{foreach from=$formFeatures key=feature item=output}
		<div class="form-group">
			{formlabel label=$output.label for=$feature}
			{forminput}
				{html_checkboxes name="settings[$feature]" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=$output.note page=$output.link}
			{/forminput}
		</div>
	{/foreach}

	<div class="form-group submit">
		<input type="submit" class="btn btn-default" name="features" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
