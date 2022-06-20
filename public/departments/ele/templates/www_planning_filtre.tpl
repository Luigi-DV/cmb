	<div class="row noprint">
		<div class="col-md-12 mb-2" id="firstLayer">
			<div class="soplanning-box form-inline pt-1" id="divPlanningDateSelector">
				<div class="btn-group cursor-pointer pt-2" id="btnDateNow">
					<a class="btn btn-default tooltipster" title="{#aujourdhui#}{$dateToday}" onClick="document.location='process/planning.php?raccourci_date=aujourdhui'" id="buttonDateNowSelector"><i class="fa fa-home fa-lg fa-fw" aria-hidden="true"></i></a>
				</div>
				{* DIV POUR CHOIX DATE *}
					<div class="btn-group ml-md-2 pt-2" id="dropdownDateSelector">
						<form action="process/planning.php" method="GET" class="form-inline" id="formChoixDates">
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
				
				{if !in_array("tasks_readonly", $user.tabDroits)}
				<div class="btn-group ml-md-1 pt-2" id="btnAddTask">
				<a class="btn btn-info" href="javascript:Reloader.stopRefresh();xajax_ajoutPeriode();undefined;">
					{if !$smarty.server.HTTP_USER_AGENT|strstr:"MSIE 8.0"}
					     <i class="fa fa-calendar-plus-o fa-lg fa-fw" aria-hidden="true"></i>
					{/if}
					<span class="d-none d-md-inline-block">&nbsp;Task</span>
				</a>
				</div>
				{/if}
				
				{if in_array("projects_manage_all", $user.tabDroits) || in_array("projects_manage_own", $user.tabDroits)}
				<div class="btn-group ml-md-1 pt-2" id="btnAddTask">
				<a class="btn btn-info" href="javascript:Reloader.stopRefresh();xajax_ajoutProjet();undefined;">
					<i class="fa fa-plus-square fa-lg fa-fw" aria-hidden="true"></i>
					<span class="d-none d-md-inline-block">&nbsp;Project</span>
				</a>
				</div>
				{/if}
				
				{* DIV PER ALS PROJECTES NO ASSIGNATS *}
					<div class="btn-group pt-2 hidden" id="dropdownTypePlanning">
						<button class="btn dropdown-toggle btn-default" data-toggle="dropdown"><i class="fa fa-archive fa-lg fa-fw" aria-hidden="true"></i>&nbsp;<span class="caret"></span></button>
						<div class="dropdown-menu">
							{foreach from=$receivedProjects item=received}
								<li><a class="dropdown-item" href="javascript:xajax_formReceived('{$received.projet_id}', 'receivedProjects');undefined;">{$received.nom}</a></li>
							{/foreach}
						</div>
					</div>
				
				{* DIV POUR CHOIX FILTRE PROJETS *}
					<div class="btn-group pt-2" id="dropdownTaskProjectFilter">
						<form action="process/planning.php" method="POST">
						<input type="hidden" name="filtreGroupeProjet" value="1" />
						<select name="filtreGroupeProjet" multiple="multiple" id="filtreGroupeProjet" class="d-none multiselect">
							{if $listeProjets|@count eq 0}
								<option>&nbsp;{#formFiltreProjetAucunProjet#}</option>
							{else}
								<optgroup id="g0" label="{#projet_liste_sansGroupes#}">
								{assign var=groupeTemp value=""}
								{foreach from=$listeProjets item=projetCourant name=loopProjets}
									{if $projetCourant.groupe_id neq $groupeTemp}
										</optgroup><optgroup id="g{$projetCourant.groupe_id}" label="{$projetCourant.groupe_nom}">
									{/if}
								<option value="{$projetCourant.projet_id}" {if in_array($projetCourant.projet_id, $filtreGroupeProjet)}selected="selected"{/if}>{$projetCourant.nom|xss_protect}</option>
								{assign var=groupeTemp value=$projetCourant.groupe_id}
								{/foreach}
							{/if}
							</optgroup></select>
						</form>
					</div>
					
					
					
					{* DIV POUR CHOIX FILTRE RESOURCES *}
					<div class="btn-group pt-2" id="dropdownTaskUserFilter">
						<form action="process/planning.php" method="POST">
						<input type="hidden" name="filtreUser" value="1" />
						<select name="filtreUser" multiple="multiple" id="filtreUser" class="d-none multiselect">
							{if $listeUsers|@count eq 0}
								<option>&nbsp;{#formFiltreUserAucunProjet#}</option>
							{else}
								{assign var=groupeTemp value=""}
								{foreach from=$listeUsers item=userCourant name=loopUsers}
									{if $userCourant.user_groupe_id neq $groupeTemp}
										</optgroup><optgroup id="gu{$userCourant.user_groupe_id}" label="{$userCourant.groupe_nom}">
									{/if}
								<option value="{$userCourant.user_id}" {if in_array($userCourant.user_id, $filtreUser)}selected="selected"{/if}>{$userCourant.nom|xss_protect}</option>
								{assign var=groupeTemp value=$userCourant.user_groupe_id}
								{/foreach}
							{/if}
							</optgroup></select>
						</form>
					</div>
					
					{* DIV POUR CHOIX FILTRE USER *}
					<div class="btn-group pt-2" id="dropdownTaskLieuFilter">
						<form action="process/planning.php" method="POST">
						<input type="hidden" name="filtreGroupeLieu" value="1" />
						<select name="filtreGroupeLieu" multiple="multiple" id="filtreGroupeLieu" class="d-none multiselect">
								{foreach from=$listeLieux item=lieuCourant name=loopLieux}
								<option value="{$lieuCourant.lieu_id}" {if in_array($lieuCourant.lieu_id, $filtreGroupeLieu)}selected="selected"{/if}>{$lieuCourant.nom|xss_protect}</option>
								{/foreach}
							</optgroup></select>
						</form>
					</div>
					
					{* DIV POUR CHOIX FILTRE EQUIPMENT *}
					<div class="btn-group pt-2" id="dropdownTaskRessourceFilter">
						<form action="process/planning.php" method="POST">
						<input type="hidden" name="filtreGroupeRessource" value="1" />
						<select name="filtreGroupeRessource" multiple="multiple" id="filtreGroupeRessource" class="d-none multiselect">
								{assign var=groupeTemp value=""}
								{foreach from=$listeRessources item=ressourceCourant}
									{if $ressourceCourant.ressource_groupe_id neq $groupeTemp}
										</optgroup><optgroup id="gu{$ressourceCourant.ressource_groupe_id}" label="{$ressourceCourant.groupe_nom}">
									{/if}
								<option value="{$ressourceCourant.ressource_id}" {if in_array($ressourceCourant.ressource_id, $filtreGroupeRessource)}selected="selected"{/if}>{$ressourceCourant.nom|xss_protect}</option>
								{assign var=groupeTemp value=$ressourceCourant.ressource_groupe_id}
								{/foreach}
								
							</optgroup></select>
						</form>
					</div>
					
					{* DIV POUR CHOIX FILTRE STATUS *}
					<div class="btn-group pt-2" id="dropdownAdvancedFilter">
						<form action="process/planning.php" method="POST">
						<button class="btn {if (($filtreStatutTache|@count > 0) or ($filtreStatutProjet|@count >0) ) }btn-danger{else}btn-default{/if} dropdown-toggle" data-toggle="dropdown" onclick="javascript:multiselecthide();"><i class="fa fa-tags fa-lg fa-fw" aria-hidden="true"></i><span class="d-none d-md-inline-block">&nbsp;</span><span class="caret"></span></button>
						<ul class="dropdown-menu">
							{if (($filtreStatutTache|@count > 0) or ($filtreGroupeLieu|@count >0) or ($filtreGroupeRessource|@count >0) or ($filtreStatutProjet|@count >0)  )}<a href="process/planning.php?desactiverFiltreAvances=1" class="btn btn-danger btn-sm margin-left-10">{#formFiltreAvancesDesactiver#}</a>{/if}
							<li class="divider"></li>
							<li>
								<table onClick="event.cancelBubble=true;" class="planning-filter">
									<tr>
										<td class="planningDropdownFilter">
											<input type="hidden" name="filtreStatutTache" value="1">
											<b>{#formChoixStatutTache#}</b><br />
											<div class="form-horizontal col-md-12">
											{foreach from=$listeStatusTaches item=statust}
											<label class="checkbox">
												<input type="checkbox" id="{$statust.status_id}" name="statutsTache[]" value="{$statust.status_id}" {if in_array($statust.status_id, $filtreStatutTache)}checked="checked"{/if} />&nbsp;{$statust.nom}
											</label>
											{/foreach}
											</div>
										</td>
										<td class="planningDropdownFilter">
											<input type="hidden" name="filtreStatutProjet" value="1">
											<b>{#formChoixStatutProjet#}</b><br />
											<div class="form-horizontal col-md-12">
											{foreach from=$listeStatusProjets item=statusp}
											<label class="checkbox">
												<input type="checkbox" id="statut_projet_{$statusp.status_id}" name="statutsProjet[]" value="{$statusp.status_id}" {if in_array($statusp.status_id, $filtreStatutProjet)}checked="checked"{/if} />&nbsp;{$statusp.nom}
											</label>
											{/foreach}
											</div>
										</td>
									</tr>
								</table>
							</li>
							<li><input type="submit" value="{#submit#}" class="btn btn-default ml-2" /></li>
						</ul>
						</form>
					</div>
					
					
					{* DIV POUR CHOIX AFFICHAGE *}
					<div class="btn-group pt-2" id="dropdownTypePlanning">
						<button class="btn dropdown-toggle btn-default" data-toggle="dropdown" onclick="javascript:multiselecthide();"><i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i><span id='label_tierpar'>&nbsp;&nbsp;{#planning_affichage#}</span>&nbsp;<span class="caret"></span></button>
						<div class="dropdown-menu">
							{if $smarty.session.baseLigne eq 'users'}
								<a class="dropdown-item" href="process/planning.php?baseLigne=users">
								<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;
							{else}
								<a class="dropdown-item" href="process/planning.php?baseLigne=users">
								<i style="margin-left:19px;">&nbsp;</i>
							{/if}
							{#planningPersonne#}</a>
							
							{if $smarty.session.baseLigne eq 'projets'}
								<a class="dropdown-item" href="process/planning.php?baseLigne=projets">
								<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;
							{else}
								<a class="dropdown-item" href="process/planning.php?baseLigne=projets">
								<i style="margin-left:19px;">&nbsp;</i>
							{/if}
							{#planningProjet#}</a>

							<div class="dropdown-divider"></div>

							{if $smarty.session.masquerLigneVide eq 0 }
								<a class="dropdown-item" href="process/planning.php?baseLigne={$smarty.session.baseLigne}&baseColonne={$smarty.session.baseColonne}&masquerLigneVide=1">
								<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;
							{else}
								<a class="dropdown-item" href="process/planning.php?baseLigne={$smarty.session.baseLigne}&baseColonne={$smarty.session.baseColonne}&masquerLigneVide=0">
								<i style="margin-left:19px;">&nbsp;</i>
							{/if}
							{#planningAfficherLignesVides#}</a>

							{if $smarty.session.afficherTableauRecap eq 1}
								<a class="dropdown-item" href="process/planning.php?baseLigne={$smarty.session.baseLigne}&baseColonne={$smarty.session.baseColonne}&afficherTableauRecap=0">
								<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;
							{else}
								<a class="dropdown-item" href="process/planning.php?baseLigne={$smarty.session.baseLigne}&baseColonne={$smarty.session.baseColonne}&afficherTableauRecap=1">
								<i style="margin-left:19px;">&nbsp;</i>
							{/if}
							{#planningAfficherTableauRecap#}</a>
						</div>
					</div>
					{* DIV POUR CHOIX EXPORT *}
					<div class="btn-group pt-2" id="dropdownExport">
						<a class="btn btn-default" href="export_csv.php"><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> CSV</a>
						
					</div>
					{* DIV POUR CHOIX DIMENSION CASE ET AFFICHAGE LARGE REDUIT *}
					<div class="btn-group pt-2" id="dropdownLarge">
						{if $dimensionCase eq "reduit"}
							<a class="btn btn-default" title="{#menuPlanningLarge#}" href="process/planning.php?dimensionCase=large"><i class="fa fa-search-plus fa-lg fa-fw" aria-hidden="true"></i></a>
						{else}
							<a class="btn btn-default" title="{#menuPlanningReduit#}" href="process/planning.php?dimensionCase=reduit"><i class="fa fa-search-minus fa-lg fa-fw" aria-hidden="true"></i></a>
						{/if}
					</div>
					{* DIV POUR CHOIX COULEUR TACHES *}
					{if in_array("parameters_all", $user.tabDroits)}
					<div class="btn-group pt-2" id="dropdownLarge">
					<form action="process/options.php" method="POST" class="form-horizontal">
					
						<div class="hidden">
							<select name="PLANNING_COULEUR_TACHE" class="form-control">
								<option value="0" {if $smarty.const.CONFIG_PLANNING_COULEUR_TACHE eq 1}selected="selected"{/if}>{#option_couleur_taches_contextuelles#}</option>									
								<option value="1" {if $smarty.const.CONFIG_PLANNING_COULEUR_TACHE eq 0}selected="selected"{/if}>{#option_couleur_taches_status#}</option>
							</select>
						</div>
						
						<button type="submit" class="btn btn-default dropdown">
							<img src="{$BASE}/bill.png" height="20px" /></button>
						
					</form>
					</div>
					{/if}
					{* DIV POUR RECHERCHE TEXTE *}
					<div class="btn-group ml-md-1 pt-2" id="searchboxPlanning">
						<form action="process/planning.php" method="POST">
							<div class="input-group">
								<input type="text" class="tooltipster form-control input-sm" name="filtreTexte" value="{$filtreTexte|xss_protect}" maxlength="50" title="{#formFiltreTexte#|escape}" id="filtreTexte" />
								<div class="input-group-append">
									<button type="submit" class="btn btn-sm {if $filtreTexte != ""}btn-danger{else}btn-default{/if}">
									<i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i></button>
									{if $filtreTexte != ""}
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="process/planning.php?desactiverFiltreTexte=1">{#formFiltreUserDesactiver#}</a></li>
											</ul>
										</div>
									{/if}
								</div>
							</div>
						</form>
					</div>
			
	
				{* DIV POUR MODIFIER ET SUPPRIMER TACHES *}
					{if !in_array("tasks_readonly", $user.tabDroits)}
					<div class="pt-1 " ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/sun.png" height="30px" id="morning" name="morning"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/luna.png" height="30px" id="afternoon" name="afternoon"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/day.png" height="30px" id="allDay" name="allDay"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/bar.png" height="30px"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="{$BASE}/trash.png" height="30px" id="buttonTrash" name="buttonTrash"/>
					</div>
					{/if}
			</div>
		</div>
	</div>