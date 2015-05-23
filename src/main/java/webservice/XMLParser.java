package webservice;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;

import org.jdom2.Document;
import org.jdom2.Element;
import org.jdom2.input.SAXBuilder;

import webservice.P3.Dia;
import webservice.P5.DiaJSON;
import webservice.P5.Prediccion;
import webservice.P5.SemanaJSON;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 6
 * @author Guillermo Perez
 *
 * Primera aplicaci�n de la pr�ctica 5, adaptada de la webservice.P3
 */
public class XMLParser {
	static Dia[] semana = new Dia[7];
	static String ciudad = "";
	static StringBuffer html = new StringBuffer(); 	
	static String JSON="";

	public static String main(String i, String funcion){
		if(funcion.equals("1")) {
			return DescargarInfoTiempo(i);
//			try{
//				StringBuffer salida = new StringBuffer();
//				BufferedReader in = new BufferedReader(
//				        new InputStreamReader(new URL(arg).openStream()));
//				String input="";
//				while ((input=in.readLine())!=null){ salida.append(input+"\n");	}
//				return salida.toString();
//			}catch(Exception ex){return "";}
		}
		else if(funcion.equals("2")){
			return GenerarJson("http://www.aemet.es/xml/municipios/localidad_"+i+".xml");
		}
		else{
			return GenerarHTML("http://www.aemet.es/xml/municipios/localidad_"+i+".xml");
		}
	}
	
	public static String DescargarInfoTiempo(String code){
		try{
			StringBuffer salida = new StringBuffer();
			BufferedReader in = new BufferedReader(
			        new InputStreamReader(new URL("http://www.aemet.es/xml/municipios/localidad_"
			        		+code+".xml").openStream()));
			String input="";
			while ((input=in.readLine())!=null){ salida.append(input+"\n");	}
			return salida.toString();
		}catch(Exception ex){return "";}
	}
	
