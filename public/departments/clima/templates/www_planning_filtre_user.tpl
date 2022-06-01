	<div class="row noprint">
		<div class="col-md-12 mb-2" id="firstLayer">
			<div class="soplanning-box form-inline pt-1" id="divPlanningDateSelector">
				<div class="btn-group cursor-pointer pt-2" id="btnDateNow">
					<a class="btn btn-default tooltipster" title="{#aujourdhui#}{$dateToday}" onClick="document.location='process/planning_user.php?raccourci_date=aujourdhui'" id="buttonDateNowSelector"><i class="fa fa-home fa-lg fa-fw" aria-hidden="true"></i></a>
				</div>
				{* DIV POUR CHOIX DATE *}
					<div class="btn-group ml-md-2 pt-2" id="dropdownDateSelector">
						<form action="process/planning_user.php" method="GET" class="form-inline" id="formChoixDates">
						<a href="#" id="buttonDateSelector" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
							<b>
							{$dateDebut}
							{if $baseLigne neq "heures"}
								- {$dateFin}
							{/if}
							</b>&nbsp;&nbsp;&nbsp;<span class="caret"></span>
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
										<input name="date_debut_affiche" id="date_debut_affiche" type="date" value="{$dateDebut|forceISODateFormat}" class="form-control" onChange="$('date_debut_custom').value= '----------------';" />
									{else}
										<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="{$dateDebut}" class="form-control datepicker" onChange="$('date_debut_custom').value= '----------------';" />
									{/if}
									<br>
										<select id="date_debut_custom" class="form-control" name="date_debut_custom" onChange="$('date_debut_affiche').value= '----------------';">
											<option value="">{#raccourci#}...</option>
											<option value="aujourdhui">{#raccourci_aujourdhui#}</option>
											<option value="semaine_derniere">{#raccourci_semaine_derniere#}</option>
											<option value="mois_dernier">{#raccourci_mois_dernier#}</option>
											<option value="debut_semaine">{#raccourci_debut_semaine#}</option>
											<option value="debut_mois">{#raccourci_debut_mois#}</option>
										</select>
									</td>
									{if $baseLigne neq "heures"}
										<td>
											&nbsp;{#formFin#} :&nbsp;
										</td>
										<td>
										{if $smarty.session.isMobileOrTablet==1}
											<input name="date_fin_affiche" id="date_fin_affiche" type="date" value="{$dateFin|forceISODateFormat}" class="form-control"  onChange="$('date_fin_custom').value= '----------------';" />
										{else}
											<input name="date_fin_affiche" id="date_fin_affiche" type="text" value="{$dateFin}" class="form-control datepicker"   onChange="$('date_fin_custom').value= '----------------';" />
										{/if}

											<br>
											<select id="date_fin_custom" name="date_fin_custom" class="form-control" onChange="$('date_fin_affiche').value= '----------------';">
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
									{/if}
									<td class="pr-3">
										<button id="dateFilterButton" class="btn btn-sm btn-default" onClick="$('formChoixDates').submit();"><i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
									</td>
								</tr>
								</table>
							</li>
						</ul>
				</form>
				</div>
				
					
					{* DIV POUR CHOIX FILTRE USER *}
					<div class="btn-group pt-2" id="dropdownTaskLieuFilter">
						<form action="process/planning_user.php" method="POST">
						<input type="hidden" name="filtreGroupeLieu1" value="1" />
						<select name="filtreGroupeLieu1" multiple="multiple" id="filtreGroupeLieu1" class="d-none multiselect">
								{foreach from=$listeLieux item=lieuCourant name=loopLieux}
								<option value="{$lieuCourant.lieu_id}" {if in_array($lieuCourant.lieu_id, $filtreGroupeLieu1)}selected="selected"{/if}>{$lieuCourant.nom|xss_protect}</option>
								{/foreach}
							</optgroup></select>
						</form>
					</div>
					
					{* DIV POUR RECHERCHE TEXTE *}
					<div class="btn-group ml-md-1 pt-2" id="searchboxPlanning">
						<form action="process/planning_user.php" method="POST">
							<div class="input-group">
								<input type="text" class="tooltipster form-control input-sm" name="filtreTexte" value="{$filtreTexte|xss_protect}" maxlength="50" title="{#formFiltreTexte#|escape}" id="filtreTexte" />
								<div class="input-group-append">
									<button type="submit" class="btn btn-sm {if $filtreTexte != ""}btn-danger{else}btn-default{/if}">
									<i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
									{if $filtreTexte != ""}
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="process/planning_user.php?desactiverFiltreTexte=1">{#formFiltreUserDesactiver#}</a></li>
											</ul>
										</div>
									{/if}
								</div>
							</div>
						</form>
					</div>
					
					{* DIV POUR CHOIX EXPORT *}
					<div class="btn-group pt-2" id="dropdownExport">
						<a class="btn btn-default" href="export_csv.php"><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> CSV</a>
						
					</div>
					
			</div>
		</div>
	</div>