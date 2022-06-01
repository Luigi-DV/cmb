<form method="POST" action="" target="_blank" id="projectForm">
	<input type="hidden" name="saved" id="saved" value="{$projet.saved}" />
	<input type="hidden" name="old_projet_id" id="old_projet_id" value="{$projet.projet_id}" />
	<input type="hidden" name="origine" id="origine" value="{$origine}" />
	<div class="hidden form-group row col-md-12">
		<label for="projet_id" class="col-md-4 col-form-label">{#winProjet_identifiant#} :</label>
		<div class="col-md-5">
			{if $projet.projet_id eq ''}
			<input class="form-control-plaintext" name="projet_id" id="projet_id" type="text" readonly value="{number_format(microtime(TRUE),4,"","")}"/>
			{else}
			<input class="form-control-plaintext" name="projet_id" id="projet_id" type="text" readonly value="{$projet.projet_id}"/>
			{/if}
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_nomProjet#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="nom" id="nom" type="text" maxlength="30" value="{$projet.nom}" />
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_groupe#} :</label>
		<div class="col-md-6">
			<select name="groupe_id" id="groupe_id" class="form-control select2">
				<option value="" {if $projet.groupe_id eq ""}selected="selected"{/if}></option>
				{foreach from=$groupes item=groupe}
					<option value="{$groupe.groupe_id}" {if $projet.groupe_id eq $groupe.groupe_id}selected="selected"{/if}>{$groupe.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Project Manager :</label>
		<div class="col-md-6">
			{if in_array("projects_manage_all", $user.tabDroits)}
				<select name="pm_id" id="pm_id" class="form-control{if $smarty.session.isMobileOrTablet==0} select2{/if}">
					<option value="" {if $createur.user_id eq ""}selected="selected"{/if}></option>
					{foreach from=$usersPM item=PM}
						<option value="{$PM.user_id}" {if isset($projet.pm_id) and $projet.pm_id eq $PM.user_id}selected="selected"{/if}>{$PM.nom}</option>
					{/foreach}
				</select>
			{else}
				{$createur.nom}
				<input type="hidden" name="pm_id" id="pm_id" value="{$createur.user_id}">
			{/if}
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Creator :</label>
		<div class="col-md-6">
				<select name="createur_id" id="createur_id" class="form-control{if $smarty.session.isMobileOrTablet==0} select2{/if}">
					<option value="" {if $createur.user_id eq ""}selected="selected"{/if}></option>
					{foreach from=$usersOwner item=owner}
						<option value="{$owner.user_id}" {if $createur.user_id eq $owner.user_id || ($createur.user_id eq "" && $owner.user_id eq $user.user_id)}selected="selected"{/if}>{$owner.nom}</option>
					{/foreach}
				</select>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_statut#} : </label>
		<div class="col-md-6">
			<select class="form-control" name="statut" id="statut">
				{foreach from=$listeStatus item=status}
					<option value="{$status.status_id}" {if (isset($projet.statut) and $projet.statut eq $status.status_id) or (!isset($projet.statut) and $defaut_status eq $status.status_id)}selected="selected"{/if}>{$status.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_billingstatut#} : </label>
		<div class="col-md-6">
			<select class="form-control" name="statut_bill" id="statut_bill">
				{foreach from=$listeStatusTaches item=status}
					<option value="{$status.status_id}" {if (isset($projet.statut_bill) and $projet.statut_bill eq $status.status_id) or (!isset($projet.statut_bill) and $defaut_status eq $status.status_id)}selected="selected"{/if}>{$status.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_standard#} :</label>
		<div class="col-md-6">
			<input type="text" class="form-control" name="charge" id="charge" maxlength="100" value="{$projet.charge}" />
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Folder : </label>
		
		<div class="col-md-6">
			<input class="form-control" style="width: 83%; display: inline;"{if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} id="ProjetURL" placeholder="Project Folder Link" size="23" value="{$projet.ProjetURL}"/>
		</div>
	
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Price :</label>
		<div class="col-md-5">
			<input type="text" class="form-control" name="price" id="price" maxlength="100" value="{$projet.price}" /> 
		</div>
		<div class="col-md-1 pt-1"> &euro; </div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_entrada#} :</label>
		<div class="col-md-8">
		{if $smarty.session.isMobileOrTablet==1}
			<input type="date" class="form-control" name="reception" id="reception" value="{$projet.reception|forceISODateFormat}" />
		{else}
			<input type="text" class="form-control datepicker" name="reception" id="reception" value="{$projet.reception|sqldate2userdate}" />		
		{/if}
		</div>
	</div>
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_livraison#} :</label>
		<div class="col-md-8">
		{if $smarty.session.isMobileOrTablet==1}
			<input type="date" class="form-control" name="livraison" id="livraison" value="{$projet.livraison|forceISODateFormat}" />
		{else}
			<input type="text" class="form-control datepicker" name="livraison" id="livraison" value="{$projet.livraison|sqldate2userdate}" />		
		{/if}
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_couleur#} :</label>
		<div class="col-md-6">
			{if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}
				{if $smarty.session.isMobileOrTablet==1}
					<input class="form-control" name="couleur" id="couleur" maxlength="6" type="color" list="colors" value="#{if $projet.couleur eq ''}{$couleurExProjet}{else}{$projet.couleur}{/if}" />
						<datalist id="colors">
							{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
								<option>{$couleurTmp}</option>
							{/foreach}
						</datalist>
				{else}
					<select name="couleur2" id="couleur2" class="form-control" style="background-color:#{$projet.couleur};color:{'#'|cat:$projet.couleur|buttonFontColor}">
						{if $projet.couleur eq ""}<option value="">{#winProjet_couleurchoix#}</option>{/if}
						{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
							<option value="{$couleurTmp|replace:'#':''}" style="background-color:{$couleurTmp};color:{$couleurTmp|buttonFontColor}" {if $couleurTmp eq "#"|cat:$projet.couleur}selected="selected"{/if}>{$couleurTmp|replace:'#':''}</option>
						{/foreach}
					</select>
				{/if}
			{else}
				{if $smarty.session.couleurExProjet neq ""}
                    {assign var=couleurExProjet value="000000"} <!--valor anterior: $smarty.session.couleurExProjet-->
                {else}
                    {assign var=couleurExProjet value="000000"}
                {/if}
				<input name="couleur" id="couleur" maxlength="6" {if $smarty.session.isMobileOrTablet==1}type="color"{else}type="text"{/if} value="#{if $projet.couleur eq ''}{$couleurExProjet}{else}{$projet.couleur}{/if}" />
			{/if}
		</div>
	</div>	
	<div class="hidden form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_lien#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="lien" id="lien" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} maxlength="255" value="{$projet.lien}" />
		</div>
		{if $projet.lien neq ""}
			<div class="col-md-2">
				<a class="btn btn-default tooltipster" title="{#winProjet_gotoLien#|escape}" href="{if $projet.lien|strpos:"http" !== 0 && $projet.lien|strpos:"\\" !== 0}http://{/if}{$projet.lien}" target="_blank"><i class="fa fa-share-square-o" aria-hidden="true"></i></a>
			</div>
		{/if}
	</div>

	
	<div class="form-group row col-md-12">
	<div class="col-md-4 col-form-label"></div>
		<div class="col-md-8">
			<br />
			<input type="button" value="{#enregistrer#|escape:"html"}" class="btn btn-primary" onClick="xajax_submitFormProjet('{$projet.projet_id}', $('#origine').val(), $('#projet_id').val(), $('#nom').val(), $('#groupe_id').val(), $('#statut_bill').val(),$('#statut').val(), $('#charge').val(),$('#livraison').val(),$('#reception').val(), $('#lien').val(), {if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}$('#couleur2 option:selected').val(){else}$('#couleur').val(){/if}, $('#createur_id').val(), $('#iteration').val(), $('#pm_id').val(), $('#price').val(), $('#ProjetURL').val())" />
		</div>
	</div>
</form>
<script>
	{literal}
	$('.tooltipster').tooltip({
		html: true,
		placement: 'auto',
		placement: 'auto',
		boundary: 'window'
	});
	{/literal}
	function openFolderWin() {
		window.open(document.getElementById('ProjetURL').value);
	}
</script>
