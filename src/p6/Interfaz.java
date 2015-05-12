package p6;

import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;
import java.util.TreeMap;
import java.util.Vector;

import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JEditorPane;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.WindowConstants;

import org.apache.axis.client.Call;
import org.apache.axis.client.Service;
import org.apache.axis.encoding.XMLType;
import org.apache.axis.utils.Options;
import webservice.XMLParser;

import javax.xml.namespace.QName;
import javax.xml.rpc.ParameterMode;

public class Interfaz {

	static Vector<String> localidades = new Vector<String>(300);
	static TreeMap<String, String> mapa;
	
	public static void main(String[] args) {
		mapa = new TreeMap<String, String>();
		leerProvincias();
		JFrame frame = new JFrame();
		frame.setSize(new Dimension(390,120));
		frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
		
		final JComboBox spin = new JComboBox(localidades);
		spin.setPreferredSize(new Dimension(215,65));
		JLabel titulo = new JLabel("Seleccione una ciudad");
		
		JPanel botonesGenerar = new JPanel();
		JButton xml = new JButton("Descargar XML"); 
		JButton html = new JButton("Generar HTML"); 
		JButton json = new JButton("Generar JSON");
		botonesGenerar.add(xml, BorderLayout.NORTH);
		botonesGenerar.add(json, BorderLayout.CENTER);
		botonesGenerar.add(html, BorderLayout.SOUTH); 
		
		frame.add(titulo, BorderLayout.WEST);
		frame.add(spin, BorderLayout.EAST); 
		frame.add(botonesGenerar, BorderLayout.SOUTH);  
		frame.setVisible(true);
		
		final String endpointURL = args[0];
		
		/*---ACTION LISTENERS---*/
		
		xml.addActionListener(new ActionListener(){
			@Override
			public void actionPerformed(ActionEvent e){
				//generar json
				int code = Integer.parseInt(mapa.get(localidades.get(spin.getSelectedIndex())));
				
				String resul = XMLParser.DescargarInfoTiempo(""+code);
				/*String resul ="";
				try{
					Service service = new Service();
					Call call = (Call) service.createCall();
					call.setTargetEndpointAddress(new java.net.URL(endpointURL));
					call.setOperationName( new QName("Practica6", "main") );
					resul = (String) call.invoke( new Object[] {""+code, ""+1} );
					
					JTextArea text = new JTextArea(); 
					text.setSize(new Dimension(300, 650)); text.setText(resul);
					
					JFrame segundo = new JFrame(); 
					segundo.setSize(new Dimension(950,600)); 
					segundo.getContentPane().add(text,BorderLayout.CENTER); 
					segundo.setVisible(true); segundo.setLocationRelativeTo(null);
					
				}catch(Exception ex){ex.printStackTrace();}*/
			}			
		});
		
		json.addActionListener(new ActionListener(){
			@Override
			public void actionPerformed(ActionEvent e){
				//generar json
				int code = Integer.parseInt(mapa.get(localidades.get(spin.getSelectedIndex())));
				
//				String resul = XMLParser.GenerarJson("http://www.aemet.es/xml/municipios/localidad_"+code+".xml");
				String resul ="";
				try{
					Service service = new Service();
					Call call = (Call) service.createCall();
					call.setTargetEndpointAddress(new java.net.URL(endpointURL));
					call.setOperationName( new QName("Practica6", "main") );
					resul = (String) call.invoke( new Object[] { ""+code, ""+2 } );
					
					JTextArea text = new JTextArea(); 
					text.setSize(new Dimension(300, 650)); text.setText(resul);
					
					JFrame segundo = new JFrame(); 
					segundo.setSize(new Dimension(300,650)); 
					segundo.getContentPane().add(text,BorderLayout.CENTER); 
					segundo.setVisible(true); segundo.setLocationRelativeTo(null);
					
				}catch(Exception ex){ex.printStackTrace();}
			}			
		});
		
		html.addActionListener(new ActionListener(){
			@Override
			public void actionPerformed(ActionEvent e){
				//generar html
				int code = Integer.parseInt(mapa.get(localidades.get(spin.getSelectedIndex())));
				
//				String resul = XMLParser.GenerarHTML("http://www.aemet.es/xml/municipios/localidad_"+code+".xml");
				String resul=""; 
				try{
					Service service = new Service();
					Call call = (Call) service.createCall();
					call.setTargetEndpointAddress(new java.net.URL(endpointURL));
					call.setOperationName( new QName("Practica6", "main") );
					resul = (String) call.invoke( new Object[] { ""+code, ""+3 } );
					
					JEditorPane jep = new JEditorPane(); 
					jep.setContentType("text/html"); jep.setText(resul); 
					JFrame segundo = new JFrame();	segundo.setSize(new Dimension(800,450));
					segundo.add(jep);  segundo.setVisible(true);
					segundo.setLocationRelativeTo(null);
				}catch(Exception ex){ex.printStackTrace();}
			}			
		});
	}

	public static void leerProvincias(){
		Scanner excel = null;
		try {
            excel = new Scanner(new File("11codmun50.txt"));
		    for(int leidos=0;leidos<293;leidos++){
                String indice = excel.next()+excel.next();
                excel.next();/*descartamos*/
                String nombre = excel.nextLine().substring(1);
                localidades.add(nombre);
                mapa.put(nombre, indice);
            }
        }
        catch (Exception e) {e.printStackTrace();}
	}
}
