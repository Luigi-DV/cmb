	<div class="row noprint">
		<div class="col-md-12 mb-2" id="firstLayer">
			<div class="soplanning-box form-inline pt-0" id="divPlanningDateSelector">
				<div class="btn-group cursor-pointer pt-2" id="btnDateNow">
					<a class="btn btn-default tooltipster" title="{#aujourdhui#}{$dateToday}" onClick="document.location='process/timetable_user.php?raccourci_date=aujourdhui'" id="buttonDateNowSelector"><i class="fa fa-home fa-lg fa-fw" aria-hidden="true"></i></a>
				</div>
			{* DIV POUR CHOIX DATE *}
					<div class="btn-group ml-md-2 pt-2" id="dropdownDateSelector">
						<form action="process/timetable_user.php" method="GET" class="form-inline" id="formChoixDates">
						<a href="#" id="buttonDateSelector" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
							<b>
							{$dateDebutTexte}
							{if $baseLigne neq "heures"}
								- {$dateFinTexte}
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
				&nbsp;&nbsp;
					
				{* DIV POUR CHOIX FILTRE USER *}
					<div class="btn-group pt-2" id="dropdownTaskLieuFilter">
						<form action="process/timetable_user.php" method="POST">
						<input type="hidden" name="filtreGroupeLieu2" value="1" />
						<select name="filtreGroupeLieu2" multiple="multiple" id="filtreGroupeLieu2" class="d-none multiselect">
								{foreach from=$listeLieux item=lieuCourant name=loopLieux}
								<option value="{$lieuCourant.lieu_id}" {if in_array($lieuCourant.lieu_id, $filtreGroupeLieu2)}selected="selected"{/if}>{$lieuCourant.nom|xss_protect}</option>
								{/foreach}
							</optgroup></select>
						</form>
					</div>
			
			
				{* DIV POUR SUPPRIMER TACHES *}
					<div class="pt-2 " ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);" >
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/bar.png" height="30px"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/sunrise.png" height="30px" id="morning" name="morning"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/sun.png" height="30px" id="afternoon" name="afternoon"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/luna.png" height="30px" id="night" name="nigth"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/day.png" height="30px" id="allDay" name="allDay"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/bar.png" height="30px"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/palmera.png" height="30px" id="palm" name="palm" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/bar.png" height="30px"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/trash.png" height="30px" id="buttonTrash" name="buttonTrash"/>
					</div>
			</div>
		</div>
		
	</div>