<nav>
			<ul>
				
		<?php if($_SESSION['rol'] == 1){   ?>
				<li class="principal">

					<a href="lista_usuarios.php">Usuarios</a>
					<ul>
						<li> <a href="registro_usuario.php">Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php">Lista de Usuarios</a></li>
					</ul>
				</li>
			<?php } ?>

				<li class="principal">
					<a href="lista_clientes.php">Clientes</a>
					<ul>
						<li><a href="registro_cliente.php">Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php">Lista de Clientes</a></li>
					</ul>
				</li>
			<?php if($_SESSION['rol'] == 1){   ?>

				<li class="principal">
					<a href="lista_proveedor.php">Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php">Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php">Lista de Proveedores</a></li>
					</ul>
				</li>
			
				<li class="principal">
					<a href="lista_producto.php">Productos</a>
					<ul>
						<li><a href="registro_producto.php">Nuevo Producto</a></li>
						<li><a href="lista_producto.php">Lista de Productos</a></li>
					</ul>
				</li>
			<?php } ?>
				<li class="principal">
					<a href="ventas.php">Venta</a>
					<ul>
						<li><a href="nueva_venta.php">Nueva Venta</a></li>
						<li><a href="ventas.php">Ventas</a></li>
					</ul>
				</li>
				<li><a href="panelcontrol.php">Panel de control</a></li>
			</ul>
</nav>