<nav class="navbar navbar-default ui-widget-header ui-corner-all">
	<div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navmenu" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#">Navigation</a>
		</div>
		<div class="collapse navbar-collapse" id="navmenu">
			<ul class="nav navbar-nav navig-bar">
				<li class="dropdown ui-state-default"><a id="bp_cars" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-car"></i>Véhicules<span class="caret"></span></a>
					<ul class="dropdown-menu ui-state-default">
						<li><a id="planning_vehicules" href="#"><i class="fa fa-calendar"></i>Planning</a></li>
						<li hidden><a id="historique_cars" href="#"><i class="fa fa-history"></i>Historiques</a></li>
					</ul>
				</li>
				<li class="dropdown ui-state-default"><a id="bp_devices" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cogs"></i>Appareils<span class="caret"></span></a>
					<ul class="dropdown-menu ui-state-default">
						<li><a id="planning_devices" href="#"><i class="fa fa-calendar"></i>Planning</a></li>
						<li hidden><a id="historique_devices" hidden href="#"><i class="fa fa-history"></i>Historiques</a></li>
					</ul>
				</li>
				<li class="dropdown ui-state-default"><a id="bp_admin" class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-pencil-square-o"></i>Administration<span class="caret"></span></a>
					<ul class="dropdown-menu ui-state-default">
						<li hidden><a id="gestion_users" href="#"><i class="fa fa-user"></i>Gestion Utilisateurs</a></li>
						<li hidden><a id="gestion_cars" href="#"><i class="fa fa-car"></i>Gestion Vehicules</a></li>
						<li hidden><a id="gestion_devices" href="#"><i class="fa fa-cogs"></i>Gestion Appareils</a></li>
						<li role="separator" class="divider"></li>
						<li hidden><a id="gestion_logs" href="#"><i class="fa fa-file-text"></i>Fichiers Logs</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a class=""><?php echo (isset($_SESSION['user']))? '<span class="fa fa-user"></span> '.$_SESSION['user'] : '' ;?></a></li>
				<li><a id="bp_login" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
			</ul>
		</div>
	</div>
</nav>