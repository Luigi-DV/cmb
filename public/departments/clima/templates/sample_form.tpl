<form method="POST" action="" target="_blank" id="sampleForm">
	<input type="hidden" name="saved" id="saved" value="{$sample.saved}" />
	<input type="hidden" name="old_sample_id" id="old_sample_id" value="{$sample.sample_id}" />
	<input type="hidden" name="origine" id="origine" value="{$origine}" />
	<div class="form-group row col-md-12">
		<label for="sample_id" class="col-md-4 col-form-label">{#winSample_identifiant#} :</label>
		<div class="col-md-5">
			{if $sample.sample_id eq ''}
			<input class="form-control-plaintext" name="sample_id" id="sample_id" type="text" readonly value="{number_format(microtime(TRUE),4,"","")}"/>
			{else}
			<input class="form-control-plaintext" name="sample_id" id="sample_id" type="text" readonly value="{$sample.sample_id}"/>
			{/if}
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_receptionDate#} (*) :</label>
		<div class="col-md-3">
		{if $smarty.session.isMobileOrTablet==1}
			<input type="date" class="form-control" name="r_date" id="r_date" value="{$sample.r_date|forceISODateFormat}" />
		{else}
			{if $sample.r_date eq ''}
			<input type="text" class="form-control datepicker" name="r_date" id="r_date" value="{date('d/m/Y')}" />	
			{else}
			<input type="text" class="form-control datepicker" name="r_date" id="r_date" value="{$sample.r_date|sqldate2userdate}" />		
			{/if}
		{/if}
		</div>
		<div class="col-md-4 pt-1">
		{#winSample_dateFormat#}
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_groupe#} (*) :</label>
		<div class="col-md-6">
			<select name="projet_id" id="projet_id" class="form-control select2">
				<option value="" {if $sample.projet_id eq ""}selected="selected"{/if}></option>
				{foreach from=$projets item=projet}
					<option value="{$projet.projet_id}" {if $sample.projet_id eq $projet.projet_id}selected="selected"{/if}>{$projet.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_charge#} :</label>
		<div class="col-md-2">
			<input type="number" class="form-control" name="n_samples" id="n_samples" min="0" maxlength="5" value="{$sample.n_samples}" onChange="{literal}
			if(this.value != 1)  $('#div_ns').addClass('hidden');
			if(this.value == 1)  $('#div_ns').removeClass('hidden');
			{/literal}"/>
		</div>
	</div>
	<div class="hidden form-group row col-md-12" id="div_ns">
		<label class="col-md-4 col-form-label">{#winSample_ns#} :</label>
		<div class="col-md-6">
			<input type="text" class="form-control" name="ns" id="ns"  value="{$sample.ns}" />
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_createur#} :</label>
		<div class="col-md-6 pt-2">
			{if $sample.user_id eq ''}
			<input name="user_id" id="user_id" type="hidden" readonly class="form-control-plaintext" value="{$user.user_id}"/>
			{else}
			<input name="user_id" id="user_id" type="hidden" readonly class="form-control-plaintext" value="{$sample.user_id}"/>
			{/if}
			<label class="form-check-label" for="user_id">{if $sample.user_id eq ''}{$user.nom}{else}{$createur.nom}{/if}</label>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_statut#} : </label>
		<div class="col-md-3">
			<select class="form-control" name="statut" id="statut" onChange="{literal}
			if(this.value == 'Received') $('#exit_date').addClass('hidden');
			if(this.value == 'Departed') $('#exit_date').removeClass('hidden');
			{/literal}">
					<option value="Received" {if (isset($sample.statut) and $sample.statut eq "Received") or !isset($sample.statut)}selected="selected"{/if}>{#winSample_received#}</option>
					<option value="Departed" {if (isset($sample.statut) and $sample.statut eq "Departed")}selected="selected"{/if}>{#winSample_departed#}</option>
			</select>
		</div>
	</div>
	<div class="{if $sample.e_date == ''}hidden{/if} form-group row col-md-12" id="exit_date">
		<label class="col-md-4 col-form-label">{#winSample_livraison#} (*) :</label>
		<div class="col-md-3">
		{if $smarty.session.isMobileOrTablet==1}
			<input type="date" class="form-control" name="e_date" id="e_date" value="{$sample.e_date|forceISODateFormat}" />
		{else}
			{if $sample.e_date eq ''}
			<input type="text" class="form-control datepicker" name="e_date" id="e_date" value="{date('d/m/Y')}" />	
			{else}
			<input type="text" class="form-control datepicker" name="e_date" id="e_date" value="{$sample.e_date|sqldate2userdate}" />		
			{/if}
		{/if}
		</div>
		<div class="col-md-4 left-2">
		{#winSample_dateFormat#}
		</div>
	</div>
	<div class="hidden form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_couleur#} :</label>
		<div class="col-md-6">
			{if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}
				{if $smarty.session.isMobileOrTablet==1}
					<input class="form-control" name="couleur" id="couleur" maxlength="6" type="color" list="colors" value="#{if $sample.couleur eq ''}{$couleurExProjet}{else}{$sample.couleur}{/if}" />
						<datalist id="colors">
							{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
								<option>{$couleurTmp}</option>
							{/foreach}
						</datalist>
				{else}
					<select name="couleur2" id="couleur2" class="form-control" style="background-color:#{$sample.couleur};color:{'#'|cat:$sample.couleur|buttonFontColor}">
						{if $sample.couleur eq ""}<option value="">{#winSample_couleurchoix#}</option>{/if}
						{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
							<option value="{$couleurTmp|replace:'#':''}" style="background-color:{$couleurTmp};color:{$couleurTmp|buttonFontColor}" {if $couleurTmp eq "#"|cat:$sample.couleur}selected="selected"{/if}>{$couleurTmp|replace:'#':''}</option>
						{/foreach}
					</select>
				{/if}
			{else}
                {if $smarty.session.couleurExProjet neq ""}
                    {assign var=couleurExProjet value=$smarty.session.couleurExProjet}
                {else}
                    {assign var=couleurExProjet value="ffffff"}
                {/if}
				<input name="couleur" id="couleur" maxlength="6" {if $smarty.session.isMobileOrTablet==1}type="color"{else}type="text"{/if} value="#{if $sample.couleur eq ''}{$couleurExProjet}{else}{$sample.couleur}{/if}" />
			{/if}
		</div>
	</div>	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_lien#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="lien" id="lien" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} maxlength="255" value="{$sample.lien}" />
		</div>
		{if $sample.lien neq ""}
			<div class="col-md-2">
				<a class="btn btn-default tooltipster" title="{#winSample_gotoLien#|escape}" href="{if $sample.lien|strpos:"http" !== 0 && $sample.lien|strpos:"\\" !== 0}http://{/if}{$sample.lien}" target="_blank"><i class="fa fa-share-square-o" aria-hidden="true"></i></a>
			</div>
		{/if}
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winSample_commentaires#} :</label>
		<div class="col-md-8">
			<textarea name="specif" id="specif" class="form-control" maxlength="255">{$sample.specif}</textarea>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<div class="col-md-8" style="font-size:9pt">
			&nbsp;(*) Required fields
		</div>
	</div>
	<div class="form-group row col-md-12">
	<div class="col-md-4 col-form-label"></div>
		<div class="col-md-8">
			<br />
			<input type="button" value="{#enregistrer1#|escape:"html"}" class="btn btn-primary" onClick="xajax_submitFormSample('{$sample.sample_id}', $('#origine').val(), $('#sample_id').val(), $('#r_date').val(), $('#projet_id option:selected').val(), $('#statut option:selected').val(), $('#n_samples').val(), $('#e_date').val(), $('#lien').val(), $('#couleur').val(), $('#user_id').val(), $('#specif').val(), $('#ns').val())" />
		</div>
	</div>
</form>
<script>
	{literal}
	$('.tooltipster').tooltip({
		html: true,
		placement: 'auto',
		boundary: 'window'
	});
	{/literal}
</script>
