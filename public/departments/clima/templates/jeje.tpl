{* Smarty *}

<form class="form-horizontal" method="post" action="" onsubmit="return false;" name="formUser" autocomplete="off">
{* pour tester si compte déjà existant ou pas *}
<div class="container-fluid">
	
	{$cosito}
	
	<div class="form-group col-md-12 text-center">
				<input type="button" class="btn btn-primary" value="{#enregistrer#}" onClick="specific_users_ids=getSelectValue('specific_user_id');xajax_submitFormUser($('#user_id').val(), $('#user_id_origine').val(), $('#user_groupe_id').val(), $('#nom').val(), $('#email_user').val(), $('#tmp_lo').val(), $('#tmp_pa').val(), !$('#visible_planningOui').is(':checked'), {if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}$('#couleur2 option:selected').val(){else}$('#couleur_user').val(){/if}, $('#notificationsOui').is(':checked'), $('#envoiMailPwd').is(':checked'), new Array(getRadioValue('users_manage'), getRadioValue('projects_manage'), getRadioValue('projectgroups_manage'), getRadioValue('planning_modif'), getRadioValue('planning_view'), getRadioValue('planning_view_users'), getRadioValue('lieux'), getRadioValue('ressources'), getRadioValue('audit'), getRadioValue('parameters'), ($('#stats_users').is(':checked') ? $('#stats_users').val() : ''), ($('#stats_projects').is(':checked') ? $('#stats_projects').val() : '')), $('#user_adress').val(), $('#user_phone').val(),$('#user_mobile').val(), $('#user_metier').val(), $('#user_comment').val(), !$('#login_actifOui').is(':checked'), specific_users_ids);" />
		</div>
	</div>
</div>