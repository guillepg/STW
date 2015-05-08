package webservice.P3;

/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 5
 * @author Guillermo Perez
 *
 * Clase que sirve de estructura para el parseo del fichero XML entrante
 */
public class Dia {
	// orden de los valores: 0-6, 6-12, 12-18, 18-24, 00-12, 12-24, 00-24
	// para los vectores de tama�o 2: min, max
	public String fecha="";
	public String[] precip = new String[7],
			nieve = new String[7],
			cielo = new String[7],
			racha = new String[7], 
			temp = new String[2], 
			sens = new String[2], 
			hum = new String[2];
	public String[][] viento = new String[7][2];
	public String UV = "";
	
	public void setLluvias(String[] ll){
		precip = ll;
	}
	public void setNieve(String[] n){
		nieve = n;
	}
	public void setCielo(String[] c){
		cielo = c;
	}
	public void setViento(String[][] v, String[] r){
		racha = r; viento = v;
	}
	public void setTemperaturas(String[] t){
		temp = t;
	}
	public void setVarios(String UV, String[] hum){
		this.UV = UV; this.hum = hum;
	}

	/*
	prob_precipitacion, 	periodo 24, 12, 6
	cota_nieve_prov, 		periodo 24, 12, 6
	estado_cielo, 			periodo 24, 12, 6 
		descripcion 
	viento, 				periodo 24, 12, 6
		direccion y velocidad
	racha_max 				periodo 24, 12, 6
	temperatura	
		maxima, minima
	sens_termica 
		maxima, minima
	humedad_relativa 
		maxima, minima
	uv_max
	*/
}
