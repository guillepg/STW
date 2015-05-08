package webservice.P3;
/**
 * Sistemas de Informacion. 2014-2015
 * Practica 2
 * @author Guillermo Perez Garcia (610382)
 * @author Andrea Aleyxendri (626549)
 * 
 */

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.StringTokenizer;

import webservice.P5.Prediccion;


public final class Servicios{
	
	static Connection connection;
	public static void main(String[] args){
		
	}
	/**
	 * A�ade una entrada (fecha y ciudad) a la tabla Fecha 
	 * @return resultado correcto o fallido de la operaci�n
	 * @throws SQLException
	 */
    public static boolean anadirFecha(String ciudad, int dia, int mes, int anyo) throws SQLException {        	
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO fecha (ciudad,dia,mes,anyo) VALUES (?, ?, ?, ?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setString(1, ciudad);
            preparedStatement.setInt(2, dia);
            preparedStatement.setInt(3, mes);
            preparedStatement.setInt(4, anyo);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) {  return false; }               
    }
    
    /**
     * @return el id de la fecha correspondiente a los par�metros, � -1 si no existe
     */
    public static int getIdFecha(int dia, int mes, int anyo, String city){
    	try{
    		connection = GestorDeConexiones.getConnection();
	    	String query="SELECT * FROM fecha WHERE dia="+dia+" AND mes="+mes+" AND anyo="+anyo+" AND ciudad like '"+city+"'";
	    	PreparedStatement ps = connection.prepareStatement(query);
	    	
	    	ResultSet rs = ps.executeQuery();
	    	if(rs.next()){   
	        	StringTokenizer st=new StringTokenizer(rs.getString(1));
	            int id = Integer.parseInt(st.nextToken());
	            return id;
	        }
	    	else{ return -1; }
    	}
    	catch(Exception ex){return -1;}
    }
    
