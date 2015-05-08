package P3;
/**
 * Sistemas y Tecnologias Web 2014-2015
 * Practica 5
 * @author Guillermo Perez Garcia
 * 
 * Clase que realiza las conexiones necesarias para la practica nº5.
 */
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class GestorDeConexiones {
	/*
	 * Atributos de la clase
	 */
	private final static String DRIVER_CLASS_NAME = "com.mysql.jdbc.Driver";
	private final static String DRIVER_URL = "jdbc:mysql://localhost:3306/stw";
	
	private final static String USER = "root";
	private final static String PASSWORD = "toor";

	
	static {		
		try { Class.forName(DRIVER_CLASS_NAME);} 
		catch (ClassNotFoundException e) { e.printStackTrace(System.err); }
	}

	private GestorDeConexiones() {}
	
	/**
	 * Conecta con la base de datos de datos meteorologicos
	 * @throws SQLException
	 */
	public final static Connection getConnection() throws SQLException {
		return DriverManager.getConnection(DRIVER_URL, USER, PASSWORD);
	}


}
