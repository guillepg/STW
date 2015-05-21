package webservice.P5;

/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 5
 * @author Guillermo Perez
 *
 * Clase que sirve de estructura para la lectura de la base de datos y transformaci�n a JSON
 */
public class Prediccion {
	
	public int hora, duracion;
	
	public int probabilidad_lluvia;
	public int cota_nieve;
	public String estado_cielo;
	public String direccion_viento; public int velocidad_viento, racha_max_viento;
	public int temperatura_max,temperatura_min,sensacion_max,sensacion_min;
	public int humedad_max, humedad_min, UV;
	
	public Prediccion(int hora, int dur){
		this.hora=hora; this.duracion=dur;
		
	}
	
}
