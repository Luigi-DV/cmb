{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box" style="font-size:17px">
				<table width="100%">
				<tr>
					<td><b>{#res_titre#|strtoupper}</b></td>
					<td align="right">
						<a href="../ressources_list.php" class="btn btn-sm btn-default">{#res_titre#}</a>
						<a href="index.php" class="btn btn-sm btn-default">{#aide_retour#}</a>
					</td>
				</tr>
				</table>
				<b></b>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box mt-2">
				{#res_contenu#}
				<br><br>
				<center>
					<a href="index.php" class="btn btn-sm btn-default">{#aide_retour#}</a>
				</center>	
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}