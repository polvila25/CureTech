# Hackathon Boehringer – CuraTech - Plataforma de Gestión Proactiva de Pacientes

Este repositorio contiene la solución desarrollada por el equipo 17 **PYMON** formado por Pol Vila Simón y Yassin Nakmouche Sahli para el **Reto 3: Mejorando la calidad de la atención primaria**, enfocada en permitir a los médicos de atención primaria, como Diego, gestionar de manera proactiva sus pacientes.

---

## 🎯 Objetivo del proyecto

Diego, médico de Atención Primaria en Barcelona, tiene asignados 1.500 pacientes y puede atender una media de 30 diarios, dedicando entre 10-15 minutos a cada uno. Su gestión tradicional es **reactiva**, atendiendo solo casos urgentes o visitas periódicas, lo que dificulta la detección temprana de enfermedades.

Nuestro proyecto busca:

- Proporcionar un **dashboard completo** con la visión de todos los pacientes asignados.
- Priorizar visitas y recomendaciones de forma **proactiva**, basada en el estado y riesgo de cada paciente.
- Permitir al médico **tomar decisiones informadas** para optimizar su tiempo y detectar enfermedades antes de que se vuelvan críticas.

---

## 💡 Concepto de la solución

- **Dashboard de estados**: Visualiza de forma inmediata el número de pacientes en cada estado (`SANO`, `BAJO`, `MEDIO`, `CRÍTICO`) mediante un gráfico circular.
- **Lista de pacientes propuestos**: Muestra hasta 30 pacientes sugeridos para revisión diaria, incluyendo un mix de pacientes críticos, medios, bajos y sanos.  
  - La lógica es evitar que solo se atiendan los críticos. Si solo se atiende a los más urgentes, los demás pacientes no se revisarán y podrían evolucionar a críticos.  
  - Se aplica un enfoque tipo **round-robin**, asegurando que todos los pacientes sean atendidos periódicamente.
- **Indicador de riesgo**: Calculado automáticamente usando un **modelo de pesos**, que considera:
  - Enfermedades crónicas y actuales.
  - Factores sociales.
  - Parámetros clínicos (tensión, glucosa, colesterol, edad).

---

## 🛠 Arquitectura tecnológica

- **Backend**: PHP + MySQL (usando **XAMPP**)
  - Modelos para pacientes (`m_getpacientes.php`) y cálculo de indicadores (`m_calcular_indicadores.php`).
  - Controladores (`c_visitasdiarias.php`, `c_pacientedetalle.php`) para orquestar la lógica.
  - Scripts de actualización de indicadores y estados de pacientes.
- **Frontend**: HTML, CSS y JavaScript
  - Dashboard interactivo con gráfico circular (Chart.js).
  - Sticky dashboard para mantener visibilidad mientras se desplaza la lista de pacientes.
  - Sistema de recomendaciones automatizadas usando **API Groq / llama-3.1-8b-instant**.
- **Base de datos**: MySQL (local, administrada con XAMPP)
  - Tabla `pacientes` con campos JSON para enfermedades, factores sociales y demás parámetros clínicos.
  - Indicador y estado calculados y actualizados automáticamente.
- **Documentación**: Carpeta `docs/` contiene diagramas, PDF del proyecto y documentación adicional.

---

## 📊 Lógica de cálculo del indicador y estado

1. **Pesos asignados** a enfermedades crónicas, actuales y factores sociales.  
2. **Sumatorio de diagnóstico** basado en parámetros clínicos: tensión, glucosa y colesterol.  
3. **Multiplicador por edad** para priorizar pacientes mayores.  
4. **Cálculo de estado**:
   - `CRÍTICO`: indicador ≥ 42
   - `MEDIO`: 23 ≤ indicador < 42
   - `BAJO`: 12 ≤ indicador < 23
   - `SANO`: indicador < 12

---

## 📂 Estructura del repositorio

- `src/` → Código fuente principal (PHP, JS, CSS, vistas)
- `docs/` → Documentación del proyecto (PDF, PPT, vídeo demo)
- `data/` → Datos locales para crear la tabla de pacientes e insertar datos
- `tests/` → Pruebas automáticas
- `.github/workflows/` → Integración continua

---

## 🚀 Cómo ejecutar

1. Instala **XAMPP** en tu máquina (PHP + MySQL + Apache).
2. Clona el repositorio: git clone https://github.com/Boehringer-hackathon/Equipo-pymon-17.git
3. Copia el contenido de src/ dentro del directorio htdocs de XAMPP.
4. Configura la base de datos MySQL local y crea la tabla pacientes (ver scripts en la carpeta data).
5. Inicia los servicios de Apache y MySQL desde XAMPP.
6. Accede a la plataforma desde el navegador: http://localhost/[tu_carpeta_del_proyecto]/index.php


