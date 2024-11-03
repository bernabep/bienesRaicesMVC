<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>
    <?php foreach($errores as $error):   ?>
        <div class="alerta error">
        <?php echo $error;?>
        </div>
    <?php endforeach;?>

    <form method="POST" class="formulario" action="/login">
        <fieldset>
            <legend>Iniciar Sesión</legend>
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" placeholder="Tu Email" />

            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Tu password" />

            <input type="submit" value="Iniciar Sesión" class="boton boton-verde" </fieldset>
    </form>
</main>