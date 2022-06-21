<div class="row noprint">
	<div class="col-md-12 mb-2" id="firstLayer">
		<div class="soplanning-box form-inline pt-2" id="divPlanningDateSelector">
			<div class="btn-group cursor-pointer pt-2 mr-5" id="btnDateNow">
				<a class="btn btn-default tooltipster bg-dark text-white" title="{#aujourdhui#}{$dateToday}" onClick="document.location='process/statistics.php?raccourci_date=aujourdhui'" id="buttonDateNowSelector"><i class="fa fa-home fa-lg fa-fw" aria-hidden="true"></i></a>
			</div>
			<div class="bg-light py-2 px-5 rounded-right rounded-left text-dark">
				<form action="process/statistics.php" method="GET" class="form-inline" id="formChoixDates">
					<div class="row">
					<div class="col">
						<div>
							{#formDebut#} :&nbsp;
						</div>
						<div>
						{if $smarty.session.isMobileOrTablet==1}
							<input name="date_debut_affiche" id="date_debut_affiche" type="date" value="{$dateDebut|forceISODateFormat}" class="form-control" onChange="$('date_debut_custom').value= '----------------';" />
						{else}
							<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="{$dateDebut}" class="form-control datepicker" onChange="$('date_debut').value= '----------------';" />
						{/if}
						<br>
						</div>
					</div>
					<div class="col">
						{if $baseLigne neq "heures"}
							<div>
								&nbsp;{#formFin#} :&nbsp;
							</div>
							<div>
								{if $smarty.session.isMobileOrTablet==1}
									<input name="date_fin_affiche" id="date_fin_affiche" type="date" value="{$dateFin|forceISODateFormat}" class="form-control"  onChange="$('date_fin_custom').value= '----------------';" />
								{else}
									<input name="date_fin_affiche" id="date_fin_affiche" type="text" value="{$dateFin}" class="form-control datepicker"   onChange="$('date_fin_custom').value= '----------------';" />
								{/if}

								<br>
							</div>
						{/if}
					</div>
					<div class="col">
						<div class="pr-3">
							<button id="dateFilterButton" class="btn btn-outline-dark" onClick="$('formChoixDates').submit();"><i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
						</div>
					</div>
					</form>
					{* 
					<div class="btn-group pt-2 text-dark" id="dropdownExport" data-original-title="Export">
						<a class="btn btn-default bg-white" href="export_statistics.php" ><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> CSV</a>
					</div>
					*}
				</div>
		</div>
	</div>
</div>