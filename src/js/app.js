document.addEventListener("DOMContentLoaded", function () {
  eventListeners();
  darkMode();
});

function darkMode() {
  const prefiereDarkMode = window.matchMedia("(prefers-color-scheme: dark)");
  // console.log(prefiereDarkMode.matches)
  if (prefiereDarkMode.matches) {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }

  prefiereDarkMode.addEventListener("change", function () {
    if (prefiereDarkMode.matches) {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  });
  const darkMode = document.querySelector(".dark-mode-boton");
  darkMode.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");
  });
}

function eventListeners() {
  const mobilMenu = document.querySelector(".mobile-menu");
  mobilMenu.addEventListener("click", navegacionResponsive);

  //Muestra campos condicionales
  const metodoContacto = document.querySelectorAll(
    'input[name="contacto[contacto]"]'
  );
  metodoContacto.forEach((input) =>
    input.addEventListener("click", mostrarMetodosContacto)
  );
}

function navegacionResponsive() {
  const navegacion = document.querySelector(".navegacion");
  /*
    if(navegacion.classList.contains('mostrar')){
        navegacion.classList.remove('mostrar');

    }else{
        navegacion.classList.add('mostrar');
    }
  */
  navegacion.classList.toggle("mostrar");
}

function mostrarMetodosContacto(e) {
  const contactoDiv = document.querySelector("#contacto");
  if (e.target.value === "telefono") {
    contactoDiv.innerHTML = `
      <label for="telefono">Número Teléfono</label>
      <input id="telefono" type="tel" placeholder="Tu Teléfono" name="contacto[telefono]" />    

      <p>Elija la fecha y la hora para la llamada</p>

      <label for="fecha">Fecha</label>
      <input id="fecha" type="date" name="contacto[fecha]"/>

      <label for="hora">Hora</label>
      <input id="hora" type="time" min="09:00" max="18:00" />
    
    `;
  } else {
    contactoDiv.innerHTML = contactoDiv.innerHTML = `
      <label for="email">E-mail</label>
      <input id="email" type="email" placeholder="Tu Email" name="contacto[email]" required/>
    
    
    `;
  }
}
