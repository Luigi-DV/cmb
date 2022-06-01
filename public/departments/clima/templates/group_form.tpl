{* Smarty *}
<form method="POST" action="" target="_blank" onsubmit="return false;">
	<div class="form-group row col-md-12 align-items-center">
		<label for="nom" class="col-md-3 col-form-label">{#groupe#} :</label>
		<div class="col-md-6">
			<input id="nom" class="form-control" type="text" value="{$groupe.nom|xss_protect}" maxlength="150" />
		</div>
	</div>
	<div class="form-group row col-md-12 align-items-center">
	<div class="col-md-4 col-form-label"></div>
	<div class="col-md-4">
		<br />
		<input type="button" class="btn btn-primary align-items-center" value="{#enregistrer#|escape:'html'}" onclick="xajax_submitFormGroupe('{$groupe.groupe_id|escape}', document.getElementById('nom').value);"/>
	</div>
	</div>
</form>