package AS;

import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;

public class Client {

    private Client() {}

    public static void main(String[] args) {

        String host = (args.length < 1) ? null : args[0];
        try {
            Registry reg = LocateRegistry.getRegistry(1099);
            Hello stub = (Hello) reg.lookup("Hello");
            String response = stub.sayHello();
            System.out.println("response: " + response);
            System.out.println("datos: " + reg.toString());
        } catch (Exception e) {
            System.err.println("Client exception: " + e.toString());
            e.printStackTrace();
        }
    }
}
