{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/ressources.php" class="btn btn-default" ><i class="fa fa-list fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuRessources#}</a>
					<a href="javascript:xajax_modifRessourceGroupe('');undefined;" class="btn btn-default"><img src="{$BASE}/toolbox.png" height="16px"/>&nbsp;&nbsp;{#menuCreerUserGroupe#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				{if $groupes|@count > 0}
					<table class="table table-striped table-hover">
						<tr>
							<th>&nbsp;</th>
							<th>
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/equi_groupes.php?page=1&order=nom&by=desc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/equi_groupes.php?page=1&order=nom&by=asc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/equi_groupes.php?page=1&order=nom&by={$by}">{#user_liste_groupe#} ({$groupes|@count})</a>
								{/if}
							</th>
							{assign var=totalRessources value=0}
							{foreach name=groupes item=groupe from=$groupes}
								{assign var=totalRessources value=$totalRessources+$groupe.totalRessources}
							{/foreach}
							<th>{#user_groupe_nbUsers#} ({$totalRessources})</th>
						</tr>
						{foreach name=groupes item=groupe from=$groupes}
							<tr>
								<td class="w40">
									<a href="javascript:xajax_modifRessourceGroupe({$groupe.ressource_groupe_id});undefined;"><i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:if(confirm('{#confirm#|escape:"javascript"}')){literal}{{/literal}javascript:xajax_supprimerRessourceGroupe({$groupe.ressource_groupe_id});{literal}}{/literal};undefined;"><i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i></a>
								</td>
								<td>{$groupe.nom|xss_protect}&nbsp;</td>
								<td>{$groupe.totalRessources}&nbsp;</td>
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
<script>
	{literal}
	var yscroll = getCookie('yposProjets');
	window.onscroll = function() {document.cookie='yposProjets=' + window.pageYOffset;};
	addEvent(window, 'load', chargerYScrollPos);
	{/literal}
</script>
{include file="www_footer.tpl"}