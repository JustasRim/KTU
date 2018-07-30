<?php
	// nuskaitome konfigūracijų failą
	include 'config.php';

	// iškviečiame prisijungimo prie duomenų bazės klasę
	include 'utils/mysql.class.php';
	
	// nustatome pasirinktą modulį
	$module = '';
	if(isset($_GET['module'])) {
		$module = mysql::escape($_GET['module']);
	}
	
	// jeigu pasirinktas elementas (sutartis, automobilis ir kt.), nustatome elemento id
	$id = '';
	if(isset($_GET['id'])) {
		$id = mysql::escape($_GET['id']);
	}
	
	// nustatome, ar kuriamas naujas elementas
	$action = '';
	if(isset($_GET['action'])) {
		$action = mysql::escape($_GET['action']);
	}
	
	// jeigu šalinamas elementas, nustatome šalinamo elemento id
	$removeId = 0;
	if(!empty($_GET['remove'])) {
		// paruošiame $_GET masyvo id reikšmę SQL užklausai
		$removeId = mysql::escape($_GET['remove']);
	}
		
	// nustatome elementų sąrašo puslapio numerį
	$pageId = 1;
	if(!empty($_GET['page'])) {
		$pageId = mysql::escape($_GET['page']);
	}
	
	// nustatome, kiek įrašų rodysime elementų sąraše
	define('NUMBER_OF_ROWS_IN_PAGE', 10);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex">
		<title>Sporto įrangos parduotuvės IS</title>
		<link rel="stylesheet" type="text/css" href="scripts/datetimepicker/jquery.datetimepicker.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="style/main.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
		<script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
	</head>
	<body>
		<div id="body">
			<div id="header">
				<h3 id="slogan"><a href="index.php">Sporto įrangos parduotuvės IS</a></h3>
			</div>
			<div id="content">
				<div id="topMenu">
					<ul class="float-left">
						<li><a href="index.php?module=contract" title="Sutartys"<?php if($module == 'contract') { echo 'class="active"'; } ?>>Sutartys</a></li>
						<li><a href="index.php?module=service" title="Paslaugos"<?php if($module == 'service') { echo 'class="active"'; } ?>>Paslaugos</a></li>
						<li><a href="index.php?module=customer" title="Klientai"<?php if($module == 'customer') { echo 'class="active"'; } ?>>Klientai</a></li>
						<li><a href="index.php?module=employee" title="Darbuotojai"<?php if($module == 'employee') { echo 'class="active"'; } ?>>Darbuotojai</a></li>
						<li><a href="index.php?module=car" title="Automobiliai"<?php if($module == 'car') { echo 'class="active"'; } ?>>Automobiliai</a></li>
						<li><a href="index.php?module=Busena" title="Būsenos"<?php if($module == 'Busena') { echo 'class="active"'; } ?>>Būsenos</a></li>
						<li><a href="index.php?module=SportoSaka" title="Sporto šakos"<?php if($module == 'SportoSaka') { echo 'class="active"'; } ?>>Sporto šakos</a></li>
						<li><a href="index.php?module=Spalva" title="Spalvos"<?php if($module == 'Spalva') { echo 'class="active"'; } ?>>Spalvos</a></li>
						<li><a href="index.php?module=SportoSakosIrankioKategorija" title="Įrankių kategorijos"<?php if($module == 'SportoSakosIrankioKategorija') { echo 'class="active"'; } ?>>Įrankių kategorijos</a></li>
						<li><a href="index.php?module=TransportoPriemones" title="Transporto priemonės"<?php if($module == 'TransportoPriemones') { echo 'class="active"'; } ?>>Transporto priemonės</a></li>
						<li><a href="index.php?module=Sezonas" title="Sezonai"<?php if($module == 'Sezonas') { echo 'class="active"'; } ?>>Sezonai</a></li>
						<li><a href="index.php?module=PristatymoBudas" title="Pristatymo būdai"<?php if($module == 'PristatymoBudas') { echo 'class="active"'; } ?>>Pristatymo būdai</a></li>
						<li><a href="index.php?module=PakuotesDydis" title="Pakuočių dydžiai"<?php if($module == 'PakuotesDydis') { echo 'class="active"'; } ?>>Pakuočių dydžiai</a></li>
						<li><a href="index.php?module=Pirkejas" title="Pirkėjai"<?php if($module == 'Pirkejas') { echo 'class="active"'; } ?>>Pirkėjai</a></li>
						<li><a href="index.php?module=MokejimoBudas" title="Mokėjimo būdai"<?php if($module == 'MokejimoBudas') { echo 'class="active"'; } ?>>Mokėjimo būdai</a></li>
						<li><a href="index.php?module=AptarnaujantisAsistentas" title="Aptarnaujantys asistentai"<?php if($module == 'AptarnaujantisAsistentas') { echo 'class="active"'; } ?>>Aptarnaujantys asistentai</a></li>
						<li><a href="index.php?module=Preke" title="Prekės"<?php if($module == 'Preke') { echo 'class="active"'; } ?>>Prekės</a></li>
						<li><a href="index.php?module=Saskaita" title="Sąskaitos"<?php if($module == 'Saskaita') { echo 'class="active"'; } ?>>Sąskaitos</a></li>
						<li><a href="index.php?module=SiuntuPervezimoTarnyba" title="Tarnybos"<?php if($module == 'SiuntuPervezimoTarnyba') { echo 'class="active"'; } ?>>Tarnybos</a></li>
						<li><a href="index.php?module=Siunta" title="Siuntos"<?php if($module == 'Siunta') { echo 'class="active"'; } ?>>Siuntos</a></li>
						<li><a href="index.php?module=Uzsakymas" title="Užsakymai"<?php if($module == 'Uzsakymas') { echo 'class="active"'; } ?>>Užsakymai</a></li>
						<li><a href="index.php?module=UzsakymoPreke" title="Užsakymo prekės"<?php if($module == 'UzsakymoPreke') { echo 'class="active"'; } ?>>Užsakymo prekės</a></li>
						<li><a href="index.php?module=PristatoSiunta" title="Pristato siuntą"<?php if($module == 'PristatoSiunta') { echo 'class="active"'; } ?>>Pristato siuntą</a></li>
					</ul>
					<ul class="float-right">
						<li><a href="index.php?module=report" title="Ataskaitos"<?php if($module == 'report') { echo 'class="active"'; } ?>>Ataskaitos</a></li>
					</ul>
				</div>
				<div id="contentMain">
					<?php
						if(!empty($module)) {
							if(empty($id) && empty($action)) {
								include "controls/{$module}_list.php";
							} else {
								include "controls/{$module}_edit.php";
							}
						}
					?>
					<div class="float-clear"></div>
				</div>
			</div>
			<div id="footer">

			</div>
		</div>
	</body>
</html>
