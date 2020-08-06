<form method="post" action="../templates/index.php">
	<div id="vue_carnetbord" class="ui-widget ui-state-default ui-corner-all container-fluid">
		<p style="color: red; font-size:30px;">Cette page est en phase de beta test</p>
		Données carnets de bord du véhicule : <?php echo (isset($vcall['carId']))? '<a class="carnetDatas">'.$vcall['carId'].'</a>' : ''?><br/>
		Immatriculation du véhicule : <?php echo (isset($vcall['IN_immat']))? '<a class="carnetDatas">'.$vcall['IN_immat'].'</a>' : ''?><br/>
		Date de la dernière utilisation : <?php echo (isset($vcall['lastUse']))? '<a class="carnetDatas">'.$vcall['lastUse'].'</a>' : ''?><br/>
		Dernier utilisateur : <?php echo (isset($vcall['lastUser']))? '<a class="carnetDatas">'.$vcall['lastUser'].'</a>' :''?><br/>
		Dernier client : <?php echo (isset($vcall['lastCustomer']))? '<a class="carnetDatas">'.$vcall['lastCustomer'].'</a>' :''?><br/>
		Dernière destination : <?php echo (isset($vcall['lastCity']))? '<a class="carnetDatas">'.$vcall['lastCity'].'</a>' :''?><br/>
		Derniers valeur connue de l'index kilométrique : <?php echo (isset($vcall['lastIndexKM']))? '<a class="carnetDatas">'.$vcall['lastIndexKM'].'</a>' :''?><br/>
		<?php
			if (isset($vcall['OUT_askTrig']) && $vcall['OUT_askTrig']){
				echo '
				Indiquer votre relevé kilométrique : <input name="newIndexKM" type="text" /><br/>
				Indiquer le numéro de projet pour imputation : <input name="projectNum" type="text" /><br/>
				<button name="valider">Valider</button>
				';
			}
		?>
		<input hidden name="saveLastIndexKM" value="<?php echo $vcall['lastIndexKM']?>"/>
		<input hidden name="saveIdEvent" value="<?php echo $vcall['idEvent']?>"/>
		<input hidden name="saveImmat" value="<?php echo $vcall['IN_immat']?>"/>
		<button>Retour</button>
	</div>
</form>
