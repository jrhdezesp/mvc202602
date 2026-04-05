<section>
	<h1>{{FormTitle}}</h1>
</section>

<section>
	{{with restaurante}}
	<form action="index.php?page=Restaurantes_Restaurante&mode={{~mode}}&id_restaurante={{id_restaurante}}" method="POST">
		<div>
			<label for="id_restaurante_d">Id</label>
			<input readonly disabled type="text" name="id_restaurante_d" id="id_restaurante_d" value="{{id_restaurante}}" />
			<input type="hidden" name="mode" value="{{~mode}}" />
			<input type="hidden" name="id_restaurante" value="{{id_restaurante}}" />
			<input type="hidden" name="restaurante_xss_token" value="{{~restaurante_xss_token}}" />
		</div>

		<div>
			<label for="nombre">Nombre</label>
			<input {{~readonly}} type="text" name="nombre" id="nombre" value="{{nombre}}" />
			{{if nombre_error}}
			<div>{{nombre_error}}</div>
			{{endif nombre_error}}
		</div>

		<div>
			<label for="tipo_cocina">Tipo Cocina</label>
			<input {{~readonly}} type="text" name="tipo_cocina" id="tipo_cocina" value="{{tipo_cocina}}" />
		</div>

		<div>
			<label for="ubicacion">Ubicación</label>
			<input {{~readonly}} type="text" name="ubicacion" id="ubicacion" value="{{ubicacion}}" />
		</div>

		<div>
			<label for="calificacion">Calificación</label>
			<input {{~readonly}} type="number" step="0.01" min="0" max="5" name="calificacion" id="calificacion" value="{{calificacion}}" />
			{{if calificacion_error}}
			<div>{{calificacion_error}}</div>
			{{endif calificacion_error}}
		</div>

		<div>
			<label for="capacidad_comensales">Capacidad Comensales</label>
			<input {{~readonly}} type="number" min="0" name="capacidad_comensales" id="capacidad_comensales" value="{{capacidad_comensales}}" />
			{{if capacidad_comensales_error}}
			<div>{{capacidad_comensales_error}}</div>
			{{endif capacidad_comensales_error}}
		</div>

		{{endwith restaurante}}

		<div>
			{{if showCommitBtn}}
			<button type="submit" name="btnConfirmar">Confirmar</button>
			{{endif showCommitBtn}}
			<button type="button" id="btnCancelar">
				{{if showCommitBtn}}
				Cancelar
				{{endif showCommitBtn}}
				{{ifnot showCommitBtn}}
				Regresar
				{{endifnot showCommitBtn}}
			</button>
		</div>
	</form>
</section>

<script>
	document.addEventListener("DOMContentLoaded", ()=>{
		const btnCancelar = document.getElementById("btnCancelar");
		btnCancelar.addEventListener("click", (e)=>{
			e.preventDefault();
			e.stopPropagation();
			window.location.assign("index.php?page=Restaurantes_Restaurantes");
		});
	});
</script>
