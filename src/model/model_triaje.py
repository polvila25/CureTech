from src.model.pesos_modelo import PESOS_ENF_CRONICAS, PESOS_ENF_ACTUALES, PESOS_SOCIAL
import json
'''
algoritmo para calcular el indicador de cada paciente, a partir de sus datos

- Nos devolvera el indicador 
'''
def calcular_indicador(p):
    '''
      se trata de un sumatorio 
            Indicador = ( ∑(enf_cronicas)  + ∑(enf_actuales)  + ∑(factores_sociales) + ∑(diagnostico) ) * (mult_edad) 
    '''

    indice_pac = 0

    #sumar_cronicos
    for ec in p.get("cronicas", []):
        indice_pac += PESOS_ENF_CRONICAS.get(ec, 0)

    #sumar_enf_actuales
    for ea in p.get("actuales", []):
        indice_pac += PESOS_ENF_ACTUALES.get(ea,0)


    #sumar_contexto_social
    for cs in p.get("social", []):
        indice_pac += PESOS_SOCIAL.get(cs, 0)

    #funcion sumatorio de diagnostico
    indice_pac += sumatorio_diagnostico(p)
    #edad
    edad = p.get("edad") or 0
    mult = 1.0

    if edad > 85:
        mult = 1.25
    
    elif edad > 75:
        mult = 1.18
    
    elif edad > 60:
        mult = 1.1

    return round(indice_pac * mult, 2)


def sumatorio_diagnostico(p):
    """Calcula una contribución al indicador basada en signos vitales/diagnóstico simple.

    Actualmente evalúa la `tension` (sistólica/diastólica) y la `glucosa`.
    Devuelve un valor numérico (puntos) que se suma al indicador.
    """
    indice_diagnostico = 0
    
    ''' la tension tiene dos valores (sistolica/diastolica)
        este funció coje los dos valores
    '''
    
    def parse_tension(t_str):
        
        if not t_str:
            return (None, None)
        try:
            parts = str(t_str).replace(' ', '').split('/')
            if len(parts) >= 2:
                sys = int(parts[0])
                dia = int(parts[1])
                return (sys, dia)
        except Exception:
            return (None, None)
        return (None, None)

    # Evaluar tensión arterial
    t = p.get('tension', '')
    sys, dia = parse_tension(t)
    if sys is not None and dia is not None:
        # Puntuación por etapas algoritmo por etapas 
        # Crisis hipertensiva
        if sys >= 180 or dia >= 120:
            indice_diagnostico += 10
            #crear estado automaticamente Critico
        
        # Hipertensión severa
        elif sys >= 160 or dia >= 100:
            indice_diagnostico += 8
        # Hipertensión grado 2
        elif sys >= 140 or dia >= 90:
            indice_diagnostico += 5
        # Elevada: prehipertensión / límite
        elif sys >= 120 or dia >= 80:
            indice_diagnostico += 2


    # Evaluar glucosa (valor puntual, si existe)
    g = p.get('glucosa')
    try:
        if g is not None:
            g = int(g)
            if g >= 200:
                indice_diagnostico += 10
                #crear estado automatico 
                # o variable que sea glucos muy elevada
            elif g >= 150:
                indice_diagnostico += 6
                #
            elif g >= 130:
                indice_diagnostico += 2
    except Exception:
        pass

    c = p.get('colesterol')
    colesterol = int(c)

    if colesterol > 160:
        indice_diagnostico += 6
    elif colesterol >= 140:
        indice_diagnostico +=2
    

    return indice_diagnostico


def fetch_pacientes_from_db(host='127.0.0.1', user='root', password='', database='hackathonsalud'):
    """Conecta a la base de datos MySQL y devuelve lista de pacientes en el formato esperado por el algoritmo.

    Requiere instalar `mysql-connector-python` (pip install mysql-connector-python).
    """
    try:
        import mysql.connector
    except Exception:
        raise RuntimeError("Falta dependencia: instale mysql-connector-python (pip install mysql-connector-python)")

    conn = mysql.connector.connect(host=host, user=user, password=password, database=database)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT * FROM pacientes")
    rows = cursor.fetchall()
    cursor.close()
    conn.close()

    pacientes = []
    for r in rows:
        def parse_json_field(val):
            if val is None:
                return []
            try:
                return json.loads(val)
            except Exception:
                return []

        p = {}
        p['id'] = r.get('id')
        p['nombre'] = r.get('nombre')
        p['edad'] = int(r['edad']) if r.get('edad') not in (None, '') else None
        p['cronicas'] = parse_json_field(r.get('enfermedades_cronicas'))
        p['actuales'] = parse_json_field(r.get('enfermedades_actuales'))
        p['social'] = parse_json_field(r.get('factor_social'))
        p['tension'] = r.get('tension')
        p['glucosa'] = r.get('glucosa')
        p['colesterol'] = r.get('colesterol')
        pacientes.append(p)

    return pacientes


def calcular_indicadores_para_todos(host='127.0.0.1', user='root', password='', database='hackathonsalud'):
    """Obtiene pacientes de la BD, calcula el indicador para cada uno y devuelve lista de dicts con resultados."""
    pacientes = fetch_pacientes_from_db(host, user, password, database)
    resultados = []
    for p in pacientes:
        indicador = calcular_indicador(p)
        resultados.append({
            'id': p.get('id'),
            'nombre': p.get('nombre'),
            'indicador': indicador
        })
    return resultados


if __name__ == '__main__':
    # Ejemplo de uso rápido (ajuste credenciales si es necesario)
    try:
        res = calcular_indicadores_para_todos()
        for r in res:
            print(f"Paciente {r.get('id')} - {r.get('nombre')}: indicador={r.get('indicador')}")
    except Exception as e:
        print('Error al obtener indicadores:', e)
