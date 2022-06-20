{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box form-inline">
				<div class="btn-group">
					<a href="javascript:xajax_modifRessourceUser();undefined;" class="btn btn-default" ><i class="fa fa-plus-square fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerRUser#}</a>
				</div>
				
				<div class="btn-group">
					<a href="{$BASE}/ressource_groupes.php" class="btn btn-default">
							<i class="fa fa-th fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuGroupesRessources#}
						</a>
				</div>

				<div class="btn-group">
					<form method="POST">
					<a href="#" class="btn {if $filtreEquipe|@count > 0}btn-danger{else}btn-default{/if} dropdown-toggle" data-toggle="dropdown">{#filtreEquipe#}&nbsp;<span class="caret"></span></a>
					<ul class="dropdown-menu">
						{if $filtreEquipe|@count > 0}
							<a href="?desactiverfiltreEquipe=1" class="btn btn-danger btn-sm ml-2">{#formFiltreUserDesactiver#}</a>
						{/if}
						<li>
							<input type="hidden" name="filtreEquipe" value="1">
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
												<input type="checkbox" id="gu{$equipeCourante.user_groupe_id}" name="gu[]" value="{$equipeCourante.user_groupe_id}" onClick="filtreCocheUserGroupe('{$equipeCourante.user_groupe_id}')" {if in_array($equipeCourante.user_groupe_id, $filtreEquipe)}checked="checked"{/if} /> <label for="gu{$equipeCourante.user_groupe_id}" style="display:inline">{$equipeCourante.nom|xss_protect}</label>
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
						<input type="text" class="form-control" name="rechercheUser" value="{$rechercheUser|default:""}" />
						<span class="input-group-append">
							<button type="submit" class="btn {if $rechercheUser != ""}btn-danger{else}btn-default{/if}"><i class="fa fa-search" aria-hidden="true"></i></button>
						</span>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	{if $users|@count > 0}

		<div class="row">
			<div class="col-md-12">
				<div class="soplanning-box mt-2">
					<table class="table table-striped table-hover" id="userTab">
						<tr>
							<th class="w140">&nbsp;</th>
							<th class="userTabColId">
								{if $order eq "user_id"}
									{if $by eq "asc"}
										<a href="{$BASE}/ressources_list.php?page=1&order=user_id&by=desc">
									{else}
										<a href="{$BASE}/ressources_list.php?page=1&order=user_id&by=asc">
									{/if}
								{else}
									<a href="{$BASE}/ressources_list.php?page=1&order=order_planning&by={$by}">
								{/if}
							</th>
							<th>
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/ressources_list.php?page=1&order=nom&by=desc">{#ruser_liste_nom#} ({$users|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/ressources_list.php?page=1&order=nom&by=asc">{#ruser_liste_nom#} ({$users|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/ressources_list.php?page=1&order=order_planning&by={$by}">{#ruser_liste_nom#} ({$users|@count})</a>
								{/if}
							</th>
							<th class="wrap d-none d-sm-table-cell d-lg-table-cell">
								{if $order eq "nom_groupe"}
									{if $by eq "asc"}
										<a href="{$BASE}/ressources_list.php?page=1&order=nom_groupe&by=desc">{#user_liste_groupe#} ({$equipes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/ressources_list.php?page=1&order=nom_groupe&by=asc">{#user_liste_groupe#} ({$equipes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/ressources_list.php?page=1&order=order_planning&by={$by}">{#user_liste_groupe#} ({$equipes|@count})</a>
								{/if}
							</th>
						</tr>
						{foreach name=users item=userTmp from=$users}
							<tr>
								<td class="w140 nowrap" style="width:175px;">
									<a href="javascript:xajax_modifRessourceUser('{$userTmp.user_id|urlencode}');undefined;"><i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:xajax_supprimerRUser('{$userTmp.user_id|urlencode}');undefined;" onClick="javascript:return confirm('{#user_liste_confirmSuppr#|escape:"javascript"}')"><i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="{$BASE}/process/planning.php?filtreSurUser={$userTmp.user_id}" title="{#planning_filtre_sur_user#|escape}"><i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:xajax_upPlanning('{$userTmp.user_id|urlencode}');undefined;" class="up"><i class="fa fa-arrow-up fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:xajax_downPlanning('{$userTmp.user_id|urlencode}');undefined;" class="down"><i class="fa fa-arrow-down fa-lg fa-fw" aria-hidden="true"></i></a>
								</td>
								<td class="userTabColId"><span class="pastille-user" style="background-color:#{$userTmp.couleur};color:{"#"|cat:$userTmp.couleur|buttonFontColor}"></span></td>
								<td>{$userTmp.nom|xss_protect}</td>
								<td class="wrap d-none d-sm-table-cell d-lg-table-cell">{$userTmp.nom_groupe|xss_protect}</td>
							</tr>
						{/foreach}
						{if $nbPages > 1}
							<tr>
								<td colspan="7" class="text-right">
									{if $currentPage > 1}<a href="{$BASE}/ressources_list.php?page={$currentPage-1}">&lt;&lt; {#action_precedent#}</a>&nbsp;&nbsp;{/if}
									{section name=pagination loop=$nbPages}
										{if $smarty.section.pagination.iteration == $currentPage}<b>{else}<a href="{$BASE}/ressources_list.php?page={$smarty.section.pagination.iteration}">{/if}
										{$smarty.section.pagination.iteration}
										{if $smarty.section.pagination.iteration == $currentPage}</b>{else}</a>{/if}&nbsp;
									{/section}
									{if $currentPage < $nbPages}<a href="{$BASE}/ressources_list.php?page={$currentPage+1}">{#action_suivant#} &gt;&gt;</a>{/if}
								</td>
							</tr>
						{/if}
					</table>
				</div>
			</div>
		</div>

	{else}
	
		<div class="row">
			<div class="col-md-12">
				<div class="soplanning-box mt-2">
					{#info_noRecord#}
				</div>
			</div>
		</div>

	{/if}
</div>

{* CHARGEMENT SCROLL Y *}
<script>
	{literal}
	var yscroll = getCookie('yposProjets');
	window.onscroll = function() {document.cookie='yposProjets=' + window.pageYOffset;};
	addEvent(window, 'load', chargerYScrollPos);
	{/literal}
</script>
{include file="www_footer.tpl"}