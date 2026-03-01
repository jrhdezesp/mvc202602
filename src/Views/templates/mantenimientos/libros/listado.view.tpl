<section class="container">
    <table class="">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Título</th>
                <th>Género</th>
            </tr>
        </thead>
        <tbody>
            {{foreach libros}}
            <tr>
                <td>{{id}}</td>
                <td>{{titulo}}</td>
                <td>{{genero}}</td>
                <td></td>
            </tr>
            {{endfor libros}}
        </tbody>
    </table>
</section>