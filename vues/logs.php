<form id="form_logs" method="post" action="index.php?frame=logs">
	<div id="log_manage" class="ui-widget ui-corner-all ui-state-default container-fluid">
		<div id="selectlog">
			<select class="ui-widget ui-state-default ui-corner-all cbboxsubmit" name="cbboxlogs" id="cbboxlogs">
				<option value="">Aucun</option>
				<?php
					for ($i=2; $i<count($list_logs);++$i){
						echo '<option>'.str_replace('.txt','',$list_logs[$i]).'</option>';
					}
					if(!empty($currentFile)){
						echo '<option selected>'.$currentFile.'</option>';
					}
				?>
			</select>
		</div>
		<button id="on_off_logs" name="on_off_logs" class="ui-widget ui-state-default ui-corner-all"><?php echo (!empty($LockLogs) && $LockLogs == 1)? 'Ecriture logs On ' : 'Ecriture logs Off ';?></button>
		<button  id="purge_log_selected" name="purge_log_selected" class="ui-widget ui-state-default ui-corner-all">Supprimer log en cours</button>
	</div>
	<div id="log_content" class="ui-widget ui-corner-all container-fluid">
		<?php
			if(!empty($_POST['cbboxlogs'])){
				foreach( File('./logs/'.$_POST['cbboxlogs'].'.txt') as $lines => $line){
					echo $line.'<br/>';
				}
			}
		?>
	</div>
</form>