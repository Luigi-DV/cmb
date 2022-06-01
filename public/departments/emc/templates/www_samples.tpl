{* Smarty *}
{include file="www_header.tpl"}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="javascript:xajax_ajoutSample('samples');undefined;" class="btn btn-default"><i class="fa fa-truck fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuAjouterSample#}</a>
				</div>				
			</div>
		</div>
	</div>
	<form action="samples.php" method="POST" id="filtresample">
	<div class="row hidden">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				<div class="form-group row col-md-12">
					<div id="projectListLabel">
					<label class="col-form-label w140">{#projet_liste_afficherProjets#} :</label>
					</div>
					<div id="projectListButton">
					<div class="btn-group" data-toggle="buttons-radio">
						<button type="button" class="btn btn-default{if $filtrageProjet eq 'tous'} btn-primary active{/if}" onclick="top.location='?filtrageProjet=tous';">{#projet_liste_afficherProjetsTous#}</button>
						<button type="button" class="btn btn-default{if $filtrageProjet neq 'tous'} btn-primary active{/if}" onclick="top.location='?filtrageProjet=date';">{#projet_liste_afficherProjetsParDate#}</button>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="soplanning-box mt-2 col-md-12">
					
					{* STATUS *}
					
						<div class="form-check form-check-inline">
						<label class="col-form-label">{#projet_liste_filtreSamples#} :&nbsp;&nbsp;</label>
							<input class="form-check-input" type="checkbox" name="statuts[]" id="Received" value="Received" onclick="javascript:$('#filtresample').submit();" {if in_array("Received", $listeStatut)}checked="checked"{/if}>
							<label class="form-check-label" for="Received">{#winSample_received#}&nbsp;&nbsp;</label>
							
							<input class="form-check-input" type="checkbox" name="statuts[]" id="Departed" value="Departed" onclick="javascript:$('#filtresample').submit();" {if in_array("Departed", $listeStatut)}checked="checked"{/if}>
							<label class="form-check-label" for="Departed">{#winSample_departed#}</label>
						</div>
					
					{* /STATUS *}
					
					{* FILTER ON GROUP *}
					<button type="button" class="btn {if $filtreGroupeProjet|@count > 0}btn-danger{else}btn-default{/if} dropdown-toggle" data-toggle="dropdown">{#filtreGroupeProjet1#}&nbsp;<span class="caret"></span></button>
					<ul class="dropdown-menu">
						{if $filtreGroupeProjet|@count > 0}
							<a href="?desactiverfiltreGroupe=1" class="btn btn-sm btn-danger ml-2">{#formFiltreProjetDesactiver#}</a>
						{/if}
						<li>
							<input type="hidden" name="filtreGroupeProjet" value="1" />
							<table onClick="event.cancelBubble=true;" class="ml-2 mr-2">
								<tr>
									<td>
										{if $groupeProjets|@count > 0}
											{math assign=nbColonnes equation="ceil(nbGroupes / nbGroupesParColonnes)" nbGroupes=$groupeProjets|@count nbGroupesParColonnes=$smarty.const.FILTER_NB_USERS_PER_COLUMN}
											{math assign=maxCol equation="ceil(nbGroupes / nbColonnes)" nbGroupes=$groupeProjets|@count nbColonnes=$nbColonnes}
											{assign var=tmpNbDansColCourante value="0"}
											{foreach from=$groupeProjets item=groupeCourant name=loopGroupes}
												{if $tmpNbDansColCourante >= $maxCol}
													{assign var=tmpNbDansColCourante value="0"}
													</td>
													<td>
												{/if}
												<input type="checkbox" id="gp{$groupeCourant.projet_id}" name="gp[]" value="{$groupeCourant.projet_id}" {if in_array($groupeCourant.projet_id, $filtreGroupeProjet)}checked="checked"{/if} /> <label for="gp{$groupeCourant.projet_id}" style="display:inline">{$groupeCourant.nom|xss_protect}</label>
												<br/>
												{assign var=tmpNbDansColCourante value=$tmpNbDansColCourante+1}
											{/foreach}
										{/if}
									</td>
								</tr>
							</table>
						</li>
						<li><input type="submit" value="{#submit#}" class="btn btn-sm btn-primary ml-2 mt-2" /></li>
					</ul>
					{* /FILTER ON GROUP *}
					
					
					{* FILTER ON RECEPTION DATE *}
					<div class="btn-group ml-md-2" id="dropdownDateSelector">
					<form action="process/planning.php" method="GET" class="form-inline" id="formChoixDates">
						<a href="#" id="buttonDateSelector" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
							Filter on reception date
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<table class="planning-dateselector">
								<tr>
									<td>
										{#formDebut#} :&nbsp;
									</td>
									<td>
									{if $smarty.session.isMobileOrTablet==1}
										<input name="date_debut_affiche" id="date_debut_affiche" type="date" value="" class="form-control"  />
									{else}
										<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="" class="form-control datepicker"  />
									{/if}
									<br>
										<select id="date_debut_custom" class="form-control" name="date_debut_custom" >
											<option value="">{#raccourci#}...</option>
											<option value="aujourdhui">{#raccourci_aujourdhui#}</option>
											<option value="semaine_derniere">{#raccourci_semaine_derniere#}</option>
											<option value="mois_dernier">{#raccourci_mois_dernier#}</option>
											<option value="debut_semaine">{#raccourci_debut_semaine#}</option>
											<option value="debut_mois">{#raccourci_debut_mois#}</option>
										</select>
									</td>
										<td>
											&nbsp;{#formFin#} :&nbsp;
										</td>
										<td>
										{if $smarty.session.isMobileOrTablet==1}
											<input name="date_fin_affiche" id="date_fin_affiche" type="date" value="" class="form-control"  />
										{else}
											<input name="date_fin_affiche" id="date_fin_affiche" type="text" value="" class="form-control datepicker"  />
										{/if}

											<br>
											<select id="date_fin_custom" name="date_fin_custom" class="form-control" >
												<option value="">{#raccourci#}...</option>
												<option value="1_semaine">{#raccourci_1_semaine#}</option>
												<option value="2_semaines">{#raccourci_2_semaines#}</option>
												<option value="3_semaines">{#raccourci_3_semaines#}</option>
												<option value="1_mois">{#raccourci_1_mois#}</option>
												<option value="2_mois">{#raccourci_2_mois#}</option>
												<option value="3_mois">{#raccourci_3_mois#}</option>
												<option value="4_mois">{#raccourci_4_mois#}</option>
												<option value="5_mois">{#raccourci_5_mois#}</option>
												<option value="6_mois">{#raccourci_6_mois#}</option>
											</select>
										</td>
									<td class="pr-3">
										<button id="dateFilterButton" class="btn btn-sm btn-default" ><i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
									</td>
								</tr>
								</table>
							</li>
						</ul>
					</form>
					</div>
					{* /FILTER ON RECEPTION DATE *}
					
					{* FILTER ON DEPARTURE DATE *}
					<div class="btn-group ml-md-2" id="dropdownDateSelector">
					<form action="process/planning.php" method="GET" class="form-inline" id="formChoixDates">
						<a href="#" id="buttonDateSelector" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
							Filter on departure date
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<table class="planning-dateselector">
								<tr>
									<td>
										{#formDebut#} :&nbsp;
									</td>
									<td>
									{if $smarty.session.isMobileOrTablet==1}
										<input name="date_debut_affiche" id="date_debut_affiche" type="date" value="" class="form-control"  />
									{else}
										<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="" class="form-control datepicker"  />
									{/if}
									<br>
										<select id="date_debut_custom" class="form-control" name="date_debut_custom" >
											<option value="">{#raccourci#}...</option>
											<option value="aujourdhui">{#raccourci_aujourdhui#}</option>
											<option value="semaine_derniere">{#raccourci_semaine_derniere#}</option>
											<option value="mois_dernier">{#raccourci_mois_dernier#}</option>
											<option value="debut_semaine">{#raccourci_debut_semaine#}</option>
											<option value="debut_mois">{#raccourci_debut_mois#}</option>
										</select>
									</td>
										<td>
											&nbsp;{#formFin#} :&nbsp;
										</td>
										<td>
										{if $smarty.session.isMobileOrTablet==1}
											<input name="date_fin_affiche" id="date_fin_affiche" type="date" value="" class="form-control"  />
										{else}
											<input name="date_fin_affiche" id="date_fin_affiche" type="text" value="" class="form-control datepicker"  />
										{/if}

											<br>
											<select id="date_fin_custom" name="date_fin_custom" class="form-control" >
												<option value="">{#raccourci#}...</option>
												<option value="1_semaine">{#raccourci_1_semaine#}</option>
												<option value="2_semaines">{#raccourci_2_semaines#}</option>
												<option value="3_semaines">{#raccourci_3_semaines#}</option>
												<option value="1_mois">{#raccourci_1_mois#}</option>
												<option value="2_mois">{#raccourci_2_mois#}</option>
												<option value="3_mois">{#raccourci_3_mois#}</option>
												<option value="4_mois">{#raccourci_4_mois#}</option>
												<option value="5_mois">{#raccourci_5_mois#}</option>
												<option value="6_mois">{#raccourci_6_mois#}</option>
											</select>
										</td>
									<td class="pr-3">
										<button id="dateFilterButton" class="btn btn-sm btn-default" ><i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
									</td>
								</tr>
								</table>
							</li>
						</ul>
					</form>
					</div>
					{* /FILTER DEPARTURE ON DATE *}
				
					{* BUSCADOR *}
					<div class="btn-group" id="projectSearchbox">
						<div class="input-group">
							<input type="text" class="form-control" name="rechercheProjet" value="{$rechercheProjet|default:""}" placeholder="{#taches_groupeRecherche#}" />
							<span class="input-group-append">
									<button type="submit" class="btn {if $rechercheProjet != ""}btn-danger{else}btn-default{/if}"><i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
							</span>
						</div>
					</div>
					{* /BUSCADOR *}
				
					
			</div>
		</div>
	</div>
	</form>
	
	
	
	{* TAULA *}
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				<table class="table table-striped table-hover" id="projectTab">
					<tr>
						<td colspan="1">
						</td>
						<td>
							{if $order eq "sample_id"}
								{if $by eq "asc"}
									<a href="?order=sample_id&by=desc">{#sample_liste_sample#} ({$samples|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
								{else}
									<a href="?order=sample_id&by=asc">{#sample_liste_sample#} ({$samples|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
								{/if}
							{else}
								<a href="?order=sample_id&by={$by}">{#sample_liste_sample#} ({$samples|@count})</a>
							{/if}
						</td>
						<td class="d-none d-md-table-cell">
							{if $order eq "r_date"}
								{if $by eq "asc"}
									<a href="?order=r_date&by=desc">{#sample_liste_livraison1#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
								{else}
									<a href="?order=r_date&by=asc">{#sample_liste_livraison1#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
								{/if}
							{else}
								<a href="?order=r_date&by={$by}">{#sample_liste_livraison1#}</a>
							{/if}
						</td>
						<td class="projectTabColCreator">
							{if $order eq "nom_createur"}
								{if $by eq "asc"}
									<a href="?order=nom_createur&by=desc">{#sample_liste_createur#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
								{else}
									<a href="?order=nom_createur&by=asc">{#sample_liste_createur#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
								{/if}
							{else}
								<a href="?order=nom_createur&by={$by}">{#sample_liste_createur#}</a>
							{/if}
						</td>
						<td class="d-none d-md-table-cell">
							{if $order eq "e_date"}
								{if $by eq "asc"}
									<a href="?order=e_date&by=desc">{#sample_liste_livraison#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
								{else}
									<a href="?order=e_date&by=asc">{#sample_liste_livraison#}</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
								{/if}
							{else}
								<a href="?order=e_date&by={$by}">{#sample_liste_livraison#}</a>
							{/if}
						</td>
						<td class="d-none d-md-table-cell" style="color:#746660;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Barcode
						</td>
					</tr>
					{assign var=groupeCourant value=""}
					{foreach from=$samples item=sample}
						{if $sample.projet_id neq $groupeCourant}
							<tr>
							<td colspan="8" class="project-group-head">{$sample.nom_groupe|xss_protect}</td>
						{/if}
						<tr>
							<td class="w140">
									<a href="javascript:xajax_modifSample('{$sample.sample_id}', 'samples');undefined;"><i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:xajax_supprimerSample('{$sample.sample_id}');undefined;" 
									onclick="javascript: return confirm('{#sample_liste_confirmSuppr#|xss_protect}')"><i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i></a>
								{if $sample.lien <> ''}
								<a href="{if $sample.lien|strpos:"http" !== 0 && $sample.lien|strpos:"\\" !== 0}http://{/if}{$sample.lien}" title="{#winProjet_gotoLien#|xss_protect}" target="_blank"><i class="fa fa-globe fa-lg fa-fw" aria-hidden="true"></i></a>
								{else}
								{/if}
							</td>
							<td>
							{if $sample.statut eq 'Received'}
								<span class="pastille-projet" style="background-color:#FF6900;color:{"#"|cat:FF6900|buttonFontColor}">{$sample.sample_id}</span></td>
							{else}
								<span class="pastille-projet" style="background-color:#B8AFAC;color:{"#"|cat:B8AFAC|buttonFontColor}">{$sample.sample_id}</span></td>
							{/if}
							<td>
								{$sample.r_date|sqldate2userdate}
							</td>
							<td class="projectTabColCreator">
								{$sample.nom_createur|xss_protect}
							</td>
							<td class="d-none d-md-table-cell">
								{if $sample.e_date neq '' && $sample.e_date neq '0000-00-00'}
									{$sample.e_date|sqldate2userdate}
								{else}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
								{/if}
							</td>
							<td class="d-none d-md-table-cell"><img src="{$BASE}/barcode.php?code={$sample.sample_id}" height="40px" width="150px"/></td>
						</tr>
						{assign var=groupeCourant value=$sample.projet_id}
					{/foreach}
				</table>
			</div>
		</div>
	</div>
</div>

{* CHARGEMENT SCROLL Y *}

<script>
	{literal}

	var yscroll = getCookie('yposProjets');
	window.onscroll = function() {document.cookie='yposProjets=' + window.pageYOffset;};
	addEvent(window, 'load', chargerYScrollPos);
	$('#rechercheProjet').keypress(function(event) {
		if (event.keyCode == 13 || event.which == 13) {
			$('#filtresample').submit();
			event.preventDefault();
		}
	});
	{/literal}
</script>
<script src="{$BASE}/assets/plugins/select2-4.0.6/dist/js/select2.min.js"></script>
<script src="{$BASE}/assets/plugins/select2-4.0.6/dist/js/i18n/{$lang}.js"></script>
{include file="www_footer.tpl"}