#vamos a definir los pesos para cada 

# Valor: Puntos de riesgo (0-10)
PESOS_ENF_CRONICAS = {
    "cancer activo": 10,
    "insuficiencia cardiaca": 9,
    "demencia alzheimer": 9,
    "epoc": 8,           # Enfermedad Pulmonar Obstructiva Crónica
    "ictus previo": 8,   # Accidente Cerebrovascular
    "insuficiencia renal": 7,
    "cirrosis hepatica": 7,
    "diabetes tipo 2": 6,
    "fibrilacion auricular": 6,
    "obesidad morbida": 6, # IMC > 40
    "depresion mayor": 6,
    "artritis reumatoide": 5,
    "vih sida": 5,
    "hipertension arterial": 4,
    "asma bronquial": 4
}

# Diccionario de Enfermedades Actuales / Motivos de Consulta (15 relevantes)
PESOS_ENF_ACTUALES = {
    "dolor toracico": 10,       # Posible infarto
    "disnea": 9,                # Dificultad respiratoria
    "descompensacion diabetes": 8,
    "crisis hipertensiva": 8,
    "fiebre alta": 7,           # > 39ºC
    "traumatismo caida": 6,     # Riesgo fractura en ancianos
    "dolor abdominal": 6,       # Posible apendicitis/cólico
    "infeccion respiratoria": 5, # Gripe/Bronquitis
    "infeccion orina": 5,
    "crisis ansiedad": 5,
    "gastroenteritis": 4,       # Riesgo deshidratación
    "mareos vertigos": 4,       # Riesgo caídas
    "migrana intensa": 4,
    "lumbago agudo": 3,
    "erupcion cutanea": 3
}

# Diccionario de Contexto Social (10 factores)
PESOS_SOCIAL = {
    "vive solo mayor 75": 5,
    "dependiente": 5,           # Necesita cuidador para básicas
    "alcoholismo": 5,
    "pobreza economica": 4,     # No puede pagar medicación/luz
    "aislamiento": 4,      # sin red familiar 
    "fumador activo": 3,
    "Depresion": 3,    # El paciente cuida a otro
    "problemas vivienda": 3,    # Hacinamiento/Insalubridad
    "obesidad": 3,
    "barrera idioma": 2
}
