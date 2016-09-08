<div id="calendarCars" class="ui-corner-all ui-widget ui-widget-content">
	<table id="calendarCars_table" style="width:100%;">
		<col style="width: 8%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 6%;"/>
		<col style="width: 8%;"/>
		<thead>
			<!--Entêtes du planning contenant les dates-->
			<tr>
				<th colspan="1" id="weekbeforeCars" name="weekbeforeCars" class="ui-widget-header ui-corner-all"><i class="fa fa-chevron-circle-left fa-2x"></i></th>
				<?php
					for ($thead=0; $thead<7; ++$thead){
						echo '<th id="day_'.$thead.'" class="ui-widget-header ui-corner-all thead" colspan="2">'.$Week_start->format('D').' '.$Week_start->format('d-M-Y').'</th>';
						$Week_start->modify('+1 day');
					}
				?>
				<th colspan="1"id="weekafterCars" name="weekafterCars" class="ui-widget-header ui-corner-all"><i class="fa fa-chevron-circle-right fa-2x"></i></th>
			</tr>
		</thead>
		<tbody>
			<?php
			//Modif Paul
				// Création dynamique du planning
				for ($rowindex=0; $rowindex<count($list_cars);++$rowindex) {
					// Création de la ligne <tr></tr> pour chaque vehicules
					echo '<tr id="row_'.$rowindex.'" class="ui-corner-all ui-state-default">';
					
					// Construction de la case pour chaque véhicule
					echo '<td colspan="1" id="row_'.$rowindex.'" class="ui-widget-header ui-corner-all"><div id="vehicule_'.$rowindex.'">'.$list_cars[$rowindex]['modele'].'<br/>'.$list_cars[$rowindex]['immatriculation'].'</div></td>';
					
					// Construction planning en fonction des évènements dans la bdd
					// 7 jours donc 7 colonnes
					$key_prev=-2;
					$taille=0;
					for ($colindex=0; $colindex<7; ++$colindex){
						$key_am=NULL;
						$key_pm=NULL;
						$key_am_pm=NULL;
						$draggable=0;
						foreach($list_eventsday as $key=>$events)
						{
							if ($events['date_start'] < $DateCompare->format('Y-m-d') && $events['date_end'] > $DateCompare->format('Y-m-d') && $events['vehicule_immat'] == $list_cars[$rowindex]['immatriculation'])
							{//evenement sur plusieur jour en cours
								$key_am_pm=$key;
								$draggable=0;
							}
							if ($events['date_start'] == $DateCompare->format('Y-m-d') && $events['date_end'] > $DateCompare->format('Y-m-d') && $events['vehicule_immat'] == $list_cars[$rowindex]['immatriculation'])
							{//debut d'un evenement sur plusieur jour
								if ($events['am_pm_start']=='pm'){
									$key_pm=$key;
								}else{
									$key_am_pm=$key;
									$draggable=1;
								}
							}	
							if ($events['date_start'] < $DateCompare->format('Y-m-d') && $events['date_end'] == $DateCompare->format('Y-m-d') && $events['vehicule_immat'] == $list_cars[$rowindex]['immatriculation'])
							{//fin d'un evenement sur plusieur jour
								if ($events['am_pm_end']=='am'){
									$key_am=$key;
								}else{
									$key_am_pm=$key;
									$draggable=0;
								}
							}	
							if ($events['date_start'] == $DateCompare->format('Y-m-d') && $events['date_end'] == $DateCompare->format('Y-m-d') && $events['vehicule_immat'] == $list_cars[$rowindex]['immatriculation'])
							{//evenement sur un jour
								if ($events['am_pm_start']=='am' && $events['am_pm_end']=='am'){
									$key_am=$key;
								}elseif($events['am_pm_start']=='pm' && $events['am_pm_end']=='pm'){
									$key_pm=$key;
								}else{
									$key_am_pm=$key;
									$draggable=1;
								}
							}							
						}
						if(isset($key_am_pm) && !isset($key_am) && !isset($key_pm))
						{//journée complète
							if($key_prev==$key_am_pm){
								$taille=$taille+2;
							}else{
								if($key_prev>=0){
									echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
								}
								$taille=2;
							}
							$key_prev=$key_am_pm;
						}elseif(!isset($key_am_pm) && isset($key_am) && !isset($key_pm))
						{//Matinée		
							if($key_prev==$key_am){
								$taille=$taille+1;
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							}else{
								if($key_prev>=0){
									echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
								}
								$taille=1;
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							}
							echo '<td colspan="1"><div id="day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-default ui-create-car case_full_empty"><a class="resa">Après Midi <br/> Libre</a></div></td>';
							$taille=0;
							$key_prev=-1;
						}elseif(!isset($key_am_pm) && !isset($key_am) && isset($key_pm))
						{//Aprés midi
							if($key_prev>0){
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
							}
							echo '<td colspan="1"><div id="day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-default ui-create-car case_full_empty"><a class="resa">Matinée <br/> Libre</a></div></td>';

							$taille=1;
							$key_prev=$key_pm;
						}elseif(!isset($key_am_pm) && isset($key_am) && isset($key_pm))
						{//Matinée + Aprés midi
							if($key_prev==$key_am){
								$taille=$taille+1;
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							}else{
								if($key_prev>=0)
								{
									echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
								}
								$taille=1;
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							}
							$taille=1;
							$key_prev=$key_pm;
						}elseif(!isset($key_am_pm) && !isset($key_am) && !isset($key_pm))
						{//journée libre
							if($key_prev>=0){
								echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
							}
							echo '<td colspan="2"><div id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default ui-create-car ui-droppable case_full_empty"><br/><br/>Libre</div></td>';
							$taille=0;
							$key_prev=-1;
						}else{//conflit
							echo 'conflit de réservation!';
						}
						
						
						/*
						if(isset($key_am_pm) && !isset($key_am) && !isset($key_pm))
						{//journée complète
							echo '<td colspan="2"><div id="idbdd_'.$list_eventsday[$key_am_pm]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm"><br/><a class="resafull">'.$list_eventsday[$key_am_pm]['utilisateur'].'<br/>'.$list_eventsday[$key_am_pm]['client'].' '.$list_eventsday[$key_am_pm]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_am_pm]['commentaire'].'</a></div></td>';
						}elseif(!isset($key_am_pm) && isset($key_am) && !isset($key_pm))
						{//Matinée				
							echo '<td ><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm"><a class="resahalf">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memohalf">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							echo '<td ><div id="day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-default ui-create-car case_full_empty"><a class="resa">Après Midi <br/> Libre</a></div></td>';
						}elseif(!isset($key_am_pm) && !isset($key_am) && isset($key_pm))
						{//Aprés midi
							echo '<td ><div id="day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-default ui-create-car case_full_empty"><a class="resa">Matinée <br/> Libre</a></div></td>';
							echo '<td ><div id="idbdd_'.$list_eventsday[$key_pm]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-highlight  ui-modify-car case_am_pm" ><a class="resahalf">'.$list_eventsday[$key_pm]['utilisateur'].'<br/>'.$list_eventsday[$key_pm]['client'].' '.$list_eventsday[$key_pm]['ville'].'</a><br/><a class="memohalf">'.$list_eventsday[$key_pm]['commentaire'].'</a></div></td>';
						}elseif(!isset($key_am_pm) && isset($key_am) && isset($key_pm))
						{//Matinée + Aprés midi
							echo '<td ><div id="idbdd_'.$list_eventsday[$key_am]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm"><a class="resahalf">'.$list_eventsday[$key_am]['utilisateur'].'<br/>'.$list_eventsday[$key_am]['client'].' '.$list_eventsday[$key_am]['ville'].'</a><br/><a class="memohalf">'.$list_eventsday[$key_am]['commentaire'].'</a></div></td>';
							echo '<td ><div id="idbdd_'.$list_eventsday[$key_pm]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm"><a class="resahalf">'.$list_eventsday[$key_pm]['utilisateur'].'<br/>'.$list_eventsday[$key_pm]['client'].' '.$list_eventsday[$key_pm]['ville'].'</a><br/><a class="memohalf">'.$list_eventsday[$key_pm]['commentaire'].'</a></div></td>';
						}elseif(!isset($key_am_pm) && !isset($key_am) && !isset($key_pm))
						{//journée libre
							echo '<td colspan="2"><div id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default ui-create-car ui-droppable case_full_empty"><br/><br/>Libre</div></td>';
						}else{//conflit
							echo '<td colspan="2">conflit de réservation!</td>';
						}
						*/
						// Ajourt + 1 jour à DateCompare	
						$DateCompare->modify('+1 day');
					}
					if($key_prev>=0){
						echo '<td colspan="'.$taille.'"><div id="idbdd_'.$list_eventsday[$key_prev]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-modify-car case_am_pm ui-draggable"><br/><a class="resafull">'.$list_eventsday[$key_prev]['utilisateur'].'<br/>'.$list_eventsday[$key_prev]['client'].' '.$list_eventsday[$key_prev]['ville'].'</a><br/><a class="memofull">'.$list_eventsday[$key_prev]['commentaire'].'</a></div></td>';
					}
					echo '<td colspan="1" id="row_'.$rowindex.'" class="ui-widget-header ui-corner-all"><div id="vehicule_'.$rowindex.'">'.$list_cars[$rowindex]['modele'].'<br/>'.$list_cars[$rowindex]['immatriculation'].'</div></td>';

					
					// Ré-init DateCompare
					$DateCompare->modify('-7 day');
					echo '</tr>';
				}
			/* avant Modif Paul
				// Création dynamique du planning
				for ($rowindex=0; $rowindex<count($list_cars);++$rowindex) {
					// Création de la ligne <tr></tr> pour chaque vehicules
					$first ='<tr id="row_'.$rowindex.'"class="ui-corner-all ui-state-default">';
					$end ='</tr>';
					// Construction de la case pour chaque véhicule
					$model_immat ='<td id="row_'.$rowindex.'" class="ui-widget-header ui-corner-all"><div id="vehicule_'.$rowindex.'" style="text-align: center;">'.$list_cars[$rowindex]['modele'].'<br/>'.$list_cars[$rowindex]['immatriculation'].'</div></td>';
					
					// Construction planning en fonction des évènements dans la bdd
					// 7 jours donc 7 colonnes
					for ($colindex=0; $colindex<7; ++$colindex){
						// Si il y a des évènements on les parcours
						if (count($list_eventsday)>0){
							// On vide la variable qui mémorise la position de l'évènements dans la list_eventsday
							unset($eventposition);
							// On parcours la list_eventsday
							for ($eventindex=0; $eventindex < count($list_eventsday); ++$eventindex){
								// Si la ligne en fonction de l'index n'est pas vide
								if (isset($list_eventsday[$eventindex]['client'])) {
									// On cherche correspondance entre la date et le vehicule en fonction de l'index
									if ($list_eventsday[$eventindex]['date_start'] <= $DateCompare->format('Y-m-d') && $list_eventsday[$eventindex]['date_end'] >= $DateCompare->format('Y-m-d') && $list_eventsday[$eventindex]['vehicule_immat'] == $list_cars[$rowindex]['immatriculation']){
										// On mémorise la position de l'évènement dans la list_eventsday
										$eventposition = $eventindex;
										//$list_eventsday[$eventindex]['vehicule_modele'] == $list_cars[$rowindex]['modele'] &&
									}
								}
							}
							// Affichage de l'évènement
							if (isset($eventposition)){
								if($list_eventsday[$eventposition]['date_start'] == $list_eventsday[$eventposition]['date_end']){
									if($list_eventsday[$eventposition]['am_pm_start'] == 'am' && $list_eventsday[$eventposition]['am_pm_end'] == 'pm'){
										${'event_am_pm'.$colindex} = '<br/><a style="font-size: 16px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'case_am_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight ui-draggable ui-modify-car" style="text-align: center;height: 100px; line-height: 20px;">'.${'event_am_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default">'.${'case_am_pm'.$colindex}.'</td>';
									} else if($list_eventsday[$eventposition]['am_pm_start'] == 'am' && $list_eventsday[$eventposition]['am_pm_end'] == 'am') {
										${'event_am'.$colindex} = '<a style="font-size: 14px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'event_pm'.$colindex} = '<a style="font-size: 16px;">Après Midi <br/> Libre</a>';
										${'case_am'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 50px;line-height: 15px;">'.${'event_am'.$colindex}.'</div>';
										${'case_pm'.$colindex} = '<div id="day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-default ui-create-car" style="text-align: center;height: 50px; line-height: 20px;">'.${'event_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default" >'.${'case_am'.$colindex}.${'case_pm'.$colindex}.'</td>';
									} else if($list_eventsday[$eventposition]['am_pm_start'] == 'pm' && $list_eventsday[$eventposition]['am_pm_end'] == 'pm') {
										${'event_am'.$colindex} = '<a style="font-size: 16px;">Matinée<br/>Libre</a>';
										${'event_pm'.$colindex} = '<a style="font-size: 16px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'case_am'.$colindex} = '<div id="day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-default ui-create-car" style="text-align: center;height: 50px; line-height: 20px;">'.${'event_am'.$colindex}.'</div>';
										${'case_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 50px; line-height: 15px;">'.${'event_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am'.$colindex}.${'case_pm'.$colindex}.'</td>';
									}
								} else if($list_eventsday[$eventposition]['date_start'] == $DateCompare->format('Y-m-d')){
									if($list_eventsday[$eventposition]['am_pm_start'] == 'am'){
										${'event_am_pm'.$colindex} = '<br/><a style="font-size: 16px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'case_am_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 100px;">'.${'event_am_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am_pm'.$colindex}.'</td>';
									} else if($list_eventsday[$eventposition]['am_pm_start'] == 'pm'){
										${'event_am'.$colindex} = '<a style="font-size: 16px;">Matinée<br/>Libre</a>';
										${'event_pm'.$colindex} = '<a style="font-size: 14px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'case_am'.$colindex} = '<div id="day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-default ui-create-car" style="text-align: center;height: 50px; line-height: 20px;">'.${'event_am'.$colindex}.'</div>';
										${'case_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 50px; line-height: 15px;">'.${'event_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am'.$colindex}.${'case_pm'.$colindex}.'</td>';
									}
								} else if ($list_eventsday[$eventposition]['date_end'] == $DateCompare->format('Y-m-d')){
									if ($list_eventsday[$eventposition]['am_pm_end'] == 'am'){
										${'event_am'.$colindex} = '<a style="font-size: 14px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'event_pm'.$colindex} = '<a style="font-size: 16px;">Après Midi <br/> Libre</a>';
										${'case_am'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 50px; line-height: 15px;">'.${'event_am'.$colindex}.'</div>';
										${'case_pm'.$colindex} = '<div id="day_'.$colindex.'_row_'.$rowindex.'_pm" class="ui-corner-all ui-state-default ui-create-car" style="text-align: center;height: 50px; line-height: 20px;">'.${'event_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am'.$colindex}.${'case_pm'.$colindex}.'</td>';
									} else if($list_eventsday[$eventposition]['am_pm_end'] == 'pm'){
										${'event_am_pm'.$colindex} = '<br/><a style="font-size: 16px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
										${'case_am_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 100px; line-height: 20px;">'.${'event_am_pm'.$colindex}.'</div>';
										${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am_pm'.$colindex}.'</td>';
									}										
								} else if (($list_eventsday[$eventposition]['date_start'] <> $DateCompare->format('Y-m-d')) && $list_eventsday[$eventposition]['date_end'] <> $DateCompare->format('Y-m-d')){
									${'event_am_pm'.$colindex} = '<br/><a style="font-size: 16px;">'.$list_eventsday[$eventposition]['utilisateur'].'<br/>'.$list_eventsday[$eventposition]['client'].' '.$list_eventsday[$eventposition]['ville'].'</a><br/><a style="font-size: 10px;">'.$list_eventsday[$eventposition]['commentaire'].'</a>';
									${'case_am_pm'.$colindex} = '<div id="idbdd_'.$list_eventsday[$eventposition]['id_events'].'_day_'.$colindex.'_row_'.$rowindex.'_am_pm" class="ui-corner-all ui-state-highlight  ui-modify-car" style="text-align: center;height: 100px; line-height: 20px;">'.${'event_am_pm'.$colindex}.'</div>';
									${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default " >'.${'case_am_pm'.$colindex}.'</td>';
								}
							} else {
								${'event_am_pm'.$colindex} = '<center><br/>Libre</center><br/>';
								${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default ui-droppable ui-create-car" style="text-align: center; height: 100px;">'.${'event_am_pm'.$colindex}.'</td>';
							}
						// Pas d'évènements, on marque libre	
						} else {
							${'event_am_pm'.$colindex} = '<center><br/>Libre</center><br/>';
							${'case'.$colindex} = '<td id="day_'.$colindex.'_row_'.$rowindex.'" class="ui-corner-all ui-state-default ui-droppable ui-create-car" style="text-align: center; height: 100px;">'.${'event_am_pm'.$colindex}.'</td>';
						}
						// Ajourt + 1 jour à DateCompare	
						$DateCompare->modify('+1 day');
					}
					// Création des lignes du planning pour chaque véhicules
					echo $first.$model_immat.$case0.$case1.$case2.$case3.$case4.$case5.$case6.$end;
					// Ré-init DateCompare
					$DateCompare->modify('-7 day');
				}
				*/
			?>
		</tbody>
	</table>
</div>