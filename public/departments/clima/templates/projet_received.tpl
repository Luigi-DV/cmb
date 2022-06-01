<form method="POST" action="" target="_blank" id="projectReceived">
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
	
	<div class="hidden">
		<label class="col-md-4 col-form-label">{#winProjet_nomProjet#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="nom" id="nom" type="text" maxlength="30" value="{$projet.nom}" />
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_nomProjet#} :</label>
		<div class="col-md-8">
		<select name="newProjetId" id="newProjetId" class="form-control {if !$smarty.session.isMobileOrTablet}select2{/if}" tabindex="1" style="width:100%">
			<option value="{$projet.nom}">{$projet.nom}</option>
			{assign var="groupeCourant" value="-1"}
			{foreach from=$listeProjets item=projetTmp}
				{if $groupeCourant != $projetTmp.groupe_id}
					{assign var="groupeCourant" value=$projetTmp.groupe_id}
					{if $projetTmp.groupe_id == ""}
						{assign var="nomgroupe" value=#projet_liste_sansGroupes#}
					{else}
						{assign var="nomgroupe" value=$projetTmp.nom_groupe}
					{/if}
					<optgroup label="{$nomgroupe}"></optgroup>
				{/if}
				<option value="{$projetTmp.projet_id}" {if $projet.projet_id eq $projetTmp.projet_id}selected="selected"{/if} {if isset($projet_id_choisi) && $projet_id_choisi eq $projetTmp.projet_id}selected="selected"{/if}>{$projetTmp.nom} {if $projetTmp.nom_groupe neq ''} ({$nomgroupe}){/if}</option>
			{/foreach}
		</select>
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">{#winProjet_groupe#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="customer" id="customer" type="text" maxlength="30" value="{$projet.customer}" />
		</div>
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Price :</label>
		<div class="col-md-5">
			<input type="text" class="form-control" name="price" id="price" maxlength="100" value="{$projet.price}" /> 
		</div>
		<div class="col-md-1 pt-1"> &euro; </div>
	</div>
	
	<div class='col-md-12'><hr /></div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Offer : </label>
		
		<div class="col-md-6">
			<input type="hidden" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} id="OfferURL" placeholder="Offer Documentation Link" size="30" value="{$projet.OfferURL}"/>
			<input type="button" value="Open Offer" onclick="openOfferWin()" />
		</div>
	
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Purchase Order : </label>
		
		<div class="col-md-6">
			<input type="hidden" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} id="CommandURL" placeholder="Client Command Link" size="30" value="{$projet.CommandURL}"/>
			<input type="button" value="Open Client Command" onClick="openCommandWin()" />
		</div>
	
	</div>
	
	<div class="form-group row col-md-12">
		<label class="col-md-4 col-form-label">Folder : </label>
		
		<div class="col-md-6">
			<input type="hidden" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} id="FolderURL" placeholder="Offer Folder Link" size="30" value="{$projet.FolderURL}"/>
			<input type="button" value="Open Offer Folder" onclick="openFolderWin()" />
		</div>
	
	</div>

	<div class="form-group row col-md-12">
	<div class="col-md-4 col-form-label"></div>
		<div class="col-md-8">
			<br />
			<input type="button" value="Accept" class="btn btn-primary" onClick="xajax_acceptProjet('0', $('#customer').val(), $('#projet_id').val(), $('#newProjetId').val(), $('#nom').val(), $('#price').val())"/>
			<input type="button" value="Reject" class="btn btn-primary" onClick="xajax_formRejected($('#projet_id').val())" />
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
	function openOfferWin() {
		window.open(document.getElementById('OfferURL').value);
	}
	function openCommandWin() {
		window.open(document.getElementById('CommandURL').value);
	}
	function openFolderWin() {
		window.open(document.getElementById('FolderURL').value);
	}
</script>
