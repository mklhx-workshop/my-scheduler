<form id="form_historique_cars" name="form_historique_cars" action="../templates/index.php" method="post">
	<div id="vue_historique_cars" class="ui-widget ui-state-default ui-corner-all">
		<div class="row">
			<label class="ui-widget-header ui-corner-all" for="StartSearchCars">Date de Début : </label>
			<label class="ui-widget-header ui-corner-all" for="EndSearchCars">Date de Fin : </label>
		</div>
		<div class="row">
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="StartSearchCars" id="StartSearchCars" readonly value="<?php if(!empty($_POST['StartSearchCars'])){ echo $_POST['StartSearchCars'];}?>"/>
			<input class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" name="EndSearchCars" id="EndSearchCars" readonly value="<?php if(!empty($_POST['EndSearchCars'])){ echo $_POST['EndSearchCars'];}?>"/>
		</div>
		<div class="row">
			<label class="ui-widget-header ui-corner-all" for="cbboxcarsearch">Véhicule : </label>
			<label class="ui-widget-header ui-corner-all" for="cbboxusersearchCars">Utilisateur : </label>
		</div>
		<div class="row">
			<select id="cbboxcarsearch" name="cbboxcarsearch">
				<option value="">Aucun</option>
				<?php
					foreach ($list_cars as $voitures) {
						echo '<option>'.$voitures['modele'].' '.$voitures['immatriculation'].'</option>';
					}
					if(!empty($_POST['cbboxcarsearch'])){
						echo '<option selected>'.$_POST['cbboxcarsearch'].'</option>';
					} else {
						echo '<option value="" disabled selected>Choisir un véhicule</option>';
					}
				?>
			</select>
			<select id="cbboxusersearchCars" name="cbboxusersearchCars">
				<option value="">Aucun</option>
				<?php 
					foreach ($list_users as $utilisateur) {
						echo '<option>'.$utilisateur['initiales'].'</option>';
					}
					if(!empty($_POST['cbboxusersearchCars'])){
						echo '<option selected>'.$_POST['cbboxusersearchCars'].'</option>';
					} else {
						echo '<option value="" disabled selected>Choisir un utilisateur</option>';
					}
				?>
			</select>
		</div>
		<div class="row">
			<button id="BP_SearchCars" name="BP_SearchCars" class="ui-corner-all ui-state-default">Rechercher</button>
			<?php 
				if(!empty($historyCars) && $historyCars != 'false'){
					echo'<a id="BP_PrintHistory" name="BP_PrintHistory" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" href="'.$csvEventsCars.'">Extraire les résultats</a>';
				}
			?>
		</div>
	</div>
	<div id="search_results_cars" class="ui-widget ui-state-default ui-corner-all">
		<h1>Résultats de la recherche :</h1>
		<table id="results_histo_cars" class="tablesorter">
			<thead>
				<tr>
					<th class="ui-widget-header ui-corner-all thead header ">Date de début</th>
					<th class="ui-widget-header ui-corner-all thead header ">1/2 Journée Départ</th>
					<th class="ui-widget-header ui-corner-all thead header ">Date de fin</th>
					<th class="ui-widget-header ui-corner-all thead header ">1/2 Journée Retour</th>
					<th class="ui-widget-header ui-corner-all thead header ">Véhicule</th>
					<th class="ui-widget-header ui-corner-all thead header ">Utilisateur</th>
					<th class="ui-widget-header ui-corner-all thead header ">Client</th>
					<th class="ui-widget-header ui-corner-all thead header ">Ville</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(!empty($historyCars) && $historyCars != 'false'){
						foreach ($historyCars as $SearchEventsCars){
							echo '
							<tr>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['date_start'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['am_pm_start'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['date_end'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['am_pm_end'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['vehicule_modele'].' '.$SearchEventsCars['vehicule_immat'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['utilisateur'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['client'].'</td>
								<td class="ui-corner-all ui-state-default ">'.$SearchEventsCars['ville'].'</td>
							</tr>';
						}
					}
				?>
			</tbody>
		</table>
	</div>
</form>
<form method="post" action="../templates/index.php">
	<div id="retour_historique_cars" class="ui-widget ui-state-default ui-corner-all">
		<button id="BP_Retour" name="frame" class="ui-corner-all ui-state-default">Retour</button>
	</div>
</form>