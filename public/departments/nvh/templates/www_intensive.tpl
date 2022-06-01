{* Smarty *}
{include file="www_header.tpl"}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/options.php" class="btn btn-default" ><i class="fa fa-cogs fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuOptions#}</a>
					<a href="javascript:xajax_modifIntensive();undefined;" class="btn btn-default" ><img src="{$BASE}/star.png" height="22px"/>&nbsp;&nbsp;{#menuCreerIntensive#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				{if $feries|@count > 0}
					<table class="table table-striped table-hover">
						<thead>
						<tr>
							<th class="w100">&nbsp;</th>
							<th class="w100">
								<b>{#feries_date#}</b>
							</th>
							<th>
								<b>{#feries_libelle#}</b>
							</th>
						</tr>
						</thead>
						<tbody>
						{foreach name=feries item=ferie from=$feries}
							<tr>
								<td class="w100">
									{*<a href="javascript:xajax_modifIntensive('{$ferie.date_ferie|urlencode}');undefined;"><i class="fa fa-pencil fa-lg fa-fw" aria-hidden="true"></i></a>*}
									<a href="javascript:xajax_supprimerIntensive('{$ferie.date_ferie|urlencode}');undefined;" onClick="javascript:return confirm('{#confirm#|escape:"javascript"}')"><i class="fa fa-trash-o fa-lg fa-fw" aria-hidden="true"></i></a>
								</td>
								<td class="w100">
									{$ferie.date_ferie|sqldate2userdate}&nbsp;
								</td>
								<td>
									{$ferie.libelle}
								</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
				{else}
					{#info_noRecord#}
				{/if}
			</div>
		</div>
	</div>
</div>
{include file="www_footer.tpl"}