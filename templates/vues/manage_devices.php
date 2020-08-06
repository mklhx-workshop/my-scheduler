<form name="form_manage_devices" id="form_manage_devices" action="../templates/index.php" method="post">
	<div id="manage_devices" class="ui-widget ui-state-default ui-corner-all">
		<div id="divSelectDevice" class="divcenterManage">
			<select id="cbboxdevice" name="cbboxdevice" class="cbboxsubmit">
				<option value="">Aucun</option>
				<?php foreach ($list_devices as $device) {
						echo '<option>'.$device['marque'].' '.$device['modele'].' '.$device['identifiant'].'</option>';;
					}
					echo '<option value="" disabled selected>Choisir un appareil</option>';
					if(isset($iddevice)){
						echo '<option selected>'.$iddevice.'</option>';
					}
				?>
			</select><br />
			<a style="font-size: 12px;color: red">* Champs obligatoires pour la création d'appareils.</a>
		</div>
		<div class="divcenterManage">
			<label class="ui-widget-header ui-corner-all" for="marque">Marque : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="modele">Modèle : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="identifiant">Identifiant : <a style="color: red">*</a></label>
		</div>
		<div class="divcenterManage">
			<input id="marque" name="marque" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($DeviceToModify['marque'])){ echo $DeviceToModify['marque'];}?>"/>
			<input id="modele" name="modele" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($DeviceToModify['modele'])){ echo $DeviceToModify['modele'];}?>"/>
			<input id="identifiant" name="identifiant" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($DeviceToModify['identifiant'])){ echo $DeviceToModify['identifiant'];}?>"/>
		</div>
		<div class="divcenterManage">
			<input type="hidden" id="iddevice" name="iddevice" value="<?php echo $iddevice ?>"/>
			<input id="BP_NewDevice" name="BP_NewDevice" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Ajouter"/>
			<input id="BP_ModifyDevice" name="BP_ModifyDevice" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Modifier"/>
			<input  id="BP_DeleteDevice" name="BP_DeleteDevice" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Suprimer"/>
		</div>
	</div>
</form>
