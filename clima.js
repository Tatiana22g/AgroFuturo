const latitud = 6.2442;
const longitud = -75.5812;

function actualizarFechaHora() {
    const fechaElemento = document.getElementById("fecha");
    const horaElemento = document.getElementById("hora");

    if (!fechaElemento || !horaElemento) {
        return;
    }

    const ahora = new Date();

    const opcionesFecha = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric"
    };

    let fecha = ahora.toLocaleDateString("es-CO", opcionesFecha);

    fecha = fecha.charAt(0).toUpperCase() + fecha.slice(1);

    const hora = ahora.toLocaleTimeString("es-CO", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    });

    fechaElemento.textContent = fecha;
    horaElemento.textContent = hora;
}


function obtenerEstadoClima(codigo) {

    const estados = {
        0: "Despejado",
        1: "Principalmente despejado",
        2: "Parcialmente nublado",
        3: "Nublado",
        45: "Niebla",
        48: "Niebla",
        51: "Llovizna ligera",
        53: "Llovizna moderada",
        55: "Llovizna intensa",
        61: "Lluvia ligera",
        63: "Lluvia moderada",
        65: "Lluvia intensa",
        71: "Nieve ligera",
        73: "Nieve moderada",
        75: "Nieve intensa",
        80: "Chubascos ligeros",
        81: "Chubascos moderados",
        82: "Chubascos intensos",
        95: "Tormenta eléctrica",
        96: "Tormenta con granizo",
        99: "Tormenta fuerte con granizo"
    };

    return estados[codigo] || "Condiciones variables";
}


function obtenerRecomendacion(codigo, temperatura, humedad) {

    if (codigo >= 61 && codigo <= 82) {
        return "Se recomienda revisar el drenaje del terreno y evitar labores de siembra durante lluvias fuertes.";
    }

    if (codigo >= 95) {
        return "Se recomienda evitar labores agrícolas al aire libre durante tormentas eléctricas y proteger los cultivos.";
    }

    if (temperatura >= 30) {
        return "Las temperaturas son elevadas. Se recomienda revisar la humedad del suelo y mantener una hidratación adecuada de los cultivos.";
    }

    if (humedad >= 80) {
        return "La humedad es alta. Se recomienda vigilar los cultivos para prevenir problemas relacionados con exceso de humedad.";
    }

    if (temperatura <= 15) {
        return "La temperatura es baja. Se recomienda proteger los cultivos sensibles y revisar las condiciones del suelo.";
    }

    return "Las condiciones actuales son favorables. Se recomienda continuar con el seguimiento del cultivo y del estado del suelo.";
}


async function obtenerClima() {

    const tempElemento = document.getElementById("temp");
    const estadoElemento = document.getElementById("estado");
    const humedadElemento = document.getElementById("humedad");
    const vientoElemento = document.getElementById("viento");
    const minimaElemento = document.getElementById("minima");
    const consejoElemento = document.getElementById("consejo");

    if (
        !tempElemento ||
        !estadoElemento ||
        !humedadElemento ||
        !vientoElemento ||
        !minimaElemento ||
        !consejoElemento
    ) {
        return;
    }

    const url =
        "https://api.open-meteo.com/v1/forecast" +
        "?latitude=" + latitud +
        "&longitude=" + longitud +
        "&current=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code" +
        "&daily=temperature_2m_min" +
        "&forecast_days=1" +
        "&timezone=America%2FBogota";

    try {

        const respuesta = await fetch(url);

        if (!respuesta.ok) {
            throw new Error("No se pudo obtener el clima.");
        }

        const datos = await respuesta.json();

        const temperatura = datos.current.temperature_2m;
        const humedad = datos.current.relative_humidity_2m;
        const viento = datos.current.wind_speed_10m;
        const codigo = datos.current.weather_code;
        const minima = datos.daily.temperature_2m_min[0];

        const estado = obtenerEstadoClima(codigo);

        const recomendacion = obtenerRecomendacion(
            codigo,
            temperatura,
            humedad
        );

        tempElemento.textContent =
            Math.round(temperatura) + "°C";

        estadoElemento.textContent = estado;

        humedadElemento.textContent =
            Math.round(humedad) + "%";

        vientoElemento.textContent =
            Math.round(viento) + " km/h";

        minimaElemento.textContent =
            Math.round(minima) + "°C";

        consejoElemento.textContent =
            recomendacion;

    } catch (error) {

        console.error("Error al obtener el clima:", error);

        tempElemento.textContent = "--°C";
        estadoElemento.textContent = "No disponible";
        humedadElemento.textContent = "--%";
        vientoElemento.textContent = "-- km/h";
        minimaElemento.textContent = "--°C";
        consejoElemento.textContent =
            "No fue posible actualizar la información del clima. Verifica la conexión a Internet.";

    }
}


document.addEventListener("DOMContentLoaded", function () {

    actualizarFechaHora();
    obtenerClima();

    setInterval(actualizarFechaHora, 1000);

    setInterval(obtenerClima, 300000);

});