<form name="form_manage_cars" id="form_manage_cars" action="../templates/index.php" method="post">
	<div id="manage_cars" class="ui-widget ui-state-default ui-corner-all">
		<div id="divSelectCar" class="divcenterManage">
			<select id="cbboxcar" name="cbboxcar" class="cbboxsubmit">
				<option value="">Aucun</option>
				<?php foreach ($list_cars as $voitures) {
						echo '<option>'.$voitures['modele'].' '.$voitures['immatriculation'].'</option>';
					}
					echo '<option value="" disabled selected>Choisir un véhicule</option>';
					if(isset($idcar)){
						echo '<option selected>'.$idcar.'</option>';
					}
				?>
			</select><br/>
			<a class="champOblig">* Champs obligatoires pour la création de véhicule.</a>
		</div>
		<div class="divcenterManage">
			<label class="ui-widget-header ui-corner-all" for="marque">Marque : <a class="champOblig">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="modele">Modèle : <a class="champOblig">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="immatriculation">Immatriculation : <a class="champOblig">*</a></label>
		</div>
		<div class="divcenterManage">
			<input id="marque" name="marque" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($CarToModify['marque'])){ echo $CarToModify['marque'];}?>"/>
			<input id="modele" name="modele" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($CarToModify['modele'])){ echo $CarToModify['modele'];}?>"/>
			<input id="immatriculation" name="immatriculation" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($CarToModify['immatriculation'])){ echo $CarToModify['immatriculation'];}?>"/>
		</div>
		<div class="divcenterManage">
			<input id="idcar" name="idcar" type="hidden" value="<?php echo $idcar;?>"/>
			<input id="BP_NewCar" name="BP_NewCar" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Ajouter"/>
			<input id="BP_ModifyCar" name="BP_ModifyCar" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled"  value="Modifier"/>
			<input  id="BP_DeleteCar" name="BP_DeleteCar" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Suprimer"/>
		</div>
	</div>
</form>
