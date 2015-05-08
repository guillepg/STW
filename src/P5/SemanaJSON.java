package P5;

import java.util.Vector;

/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 5
 * @author Guillermo Perez
 *
 * Clase que sirve de estructura para la lectura de la base de datos y transformación a JSON
 */
public class SemanaJSON {

	public Vector<DiaJSON> prediccionSemana;
	
	public SemanaJSON(){ prediccionSemana=new Vector<DiaJSON>();}
}
