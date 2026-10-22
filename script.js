const menuToggle = document.getElementById("menuToggle");
const nav = document.getElementById("nav");

if (menuToggle && nav) {
    menuToggle.addEventListener("click", () => {
        nav.classList.toggle("show");
    });
}

const registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
        const password = document.getElementById("registroPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Las contraseñas no coinciden.");
            return;
        }

        if (password.length < 6) {
            event.preventDefault();
            alert("La contraseña debe tener mínimo 6 caracteres.");
            return;
        }
    });
}

const contactForm = document.getElementById("contactForm");

if (contactForm) {
    contactForm.addEventListener("submit", function () {
        return true;
    });
}

function calcularFaseLunar() {
    const moonDisplay = document.getElementById("moonDisplay");
    const moonPhase = document.getElementById("moonPhase");
    const moonDate = document.getElementById("moonDate");
    const moonDescription = document.getElementById("moonDescription");
    const moonRecommendation = document.getElementById("moonRecommendation");

    if (
        !moonDisplay ||
        !moonPhase ||
        !moonDate ||
        !moonDescription ||
        !moonRecommendation
    ) {
        return;
    }

    const fechaActual = new Date();

    const fechaReferencia = new Date(
        2000,
        0,
        6,
        18,
        14,
        0
    );

    const cicloLunar = 29.530588853;

    const diferenciaMilisegundos =
        fechaActual.getTime() -
        fechaReferencia.getTime();

    const diasTranscurridos =
        diferenciaMilisegundos /
        (1000 * 60 * 60 * 24);

    let edadLunar =
        diasTranscurridos % cicloLunar;

    if (edadLunar < 0) {
        edadLunar += cicloLunar;
    }

    let fase;
    let descripcion;
    let recomendacion;

    if (edadLunar < 1.84566) {
        fase = "Luna Nueva";
        descripcion =
            "La Luna se encuentra en una etapa en la que su cara iluminada es prácticamente invisible desde la Tierra.";
        recomendacion =
            "Tradicionalmente se utiliza como referencia para preparar el suelo, planificar nuevas siembras y organizar las labores del cultivo.";
    } else if (edadLunar < 7.38265) {
        fase = "Luna Creciente";
        descripcion =
            "La parte iluminada de la Luna comienza a aumentar progresivamente.";
        recomendacion =
            "Tradicionalmente se relaciona con actividades orientadas al crecimiento y desarrollo de plantas y cultivos.";
    } else if (edadLunar < 9.22831) {
        fase = "Cuarto Creciente";
        descripcion =
            "La Luna presenta aproximadamente la mitad de su superficie visible iluminada.";
        recomendacion =
            "Puede utilizarse como referencia tradicional para favorecer labores relacionadas con el desarrollo vegetativo de los cultivos.";
    } else if (edadLunar < 14.76529) {
        fase = "Gibosa Creciente";
        descripcion =
            "La superficie iluminada de la Luna continúa aumentando y se acerca a la Luna Llena.";
        recomendacion =
            "Tradicionalmente se asocia con el crecimiento y desarrollo de los cultivos. Conviene complementar estas decisiones con las condiciones del suelo y el clima.";
    } else if (edadLunar < 16.611) {
        fase = "Luna Llena";
        descripcion =
            "La Luna se encuentra aproximadamente completamente iluminada.";
        recomendacion =
            "Puede aprovecharse para observar el estado general de los cultivos y realizar labores de mantenimiento y cuidado.";
    } else if (edadLunar < 22.148) {
        fase = "Gibosa Menguante";
        descripcion =
            "La parte iluminada de la Luna comienza a disminuir después de la Luna Llena.";
        recomendacion =
            "Tradicionalmente se relaciona con labores de mantenimiento y cuidado del cultivo.";
    } else if (edadLunar < 23.99366) {
        fase = "Cuarto Menguante";
        descripcion =
            "La Luna presenta aproximadamente la mitad de su superficie visible iluminada.";
        recomendacion =
            "Tradicionalmente se asocia con labores como poda, mantenimiento y preparación del terreno.";
    } else {
        fase = "Luna Menguante";
        descripcion =
            "La iluminación visible de la Luna continúa disminuyendo hasta llegar nuevamente a la Luna Nueva.";
        recomendacion =
            "Puede utilizarse como referencia tradicional para labores de mantenimiento, preparación del terreno y planificación del siguiente ciclo.";
    }

    moonDisplay.textContent = fase;
    moonPhase.textContent = fase;
    moonDescription.textContent = descripcion;
    moonRecommendation.textContent = recomendacion;

    const opcionesFecha = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric"
    };

    let fechaFormateada =
        fechaActual.toLocaleDateString(
            "es-CO",
            opcionesFecha
        );

    fechaFormateada =
        fechaFormateada.charAt(0).toUpperCase() +
        fechaFormateada.slice(1);

    moonDate.textContent = fechaFormateada;
}

document.addEventListener(
    "DOMContentLoaded",
    function () {
        calcularFaseLunar();
    }
);

setInterval(
    calcularFaseLunar,
    60 * 60 * 1000
);

if (nav) {
    const enlaces = nav.querySelectorAll("a");

    enlaces.forEach(function (enlace) {
        enlace.addEventListener("click", function () {
            nav.classList.remove("show");
        });
    });
}

document.querySelectorAll('a[href^="#"]').forEach(function (enlace) {
    enlace.addEventListener("click", function (evento) {
        const destino = document.querySelector(
            this.getAttribute("href")
        );

        if (destino) {
            evento.preventDefault();

            destino.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});

const categoryButtons = document.querySelectorAll(".category-btn");
const cropCards = document.querySelectorAll(".crop-card");
const noResults = document.getElementById("noResults");

categoryButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        const category = button.getAttribute("data-category");

        categoryButtons.forEach(function (btn) {
            btn.classList.remove("active");
        });

        button.classList.add("active");

        let visibleCards = 0;

        cropCards.forEach(function (card) {
            const cardCategory =
                card.getAttribute("data-category");

            if (
                category === "todos" ||
                cardCategory === category
            ) {
                card.style.display = "";
                visibleCards++;
            } else {
                card.style.display = "none";
            }
        });

        if (noResults) {
            noResults.style.display =
                visibleCards === 0 ? "block" : "none";
        }
    });
});