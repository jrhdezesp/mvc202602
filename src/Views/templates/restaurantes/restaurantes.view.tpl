<h1>Restaurantes</h1>

<section>
	<form action="index.php" method="get">
		<input type="hidden" name="page" value="Restaurantes_Restaurantes" />
		<label for="partialNombre">Nombre</label>
		<input type="text" name="partialNombre" id="partialNombre" value="{{partialNombre}}" />
		<button type="submit">Filtrar</button>
	</form>
</section>

<section>
	<table>
		<thead>
			<tr>
				<th>
					{{ifnot OrderByIdRestaurante}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=id_restaurante&orderDescending=0">Id</a>
					{{endifnot OrderByIdRestaurante}}
					{{if OrderIdRestauranteDesc}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=clear&orderDescending=0">Id</a>
					{{endif OrderIdRestauranteDesc}}
					{{if OrderIdRestaurante}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=id_restaurante&orderDescending=1">Id</a>
					{{endif OrderIdRestaurante}}
				</th>
				<th>
					{{ifnot OrderByNombre}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=nombre&orderDescending=0">Nombre</a>
					{{endifnot OrderByNombre}}
					{{if OrderNombreDesc}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=clear&orderDescending=0">Nombre</a>
					{{endif OrderNombreDesc}}
					{{if OrderNombre}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=nombre&orderDescending=1">Nombre</a>
					{{endif OrderNombre}}
				</th>
				<th>Tipo Cocina</th>
				<th>Ubicación</th>
				<th>
					{{ifnot OrderByCalificacion}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=calificacion&orderDescending=0">Calificación</a>
					{{endifnot OrderByCalificacion}}
					{{if OrderCalificacionDesc}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=clear&orderDescending=0">Calificación</a>
					{{endif OrderCalificacionDesc}}
					{{if OrderCalificacion}}
					<a href="index.php?page=Restaurantes_Restaurantes&orderBy=calificacion&orderDescending=1">Calificación</a>
					{{endif OrderCalificacion}}
				</th>
				<th>Capacidad</th>
				<th><a href="index.php?page=Restaurantes_Restaurante&mode=INS">Nuevo</a></th>
			</tr>
		</thead>
		<tbody>
			{{foreach restaurantes}}
			<tr>
				<td>{{id_restaurante}}</td>
				<td>
					<a href="index.php?page=Restaurantes_Restaurante&mode=DSP&id_restaurante={{id_restaurante}}">{{nombre}}</a>
				</td>
				<td>{{tipo_cocina}}</td>
				<td>{{ubicacion}}</td>
				<td>{{calificacion}}</td>
				<td>{{capacidad_comensales}}</td>
				<td>
					<a href="index.php?page=Restaurantes_Restaurante&mode=UPD&id_restaurante={{id_restaurante}}">Editar</a>
					<a href="index.php?page=Restaurantes_Restaurante&mode=DEL&id_restaurante={{id_restaurante}}">Eliminar</a>
				</td>
			</tr>
			{{endfor restaurantes}}
		</tbody>
	</table>
	{{pagination}}
</section>
