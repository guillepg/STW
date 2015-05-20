import org.jdom2.Document;
import org.jdom2.Element;
import org.jdom2.input.SAXBuilder;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.net.URL;

/**
 * Created by Guille on 13/05/2015.
 */
public class asdfas {
    public static void main(String[] args){
        SAXBuilder SAX = new SAXBuilder();
        FileWriter fw=null;
        BufferedWriter bw=null;
        try{
            Document doc = SAX.build("resources/estacion-bicicleta.xml");
            fw=new FileWriter("resources/texto.txt");
            bw=new BufferedWriter(fw);
            Element raiz = doc.getRootElement();
            if(raiz.getName().equals("resultado")){

                for(Element nivel2: raiz.getChildren()){
                    if(nivel2.getName().equals("result")){

                        for(Element estaciones: nivel2.getChildren()){

                            for(Element propiedades_estacion: estaciones.getChildren()){
                                if (propiedades_estacion.getName().equals("geometry")){
                                    String coord = propiedades_estacion.getChild("coordinates").getValue();
                                    int coma = coord.indexOf(',');
                                    bw.write(coord.substring(0, coma) + "\n"); System.out.println(coord.substring(0, coma) + "\n");
                                    bw.write(coord.substring(coma + 1) + "\n"); System.out.println(coord.substring(coma + 1) + "\n");
                                }
                            }
                        }
                    }
                }
            }
            bw.close(); fw.close();
        }catch(Exception ex){}
    }

}
