<h1>Formulario de Libros</h1>
<section class="grid row">
    <form class="depth-0offset-4 col-6" action='index.php?page=Mantenimientos-Libros-Formulario' method="POST">
        <div class="'row">
            <div class="col-4">
                <label for="id">Codigo</label>
            </div>
            <div class="col-8">
                <input type="text" value="{{id}}" disabled class="col-12"/>
            </div>
            <div class>
                <label for="titulo">Titulo</label>
            </div>
            <div>
                <input type="text" name="titulo" value="{{titulo}}" class="col-12"/>
            </div>
            <div>
                <label for="autor">Autor</label>
            </div>
            <div>
                <input type="text" name="autor" value="{{autor}}" class="col-12"/>
            </div>
            <div>
                <label for="resumen">Resumen</label>
            </div>
            <div>
                <textarea name="resumen" class="col-12">{{resumen}}</textarea>
            </div>
        </div>
    </form>
</section>