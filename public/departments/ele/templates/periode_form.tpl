{* Smarty *}
<form class="form-horizontal" method="POST" target="_blank" id="periodForm">
	<input type="hidden" id="periode_id" name="periode_id" value="{$periode.periode_id}" />
	<input type="hidden" id="saved" name="saved" value="{$periode.saved}" />
	<div class="container-fluid">
	{* Fila en la cual se define el título que llevará la reserva (identificador)*}
	<div class="form-group row col-md-12">
		<label class="col-md-2 col-form-label">{#winPeriode_titre#} :</label>
		<div class="col-md-10">
			<input type="text" class="form-control" name="titre" id="titre" maxlength="2000" value="{$periode.titre|xss_protect}" onFocus="xajax_autocompleteTitreTache($('#projet_id').val());"   data-provide="typeahead" tabindex="21" />
		</div>
	</div>

	<div class="form-group row col-md-12">
		{*Fila en la cual se escoje el proyecto asociado a la rerserva, se selecciona a partir de los elementos de la base de datos*}
			<label class="col-md-2 col-form-label">{#winPeriode_projet#} :</label>
			<div class="col-md-4">
				<select name="projet_id" id="projet_id" class="form-control {if !$smarty.session.isMobileOrTablet}select2{/if}" tabindex="1" style="width:100%" onChange="xajax_checkTaskDate(this.value, $('#date_debut').val(), $('#dateFinRepetitionJour').val()); xajax_getProjectCreator(this.value);">
					<option value="">- - - - - - - - - - -</option>
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
						<option value="{$projetTmp.projet_id}" {if $periode.projet_id eq $projetTmp.projet_id}selected="selected"{/if} {if isset($projet_id_choisi) && $projet_id_choisi eq $projetTmp.projet_id}selected="selected"{/if}>{$projetTmp.nom} {if $projetTmp.nom_groupe neq ''} ({$nomgroupe}){/if}</option>
					{/foreach}
				</select>
				<!--Navigate to Global App -->
				{foreach from=$listeProjets item=projetTmp}
					{if ($periode.projet_id eq $projetTmp.projet_id) || (isset($projet_id_choisi) && $projet_id_choisi eq $projetTmp.projet_id)}
						<div class="item-start">
							<a target="_blank" href="{$_SERVER['DOCUMENT_ROOT']}/nvh/projects/{$projetTmp.projet_id}">
								Navigate to project 
							</a>
						</div>
					{/if}
				{/foreach} 
			</div>
			{*Fila en la cual se escoje el recurso, en nuestro caso se trata de la sala dónde se realizará el ensayo, tal vez habria que hacer que dejara de ser un multiseleccionable, ya que siempre será la que se selecciona sobre la planificación*}
			<label class="col-md-2 col-form-label">{#winPeriode_user#}:</label>
			<div class="col-md-4">
				<select multiple="multiple" name="user_id" id="user_id" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="2" style="width:100%">
					{assign var=groupeTemp value=""}
					{foreach from=$listeUsers item=userCourant name=loopUsers}
						{if $userCourant.user_groupe_id neq $groupeTemp}
							<optgroup label="{$userCourant.groupe_nom}">
						{/if}
						<option value="{$userCourant.user_id}" {if $periode.user_id eq $userCourant.user_id}selected="selected"{/if} {if isset($user_id_choisi) && $user_id_choisi eq $userCourant.user_id}selected="selected"{/if}>{$userCourant.nom}</option>
						{if $userCourant.user_groupe_id neq $groupeTemp}
							</optgroup>
						{/if}
						{assign var=groupeTemp value=$userCourant.user_groupe_id}
					{/foreach}
				</select>
			</div>
		</div>
		
		
		
		{if isset($periode_premiere) && isset($periode_derniere)}
		
		<div class='col-md-12'><hr /></div>
		<div class="form-group row col-md-12">
			<label class="col-md-2 col-form-label">{#winPeriode_debut#} :</label>
			<div class="col-md-2">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" name="periode_premiere" id="periode_premiere" maxlength="10" value="{$periode_premiere.date_debut|forceISODateFormat}" onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');"/>
				{else}
					<input type="text" class="form-control datepicker" name="periode_premiere" id="periode_premiere" maxlength="10" value="{$periode_premiere.date_debut|sqldate2userdate}" onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');"/>
				{/if}
			</div>
			<label class="col-md-2 col-form-label">{#winPeriode_repeter_jusque#} :</label>
			<div class="col-md-2">
			{if $smarty.session.isMobileOrTablet==1}
				<input type="date" class="form-control" id="periode_derniere" value="{$periode_derniere.date_debut|sqldate2userdate}" size="11" maxlength="10" onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
			{else}
				<input type="text" class="form-control datepicker" id="periode_derniere" value="{$periode_derniere.date_debut|sqldate2userdate}" size="11" maxlength="10" onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
			{/if}
			</div>
		
			<label class="col-md-1 col-form-label">Day :</label>
			<div class="col-md-2">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|forceISODateFormat}" tabindex="4"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());" readonly/>
				{else}
					<input type="text" class="form-control datepicker" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|sqldate2userdate}" tabindex="4"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());" readonly/>
				{/if}
			</div>
		</div>
		
		{else}
		
		<div class='col-md-12'><hr /></div>
		<div class="form-group row col-md-12">
			<label class="col-md-2 col-form-label">{#winPeriode_debut#} :</label>
			<div class="col-md-2">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|forceISODateFormat}" tabindex="4"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
				{else}
					<input type="text" class="form-control datepicker" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|sqldate2userdate}" tabindex="4"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
				{/if}
			</div>
			
			<label class="col-md-2 col-form-label">{#winPeriode_repeter_jusque#} :</label>
			<div class="col-md-2">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" id="dateFinRepetitionJour" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
				{else}
					<input type="text" class="form-control datepicker" id="dateFinRepetitionJour" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18"  onChange="xajax_checkTaskDate($('#projet_id').val(), $('#date_debut').val(), $('#dateFinRepetitionJour').val());xajax_checkHoliday($('#lieu option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
				{/if}
			</div>
		</div>
		
		{/if}
		
		
		<div class="form-group row col-md-12">
			<label class="col-md-3 col-form-label">Choose kind of shift :</label>
			<div class="col-md-9 pt-2">	
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="allday" id="allday" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'duree' || !$periode.duree_details}checked="checked"{/if}>
						<label class="form-check-label" for="allday">All day shift </label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="matin" id="matin" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'AM'}checked="checked"{/if}>
						<label class="form-check-label" for="matin">{#winPeriode_matin#} </label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="apresmidi" id="apresmidi" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'PM'}checked="checked"{/if}>
						<label class="form-check-label" for="apresmidi">{#winPeriode_apresmidi#} </label>
					</div>
					<div class="hidden form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="nuit" id="nuit" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'N'}checked="checked"{/if}>
						<label class="form-check-label" for="nuit">{#winPeriode_nuit#} </label>
					</div>
			</div>
		</div>
		
		<div class="form-group row col-md-12">
			
			<div class="hidden">
			{*<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="radioChoixFin" id="radioChoixFinDate" value="" {if $periode.duree_details eq ""}checked="checked"{/if} onChange="$('#divFinChoixDate').removeClass('d-none');$('#divFinChoixDuree').addClass('d-none');" tabindex="5">
				<label class="form-check-label" for="radioChoixFinDate">{#winPeriode_finChoixDate#}</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="radioChoixFin" id="radioChoixFinDuree" value="" {if $periode.duree_details neq ""}checked="checked"{/if} onChange="$('#divFinChoixDuree').removeClass('d-none');$('#divFinChoixDate').addClass('d-none');" tabindex="6">
				<label class="form-check-label" for="radioChoixFinDuree">{#winPeriode_finChoixDuree#}</label>
			</div>
			</div>*}
			{* TO HIDE, aparece opción de seleccionar una fecha final, crea toda la reserva como un bloque, no es nada útil a la hora de realizar las reservas*}
			<div class="hidden" id="divFinChoixDate">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="hidden" name="date_fin" id="date_fin" maxlength="10" value="{$periode.date_fin|forceISODateFormat}" onFocus="remplirDateFinPeriode();videChampsFinTache(this.id);" onChange="videChampsFinTache(this.id);" tabindex="7" />
				{else}
					<input type="text" class="hidden" name="date_fin" id="date_fin" maxlength="10" value="{$periode.date_fin|sqldate2userdate}" onFocus="remplirDateFinPeriode();videChampsFinTache(this.id);" onChange="videChampsFinTache(this.id);" tabindex="7" />
				{/if}
				&nbsp;{#winPeriode_ouNBJours#} :&nbsp;
				<input type="number" class="hidden" name="nb_jours" id="nb_jours" size="2"  onChange="videChampsFinTache(this.id);" tabindex="10" />
			{if $periode.periode_id neq 0 && $periode.date_fin neq ""}
				<label class="hidden" ><input type="checkbox" id="conserver_duree" name="conserver_duree" value="1" onClick="videChampsFinTache('');" tabindex="11" />{#winPeriode_conserverDuree#|sprintf:$nbJours}</label>
			{else}
				<input type="hidden" id="conserver_duree" value="" />
			{/if}
			</div>
		</div>
		{* Formulario en el cual se puede realizar la reserva por horas en lugar de por dias, se quiere conservar la opción de mañana o de tarde. OCULTAR TODO LO DEMAS*}
			<div class="hidden row col-md-12 form-inline  id="divFinChoixDuree">
			<label class="hidden col-md-1 col-form-label">{#winPeriode_fin#} :</label>
				<div class="hidden offset-md-1 col-md-6">
					<span title="{#winPeriode_FormatDuree#|xss_protect}" class="cursor-help tooltipster">&nbsp;<i class="hidden" aria-hidden="true"></i></span> 
					<input type="time" class="form-control" name="duree" id="duree" size="3" value="{if $periode.duree_details eq 'duree'}{$periode.duree|sqltime2usertime}{/if}" onFocus="if(this.value == '')this.value='{$smarty.const.CONFIG_DURATION_DAY|usertime2sqltime:"short"}';" onChange="videChampsFinTache(this.id);" tabindex="12" />
				</div>
{* TO HIDE solo se quiere dejar la opción de mañana o tarde*}
				<div class="hidden">
					{#winPeriode_heureDebut#} <span title="{#winPeriode_FormatDuree#|xss_protect}" class="cursor-help tooltipster">&nbsp;<i class="fa fa-question-circle" aria-hidden="true"></i></span> :
					<input type="time" class="form-control" id="heure_debut" id="heure_debut" size="3"  value="{if isset($periode.duree_details_heure_debut)}{$periode.duree_details_heure_debut|sqltime2usertime}{/if}" onChange="videChampsFinTache(this.id);" tabindex="13" />
					{#winPeriode_heureFin#} <span title="{#winPeriode_FormatDuree#|xss_protect}" class="cursor-help tooltipster">&nbsp;<i class="fa fa-question-circle" aria-hidden="true"></i></span> :
					<input type="time" class="form-control" id="heure_fin" size="3" value="{if isset($periode.duree_details_heure_fin)}{$periode.duree_details_heure_fin|sqltime2usertime}{/if}" onChange="videChampsFinTache(this.id);" tabindex="14" />
				</div>
			</div>
		<div class="hidden form-group row col-md-12">
			{if !isset($estFilleOuParente)}
				<input type="hidden" id="appliquerATous" value="0">
				<label class="col-md-2 col-form-label">{#winPeriode_repeter#} :</label>
				<div class="col-md-4">
					<select name="repetition" id="repetition" onChange="{literal}
						if(this.value=='jour')
						{
							$('#divOptionsRepetitionJour').removeClass('d-none');
						}else{
							$('#divOptionsRepetitionJour').addClass('d-none');
							$('#divOptionsjourderepetition').addClass('d-none');
							$('#divOptionsRepetitionJS').addClass('d-none');
						}
						if(this.value=='semaine')
						{
							$('#divOptionsRepetitionJS').removeClass('d-none');
							$('#divOptionsRepetitionSemaine').removeClass('d-none');
							$('#divExceptionRepetition').removeClass('d-none');
						}else{
							$('#divOptionsRepetitionSemaine').addClass('d-none');
							$('#divOptionsjourderepetition').addClass('d-none');
							$('#divOptionsRepetitionJS').addClass('d-none');
						}
						if(this.value=='mois'){
							$('#divOptionsRepetitionMois').removeClass('d-none');
							$('#divOptionsjourderepetition').removeClass('d-none');
							$('#divExceptionRepetition').removeClass('d-none');
						}else{
							$('#divOptionsRepetitionMois').addClass('d-none');
						}
						if(this.value==''){
							$('#divOptionsRepetitionJour').addClass('d-none');
							$('#divOptionsRepetitionSemaine').addClass('d-none');
							$('#divOptionsRepetitionMois').addClass('d-none');
							$('#divExceptionRepetition').addClass('d-none');
							$('#divOptionsjourderepetition').addClass('d-none');
							$('#divOptionsRepetitionJS').addClass('d-none');
						}
						{/literal}" class="form-control" tabindex="18">
							{*<option value="">{#winPeriode_repeter_pasderepetition#}</option>*}
							<option value="jour">{#winPeriode_repeter_jour#}</option>
							{*<option value="semaine">{#winPeriode_repeter_semaine#}</option>
							<option value="mois">{#winPeriode_repeter_mois#}</option>*}
					</select>
				</div>
				<div class="col-md-6 form-row form-inline">
						<div id="divOptionsRepetitionJour" class="d-none form-group form-inline" tabindex="19">&nbsp;
							<select name='nbRepetitionJour' id='nbRepetitionJour' class="hidden">
							<option value="1">1</option>
							</select>
							&nbsp;{#winPeriode_jour#}&nbsp;{#winPeriode_repeter_jusque#}&nbsp;
							
						</div>
						<div id="divOptionsRepetitionSemaine" class="d-none form-group form-inline" tabindex="20">
							{#winPeriode_repeter_tousles#}&nbsp;
							<select name='nbRepetitionSemaine' id='nbRepetitionSemaine' class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
							&nbsp;{#winPeriode_semaine#}&nbsp;{#winPeriode_repeter_jusque#}&nbsp;
							{if $smarty.session.isMobileOrTablet==1}
								<input type="date" class="form-control" id="dateFinRepetitionSemaine" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{else}
								<input type="text" class="form-control datepicker" id="dateFinRepetitionSemaine" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{/if}
						</div>
						<div id="divOptionsRepetitionJS" class="d-none form-group form-inline">
							<label class="col-form-label">{#winPeriode_repeter_jourderepetition#} :&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine1" value="1" checked="checked">
								<label class="form-check-label" for="jourSemaine1">{#initial_day_1#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine2" value="2">
								<label class="form-check-label" for="jourSemaine2">{#initial_day_2#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine3" value="3">
								<label class="form-check-label" for="jourSemaine3">{#initial_day_3#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine4" value="4">
								<label class="form-check-label" for="jourSemaine4">{#initial_day_4#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine5" value="5">
								<label class="form-check-label" for="jourSemaine5">{#initial_day_5#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine6" value="6">
								<label class="form-check-label" for="jourSemaine6">{#initial_day_6#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="jourSemaine" id="jourSemaine0" value="0">
								<label class="form-check-label" for="jourSemaine0">{#initial_day_0#}</label>
							</div>
						</div>
						<div id="divOptionsRepetitionMois" class="d-none form-group form-inline" tabindex="18">
							{#winPeriode_repeter_tousles#}&nbsp;
							<select name='nbRepetitionMois' id='nbRepetitionMois' class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
							&nbsp;{#winPeriode_mois#}&nbsp;{#winPeriode_repeter_jusque#}&nbsp;
							{if $smarty.session.isMobileOrTablet==1}
								<input type="date" class="form-control" id="dateFinRepetitionMois" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{else}
								<input type="text" class="form-control datepicker" id="dateFinRepetitionMois" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{/if}
							</div>
							<div id="divOptionsjourderepetition" class="d-none form-group form-inline">
							<label class="col-form-label">{#winPeriode_repeter_jourderepetition#} :&nbsp;&nbsp;&nbsp;</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="radioChoixJourRepetition" id="radioChoixJourRepetition" value="0" checked="checked">
								<label class="form-check-label" for="radioChoixJourRepetition">{#winPeriode_repeter_jourderepetition_jourmois#}</label>
							</div>
							</div>	
							<div id="divExceptionRepetition" class="form-group form-inline d-none" tabindex="19">
							<label class="col-form-label">{#winPeriode_repeter_exception_siferie#} :&nbsp;&nbsp;&nbsp;</label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="exceptionRepetition" id="exceptionRepetition1" value="1" >
								<label class="form-check-label" for="exceptionRepetition1">{#winPeriode_repeter_exception_decaler#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="exceptionRepetition" id="exceptionRepetition2" value="2">
								<label class="form-check-label" for="exceptionRepetition2">{#winPeriode_repeter_exception_pasajout#}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="exceptionRepetition" id="exceptionRepetition3" value="3" checked="checked">
								<label class="form-check-label" for="exceptionRepetition3">{#winPeriode_repeter_exception_ajout#}</label>
							</div>
						</div>
					</div>
			{else}
					<label class="col-md-2 col-form-label">{#winPeriode_repeter#} :</label>
					<div class="col-md-10 col-form-label">
						<b>{#winPeriode_recurrente#}{$prochaineOccurence|sqldate2userdate}</b>
					</div>
					<input type="hidden" name="repetition" id="repetition" value="" />
					<input type="hidden" name="dateFinRepetitionJour" id="dateFinRepetitionJour" value="" />
					<input type="hidden" name="dateFinRepetitionSemaine" id="dateFinRepetitionSemaine" value="" />
					<input type="hidden" name="dateFinRepetitionMois" id="dateFinRepetitionMois" value="" />
					<input type="hidden" name="nbRepetitionJour" id="nbRepetitionJour" value="" />
					<input type="hidden" name="nbRepetitionSemaine" id="nbRepetitionSemaine" value="" />
					<input type="hidden" name="nbRepetitionMois" id="nbRepetitionMois" value="" />
					<input type="hidden" name="jourSemaine" id="jourSemaine" value="" />
			{/if}
		</div>
		<div class='col-md-12'><hr /></div>
		<div class="hidden form-group row col-md-12">
			<label class="hidden col-md-2 col-form-label">{#winPeriode_statut#}:</label>
			<div class="hidden col-md-4">
				<select name="statut_tache" id="statut_tache" class="form-control" tabindex="19">
				{foreach from=$listeStatus item=status}
					<option value="{$status.status_id}" {if (isset($periode.statut_tache) and $periode.statut_tache eq $status.status_id) or (!isset($periode.statut_tache) and $defaut_status eq $status.status_id)}selected="selected"{/if}>{$status.nom}</option>
				{/foreach}
				</select>
			</div>
			<label class="col-md-2 col-form-label">{#winPeriode_livrable#} :</label>
			<div class="col-md-4" >
				<select name="livrable" id="livrable" class="form-control" tabindex="21">
					<option value="oui" {if $periode.livrable eq "oui"}selected="selected"{/if}>{#oui#}</option>
					<option value="non" {if $periode.livrable eq "non"}selected="selected"{/if}>{#non#}</option>
				</select>
			</div>
		</div>
		<div class="form-group row col-md-12">
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 1 }
			<div class="col-md-2 col-form-label">{#winPeriode_lieu#} :
				<a href="{$BASE}/www/process/planning_user.php?dimensionCase=reduit&afficherTableauRecap=0" class="dropdown-item" target="_blank">
					<i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>
				</a>
			</div>
			<div class="col-md-3">
				<select multiple="multiple" name="lieu" id="lieu" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="22" style="width:100%" onChange="xajax_checkHoliday(getSelectValue('lieu'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
					{assign var=groupeTemp value=""}
					{foreach from=$listeLieuxTech item=lieuTmp}
						<option value="{$lieuTmp.lieu_id}" {if in_array($lieuTmp.lieu_id, $periode.lieu)} selected="selected" {/if}>{$lieuTmp.nom}</option>
						{assign var=groupeTemp value=$lieuTmp.lieu_id}
					{/foreach}
				</select>
				<span id="divStatutCheckHoliday1"></span>
				<span id="divStatutCheckHoliday2"></span>
				<span id="divStatutCheckHoliday3"></span>
				<span id="divStatutCheckHoliday4"></span>
				<span id="divStatutCheckHoliday5"></span>
			</div>
		{/if}		
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 }
		    <div class="col-md-2 col-form-label">{#winPeriode_ressource#} :
				<a href="{$BASE}/www/process/planning_equi.php?dimensionCase=reduit&afficherTableauRecap=0" class="dropdown-item" target="_blank">
						<i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>
				</a>
			</div>
				<div class="col-md-4">
					<select multiple="multiple" name="ressource" id="ressource" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="23" style="width:100%" onChange="xajax_checkRessource(getSelectValue('ressource'), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}');">
						{assign var=groupeTemp value=""}
						{foreach from=$listeRessources item=ressourceTmp}
							<option value="{$ressourceTmp.ressource_id}" {if in_array($ressourceTmp.ressource_id, $periode.ressource)}selected="selected"{/if}>{$ressourceTmp.nom}</option>
							{if $ressourceTmp.ressource_groupe_id neq $groupeTemp}
								</optgroup>
							{/if}
							{assign var=groupeTemp value=$ressourceTmp.ressource_groupe_id}
						{/foreach}
					</select>
					<span id="divStatutCheckRessourceId1"></span>
					<span id="divStatutCheckRessourceId2"></span>
					<span id="divStatutCheckRessourceId3"></span>
					<span id="divStatutCheckRessourceId4"></span>
					<span id="divStatutCheckRessourceId5"></span>
					<span id="divStatutCheckRessourceId6"></span>
					<span id="divStatutCheckRessourceId7"></span>
					<span id="divStatutCheckRessourceId8"></span>
					<span id="divStatutCheckRessourceId9"></span>
					<span id="divStatutCheckRessourceId10"></span>
					<span id="divStatutCheckRessourceId11"></span>
					<span id="divStatutCheckRessourceId12"></span>
					<span id="divStatutCheckRessourceId13"></span>
					<span id="divStatutCheckRessourceId14"></span>
					<span id="divStatutCheckRessourceId15"></span>
					<span id="divStatutCheckRessourceId16"></span>
					<span id="divStatutCheckRessourceId17"></span>
					<span id="divStatutCheckRessourceId18"></span>
					<span id="divStatutCheckRessourceId19"></span>
					<span id="divStatutCheckRessourceId20"></span>
				</div>
		
		{/if}
		</div>
		<div class="form-group row col-md-12">
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 0 }
		<input type="hidden" name="lieu" id="lieu" value="">
		{/if}
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 0 }
		<input type="hidden" name="ressource" id="ressource" value="">
		{/if}
		</div>
		<div class="form-group row col-md-12">
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 1 }
			<div class="col-md-2 col-form-label">Test Manager:</div>
			<div class="col-md-3">
				<select name="lieu_TM" id="lieu_id_TM" class="form-control" tabindex="22" style="width:100%"
				onChange="xajax_checkHoliday($('#lieu_id option:selected').val(), $('#date_debut').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'),$('#nuit').is(':checked'), $('#dateFinRepetitionJour').val(), '{$periode.periode_id}'); ">
					<option value="">Without TM</option>
					{foreach from=$listeLieuxTM item=lieuTmp_TM}
						<option value="{$lieuTmp_TM.lieu_id}"
							{if isset($projectCreator)} selected="selected" {/if}
							{if $lieuTmp_TM.lieu_id === $projet.createur_id} selected="selected" {/if}>
							{$lieuTmp_TM.nom}
						</option>
					{/foreach}
				</select>
			</div>
		{/if}
		</div>
		
		<div class='col-md-12'><hr /></div>

		<div class="form-group row col-md-12">
			<label class="col-md-2 col-form-label">{#winPeriode_lien#} :</label>
			<div class="col-md-10 form-inline">
				<input {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} class="form-control col-md-11{if $periode.lien neq ""} input-withicon{/if}" name="lien" id="lien" maxlength="2000" value="{$periode.lien}" tabindex="24" />
				{if $periode.lien neq ""}
					<span title='{#winPeriode_gotoLien#|xss_protect}' onclick="window.open('{if ($periode.lien|strpos:"http" !== FALSE || $periode.lien|strpos:"ftp" !== FALSE) && $periode.lien|strpos:"\\" !== FALSE}http://{/if}{$periode.lien}', '_blank')" target="_blank" class="btn btn-default tooltipster ml-1"><i class="fa fa-share-square-o" aria-hidden="true"></i></span>
				{/if}
			</div>
		</div>
		<div class="form-group row col-md-12">
			<label class="col-md-2 col-form-label">{#winPeriode_commentaires#} :</label>
			<div class="col-md-10 form-inline">
				<textarea class="form-control col-md-11" rows="1" id="notes" name="notes" tabindex="230" >{$periode.notes_xajax|xss_protect}</textarea>
			</div>
		</div>
		<div class="form-group row col-md-12">
			<label class="hidden">{#winPeriode_custom#} :</label>
			<div class="hidden">
				<input type="text" class="form-control float-left input-withicon" name="custom" id="custom" maxlength="255" value="" tabindex="23" />
				<div title='{#winPeriode_custom_aide#|xss_protect}' class="glyphicon glyphicon-question-sign cursor-help small tooltipster ml-2"></div>
			</div>
		</div>
		
		<div class='col-md-12'><hr /></div>
		
		<div class="form-group row col-md-12">
			<label class="col-md-2 col-form-label">Price :</label>
			<div class="col-md-4">
				<input type="text" class="form-control" name="price" id="price" maxlength="100" value="{$periode.price}" /> 
			</div>
			<div class="col-md-1 pt-1"> &euro; </div>
		</div>
		
		<div class="row col-md-12">
			<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="sample" id="sample" {if $periode.sample == '1'}checked="checked"{/if}>
					<label class="form-check-label" for="sample"> Mostra al Laboratori </label>
			</div>
		</div>

		{if !isset($projet) || in_array("projects_manage_all", $user.tabDroits) || (in_array("tasks_modify_own_project", $user.tabDroits) && isset($projet) && $user.user_id eq $projet.createur_id) || in_array("tasks_modify_all", $user.tabDroits) || (in_array("tasks_modify_own_task", $user.tabDroits) && $periode.user_id eq $user.user_id)}
			{assign var=buttonSubmitTache value=1}
		{else}
			{assign var=buttonSubmitTache value=0}
		{/if}

		<div id="divSubmitPeriode" class="form-group row col-md-12 justify-content-end {if $buttonSubmitTache eq 0}d-none{/if}">
			<span id="divCheckTaskDate1"></span>
			<span id="divCheckTaskDate2"></span>
			<span id="divCheckTaskDate3"></span>
			{if $smarty.const.CONFIG_SMTP_HOST neq ''}
				<div class="hidden form-check form-check-inline">
						<input class="form-check-input" type="checkbox" id="notif_email" checked="checked">
						<label class="form-check-label" for="notif_email" style="font-weight:normal" class="padding-right-25">{#winPeriode_notif_email#}</label>
				</div>
			{else}
				<input type="hidden" id="notif_email" value="false">
			{/if}
			
		{if isset($estFilleOuParente)}
		<hr />
		<div class="row col-md-12">
			<div class="col-md-8 offset-md-8">
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="appliquerATous" checked="checked" value="1"><label class="form-check-label" for="appliquerATous">{#winPeriode_appliquerATous#}</label>
			</div>
			</div>
		</div>
		<hr />
		{/if}
			<div class="btn-group" role="group">
				{if $audit_id neq ''}
				<a href="javascript:xajax_modifAudit('{$audit_id}');undefined;" class="btn btn-default" ><i class="fa fa-history fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#audit_restaurer#}</a>
				{/if}
				{if $periode.periode_id neq 0}
					<button type="button" class="btn btn-info" onClick="if(confirm('{#winPeriode_confirmSuppr#|xss_protect}'))xajax_supprimerPeriode({$periode.periode_id}, false, $('#notif_email').is(':checked'));">{#winPeriode_supprimer#}</button>
					<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_dupliquer#|xss_protect} ?'))xajax_ajoutPeriode('', '', {$periode.periode_id});">{#winPeriode_dupliquer#}</button>
					{if isset($estFilleOuParente)}
						<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|xss_protect}'))xajax_supprimerPeriode({$periode.periode_id}, true, $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition#}</button>
						<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|xss_protect}'))xajax_supprimerPeriode({$periode.periode_id}, 'avant', $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition_avant#}</button>
						<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|xss_protect}'))xajax_supprimerPeriode({$periode.periode_id}, 'apres', $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition_apres#}</button>
					{/if}
				{/if}
				<button type="button" id="butSubmitPeriode" class="btn btn-primary" tabindex="24" onClick="
					$('#divPatienter').removeClass('d-none');
					this.disabled=true;
					users_ids=getSelectValue('user_id');
					lieus_ids=getSelectValue('lieu');
					ressources_ids=getSelectValue('ressource');
					xajax_changeProjectTM($('#projet_id').val(), $('#lieu_id_TM').val());
					xajax_submitFormPeriode('{$periode.periode_id}', $('#projet_id').val(), users_ids, $('#date_debut').val(), $('#conserver_duree').is(':checked'), $('#date_fin').val(), $('#nb_jours').val(), $('#duree').val(), $('#heure_debut').val(), $('#heure_fin').val(), 
											$('#matin').is(':checked'), $('#apresmidi').is(':checked'), $('#nuit').is(':checked'), 'jour', $('#dateFinRepetitionJour').val(),$('#dateFinRepetitionSemaine').val(),$('#dateFinRepetitionMois').val(), $('#nbRepetitionJour option:selected').val(),
											$('#nbRepetitionSemaine option:selected').val(),$('#nbRepetitionMois option:selected').val(),getRadioValue('jourSemaine'),getRadioValue('exceptionRepetition'),$('#appliquerATous').is(':checked'), $('#statut_tache').val(),lieus_ids, 
											ressources_ids, $('#livrable').val(), $('#titre').val(), $('#notes').val(), $('#lien').val(), $('#custom').val(), $('#notif_email').is(':checked'), 0, 'false', $('#periode_premiere').val(), $('#periode_derniere').val(), $('#price').val() , $('#sample').is(':checked'));
					">{#winPeriode_valider#|xss_protect}</button>
			
			</div>
			<div id="divPatienter" class="d-none justify-content-end form-group" style="position:absolute;right:0;"><img src="assets/img/pictos/loading16.gif" alt="" /></div>
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