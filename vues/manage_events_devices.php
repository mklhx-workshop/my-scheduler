<div id="manage_events_devices" class="ui-corner-all ui-widget ui-widget-content container-fluid">
	<form name="form_manage_events" id="form_manage_events" action="../templates/index.php" method="post">
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all">Utilisateur : </label>
			<label class="ui-widget-header ui-corner-all">Appareil : </label>
		</div>
		<div class="divcenter">
			<select name="cbboxusers" id="cbboxusers">
				<?php foreach ($list_users as $utilisateurs) {
						echo '<option>'.$utilisateurs['initiales'].'</option>';
					}
					echo '<option value="" disabled selected>Choisir un utilisateur</option>';
					if(!empty($modifyDevices['utilisateur'])){
						echo '<option selected>'.$modifyDevices['utilisateur'].'</option>';
					}
				?>
			</select>
			<select name="cbboxdevices" id="cbboxdevices">
				<?php foreach ($list_devices as $devices) {
						echo '<option>'.$devices['modele'].' '.$devices['identifiant'].'</option>';
					}
					echo '<option selected>'.$cbboxdeviceselected.'</option>';
				?>
			</select>
		</div>
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all">Client : </label>
			<label class="ui-widget-header ui-corner-all">Ville : </label>
		</div>
		<div class="divcenter">
			<input class="ui-widget ui-state-default ui-corner-all autocompleteCustomers" type="text" name="Client" id="Client" value="<?php if(!empty($Client)){echo $Client;}?>"/>
			<input class="ui-widget ui-state-default ui-corner-all autocompleteCities" type="text" name="Ville" id="Ville" value="<?php if(!empty($Ville)){echo $Ville;}?>"/>
		</div>
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all">Date de Début : </label>
			<label class="ui-widget-header ui-corner-all">Date de Fin : </label>
		</div>
		<div class="divcenter divfivelines">
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="DatepickerStart" id="DateStart" readonly value="<?php echo $Date;?>"/>
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="DatepickerEnd" id="DateEnd" readonly value="<?php echo $DatePlus1;?>"/>
			<select type="text" name="cbbox_am_pm_start" id="cbbox_am_pm_start">
				<?php
					if(!empty($modifyDevices['am_pm_start'])){
						if($modifyDevices['am_pm_start'] == 'am'){
							echo '
							<option value="'.$modifyDevices['am_pm_start'].'" selected>Matin</option>
							<option value="pm">Après Midi</option>';
						} else {
							echo '
							<option value="'.$modifyDevices['am_pm_start'].'" selected>Après Midi</option>
							<option value="am">Matin</option>';
						}
					} else if (!empty($halfdayDevices) && $halfdayDevices == 'am'){
						echo '
						<option value="am" selected>Matin</option>
						<option value="pm">Après Midi</option>';
					} else if (!empty($halfdayDevices) && $halfdayDevices == 'pm'){
						echo '
						<option value="am">Matin</option>
						<option value="pm" selected>Après Midi</option>';
					} else {
							echo '
							<option value="am" selected>Matin</option>
							<option value="pm">Après Midi</option>';
					}
				?>
			</select>
			<select type="text" name="cbbox_am_pm_end" id="cbbox_am_pm_end">
				<?php
					if(!empty($modifyDevices['am_pm_end'])){
						if($modifyDevices['am_pm_end'] == 'am'){
							echo '
							<option value="'.$modifyDevices['am_pm_end'].'" selected>Matin</option>
							<option value="pm">Après Midi</option>';
						} else {
							echo '
							<option value="'.$modifyDevices['am_pm_end'].'" selected>Après Midi</option>
							<option value="am">Matin</option>';
						}
					} else if (!empty($halfdayDevices) && $halfdayDevices == 'am'){
						echo '
						<option value="am" selected>Matin</option>
						<option value="pm">Après Midi</option>';
					} else if (!empty($halfdayDevices) && $halfdayDevices == 'pm'){
						echo '
						<option value="am">Matin</option>
						<option value="pm" selected>Après Midi</option>';
					} else {
							echo '
							<option value="am">Matin</option>
							<option value="pm" selected>Après Midi</option>';
					}
				?>
			</select>
		</div>
		<div class="divcenter divfourlines">
			<label class="ui-widget-header ui-corner-all">Commentaire : </label>
			<textarea id="comment" name="comment" class="ui-widget ui-state-default ui-corner-all" maxlength="50" placeholder="vous pouvez indiquer ici l'heure estimée à laquelle l'appareil sera disponible. 50 Caractères max"><?php echo (!empty($modifyDevices['commentaire']))? $modifyDevices['commentaire'] : '';?></textarea>
		</div>
		<div class="divcenter divfivelines">
			<input hidden id="idvueDevices" type="text" name="idvueDevices" value="<?php echo $idsqldevice ?>"/>
			<?php echo $Boutons; ?>
			<button id="BP_Retour" name="BP_Retour" class="ui-widget ui-state-default ui-corner-all">Retour</button>
		</div>
	</form>
</div>
<script>
	$(function(){
		$('#cbboxusers').selectmenu({
			change: function( event, ui ) {
				if ($('#Client').val() !== "" && $('#Ville').val() !== "" && $('#cbboxusers').val() !== null){
					$('#BP_CreateEventDevices').removeAttr("disabled").removeClass( 'ui-state-disabled' );
				}else {
					$('#BP_CreateEventDevices').attr("disabled", "disabled").addClass( 'ui-state-disabled' );
				}	
			 }
		});
		$( "#Client,#Ville" ).change(function() {
			if ($('#Client').val() !== "" && $('#Ville').val() !== "" && $('#cbboxusers').val() !== null){
				$('#BP_CreateEventDevices').removeAttr("disabled").removeClass( 'ui-state-disabled' );
			}else {
				$('#BP_CreateEventDevices').attr("disabled", "disabled").addClass( 'ui-state-disabled' );
			}			
		});
		
	});
</script>