    /**
     * A�ade una entrada a la tabla Lluvia unida a una fecha existente
     * @return resultado de la operaci�n
     * @throws SQLException
     */
    public static boolean anadirLluvia(String ciudad, int id, int hora, int dur, int prob) throws SQLException {      
    	try{
        	connection = GestorDeConexiones.getConnection();
    	
            String queryString = "INSERT INTO lluvia (fecha_idfecha,fecha_ciudad,hora, duracion, probabilidad) VALUES (?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
        	
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, id);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, hora);
            preparedStatement.setInt(4, dur);
            preparedStatement.setInt(5, prob);
            /* Execute update. */                    
            int insertedRows = preparedStatement.executeUpdate(); 
            if (insertedRows == 0) return false;
            else return true;
            
        } catch (Exception e) { return false; }     
    }
    
    /**
     * A�ade una entrada a la tabla Nieve unida a una fecha existente
     * @return resultado de la operaci�n
     * @throws SQLException
     */
    public static boolean anadirNieve(String ciudad, int id, int hora, int dur, int cota) throws SQLException {      
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO nieve (fecha_idfecha,fecha_ciudad,hora, duracion, cota) VALUES (?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, id);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, hora);
            preparedStatement.setInt(4, dur);
            preparedStatement.setInt(5, cota);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
            
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) { return false; }  
    }
    
    /**
     * A�ade una entrada a la tabla Viento unida a una fecha existente
     * @return resultado de la operaci�n
     * @throws SQLException
     */
    public static boolean anadirViento(String ciudad, int id, int hora, int dur, String dir, int vel, int racha) throws SQLException {      
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO viento (fecha_idfecha,fecha_ciudad,hora, duracion, direccion, velocidad, racha_max)"
        			+ " VALUES (?,?,?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, id);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, hora);
            preparedStatement.setInt(4, dur);
            preparedStatement.setString(5, dir);
            preparedStatement.setInt(6, vel);
            preparedStatement.setInt(7, racha);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
           
            
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) { return false; }  
    }
    
    /**
     * A�ade una entrada a la Temperatura Lluvia unida a una fecha existente
     * @return resultado de la operaci�n
     * @throws SQLException
     */
    public static boolean anadirTemperatura(String ciudad, int fecha, int min, int max, int min_sens, int max_sens) throws SQLException {      
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO temperatura (fecha_idfecha, fecha_ciudad, min, max, sens_min, sens_max) "
        			+ "VALUES (?,?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, fecha);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, min);
            preparedStatement.setInt(4, max);
            preparedStatement.setInt(5, min_sens);
            preparedStatement.setInt(6, max_sens);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
            
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) { return false; }  
    }
    
    /**
     * A�ade una entrada a la tabla Lluvia unida a una fecha existente
     * @return resultado de la operaci�n
     * @throws SQLException
     */
    public static boolean anadirCielo(String ciudad, int fecha, int hora, int dur, String estado){
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO cielo (fecha_idfecha, fecha_ciudad, hora, duracion, estado) VALUES (?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, fecha);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, hora);
            preparedStatement.setInt(4, dur);
            preparedStatement.setString(5, estado);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
            
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) { return false; }  
    }
    
    public static boolean anadirVarios(String ciudad, int fecha, int hum_max, int hum_min, int UV){
    	try{
        	connection = GestorDeConexiones.getConnection();
        	String queryString = "INSERT INTO varios (fecha_idfecha, fecha_ciudad, humedad_max, humedad_min, UV) "
        			+ "VALUES (?,?,?,?,?)";                    
            PreparedStatement preparedStatement = connection.prepareStatement(queryString);
            
            /* Fill "preparedStatement". */
            preparedStatement.setInt(1, fecha);
            preparedStatement.setString(2, ciudad);
            preparedStatement.setInt(3, hum_max);
            preparedStatement.setInt(4, hum_min);
            preparedStatement.setInt(5, UV);
            
            /* Execute query. */                    
            int insertedRows = preparedStatement.executeUpdate();
            
            if (insertedRows == 0) return false;
            else return true;
        } catch (Exception e) { return false; }  
    }
    		
    /**
     * @param n numero de peliculas que queremos que sean presentadas
     * @return vector de pares de elementos (id,pelicula)
     * @throws SQLException
     */
    public static Prediccion getPrediccionesDiayPeriodo(String ciudad, int dia, int mes, int anyo, int hora, int dur) throws SQLException{
    	connection = GestorDeConexiones.getConnection();
    	int idfecha = getIdFecha(dia,mes,anyo,ciudad);
    	Prediccion pred= new Prediccion(hora,dur);
    	
    	if(idfecha>0){
    		String query1="SELECT probabilidad FROM lluvia where fecha_idfecha = ? and hora = ? and duracion = ?";
        	PreparedStatement ps1 = connection.prepareStatement(query1);
        	ps1.setInt(1, idfecha);	ps1.setInt(2, hora); ps1.setInt(3, dur);
            ResultSet rs1 = ps1.executeQuery();            
            if(rs1.next()){   
	        	StringTokenizer st=new StringTokenizer(rs1.getString(1));
	            pred.probabilidad_lluvia=Integer.parseInt(st.nextToken());
	        } 
            
            String query2="SELECT cota FROM nieve where fecha_idfecha = ? and hora = ? and duracion = ?";
        	PreparedStatement ps2 = connection.prepareStatement(query2);
        	ps2.setInt(1, idfecha);    	ps2.setInt(2, hora);    ps2.setInt(3, dur);
            ResultSet rs2 = ps2.executeQuery();
            if(rs2.next()){   
	        	StringTokenizer st=new StringTokenizer(rs2.getString(1)); 
	        	pred.cota_nieve=Integer.parseInt(st.nextToken());
	        } 
            
            String query3="SELECT estado FROM cielo where fecha_idfecha = ? and hora = ? and duracion = ?";
        	PreparedStatement ps3 = connection.prepareStatement(query3);
        	ps3.setInt(1, idfecha);    	ps3.setInt(2, hora);   	ps3.setInt(3, dur);
            ResultSet rs3 = ps3.executeQuery();
            if(rs3.next()){  
            	StringTokenizer st=new StringTokenizer(rs3.getString(1)); 
            	pred.estado_cielo=st.nextToken();
            }
            
            String query4="SELECT direccion,velocidad,racha_max FROM viento where fecha_idfecha = ? and hora = ? and duracion = ?";
        	PreparedStatement ps4 = connection.prepareStatement(query4);
        	ps4.setInt(1, idfecha);    	ps4.setInt(2, hora);   	ps4.setInt(3, dur);
            ResultSet rs4 = ps4.executeQuery();
            if(rs4.next()){  
            	StringTokenizer st=new StringTokenizer(rs4.getString(1)); pred.direccion_viento=st.nextToken(); 
            	st=new StringTokenizer(rs4.getString(2)); pred.velocidad_viento=Integer.parseInt(st.nextToken()); 
            	st=new StringTokenizer(rs4.getString(3)); pred.racha_max_viento=Integer.parseInt(st.nextToken());
            }
            
            String query5="SELECT max,min,sens_max,sens_min FROM temperatura where fecha_idfecha = ?";
        	PreparedStatement ps5 = connection.prepareStatement(query5);
        	ps5.setInt(1, idfecha);
            ResultSet rs5 = ps5.executeQuery();
            if(rs5.next()){  
            	StringTokenizer st=new StringTokenizer(rs5.getString(1)); pred.temperatura_max=Integer.parseInt(st.nextToken()); 
            	st=new StringTokenizer(rs5.getString(2)); pred.temperatura_min=Integer.parseInt(st.nextToken());
            	st=new StringTokenizer(rs5.getString(3)); pred.sensacion_max=Integer.parseInt(st.nextToken()); 
            	st=new StringTokenizer(rs5.getString(4)); pred.sensacion_min=Integer.parseInt(st.nextToken()); 
            }
            
            String query6="SELECT humedad_min,humedad_max,UV FROM varios where fecha_idfecha = ?";
        	PreparedStatement ps6 = connection.prepareStatement(query6);
        	ps6.setInt(1, idfecha);
            ResultSet rs6 = ps6.executeQuery();
            if(rs6.next()){  
            	StringTokenizer st=new StringTokenizer(rs6.getString(1)); pred.humedad_min=Integer.parseInt(st.nextToken());
            	st=new StringTokenizer(rs6.getString(2)); pred.humedad_max=Integer.parseInt(st.nextToken());
            	st=new StringTokenizer(rs6.getString(3)); pred.UV=Integer.parseInt(st.nextToken()); 
            }
            
            return pred;
    	}
    	else {
    		System.out.println("La fecha no existe."); return null;}
    }
    
   
    
}
