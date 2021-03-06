{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/ressources_list.php" class="btn btn-default" ><i class="fa fa-list fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuGestionRUsers#}</a>
					<a href="javascript:xajax_modifUserGroupe('','ressource');undefined;" class="btn btn-default"><i class="fa fa-th fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerUserGroupe#}</a>
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
										<a href="{$BASE}/user_groupes.php?page=1&order=nom&by=desc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/user_groupes.php?page=1&order=nom&by=asc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/user_groupes.php?page=1&order=nom&by={$by}">{#user_liste_groupe#} ({$groupes|@count})</a>
								{/if}
							</th>
							{assign var=totalUsers value=0}
							{foreach name=groupes item=groupe from=$groupes}
								{assign var=totalUsers value=$totalUsers+$groupe.totalUsers}
							{/foreach}
							<th>{#user_groupe_nbRUsers#} ({$totalUsers})</th>
						</tr>
						{foreach name=groupes item=groupe from=$groupes}
							<tr>
								<td class="w40">
									{if $groupe.nom neq "Users" && $groupe.nom neq "Project Manager"}
										<a href="javascript:xajax_modifUserGroupe({$groupe.user_groupe_id},'ressource');undefined;"><i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i></a>
										<a href="javascript:if(confirm('{#confirm#|escape:"javascript"}')){literal}{{/literal}javascript:xajax_supprimerUserGroupe({$groupe.user_groupe_id});{literal}}{/literal};undefined;"><i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i></a>
									{/if}
								</td>
								<td>{$groupe.nom|xss_protect}&nbsp;</td>
								<td>{$groupe.totalUsers}&nbsp;</td>
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