<?php
session_start();
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Sisteme Ventas</title>
</head>
<body>
	<?php 
		include "includes/header.php";
		include "../conexion.php";

			//Datos empresa
		$RIF           = '';
		$nombreEmpresa = '';
		$razonSocial   = '';
		$telEmpresa    = '';
		$emailEmpresa  = '';
		$dirEmpresa    = '';
		$iva           = '';

		$query_empresa = mysqli_query($conection,"SELECT * FROM configuracion");
		$row_empresa   = mysqli_num_rows($query_empresa);
		if($row_empresa > 0){
			while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)){
				$RIF = $arrInfoEmpresa['RIF'];
				$nombreEmpresa = $arrInfoEmpresa['nombre'];
				$razonSocial = $arrInfoEmpresa['razon_social'];
				$telEmpresa = $arrInfoEmpresa['telefono'];
				$emailEmpresa = $arrInfoEmpresa['email'];
				$dirEmpresa = $arrInfoEmpresa['direccion'];
				$iva = $arrInfoEmpresa['iva'];
			}
		}

		$query_dash = mysqli_query($conection,"CALL dataDashboard();");
		$result_dash = mysqli_num_rows($query_dash);
		if($result_dash>0){
			$data_dash  = mysqli_fetch_assoc($query_dash);
		}
	?>
	<section id="container">
    	<div class="divContainer">
        <div>
            <h1 class="titlePanelControl">Panel de control</h1>
        </div>
		<div class="dashboard">
			<?php
  				if($_SESSION['rol'] == 1){
			?>
    			
			<a href="lista_usuarios.php">
				<p>
					<strong>Usuarios</strong><br>
					<span><?php echo $data_dash['usuarios'];	 ?></span>
				</p>
			</a>
			<?php } ?>
			<a href="lista_clientes.php">
				<p>
					<strong>Clientes</strong><br>
					<span><?php echo $data_dash['clientes'];	 ?></span>
				</p>
			</a>
			<?php
  				if($_SESSION['rol'] == 1){
			?>
			<a href="lista_proveedor.php">
				<p>
					<strong>Proveedores</strong><br>
					<span><?php echo $data_dash['proveedores'];	 ?></span>
				</p>
			</a>
			<?php } ?>
			<a href="lista_producto.php">
				<p>
					<strong>Productos</strong><br>
					<span><?php echo $data_dash['productos'];	 ?></span>
				</p>
			</a>
			<a href="ventas.php">
				<p>
					<strong>Ventas</strong><br>
					<span><?php echo $data_dash['ventas'];	 ?></span>
				</p>
			</a>
		</div>
    
    	</div>
		<div class="divInfoSistema">
			<div>
    	        <h1 class="titlePanelControl">Configuracion</h1>
    	    </div>
			<div class="containerPerfil">
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="img/user_icon.png">
					</div>
					<div class="divDataUser">
					
						<h4>Información Personal</h4>
						<div>
							<label>Nombre:</label> <span><?= $_SESSION['nombre'];?></span>
						</div>
						<div>
							<label>Correo:</label> <span><?= $_SESSION['email'];?></span>
						</div>
						
						<h4>Datos Usuario</h4>
						<div>
							<label>Rol:</label> <span><?= $_SESSION['rol_name'];?></span>
						</div>
						<div>
							<label>Usuario:</label> <span><?= $_SESSION['user'];?></span>
						</div>

						<h4>Cambiar contraseña</h4>
						<form action="" method="post" name="formChangePass" id="formChangePass">
							<div>
								<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar contraseña" required>
							</div>
							<div class="alertChangePass" style="display: none;">

							</div>
							<div>
								<button type="submit" class="btn_save btnChangePass">Cambiar contraseña</button>
							</div>
						</form>
					</div>
				</div>
				<?php
  				if($_SESSION['rol'] == 1) { ?>
				<div class="containerDataEmpresa">
					<div class="logoEmpresa">
						<img src="img/logo_empresa2.jpg">
					</div>
					<h4>Datos de la empresa</h4>
					<form action="" method="post" name="formEmpresa" id="formEmpresa">
						<input type="hidden" name="action" value="updateDataEmpresa">
						<div>
							<label>RIF:</label><input type="text" name="txtRIF" id="txtRIF" placeholder="RIF de la empresa" value="<?= $RIF; ?>" required>
						</div>
						<div>
							<label>Nombre:</label><input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="<?= $nombreEmpresa; ?>" required>
						</div>
						<div>
							<label>Razon Social:</label><input type="text" name="txtRSocial" id="txtRSocial" placeholder="Razon Social" value="<?= $razonSocial; ?>">
						</div>
						<div>
							<label>Telefono:</label><input type="text" name="txtTelEmpresa" id="txtTelEmpresal" placeholder="Teléfono de la empresa " value="<?= $telEmpresa; ?>">
						</div>
						<div>
							<label>Correo electrónico</label><input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo de la empresa" value="<?= $emailEmpresa; ?>" required>
						</div>
						<div>
							<label>Dirección</label><input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la empresa" value="<?= $dirEmpresa; ?>" required>
						</div>
						<div>
							<label>IVA (%):</label><input type="text" name="txtIva" id="txtIva" placeholder="Impuesto al valor agregado (IVA)" value="<?= $iva; ?>" required>
						</div>
						<div class="alertFormEmpresa" style="display: none;"></div>
						<div>
							<button type="submit" class="btn_save btnChangePass">Guardar datos</button>
						</div>
					</form>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>