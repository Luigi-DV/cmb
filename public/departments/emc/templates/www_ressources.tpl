{* Smarty *}
{include file="www_header.tpl"}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="javascript:xajax_modifRessource();undefined;" class="btn btn-default" ><i class="fa fa-plus-square fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerRessource#}</a>
				</div>
				
				<div class="btn-group">
					<a href="{$BASE}/equi_groupes.php" class="btn btn-default">
							<img src="{$BASE}/toolbox.png" height="16px"/>&nbsp;&nbsp;{#menuGroupesEqui#}
						</a>
				</div>
				
				<div class="btn-group">
					<form method="POST">
					<a href="#" class="btn {if $filtreEquipeGroupe|@count > 0}btn-danger{else}btn-default{/if} dropdown-toggle" data-toggle="dropdown">{#filtreEquipe#}&nbsp;<span class="caret"></span></a>
					<ul class="dropdown-menu">
						{if $filtreEquipeGroupe|@count > 0}
							<a href="?desactiverfiltreEquipeGroupe=1" class="btn btn-danger btn-sm ml-2">{#formFiltreUserDesactiver#}</a>
						{/if}
						<li>
							<input type="hidden" name="filtreEquipeGroupe" value="1">
							<table onClick="event.cancelBubble=true;" class="ml-2 mr-2">
								<tr>
									<td>
										{if $equipes|@count > 0}
											{math assign=nbColonnes equation="ceil(nbEquipes / nbEquipesParColonnes)" nbEquipes=$equipes|@count nbEquipesParColonnes=$smarty.const.FILTER_NB_USERS_PER_COLUMN}
											{math assign=maxCol equation="ceil(nbEquipes / nbColonnes)" nbEquipes=$equipes|@count nbColonnes=$nbColonnes}
											{assign var=tmpNbDansColCourante value="0"}
											{foreach from=$equipes item=equipeCourante name=loopEquipes}
												{if $tmpNbDansColCourante >= $maxCol}
													{assign var=tmpNbDansColCourante value="0"}
													</td>
													<td>
												{/if}
												<input type="checkbox" id="gu{$equipeCourante.ressource_groupe_id}" name="gu[]" value="{$equipeCourante.ressource_groupe_id}" onClick="filtreCocheUserGroupe('{$equipeCourante.ressource_groupe_id}')" {if in_array($equipeCourante.ressource_groupe_id, $filtreEquipeGroupe)}checked="checked"{/if} /> <label for="gu{$equipeCourante.ressource_groupe_id}" style="display:inline">{$equipeCourante.nom|xss_protect}</label>
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
					</form>	
				</div>
				
				<div class="btn-group">
					<form method="POST">
					<div class="input-group">
						<input type="text" class="form-control" name="rechercheEqui" value="{$rechercheEqui|default:""}" />
						<span class="input-group-append">
							<button type="submit" class="btn {if $rechercheEqui != ""}btn-danger{else}btn-default{/if}"><i class="fa fa-search" aria-hidden="true"></i></button>
						</span>
					</div>
					</form>
				</div>
				
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				{if $ressources|@count > 0}
					<table class="table table-striped table-hover" id="ressourceTab">
						<tr>
							<th class="w100">&nbsp;</th>
							<th>
								{*<b>{#ressource_nom#}</b>*}
								
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/ressources.php?order=nom&by=desc">{#ressource_nom#} ({$ressources|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/ressources.php?order=nom&by=asc">{#ressource_nom#} ({$ressources|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/ressources.php?order=nom&by={$by}">{#ressource_nom#} ({$ressources|@count})</a>
								{/if}
								
							</th>
							<th>
								{*<b>{#ressource_team#}</b>*}
								
								{if $order eq "nom_groupe"}
									{if $by eq "asc"}
										<a href="{$BASE}/ressources.php?order=nom_groupe&by=desc">{#ressource_team#} ({$equipes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/ressources.php?order=nom_groupe&by=asc">{#ressource_team#} ({$equipes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/ressources.php?order=nom_groupe&by={$by}">{#ressource_team#} ({$equipes|@count}) </a>
								{/if}
								
							</th>
							<th class="d-none d-md-table-cell d-lg-table-cell">
								<b>{#ressource_commentaire#}</b>
							</th>
							
							</tr>
						{foreach name=ressources item=ressource from=$ressources}
							<tr>
								<td class="w100">
									<a href="javascript:xajax_modifRessource('{$ressource.ressource_id|urlencode}');undefined;">
									<i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i>
									</a>
									<a href="javascript:xajax_supprimerRessource('{$ressource.ressource_id|urlencode}');undefined;" onClick="javascript:return confirm('{#confirm#|escape:"javascript"}')">
									<i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i>
									</a>
									<a href="{$BASE}/process/planning.php?filtreSurRessource={$ressource.ressource_id}" title="{#planning_filtre_sur_ressource#|escape}">
									<i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>
									</a>
								</td>
								<td class="wrap">
									{$ressource.nom}&nbsp;
								</td>
								<td class="wrap">
									{$ressource.nom_groupe}&nbsp;
								</td>
								<td class="wrap d-none d-md-table-cell d-lg-table-cell">
									{$ressource.commentaire}
								</td>
								
							</tr>
						{/foreach}
					</table>
				{else}
					{#info_noRecord#}
				{/if}
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}