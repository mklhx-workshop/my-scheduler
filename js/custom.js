$(function(){
	//
	/*Gestion navigation et menus avec l'authetification*/
	//
	if (rights != "user" && rights != "empty"){
		$("#historique_cars,#historique_devices,#gestion_users,#gestion_cars,#gestion_devices").parents().removeAttr("hidden");
		if (rights == "admin") {
			$("#gestion_logs").parents().removeAttr("hidden");
		}
	} else {
		$("#historique_cars,#historique_devices,#gestion_users,#gestion_cars,#gestion_devices,#gestion_logs").attr("hidden");
	}
	$("#planning_vehicules").click(function(){
		$("#planning_vehicules").removeClass( "ui-state-disabled");
		$("#historique_cars").addClass("ui-state-disabled");
		window.open("index.php?frame=planning_vehicules","_self");
	});
	$("#planning_devices").click(function(){
		$("#planning_devices").removeClass( "ui-state-disabled");
		$("#historique_devices").addClass("ui-state-disabled");
		window.open("index.php?frame=planning_devices","_self");
	});
	$("#historique_cars").click(function(){
		$("#historique_cars").removeClass( "ui-state-disabled");
		$("#planning_vehicules").addClass("ui-state-disabled");
		window.open("index.php?frame=historique_cars","_self");
	});
	$("#historique_devices").click(function(){
		$("#historique_devices").removeClass( "ui-state-disabled");
		$("#planning_devices").addClass("ui-state-disabled");
		window.open("index.php?frame=historique_devices","_self");
	});
	$("#gestion_users").click(function(){
		$("#gestion_users").removeClass("ui-state-disabled");
		$("gestion_cars").addClass("ui-state-disabled");
		$("#gestion_devices").addClass("ui-state-disabled");
		$("#gestion_logs").addClass("ui-state-disabled");
		window.open("index.php?frame=manage_users","_self");
	});
	$("#gestion_cars").click(function(){
		$("#gestion_cars").removeClass("ui-state-disabled");
		$("#gestion_users").addClass("ui-state-disabled");
		$("#gestion_devices").addClass("ui-state-disabled");
		$("#gestion_logs").addClass("ui-state-disabled");
		window.open("index.php?frame=manage_cars","_self");
	});
	$("#gestion_devices").click(function(){
		$("#gestion_devices").removeClass("ui-state-disabled");
		$("#gestion_cars").addClass("ui-state-disabled");
		$("#gestion_users").addClass("ui-state-disabled");
		$("#gestion_logs").addClass("ui-state-disabled");
		window.open("index.php?frame=manage_devices","_self");
	});
	$("#gestion_logs").click(function(){
		$("#gestion_logs").removeClass("ui-state-disabled");
		$("#gestion_cars").addClass("ui-state-disabled");
		$("#gestion_users").addClass("ui-state-disabled");
		$("#gestion_devices").addClass("ui-state-disabled");
		window.open("index.php?frame=logs","_self");
	});
	$(document).on("click","#bp_login",function(){
		window.open("index.php?frame=connection","_self");
	});
	$(document).on("click","#bp_logout",function(){
		window.open("index.php?log=out","_self");
	});
	//
	/*Etat des dropdown en fonction de la connexion utilisateurs*/
	//
	if (connectOk == true) {
		$("#bp_admin").removeClass("disabled");
		$( "#bp_login" ).replaceWith( '<a id="bp_logout" href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a>' );
	} else {
		$( "#bp_logout" ).replaceWith( '<a id="bp_login" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a>');	
	}
	if (connectOk == "false"){
		$("#bp_admin").addClass("disabled");
		$(document).on("click","#bp_admin",function(){
			window.open("index.php?frame=connection","_self");
		});
	}
	// 
	/*Déclarations jquery ui*/ 
	//
	/* Les inputs  datetimepickers sont déclarés comme tels en jquery*/
	$(".datetimepicker").datepicker({
		inline: true,
		autoSize: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
		monthNames: [ "Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre"],
	});
	//
	/* Les selects sont déclarés comme tel jquery*/
	//
	$("select").selectmenu({
		
	});
	/*Autocomplete sur les clients et villes*/
	$(".autocompleteCustomers").autocomplete({
			source: listCustomers
	});
	$(".autocompleteCities").autocomplete({
			source: listCities
	});
	//
	/*Combobox auto validation pour : le choix des véhicules, des appareils, des utilisateurs et des logs*/
	//
	$(".cbboxsubmit").selectmenu({
		change: function( event, ui ) {
			$("form").submit();
		 }
	});
	//
	/*Déclaration des Table sorter*/
	//
	$(".tablesorter").tablesorter( {sortList: [[0,0], [1,0]]} ); 
	//
	/*Gestion des véhicules*/
	//
	if ($("#cbboxcar").val() !== null ){
		$("#immatriculation").prop("readonly", true);
		$("#immatriculation").addClass("ui-state-disabled");
		$("#BP_ModifyCar").removeAttr("disabled").removeClass( "ui-state-disabled" );
		$("#BP_DeleteCar").removeAttr("disabled").removeClass( "ui-state-disabled" );
	}else {
		$("#immatriculation").prop("readonly", false);
		$("#immatriculation").removeClass("ui-state-disabled");
		$("#marque,#modele,#immatriculation").change(function() {
			if ($("#marque").val() !== "" && $("#modele").val() !== "" && $("#immatriculation").val() !== ""){
				$("#BP_NewCar").removeAttr("disabled").removeClass("ui-state-disabled");
			}else {
				$("#BP_NewCar").attr("disabled", "disabled").addClass("ui-state-disabled");
			}
		});
	}
	//
	/*Gestion des appareils*/
	//
	if ($("#cbboxdevice").val() !== null ){
		$("#identifiant").prop("readonly", true);
		$("#identifiant").addClass("ui-state-disabled");
		$("#BP_ModifyDevice").removeAttr("disabled").removeClass( "ui-state-disabled" );
		$("#BP_DeleteDevice").removeAttr("disabled").removeClass( "ui-state-disabled" );
	}else {
		$("#identifiant").prop("readonly", false);
		$("#identifiant").removeClass("ui-state-disabled");
		$("#marque,#modele,#identifiant").change(function() {
			if ($("#marque").val() !== "" && $("#modele").val() !== "" && $("#identifiant").val() !== ""){
				$("#BP_NewDevice").removeAttr("disabled").removeClass("ui-state-disabled");
			}else {
				$("#BP_NewDevice").attr("disabled", "disabled").addClass("ui-state-disabled");
			}
		});
	}
	//
	/*Gestion des utilisateurs*/
	//
	if ($("#cbboxuser").val() !== null ){
		$("#initials").prop("readonly", true);
		$("#initials").addClass("ui-state-disabled");
		$("#BP_ModifyUser").removeAttr("disabled").removeClass( "ui-state-disabled" );
		$("#BP_DeleteUser").removeAttr("disabled").removeClass( "ui-state-disabled" );
	}else {
		$("#initials").prop("readonly", false);
		$("#initials").removeClass("ui-state-disabled");
		$("#initials,#name,#firstname").change(function() {
			if ($("#initials").val() !== "" && $("#name").val() !== "" && $("#firstname").val() !== ""){
				$("#BP_NewUser").removeAttr("disabled").removeClass("ui-state-disabled");
			}else {
				$("#BP_NewUser").attr("disabled", "disabled").addClass("ui-state-disabled");
			}
		});
	}
	if ($("#cbboxuser").val() !== null && $("#login").val() !== ""){
		$("#login").prop("readonly", true);
		$("#login").addClass("ui-state-disabled");
	}
	//
	/* En fonction du terminal nous écoutons pas le même event pour appel réservation*/
	//
	/*Si terminal du type PC on écoute le double clique souris*/
	if (terminal == "pc" || terminal == "PC" || terminal == "Pc") {
		//
		/*Appel page création et modification réservation appareils*/
		//
		$(document).on("dblclick",".ui-create-device",function(){
			var idarray = ($(this).attr("id")).split("_");
			var day = $("#"+(idarray[0]+"_"+idarray[1])).text();
			var device = $("#"+(idarray[2].replace("row","device")+"_"+idarray[3])).html().replace("<br>"," ");
			if(idarray.length<5){
				window.open("index.php?frame=create_event_device&day="+day+"&device="+device,"_self");
			} else {
				window.open("index.php?frame=create_event_device&day="+day+"&device="+device+"&halfdayDevices="+idarray[4],"_self");
			}
		});
		$(document).on("dblclick",".ui-modify-device",function(){
			var identity = ($(this).attr("id"));
			var idarray = identity.split("_");
			var id = idarray[1];
			window.open("index.php?frame=modify_event_device&idsqldevice="+id,"_self");
		});
		//
		/*Appel page création et modification réservation véhicule*/
		//
		$(document).on("dblclick",".ui-create-car",function(){
			var idarray = ($(this).attr("id")).split("_");
			var day = $("#"+(idarray[0]+"_"+idarray[1])).text();
			var car = $("#"+(idarray[2].replace("row","vehicule")+"_"+idarray[3])).html().replace("<br>"," ");
			if(idarray.length<5){
				window.open("index.php?frame=create_event_car&day="+day+"&car="+car,"_self");
			} else {
				window.open("index.php?frame=create_event_car&day="+day+"&car="+car+"&halfdayCars="+idarray[4],"_self");
			}
		});
		$(document).on("dblclick",".ui-modify-car",function(){
			var identity = ($(this).attr("id"));
			var idarray = identity.split("_");
			var id = idarray[1];
			window.open("index.php?frame=modify_event_car&idsqlcar="+id,"_self");
		});
	/*Si terminal du type Mobile on écoute le simple clique*/
	} else if (terminal == "mobile" || terminal == "MOBILE" || terminal == "Mobile") {
		//
		/*Appel page création et modification réservation appareils*/
		//
		$(document).on("click",".ui-create-device",function(){
			var idarray = ($(this).attr("id")).split("_");
			var day = $("#"+(idarray[0]+"_"+idarray[1])).text();
			var device = $("#"+(idarray[2].replace("row","device")+"_"+idarray[3])).html().replace("<br>"," ");
			if(idarray.length<5){
				window.open("index.php?frame=create_event_device&day="+day+"&device="+device,"_self");
			} else {
				window.open("index.php?frame=create_event_device&day="+day+"&device="+device+"&halfdayDevices="+idarray[4],"_self");
			}
		});
		$(document).on("click",".ui-modify-device",function(){
			var identity = ($(this).attr("id"));
			var idarray = identity.split("_");
			var id = idarray[1];
			window.open("index.php?frame=modify_event_device&idsqldevice="+id,"_self");
		});
		//
		/*Appel page création et modification réservation véhicule*/
		//
		$(document).on("click",".ui-create-car",function(){
			var idarray = ($(this).attr("id")).split("_");
			var day = $("#"+(idarray[0]+"_"+idarray[1])).text();
			var car = $("#"+(idarray[2].replace("row","vehicule")+"_"+idarray[3])).html().replace("<br>"," ");
			if(idarray.length<5){
				window.open("index.php?frame=create_event_car&day="+day+"&car="+car,"_self");
			} else {
				window.open("index.php?frame=create_event_car&day="+day+"&car="+car+"&halfdayCars="+idarray[4],"_self");
			}
		});
		$(document).on("click",".ui-modify-car",function(){
			var identity = ($(this).attr("id"));
			var idarray = identity.split("_");
			var id = idarray[1];
			window.open("index.php?frame=modify_event_car&idsqlcar="+id,"_self");
		});
		
	}
	$(document).on("click","#weekbeforeDevices",function(){
		window.open("index.php?frame=planning_devices&sem="+sembefore,"_self");
	});
	$(document).on("click","#weekafterDevices",function(){
		window.open("index.php?frame=planning_devices&sem="+semnext,"_self");
	});
	$(document).on("click","#weekbeforeCars",function(){
		window.open("index.php?frame=planning_vehicules&sem="+sembefore,"_self");
	});
	$(document).on("click","#weekafterCars", function(){
		window.open("index.php?frame=planning_vehicules&sem="+semnext,"_self");
	});
	//
	/*Fonction Ajax pour mise à jour auto des données dans le planning*/
	//
	
	if (vueEnCours == "planning_vehicules.php" ){
		setInterval(function reloadPlanning () {
			$("#calendarCars").load("index.php?frame=planning_vehicules&sem="+semactu+" #calendarCars_table");
		},20000);
	} else if (vueEnCours == "planning_devices.php" ){
		setInterval(function reloadPlanning () {
			$("#calendarDevices").load("index.php?frame=planning_devices&sem="+semactu+" #calendarDevices");
		},20000);
	}
	//
	/*Choix de la semaine sur icone class fa-calendar-o*/
	//
	$("#spinnerWeek").spinner();
	$(document).on("click",".fa-calendar-o", function(){
		$("#dialogWeek,#spinnerWeek,#validSpinnerWeek").removeAttr("hidden");
		$("#dialogWeek").dialog({
			modal: true,
			width: 380
		});
	});
	$(document).on("click","#validSpinnerWeek",function(){
		var spinnerWeek = $( "#spinnerWeek" ).spinner( "value" );
		$("#dialogWeek").dialog("close");
		if (vueEnCours == "planning_vehicules.php" ){
			window.open("index.php?frame=planning_vehicules&sem="+spinnerWeek,"_self");
		} else if (vueEnCours == "planning_devices.php" ){
			window.open("index.php?frame=planning_devices&sem="+spinnerWeek,"_self");
		}
	});
	//
	/*Gestion draggable et droppable*/
	//
	var idDrag;
	var dragArray;
	if (vueEnCours == "planning_vehicules.php" || vueEnCours == "planning_vehicules2.php"){
		$(document).on("mousemove", ".ui-draggable", function(){
			$(this).draggable({
				snap : '.ui-droppable',
				snapMode: "inner",
				snapTolerance: 30,
				containment:'#calendarCars_table',
				revert : "invalid",
				start: function(event, ui){
					dragArray = ($(this).attr("id")).split("_");
					idDrag=dragArray[1];
				}
			});
			$(".ui-droppable").droppable({
				drop: function( event, ui ) {
					$(this).addClass("ui-state-highlight");
					var dropArray = ($(this).attr("id")).split("_");
					var dropDestDate=$("#day_"+dropArray[1]).text();
					var dropDestLigne= dropArray[3];
					if(confirm("Validez-vous la modification?") == true){
						var trigger = true;
						window.open("index.php?frame=planning_vehicules&sem="+semactu+"&triggerDragDropCar="+trigger+"&dateDropEvent="+dropDestDate+"&ligneDropEvent="+dropDestLigne+"&idDragEvent="+idDrag,"_self");
					}else{
						$("#calendarCars").load("index.php?frame=planning_vehicules&sem="+semactu+" #calendarCars_table");
					}
				}
			});
		});
	} else if (vueEnCours == "planning_devices.php" ){
		$(document).on("mousemove", ".ui-draggable", function(){
			$(this).draggable({
				snap : '.ui-droppable',
				snapMode: "inner",
				snapTolerance: 30,
				containment:'#calendarDevices_table',
				revert : "invalid",
				start: function(event, ui){
					dragArray = ($(this).attr("id")).split("_");
					idDrag=dragArray[1];
				}
			});
			$(".ui-droppable").droppable({
				drop: function( event, ui ) {
					$(this).addClass("ui-state-highlight");
					var dropArray = ($(this).attr("id")).split("_");
					var dropDestDate=$("#day_"+dropArray[1]).text();
					var dropDestLigne= dropArray[3];
					if(confirm("Validez-vous la modification?") == true){
						var trigger = true;
						window.open("index.php?frame=planning_devices&sem="+semactu+"&triggerDragDropDevices="+trigger+"&dateDropEvent="+dropDestDate+"&ligneDropEvent="+dropDestLigne+"&idDragEvent="+idDrag,"_self");
					}else{
						$("#calendarDevices").load("index.php?frame=planning_devices&sem="+semactu+" #calendarDevices_table");
					}
				}
			});
		});
	}
	// Aide contextuelle
	$(document).on("click",".aide",function(){
		window.open("doc/documentation.html","_blank");
	});
});