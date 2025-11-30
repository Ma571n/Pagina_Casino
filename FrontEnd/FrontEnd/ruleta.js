// Orden real de los números en una ruleta americana
const NUMEROS_RULETA = [
    "0", "28", "9", "26", "30", "11", "7", "20", "32",
    "17", "5", "22", "34", "15", "3", "24", "36", "13",
    "1", "00", "27", "10", "25", "29", "12", "8", "19",
    "31", "18", "6", "21", "33", "16", "4", "23", "35",
    "14", "2"
  ];
  
  // Colores de la ruleta americana
  const COLORES = {
    verde: ["0", "00"],
    rojo: [
      "1","3","5","7","9","12","14","16","18",
      "19","21","23","25","27","30","32","34","36"
    ],
    negro: [
      "2","4","6","8","10","11","13","15","17",
      "20","22","24","26","28","29","31","33","35"
    ]
  };
  
  // Devuelve "rojo", "negro" o "verde" según el número
  function obtenerColor(num) {
    if (COLORES.verde.includes(num)) return "verde";
    if (COLORES.rojo.includes(num)) return "rojo";
    if (COLORES.negro.includes(num)) return "negro";
    return "desconocido";
  }
  
  let ruletaGirando = false;
  const TIEMPO_GIRO_MS = 4000;
  
  // Ángulo acumulado para que siempre gire hacia el mismo lado
  let anguloActual = 0;
  
  function girarRuleta() {
    if (ruletaGirando) return;
    ruletaGirando = true;
  
    const ruletaImg = document.getElementById("ruleta-img");
    const resultadoChip = document.getElementById("resultado-chip");
  
    if (!ruletaImg || !resultadoChip) {
      console.warn("No se encontró la imagen de la ruleta o el contenedor del resultado.");
      ruletaGirando = false;
      return;
    }
  
    // Vueltas completas (entre 3 y 5)
    const vueltas = Math.floor(Math.random() * 3) + 3;
  
    // Ángulo extra aleatorio (0–360)
    const gradosExtra = Math.floor(Math.random() * 360);
  
    // Sumamos al ángulo actual → siempre gira hacia el mismo lado
    anguloActual += vueltas * 360 + gradosExtra;
  
    // Aplicar la rotación
    ruletaImg.style.transform = `rotate(${anguloActual}deg)`;
  
    // Elegir número aleatorio de la ruleta americana
    const index = Math.floor(Math.random() * NUMEROS_RULETA.length);
    const numeroGanador = NUMEROS_RULETA[index];
  
    // Determinar color del número
    const colorGanador = obtenerColor(numeroGanador);
  
    // Esperar a que termine la animación para mostrar el resultado
    setTimeout(() => {
      let colorClase = "";
      if (colorGanador === "rojo") colorClase = "red";
      else if (colorGanador === "negro") colorClase = "black";
      else colorClase = "green"; // 0 y 00
  
      // Mostrar el chip del número ganador
      resultadoChip.innerHTML = `
        <span class="chip ${colorClase} chip-big">
          ${numeroGanador}
        </span>
      `;
  
      ruletaGirando = false;
    }, TIEMPO_GIRO_MS);
  }
  
  // Conectar botón al cargar el DOM
  document.addEventListener("DOMContentLoaded", () => {
    const botonGirar = document.getElementById("btn-girar");
    if (botonGirar) {
      botonGirar.addEventListener("click", girarRuleta);
    }
  });
  