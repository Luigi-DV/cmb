{* Smarty *}
{include file="www_header.tpl"}

<div class="container-fluid">
{include file="www_statistics_filtre.tpl"}

	{* le planning *}
	<div class="row">
		<div class="col-md-12" id="thirdLayer">
			<div class="soplanning-box" id="divPlanning">
				<div id="top-scroll">
					<div id="top-scroll-inner">
					</div>
				</div>
				<h2> % Ocupation </h2>
				<h4> 3 shifts of 8 hours on 5 days </h4>
				<table class="ocupationTable" id="tableOcupation">
				<tr>
					<th class="ocupationTable"> SALAS </th>
					{foreach from=$weeks item=number}
						{if $number=='AVERAGE' or $number=='TOTAL h'}
							<th class="ocupationTable" > {$number} </th>
						{else}
							<th class="ocupationTable" > W{$number} </th>
						{/if}
					{/foreach}
					{$htmlTableau}
				</tr>
				</table>
				</div>
		</div>
	</div>
</div>

{*<div id="divChoixDragNDrop" onMouseOut="masquerSousMenuDelai('divChoixDragNDrop');" onMouseOver="AnnuleMasquerSousMenu('divChoixDragNDrop');" onfocus="AnnuleMasquerSousMenu('divChoixDragNDrop')">
	<a href="javascript:windowPatienter();xajax_moveCasePeriode(idCaseEnCoursDeplacement, idCaseDestination, false, 'seule');undefined;">{#planning_deplacer#}</a>
	<br /><br />
	<div id="divLienDeplacementToutesTaches">
		<a href="javascript:windowPatienter();xajax_moveCasePeriode(idCaseEnCoursDeplacement, idCaseDestination, false, 'toutes');undefined;">{#planning_deplacer_toutestaches#}</a>
		<br /><br />
	</div>
	<a href="javascript:windowPatienter();xajax_moveCasePeriode(idCaseEnCoursDeplacement, idCaseDestination, true);undefined;">{#planning_copier#}</a>
	<br /><br />
	<a href="javascript:masquerSousMenu('divChoixDragNDrop');">{#planning_annuler#}</a>
</div>*}
<script>
{literal}
Reloader.init({/literal}{$smarty.const.CONFIG_REFRESH_TIMER}{literal});
{/literal}
{* when coming from an email *}
{if isset($direct_periode_id)}
	addEvent(window, 'load', function(){literal}{{/literal}xajax_modifPeriode({$direct_periode_id}){literal}}{/literal});
{/if}
function resizeDivConteneur()
{
	var b = $("#tabContenuPlanning");
	var pos = b.offset();
	var h = pos.top;
	var h2 = window.innerHeight;
	var h3 = h2 - h - 65;
	$('#divConteneurPlanning').css('max-height',h3);
		var largertab=$('#divConteneurPlanning').width();
	var largertab2=document.getElementById('tabContenuPlanning').offsetWidth + 18 + 'px';
	document.getElementById('divConteneurPlanning').scrollLeft = xscroll;
	document.getElementById('divConteneurPlanning').scrollTop = yscroll;
	$("#top-scroll").width(largertab);
	$("#top-scroll-inner").width(largertab2);
}
{* textes pour erreur dans fichier JS *}
var js_choisirProjet = '{#js_choisirProjet#|xss_protect}';
var js_choisirUtilisateur = '{#js_choisirUtilisateur#|xss_protect}';
var js_choisirDateDebut = '{#js_choisirDateDebut#|xss_protect}';
var js_saisirFormatDate = '{#js_saisirFormatDate#|xss_protect}';
var js_dateFinInferieure = '{#js_dateFinInferieure#|xss_protect}';
var js_deposerCaseSurDate = '{#js_deposerCaseSurDate#|xss_protect}';
var js_deplacementOk = '{#js_deplacementOk#|xss_protect}';
var js_patienter = '{#js_patienter#|xss_protect}';
var idDrag;
var dragElementParent;
var oldDragBorder;
var displayMode = {$modeAffichage|@json_encode};
var dateDebut = {$dateDebut|@json_encode};
var dateFin = {$dateFin|@json_encode};
{literal}
    $(document).ready(function() {
	$("#tabContenuPlanning").tableHeadFixer(); 
    });
	// Gestion du filtre Projet
		$("#filtreGroupeProjet").multiselect({
			selectAll:false,
			noUpdatePlaceholderText:true,
			nameSuffix: 'projet',
			desactivateUrl: 'process/statistics.php?desactiverFiltreGroupeProjet=1',
			placeholder: '{/literal}<i class="fa fa-folder-open fa-lg" aria-hidden="true"></i><span class="d-none d-md-inline-block">&nbsp;</span>{literal}',
			texts: {
				selectAll    : '{/literal}{#formFiltreProjetCocherTous#}{literal}',
				unselectAll    : '{/literal}{#formFiltreProjetDecocherTous#}{literal}',
				disableFilter : '{/literal}{#formFiltreProjetDesactiver#}{literal}',
				validateFilter : '{/literal}{#submit#}{literal}',
				search : '{/literal}{#search#}{literal}'
			},
		});
		$("#filtreGroupeProjet").show();
	// Gestion du filtre Ressources
		$("#filtreUser").multiselect({
			selectAll:false,
			noUpdatePlaceholderText:true,
			nameSuffix: 'user',
			desactivateUrl: 'process/statistics.php?desactiverFiltreUser=1',
			placeholder: '{/literal}<i class="fa fa-map-pin fa-lg fa-fw" aria-hidden="true"></i><span class="d-none d-md-inline-block">&nbsp;</span>{literal}',
			texts: {
				selectAll    : '{/literal}{#formFiltreUserCocherTous#}{literal}',
				unselectAll    : '{/literal}{#formFiltreUserDecocherTous#}{literal}',
				disableFilter : '{/literal}{#formFiltreUserDesactiver#}{literal}',
				validateFilter : '{/literal}{#submit#}{literal}',
				search : '{/literal}{#search#}{literal}'
			},
		});
		$("#filtreUser").show();
	// Gestion du filtre Equipment
		$("#filtreGroupeRessource").multiselect({
			selectAll:false,
			noUpdatePlaceholderText:true,
			nameSuffix: 'ressource',
			desactivateUrl: 'process/statistics.php?desactiverFiltreRessource=1',
			placeholder: '{/literal}<i class="fa fa-plug fa-lg fa-fw" aria-hidden="true"></i><span class="d-none d-md-inline-block">&nbsp;</span>{literal}',
			texts: {
				selectAll    : '{/literal}{#formFiltreUserCocherTous#}{literal}',
				unselectAll    : '{/literal}{#formFiltreUserDecocherTous#}{literal}',
				disableFilter : '{/literal}{#formFiltreUserDesactiver#}{literal}',
				validateFilter : '{/literal}{#submit#}{literal}',
				search : '{/literal}{#search#}{literal}'
			},
		});
		$("#filtreGroupeRessource").show();
	// Gestion du filtre Users
		$("#filtreGroupeLieu").multiselect({
			selectAll:false,
			noUpdatePlaceholderText:true,
			nameSuffix: 'ressource',
			desactivateUrl: 'process/statistics.php?desactiverFiltreLieu=1',
			placeholder: '{/literal}<i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i><span class="d-none d-md-inline-block">&nbsp;</span>{literal}',
			texts: {
				selectAll    : '{/literal}{#formFiltreUserCocherTous#}{literal}',
				unselectAll    : '{/literal}{#formFiltreUserDecocherTous#}{literal}',
				disableFilter : '{/literal}{#formFiltreUserDesactiver#}{literal}',
				validateFilter : '{/literal}{#submit#}{literal}',
				search : '{/literal}{#search#}{literal}'
			},
		});
		$("#filtreGroupeLieu").show();
	// Ajout des boutons de scroll de planning
	var e = $("#divConteneurPlanning").get(0);
	if (e.scrollWidth > e.clientWidth)
	{
		{/literal}
		{if $fleches eq 1}
		{literal}
			$('#left-scroll').show();
			$('#right-scroll').show();
			$('#divConteneurPlanning').css({'margin-left':'30px','margin-right':'30px'});
			$('#top-scroll').css({'margin-left':'30px','margin-right':'30px'});
			$('#right-button').click(function() {
				$('#divConteneurPlanning').animate({
				scrollLeft: "+=600px"
				}, 300);
			});
			$('#left-button').click(function() {
				$('#divConteneurPlanning').animate({
				scrollLeft: "-=600px"
				}, 300);
			});
		{/literal}
		{/if}
		{if $ascenceur eq 1}
		{literal}
			$('#top-scroll').show();
			$('#top-scroll').scroll(function(){
				$('#divConteneurPlanning').scrollLeft($('#top-scroll').scrollLeft());
			});
			$('#divConteneurPlanning').scroll(function(){
				$('#top-scroll').scrollLeft($('#divConteneurPlanning').scrollLeft());
			});
		{/literal}
		{/if}
		{literal}
	}
		{/literal}
		{if $baseligne == "heures"}
		{literal}
			$('#divConteneurPlanning').attr('style','overflow:visible');
		{/literal}
		{/if}		
		{literal}
		// Fixe les premi�res colonnes
		$("#tabContenuPlanning").tableHeadFixer({
			'left' : 1,
			'z-index' : 10,
		{/literal}
		{if $entetesflottantes eq 0}
		{literal}
			'head' : false
		{/literal}
		{/if}
		{literal}
		});
		{/literal}
		// Ent�te flottantes
		{if $entetesflottantes eq 1}
		{literal}
		$(window).resize(function(){
			resizeDivConteneur();
		});
		{/literal}
		{/if}
		{if isset($droitAjoutPeriode) and $droitAjoutPeriode== true}
	{literal}
	// Affichage du formulaire p�riode si clic sur case vide
	$('#tabContenuPlanning td.week,#tabContenuPlanning div.cellTask,#tabContenuPlanning div.cellTaskcons,#tabContenuPlanning td.weekend,#tabContenuPlanning .cellProject,#tabContenuPlanning .cellProjectAM,#tabContenuPlanning .cellProjectPM,#tabContenuPlanning .cellProjectN').click(function(ev){
	ev.preventDefault();
	if ($(this).hasClass("cellProject") || $(this).hasClass("cellProjectAM") || $(this).hasClass("cellProjectPM") || $(this).hasClass("cellProjectN"))
		{
		 cellClic(this.id,0);
		}else cellClic(this.id,1);
		return false;
	});
	{/literal}
	{literal}
	// Affichage du formulaire p�riode si clic sur case vide
	$('#tabContenuPlanning td.weekcons,#tabContenuPlanning td.weekend,#tabContenuPlanning .cellProjectcons,#tabContenuPlanning .cellProjectAMcons,#tabContenuPlanning .cellProjectPMcons,#tabContenuPlanning .cellProjectNcons').click(function(ev){
	ev.preventDefault();
	if ($(this).hasClass("cellProjectcons") || $(this).hasClass("cellProjectAMcons") || $(this).hasClass("cellProjectPMcons") || $(this).hasClass("cellProjectNcons"))
		{
		 cellClic(this.id,0);
		}else cellClic(this.id,1);
		return false;
	});
	{/literal}
{/if}
	{literal}
	// Gestion du cookie de positionnement
	function writeCookie(displayMode){
		if (displayMode == 'mois'){
			document.cookie='yposMois=' + window.pageYOffset;
			document.cookie='xposMoisWin=' + window.pageXOffset;
		}else if (displayMode == 'jour'){
			document.cookie='yposJours=' + window.pageYOffset;
			document.cookie='xposJoursWin=' + window.pageXOffset;
		}
	}
	{/literal}
	// M�morisation scrolling
	{if isset($smarty.cookies.dateDebut)}
		var cookieDateDebut = '{$smarty.cookies.dateDebut}';
	{else}
		var cookieDateDebut = 0;
	{/if}
	{if isset($smarty.cookies.dateFin)}
		var cookieDateFin = '{$smarty.cookies.dateFin}';
	{else}
		var cookieDateFin = 0;
	{/if}
	{literal}
	if (dateDebut != cookieDateDebut || dateFin != cookieDateFin)  
	{
		document.cookie='dateDebut=' + dateDebut ;
		document.cookie='dateFin=' + dateFin ;
		document.cookie='xposMoisWin=0';
		document.cookie='xposMois=0';
		document.cookie='xposJoursWin=0';
		document.cookie='xposJours=0';
		document.cookie='yposMoisWin=0';
		document.cookie='yposMois=0';
		document.cookie='yposJoursWin=0';
		document.cookie='yposJours=0';
	}
	// R�cuperation
	if (displayMode == 'mois')
	{
		{/literal}
		{if isset($smarty.cookies.xposMois)}
			var xscroll = {$smarty.cookies.xposMois};
		{else}
			var xscroll = 0;
		{/if}
		{if isset($smarty.cookies.xposMoisWin)}
			var xscrollWin = {$smarty.cookies.xposMoisWin};
		{else}
			var xscrollWin = 0;
		{/if}
		{if isset($smarty.cookies.yposMois)}
			var yscroll = {$smarty.cookies.yposMois};
		{else}
			var yscroll = 0;
		{/if}
		{literal}
	}else if (displayMode == 'jour'){
		{/literal}
		{if isset($smarty.cookies.xposJours)}
			var xscroll = {$smarty.cookies.xposJours};
		{else}
			var xscroll = 0;
		{/if}
		{if isset($smarty.cookies.xposJoursWin)}
			var xscrollWin = {$smarty.cookies.xposJoursWin};
		{else}
			var xscrollWin = 0;
		{/if}
		{if isset($smarty.cookies.yposJours)}
			var yscroll = {$smarty.cookies.yposJours};
		{else}
			var yscroll = 0;
		{/if}
		{literal}
	}
	resizeDivConteneur();
	window.onscroll = function() {writeCookie(displayMode)};
	$('#divConteneurPlanning').scroll(function(){
	document.cookie='xposMois=' + document.getElementById('divConteneurPlanning').scrollLeft;
	document.cookie='yposMois=' + document.getElementById('divConteneurPlanning').scrollTop;
	});
	{/literal}
	// Onload
	jQuery(function() {
	{if $smarty.session.isMobileOrTablet==0}
	{literal}
	// hack pour empecher fermeture du layer au click sur les boutons du calendrier1
	$("#ui-datepicker-div").click( function(event) {
		event.stopPropagation();
	});
	jQuery('#dropdownDateSelector .dropdown-menu').on({
	"click":function(e){
			e.stopPropagation();
		}
	});
	{/literal}
	{/if}
	});
	
	function mouseDown(e, id){
	e = e || window.event;
	switch (e.which) {
	case 2: alert('middle'); break;
	case 3: windowPatienter();xajax_moveCasePeriode(id, idCaseDestination, true);undefined; break; 
	}
	}
</script>

{include file="www_footer.tpl"}