{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box" style="font-size:17px">
				{#index_contenu#}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				<table width="100%">
				<tr>
					<td style="font-size:18px">
						<a href="utilisateurs.php"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#users_titre#}</a>
						<br><br>
						<a href="equipes.php"><i class="fa fa-street-view fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#equipes_titre#}</a>
						<br><br>
						<a href="projets.php"><i class="fa fa-book fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#projets_titre#}</a>
						<br><br>
						<a href="groupes.php"><i class="fa fa-folder-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#groupes_titre#}</a>
						<br><br>
					</td>
					<td style="font-size:18px">
						<a href="planning.php">&nbsp;<i class="fa fa-calendar fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#planning_titre#}</a>
						<br><br>
						<a href="equipment.php"><i class="fa fa-plug fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#ressources_titre#}</a>
						<br><br>
						<a href="resource.php">&nbsp;&nbsp;<i class="fa fa-map-pin fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#res_titre#}</a>
						<br><br>
						{*<a href="samples.php"><img src="{$BASE}/people-carry2.png" height="22px"/>&nbsp;&nbsp;{#samples_titre#}</a>*}
					</td>
				</tr>
				</table>

				<br>
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}