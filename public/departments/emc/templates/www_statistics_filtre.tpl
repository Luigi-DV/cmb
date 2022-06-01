	<div class="row noprint">
		<div class="col-md-12 mb-2" id="firstLayer">
			<div class="soplanning-box form-inline pt-1" id="divPlanningDateSelector">
				<div class="btn-group cursor-pointer pt-2" id="btnDateNow">
					<a class="btn btn-default tooltipster" title="{#aujourdhui#}{$dateToday}" onClick="document.location='process/statistics.php?raccourci_date=aujourdhui'" id="buttonDateNowSelector"><i class="fa fa-home fa-lg fa-fw" aria-hidden="true"></i></a>
				</div>
				{* DIV POUR CHOIX DATE *}
					<div class="btn-group ml-md-2 pt-2" id="dropdownDateSelector">
						<form action="process/statistics.php" method="GET" class="form-inline" id="formChoixDates">
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
										<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="{$dateDebut}" class="form-control datepicker" onChange="$('date_debut').value= '----------------';" />
									{/if}
									<br>
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
				<div class="btn-group pt-2 " id="dropdownExport" data-original-title="Export">
						<a class="btn btn-default" {* href="export_statistics.php" *}><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> CSV</a>
				</div>
			</div>
		</div>
	</div>