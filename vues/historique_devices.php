<form id="form_historique_devices" name="form_historique_devices" action="../templates/index.php" method="post">
	<div id="vue_historique_devices" class="ui-widget ui-state-default ui-corner-all">
		<div class="row">
			<label class="ui-widget-header ui-corner-all"  for="DateStart"-->Date de Début : </label>
			<label class="ui-widget-header ui-corner-all"  for="DateEnd">Date de Fin : </label>
		</div>
		<div class="row">
			<input id="StartSearchDevices" name="StartSearchDevices" class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" readonly value="<?php if(!empty($_POST['StartSearchDevices'])){ echo $_POST['StartSearchDevices'];}?>"/>
			<input  id="EndSearchDevices" name="EndSearchDevices" class="ui-widget ui-state-default ui-corner-all datetimepicker" type="text" readonly value="<?php if(!empty($_POST['EndSearchDevices'])){ echo $_POST['EndSearchDevices'];}?>"/>
		</div>
		<div class="row">
			<label class="ui-widget-header ui-corner-all"  for="cbboxdevicesearch">Appareil : </label>
			<label class="ui-widget-header ui-corner-all"  for="cbboxusersearchDevices">Utilisateur : </label>
		</div>
		<div class="row">
			<select id="cbboxdevicesearch" name="cbboxdevicesearch">
				<option value="">Aucun</option>
				<?php
					foreach ($list_devices as $devices) {
						echo '<option>'.$devices['modele'].' '.$devices['identifiant'].'</option>';
					}
					if(!empty($_POST['cbboxdevicesearch'])){
						echo '<option selected>'.$_POST['cbboxdevicesearch'].'</option>';
					} else {
						echo '<option value="" disabled selected>Choisir un appareil</option>';
					}
				?>
			</select>
			<select id="cbboxusersearchDevices" name="cbboxusersearchDevices">
				<option value="">Aucun</option>
				<?php 
					foreach ($list_users as $utilisateur) {
						echo '<option>'.$utilisateur['initiales'].'</option>';
					}
					if(!empty($_POST['cbboxusersearchDevices'])){
						echo '<option selected>'.$_POST['cbboxusersearchDevices'].'</option>';
					} else {
						echo '<option value="" disabled selected>Choisir un utilisateur</option>';
					}
				?>
			</select>
		</div>
		<div>
			<button id="BP_SearchDevices" name="BP_SearchDevices" class="ui-corner-all ui-state-default" >Rechercher</button>
			<?php 
				if(!empty($historyDevices) && $historyDevices != 'false'){
					echo'<a id="BP_PrintHistory" name="BP_PrintHistory" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" href="'.$csvEventsDevices.'">Extraire les résultats</a>';
				}
			?>
		</div>
	</div>
	<div id="search_results_devices" class="ui-widget ui-state-default ui-corner-all">
		<h1>Résultats de la recherche :</h1>
		<table id="results_histo_devices" class="tablesorter">
			<thead>
				<tr>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Date de début</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">1/2 Journée Départ</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Date de fin</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">1/2 Journée Retour</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Appareil</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Utilisateur</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Client</th>
					<th class="ui-widget-header ui-corner-all thead header headerSortDown">Ville</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(!empty($historyDevices) && $historyDevices != 'false'){
						foreach ($historyDevices as $SearchEventsDevices){
							echo '
							<tr>
								<td class="ui-corner-all ui-state-default"> '.$SearchEventsDevices['date_start'].'</td>
								<td class="ui-corner-all ui-state-default">'.$SearchEventsDevices['am_pm_start'].'</td>
								<td class="ui-corner-all ui-state-default"> '.$SearchEventsDevices['date_end'].'</td>
								<td class="ui-corner-all ui-state-default">'.$SearchEventsDevices['am_pm_end'].'</td>
								<td class="ui-corner-all ui-state-default">'.$SearchEventsDevices['appareil_modele'].' '.$SearchEventsDevices['appareil_id'].'</td>
								<td class="ui-corner-all ui-state-default"> '.$SearchEventsDevices['utilisateur'].'</td>
								<td class="ui-corner-all ui-state-default"> '.$SearchEventsDevices['client'].'</td>
								<td class="ui-corner-all ui-state-default"> '.$SearchEventsDevices['ville'].'</td>
							</tr>';
						}
					}
				?>
			</tbody>
		</table>
	</div>
</form>
<form method="post" action="../templates/index.php">
	<div  id="retour_historique_devices" class="ui-widget ui-state-default ui-corner-all">
		<button id="BP_Retour" name="frame" class="ui-corner-all ui-state-default" value="planning">Retour</button>
	</div>
</form>