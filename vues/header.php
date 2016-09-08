<div class="container-fluid">
	<div class="id">
		<span><i class="fa fa-calendar-o fa-2x clickable"></i><br/><?php echo date('d/m/Y').'<br/>Semaine n°'.$WeekNum; ?></span>
	</div>
	<div class="titre">
		<h1><?php echo $headerTitle; ?></h1>
		<h2><?php if (isset($msg)){ echo $msg;} ?></h2>
	</div>
	<div class="aide">
		<i class="fa fa-question-circle"></i>
	</div>	
	<div id="dialogWeek" hidden title="Selectionnez la semaine à afficher">
		<input id="spinnerWeek" hidden name="value" value="<?php echo $WeekNum;?>">
		<button id="validSpinnerWeek" class="ui-corner-all ui-state-default" hidden >Valider</button>
	</div>
	<div id="alertmobile">Pour un meilleure expérience, utilisez votre smartphone en paysage.</div>
</div>
