
<div id="manage_events_cars" class="ui-corner-all ui-widget ui-widget-content container-fluid">
	<form name="form_manage_events" id="form_manage_events" action="../templates/index.php" method="post">
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all" for="cbboxusers">Utilisateur : </label>
			<label class="ui-widget-header ui-corner-all" for="cbboxvoitures">Véhicule : </label>
		</div>
		<div class="divcenter">
			<select name="cbboxusers" id="cbboxusers">
				<?php foreach ($list_users as $utilisateurs) {
						echo '<option>'.$utilisateurs['initiales'].'</option>';
					}
					echo '<option value="" disabled selected>Choisir un utilisateur</option>';
					if(!empty($modifyCars['utilisateur'])){
						echo '<option selected>'.$modifyCars['utilisateur'].'</option>';
					}
				?>
			</select>
			<select name="cbboxvoitures" id="cbboxvoitures">
				<?php foreach ($list_cars as $voitures) {
						echo '<option>'.$voitures['modele'].' '.$voitures['immatriculation'].'</option>';
					}
					echo '<option selected>'.$cbboxcarselected.'</option>';
				?>
			</select>
		</div>
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all" for="Client">Client : </label>
			<label class="ui-widget-header ui-corner-all" for="Ville">Ville : </label>
		</div>
		<div class="divcenter">
			<input class="ui-widget ui-state-default ui-corner-all autocompleteCustomers" type="text" name="Client" id="Client" value="<?php if(!empty($Client)){echo $Client;}?>"/>
			<input class="ui-widget ui-state-default ui-corner-all autocompleteCities" type="text" name="Ville" id="Ville" value="<?php if(!empty($Ville)){echo $Ville;}?>"/>
		</div>
		<div class="divcenter">
			<label class="ui-widget-header ui-corner-all" for="DateStart">Date de Début : </label>
			<label class="ui-widget-header ui-corner-all" for="DateEnd">Date de Fin : </label>
		</div>
		<div class="divcenter divfivelines">
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="DatepickerStart" id="DateStart" readonly value="<?php echo $Date;?>"/>
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="DatepickerEnd" id="DateEnd" readonly value="<?php echo $DatePlus1;?>"/>
			<select type="text" name="cbbox_am_pm_start" id="cbbox_am_pm_start">
				<?php
					if(!empty($modifyCars['am_pm_start'])){
						if($modifyCars['am_pm_start'] == 'am'){
							echo '
							<option value="'.$modifyCars['am_pm_start'].'" selected>Matin</option>
							<option value="pm">Après Midi</option>';
						} else {
							echo '
							<option value="'.$modifyCars['am_pm_start'].'" selected>Après Midi</option>
							<option value="am">Matin</option>';
						}
					} else if (!empty($halfdayCars) && $halfdayCars == 'am'){
						echo '
						<option value="am" selected>Matin</option>
						<option value="pm">Après Midi</option>';
					} else if (!empty($halfdayCars) && $halfdayCars == 'pm'){
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
					if(!empty($modifyCars['am_pm_end'])){
						if($modifyCars['am_pm_end'] == 'am'){
							echo '
							<option value="'.$modifyCars['am_pm_end'].'" selected>Matin</option>
							<option value="pm">Après Midi</option>';
						} else {
							echo '
							<option value="'.$modifyCars['am_pm_end'].'" selected>Après Midi</option>
							<option value="am">Matin</option>';
						}
					} else if (!empty($halfdayCars) && $halfdayCars == 'am'){
						echo '
						<option value="am" selected>Matin</option>
						<option value="pm">Après Midi</option>';
					} else if (!empty($halfdayCars) && $halfdayCars == 'pm'){
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
			<label class="ui-widget-header ui-corner-all" for="comment">Commentaire : </label>
			<textarea id="comment" name="comment" class="ui-widget ui-state-default ui-corner-all" maxlength="50" placeholder="vous pouvez indiquer ici l'heure estimée à laquelle le véhicule sera disponible. 50 Caractères max"><?php echo (!empty($modifyCars['commentaire']))? $modifyCars['commentaire'] : '';?></textarea>
		</div>
		<div class="divcenter divfivelines">
			<input hidden id="idvueCars" name="idvueCars" type="text" value="<?php echo $idsqlCars ?>"/>
			<?php echo $Boutons;?>
			<button id="BP_Retour" name="BP_Retour" class="ui-widget ui-state-default ui-corner-all">Retour</button>
		</div>

	</form>
</div>
<script>
	$(function(){
		$('#cbboxusers').selectmenu({
			change: function( event, ui ) {
				if($('#Client').val() !== "" && $('#Ville').val() !== "" && $('#cbboxusers').val() !== null){
					$('#BP_CreateEventCars').removeAttr("disabled").removeClass( 'ui-state-disabled' );
				} else {
					$('#BP_CreateEventCars').attr("disabled", "disabled").addClass( 'ui-state-disabled' );
				}
			}
		});
		$('#Client,#Ville').change(function() {
			if ($('#Client').val() !== "" && $('#Ville').val() !== "" && $('#cbboxusers').val() !== null){
				$('#BP_CreateEventCars').removeAttr("disabled").removeClass( 'ui-state-disabled' );
			}else {
				$('#BP_CreateEventCars').attr("disabled", "disabled").addClass( 'ui-state-disabled' );
			}
			
		});
	});
</script>
