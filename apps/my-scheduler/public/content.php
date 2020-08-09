<!DOCTYPE html>
<html lang="fr-FR">
	<head>
		<title>Planning et Réservation Clemessy Orléans</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=yes"/>
		<meta name="description" content="Outil de plannification des véhicules et des appareils communs de l'agence Clemessy Orléans"/>
		<link rel="icon" type="image/png" href="ico/cy(<?php echo $numFavicon ?>).ico" />
		<!--[if IE]>
        <link rel="shortcut icon" type="image/x-icon" href="ico/cy(14).ico"/><![endif]-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<link href="jquery/jquery-ui.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="jquery/jquery-ui.structure.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="jquery/jquery-ui.theme.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../assets/css/custom.css" rel="stylesheet" media="all" type="text/css">
		<!--Jquery-->
		<script type="text/javascript" src="jquery/external/jquery/jquery.js"></script>
		<!--Jquery-UI-->
		<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
		<!--Jquery Table sorter-->
		<script type="text/javascript" src="jquery/jquery.tablesorter/jquery.tablesorter.min.js"></script>
		<!--Jquery-mobile-->
		<!--script type="text/javascript" src="/jquerymobile/jquery.mobile-1.4.5.min.js"></script>
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1"/>
		<link href="../jquery/jquerymobile/jquery.mobile-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.theme-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.structure-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.inline-svg-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.inline-png-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.icons-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/>
		<link href="../jquery/jquerymobile/jquery.mobile.external-png-1.4.5.min.css" rel="stylesheet" media="screen" type="text/css" title="Design"/-->
		<!--Bootstrap-->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!--script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" integrity="sha256-xI/qyl9vpwWFOXz7+x/9WkG5j/SVnSw21viy8fWwbeE=" crossorigin="anonymous"></script-->
		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->
		<script type="text/javascript" src="../assets/js/custom.js"></script>
		<script>
			var connectOk = <?php echo (!empty($_SESSION['connected']) && $_SESSION['connected'] == True)? $_SESSION['connected'] : '"false"';?>;
			var rights = "<?php echo (!empty($_SESSION['rights']))? $_SESSION['rights'] : 'empty';?>";
			var semactu = <?php echo $WeekNum*1;?>;
			var sembefore = <?php echo $WeekNum-1;?>;
			var semnext = <?php echo $WeekNum+1;?>;
			var vueEnCours = "<?php echo (!empty($vue))? $vue : '';?>";
			var listCustomers = <?php echo (!empty ($list_customers))? JSON_Encode($list_customers) : '""'; ?>;
			var listCities = <?php echo (!empty($list_cities))? JSON_Encode($list_cities) : '""'; ?>;
			var terminal = "<?php echo (!empty($terminal))? $terminal : '';?>";
		</script>
		<style type="text/css"></style>		
	</head>
	<body>
		<div class="container-fluid">
			<?php 
				//if(!empty($terminal) && $terminal == 'pc'){
					echo '<header id="header" class="ui-corner-all ui-widget ui-widget-content">';
						include('vues/header.php');
					echo '</header>';
				//}
				//if(!empty($terminal) && $terminal == 'pc'){
					include('vues/menus.php');
					include('vues/'.$vue);
				//} else if(!empty($terminal) && $terminal == 'mobile'){
				//	include('./mobiledesign/'.$vue);
				//}
				//if(!empty($terminal) && $terminal == 'pc'){
					echo '<footer id="footer" class="ui-corner-all ui-widget ui-widget-content">';
						include('vues/footer.php');
					echo '</footer>';
				//}
			?>
		</div>
	</body>
</html>