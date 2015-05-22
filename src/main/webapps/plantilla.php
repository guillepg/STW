<!DOCTYPE html>
<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Trabajo STW</title>
		<link rel="stylesheet" type="text/css" href="plantilla.css" media="screen" />
</head>
<body>
	<div id="bizis" class="cont">
		<form method="POST" action="">
		<fieldset>
		<legend>Bizis:</legend>
			Dirección:
			<input type="text" name="direccion"/>
			</br></br>
			<select class="centrar">
				<option value="estacion1">Estacion1</option>
				<option value="">Estacion2</option>
				<option value="">Estacion3</option>
				<option value="">Estacion4</option>
			</select>
			<input id="submit" type="submit" value="Calcula" class="centrar"/>
		</fieldset>
		</form>
	</div>
	<div id="map" class="cont">
		<fieldset>
		<legend>Mapa:</legend>
			Aqui se colocará el mapa
		</fieldset>
	</div>
	<div id="p6" class="cont">
		<fieldset>
		<legend>Previsión meteorológica:</legend>
		<div id="tableTiempo">
		<?php
			//cliente soap que llame a la operacion DescargarInfoTiempo(codigo)
			//y despues llame a GenerarHTML(xml) e imprima el resultado aquii
		
		?>
		</div>		
		<form method="GET" action="plantilla.php">
		
		<?php 
			$fp=fopen("../resources/municipios.txt", "r");
			$linea;
			$municipios=array();
			$codigos=array();
			
			while( ($linea=fgets($fp)) !== false ){
				list($cpro, $mun, $dc, $nombre)=split("[\r\t]+", $linea);

				array_push($municipios, $nombre);
				array_push($codigos, $cpro.$mun);
			}
			fclose($fp);
		?>
		
			<select class="centrar" name="mun">
				<?php 
				foreach($municipios as $res){
					echo '<option value="'.$res.'">'.$res.'</option>';
				}
				?>
			</select>
			<input id="submit" type="submit" value="Obtener información meteorológica" class="centrar"/>
		</form>
		
		<?php
			if(isset($_GET['mun'])){
				$munSel=$_GET['mun'];
				echo $munSel;
				$codigo=obtenerCod($munSel, $municipios, $codigos);
			}
			
			function obtenerCod($string, $muni, $codi) {
				$cont=1;
				foreach($muni as $m){
					if($m==$string){
						echo '</br>'.$codi[$cont];
						return $cont;
					}
					$cont+=1;
				}
				return 0;
			}
			
		?>

		</fieldset>
	</div>
</body>
</html>
