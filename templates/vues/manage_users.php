<form name="form_manage_users" id="form_manage_users" action="../templates/index.php" method="post">
	<div id="manage_users" class="ui-widget ui-corner-all ui-widget-content">
		<div id="divSelectUser" class="divcenterManage">
			<select id="cbboxuser" name="cbboxuser" class="cbboxsubmit">
				<option value="">Aucun</option>
				<?php foreach ($list_users as $utilisateur) {
						echo '<option>'.$utilisateur['initiales'].'</option>';
					}
					echo '<option value="" disabled selected>Choisir un utilisateur</option>';
					if(isset($iduser)){
						echo '<option selected>'.$iduser.'</option>';
					}
				?>
			</select><br/>
			<a style="font-size: 12px;color: red">* Champs obligatoires pour la création d'utilisateur</a>
		</div>
		<div class="divcenterManage">
			<label class="ui-widget-header ui-corner-all" for="initials">Initiales : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="name">Nom : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="firstname">Prénom : <a style="color: red">*</a></label>
		</div>
		<div class="divcenterManage">
			<input id="initials" name="initials" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($UserToModify['initiales'])){ echo $UserToModify['initiales'];}?>" />
			<input id="name" name="name" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($UserToModify['nom'])){ echo $UserToModify['nom'];}?>" />
			<input id="firstname" name="firstname" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($UserToModify['prenom'])){ echo $UserToModify['prenom'];}?>" />
		</div>
		<div class="divcenterManage">
			<label class="ui-widget-header ui-corner-all" for="entity">Entité : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all" for="email">Email : </label>
			<label class="ui-widget-header ui-corner-all" for="phone">Téléphone : </label>
		</div>
		<div id="gestEntity" class="divcenterManage">
			<div id="selectEntity">
				<select id="entity" name="entity">
					<?php 
						if(isset($UserToModify['entreprise'])){
							if($UserToModify['entreprise'] == 'clemessy'){
								echo '<option value="'.$UserToModify['entreprise'].'" selected>'.$UserToModify['entreprise'].'</option>
									<option value="hyline">hyline</option>';
							} else if ($UserToModify['entreprise'] == 'hyline') {
								echo '<option value="'.$UserToModify['entreprise'].'" selected>'.$UserToModify['entreprise'].'</option>
									<option value="clemessy">clemessy</option>';
							}
						} else {
							echo '<option value="clemessy">clemessy</option>
								<option value="hyline">hyline</option>';
						}
					?>
				</select>
			</div>
			<div id="mailPhone">
				<input id="email" name="email" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="text" readonly="readonly" value="<?php if(isset($UserToModify['initiales'],$UserToModify['entreprise'])){ echo $UserToModify['initiales'].'@'.$UserToModify['entreprise'].'.fr';} ?>" />
				<input id="phone" name="phone" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($UserToModify['telephone'])){ echo $UserToModify['telephone'];}?>" />
			</div>
		</div>
		<div class="divcenterManage">
			<label class="ui-widget-header ui-corner-all" for="rights">Droits : <a style="color: red">*</a></label>
			<label class="ui-widget-header ui-corner-all"  for="login">Login : </label>
			<label class="ui-widget-header ui-corner-all" for="pwd">Password : </label>
		</div>
		<div id="gestRights" class="divcenterManage">
			<div id="selectrights">
				<select id="rights" name="rights">
					<?php 
						if(isset($UserToModify['droits'])){
							if($UserToModify['droits'] == 'user+'){
								echo '<option selected>'.$UserToModify['droits'].'</option>
									<option value="user">user</option>
									<option value="admin">admin</option>';
							} else if($UserToModify['droits'] == 'user'){
								echo '<option selected>'.$UserToModify['droits'].'</option>
									<option value="user">user+</option>
									<option value="admin">admin</option>';
							} else if($UserToModify['droits'] == 'admin'){
								echo '<option selected>'.$UserToModify['droits'].'</option>
									<option value="user">user+</option>
									<option value="admin">user</option>';
							}
						} else {
							echo '
								<option value="user">user</option>
								<option value="user+">user+</option>
								<option value="admin">admin</option>';
						}
					?>
				</select>
			</div>
			<div id="loginPwd">
				<input id="login" name="login" class="ui-widget ui-state-default ui-corner-all" type="text" value="<?php if(isset($UserToModify['login'])){ echo $UserToModify['login'];} ?>" />
				<input id="pwd" name="pwd" class="ui-widget ui-state-default ui-corner-all" type="password" value="<?php if(isset($UserToModify['pword'])){ echo $UserToModify['pword'];}?>" />
			</div>
		</div>
		<div align="center" style="padding:10px;">
			<input id="iduser" name="iduser" type="hidden" value="<?php echo $iduser ?>"/>
			<input id="BP_NewUser" name="BP_NewUser" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Ajouter"/>
			<input id="BP_ModifyUser" name="BP_ModifyUser" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled" value="Modifier" />
			<input id="BP_DeleteUser" name="BP_DeleteUser" class="ui-widget ui-state-default ui-corner-all ui-state-disabled" type="submit" disabled="disabled"  value="Suprimer"/>
		</div>
	</div>
</form>
