{* Smarty *}
<form method="post" action="" target="_blank" onsubmit="return false;">
	<div class="form-group row col-md-12 align-items-center">
		<label class="col-md-4 col-form-label">{#feries_date#} :</label>
		<div class="col-md-7">
			{if $smarty.session.isMobileOrTablet==1}
				<input type="date" class="form-control" id="date_ferie" maxlength="10" value="{$ferie.date_ferie|forceISODateFormat}" />		
			{else}
				<input type="text" class="form-control datepicker" id="date_ferie" maxlength="10" value="{$ferie.date_ferie|sqldate2userdate}" />		
			{/if}
			
		</div>
	</div>
	<div class="form-group row col-md-12 align-items-center">
		<label class="col-md-4 col-form-label">{#feries_libelle#} :</label>
		<div class="col-md-6">
			<input id="libelle" maxlength="50" type="text" value="{$ferie.libelle}" class="form-control" />
		</div>
	</div>
	<div class="form-group row col-md-12 align-items-center">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<br />
			<input type="button" class="btn btn-primary" value="{#enregistrer#|escape:"html"}" onclick="xajax_submitFormFerie(document.getElementById('date_ferie').value, document.getElementById('libelle').value, 'ffe0cc', 1);"/>
		</div>
</form>