	/**
	 * @param ruta, la URL donde se encuentra el fichero XML
	 */
	public static void parseXml(String ruta) {
		SAXBuilder SAX = new SAXBuilder();   
		try{
			Document doc = SAX.build(new URL(ruta));
			Element raiz = doc.getRootElement();
			if(raiz.getName().equals("root")){

				//Descendemos nivel a nivel hasta llegar a la predicci�n de un d�a
				
				for(Element nivel2: raiz.getChildren()){
					ciudad=raiz.getChild("nombre").getValue().toLowerCase();
					if(nivel2.getName().equals("prediccion")){
						int index_dia = 0;
						for(Element dia: nivel2.getChildren()){
							
							semana[index_dia] = new Dia();
							semana[index_dia].fecha = dia.getAttributeValue("fecha");
							for(Element tag : dia.getChildren()){
								
								try{
									//Distinguimos entre los tipos de predicciones y los intervalos proporcionados
									switch(tag.getName()){
									case "prob_precipitacion": 
										if(!tag.getValue().equals(null) && !tag.getAttributeValue("periodo").equals(null)){
											// De las 7 posiciones del vector asignamos una a cada per�odo.
											// No tienen por qu� estar completas todas. No siempre existen datos de todos los intervalos.
											// El orden de los vectores se describe en la clase Dia
											if(tag.getAttributeValue("periodo").equals("00-06")){
												semana[index_dia].precip[0] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("06-12")){
												semana[index_dia].precip[1] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-18")){
												semana[index_dia].precip[2] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("18-24")){
												semana[index_dia].precip[3] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("00-12")){
												semana[index_dia].precip[4] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-24")){
												semana[index_dia].precip[5] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("00-24")){
												semana[index_dia].precip[6] = tag.getValue();
											}
										}
										else{semana[index_dia].precip[6]= tag.getValue();}
										break;
										
									case "cota_nieve_prov": 
										if(!tag.getValue().equals(null) && !tag.getAttributeValue("periodo").equals(null)){
											if(tag.getAttributeValue("periodo").equals("00-06")){
												semana[index_dia].nieve[0] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("06-12")){
												semana[index_dia].nieve[1] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-18")){
												semana[index_dia].nieve[2]= tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("18-24")){
												semana[index_dia].nieve[3] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("00-12")){
												semana[index_dia].nieve[4] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-24")){
												semana[index_dia].nieve[5] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("00-24")){
												semana[index_dia].nieve[6] = tag.getValue();
											}
										}
										else{semana[index_dia].nieve[6]= tag.getValue();}
										break;
											
									case "estado_cielo": 
										if(!tag.getValue().equals(null) && !tag.getAttributeValue("periodo").equals(null)){
											if(tag.getAttributeValue("periodo").equals("00-06")){
												semana[index_dia].cielo[0] = tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("06-12")){
												semana[index_dia].cielo[1] = tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("12-18")){
												semana[index_dia].cielo[2]= tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("18-24")){
												semana[index_dia].cielo[3] = tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("00-12")){
												semana[index_dia].cielo[4] = tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("12-24")){
												semana[index_dia].cielo[5] = tag.getAttributeValue("descripcion");
											}else if(tag.getAttributeValue("periodo").equals("00-24")){
												semana[index_dia].cielo[6] = tag.getAttributeValue("descripcion");
											}
										}
										else{semana[index_dia].nieve[6]= tag.getValue();}
										break;
											
									case "viento": 
										if(tag.getAttributeValue("periodo").equals("00-06")){
											semana[index_dia].viento[0][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[0][1] = tag.getChildren().get(1).getValue();
										}else if(tag.getAttributeValue("periodo").equals("06-12")){
											semana[index_dia].viento[1][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[1][1] = tag.getChildren().get(1).getValue();
										}else if(tag.getAttributeValue("periodo").equals("12-18")){
											semana[index_dia].viento[2][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[2][1] = tag.getChildren().get(1).getValue();
										}else if(tag.getAttributeValue("periodo").equals("18-24")){
											semana[index_dia].viento[3][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[3][1] = tag.getChildren().get(1).getValue();
										}else if(tag.getAttributeValue("periodo").equals("00-12")){
											semana[index_dia].viento[4][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[4][1] = tag.getChildren().get(1).getValue();
										}else if(tag.getAttributeValue("periodo").equals("12-24")){
											semana[index_dia].viento[5][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[5][1] = tag.getChildren().get(1).getValue();
										}else{
											semana[index_dia].viento[6][0] = tag.getChildren().get(0).getValue();
											semana[index_dia].viento[6][1] = tag.getChildren().get(1).getValue();
										} 
										break;
									
									case "racha_max": 
										if(!tag.getValue().equals(null) && !tag.getAttributeValue("periodo").equals(null)){
											if(tag.getAttributeValue("periodo").equals("00-06")){
												semana[index_dia].racha[0] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("06-12")){
												semana[index_dia].racha[1] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-18")){
												semana[index_dia].racha[2] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("18-24")){
												semana[index_dia].racha[3] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("00-12")){
												semana[index_dia].racha[4] = tag.getValue();
											}else if(tag.getAttributeValue("periodo").equals("12-24")){
												semana[index_dia].racha[5] = tag.getValue();
											}else{
												semana[index_dia].racha[6] = tag.getValue();
											}
										}
										break;
											
									case "temperatura": 
										for(Element dato: tag.getChildren()){
											switch(dato.getName()){ 
											case "maxima":  
												semana[index_dia].temp[1] = dato.getValue(); break;
											case "minima": 
												semana[index_dia].temp[0] = dato.getValue(); break;
											}
										}
										break;
												
									case "sens_termica": 
										for(Element dato: tag.getChildren()){
											switch(dato.getName()){
											case "maxima": 
												semana[index_dia].sens[1] = dato.getValue(); break;
											case "minima": 
												semana[index_dia].sens[0] = dato.getValue(); break;
											}							
										}
										break;
										
									case "humedad_relativa": 
										for(Element dato: tag.getChildren()){
											switch(dato.getName()){
											case "maxima": 
												semana[index_dia].hum[1] = dato.getValue(); break;
											case "minima": 
												semana[index_dia].hum[0] = dato.getValue(); break;
											}
										}
										break;
									
									case "uv_max":
										semana[index_dia].UV = tag.getValue();break;
									}//fin switch elementos
								}catch (Exception ex){}
							}//fin for tags
							
							index_dia++; //Pasamos a leer la info del siguiente d�a 
						}//fin for dias
						
					}//fin if prediccion
					
				}//fin for hijos de la raiz
			}
		}catch(Exception ex){ex.printStackTrace();}
	}
	
	public static String GenerarJson(String xml){
		parseXml(xml);
		Gson gs = new GsonBuilder().setPrettyPrinting().create();
		SemanaJSON semanajson = new SemanaJSON();
		DiaJSON diaObj = null;
		Prediccion p=null;
		int hora=0, duracion=0;
		// orden de los valores: 0-6, 6-12, 12-18, 18-24, 00-12, 12-24, 00-24
		// para los vectores de tama�o 2: min, max
		for(int dia=0;dia<7;dia++){
			int day = Integer.parseInt(semana[dia].fecha.substring(8,10));
			int month = Integer.parseInt(semana[dia].fecha.substring(5,7));
			int year = Integer.parseInt(semana[dia].fecha.substring(0,4));
			diaObj = new DiaJSON(day,month,year,ciudad);
			
			for(int pred=0;pred<7;pred++){
				if(pred==0){ hora=0; duracion=6;}
				else if(pred==1){ hora=6; duracion=6;}
				else if(pred==2){ hora=12; duracion=6;}
				else if(pred==3){ hora=18; duracion=6;}
				else if(pred==4){ hora=0; duracion=12;}
				else if(pred==5){ hora=12; duracion=12;}
				else if(pred==6){ hora=0; duracion=24;}
				p=new Prediccion(hora, duracion);
				
				if (semana[dia].precip[pred]!=null && semana[dia].precip[pred].length()>0)
					p.probabilidad_lluvia=Integer.parseInt(semana[dia].precip[pred]);
				if (semana[dia].nieve[pred]!=null && semana[dia].nieve[pred].length()>0)
					p.cota_nieve=Integer.parseInt(semana[dia].nieve[pred]);
				if (semana[dia].cielo[pred]!=null && semana[dia].cielo[pred].length()>0)
					p.estado_cielo=semana[dia].cielo[pred];
				if (semana[dia].racha[pred]!=null && semana[dia].racha[pred].length()>0)
					p.racha_max_viento=Integer.parseInt(semana[dia].racha[pred]);
				if (semana[dia].viento[pred][0]!=null && semana[dia].viento[pred][0].length()>0){
					p.velocidad_viento=Integer.parseInt(semana[dia].viento[pred][1]);
					p.direccion_viento=semana[dia].viento[pred][0];
				}
				if (semana[dia].temp[0]!=null && semana[dia].temp[0].length()>0){
					p.temperatura_min=Integer.parseInt(semana[dia].temp[0]);
					p.temperatura_max=Integer.parseInt(semana[dia].temp[1]);
				}
				if (semana[dia].sens[0]!=null && semana[dia].sens[0].length()>0){
					p.sensacion_min=Integer.parseInt(semana[dia].sens[0]);
					p.sensacion_max=Integer.parseInt(semana[dia].sens[1]);
				}
				if (semana[dia].hum[0]!=null && semana[dia].hum[0].length()>0
						&& semana[dia].hum[1].length()>0){
					p.humedad_min=Integer.parseInt(semana[dia].hum[0]);
					p.humedad_max=Integer.parseInt(semana[dia].hum[1]);
				}
				if (semana[dia].UV!=null && semana[dia].UV.length()>0){
					p.UV=Integer.parseInt(semana[dia].UV);
				}
				diaObj.predicciones.add(p);
			}
			semanajson.prediccionSemana.add(diaObj);
		}
		return gs.toJson(semanajson);
	}
	
	public static String GenerarHTML(String xml){
		Gson gson = new GsonBuilder().setPrettyPrinting().create();
		JSON=GenerarJson(xml);
		SemanaJSON sem = gson.fromJson(JSON, SemanaJSON.class);
		html.append("<html>\n	<head>\n<title>Prueba de tablas</title>\n</head>\n");
		html.append("<body>\n <table style=\"width:100%\">  ");
		
		int index_dia=0; 
		int[] maximos = new int[7]; String[] intervalos = new String[7];
		html.append("\n<tr> <th>1)  Dia</th>");
		
		for(index_dia=0;index_dia<7;index_dia++){
			int max=0;
			DiaJSON day = sem.prediccionSemana.get(index_dia);	
			for(int i=1;i<day.predicciones.size();i++){ //intervalo m�s grande de este d�a
				if(day.predicciones.get(i).duracion>day.predicciones.get(i-1).duracion){ max=i;	}
			}
			maximos[index_dia]=max;
			intervalos[index_dia]= " ("+day.predicciones.get(max).hora + "-" +(day.predicciones.get(max).hora
					+day.predicciones.get(max).duracion)+"h)";
		}
		
		for(index_dia=0;index_dia<7;index_dia++){
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.dia+"/"+day.mes+ intervalos[index_dia] +"</td>");
			sem.prediccionSemana.get(index_dia).predicciones.get(0);
		}
		
		html.append("\n<tr> <th>2)  Precipitaciones</th>"); 
		
		for(index_dia=0;index_dia<7;index_dia++){
			int max=0;
			DiaJSON day = sem.prediccionSemana.get(index_dia);			
			html.append("<td>"+day.predicciones.get(max).probabilidad_lluvia+"</td>");
			
		}		
		
		html.append("</tr>\n<tr> <th>3)  Cota de nieve</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.predicciones.get(max).cota_nieve+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>4)  Estado del cielo</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.predicciones.get(max).estado_cielo+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>5)  Viento</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.predicciones.get(max).velocidad_viento+"km/h "+day.predicciones.get(max).direccion_viento+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>6)  Racha maxima</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.predicciones.get(max).racha_max_viento+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>7)  Temperatura</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>min: "+day.predicciones.get(max).temperatura_min + ", max: " +day.predicciones.get(max).temperatura_max+"</td>");
			}
		
		html.append("</tr>\n<tr> <th>8)  Sensacion termica</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>min: "+day.predicciones.get(max).sensacion_min + ", max: " + day.predicciones.get(max).sensacion_max+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>9)  Humedad relativa</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>min: "+day.predicciones.get(max).humedad_min + ", max: " +day.predicciones.get(max).humedad_max+"</td>");
		}
		
		html.append("</tr>\n<tr> <th>10) Indice UV</th>");
		for(index_dia=0;index_dia<7;index_dia++){
			int max = maximos[index_dia];
			DiaJSON day = sem.prediccionSemana.get(index_dia);
			html.append("<td>"+day.predicciones.get(max).UV+"</td>");
		}
		
		html.append("</tr></table></body>\n</html>");
		return html.toString();
	}
	
	
}
