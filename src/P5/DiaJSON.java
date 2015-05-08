package P5;

import java.util.Vector;

/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 5
 * @author Guillermo Perez
 *
 * Clase que sirve de estructura para la lectura de la base de datos y transformación a JSON
 */
public class DiaJSON {

	public int dia, mes, anyo; 
	public String ciudad;
	public Vector<Prediccion> predicciones;
	
	public DiaJSON(int dia, int mes, int anyo, String ciudad){
		this.dia=dia; this.mes=mes; this.anyo=anyo;
		this.ciudad=ciudad;
		this.predicciones=new Vector<Prediccion>();
	}
}
