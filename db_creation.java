import java.sql.*;

public class db_creation
{
    public static void main(String[] args)
    {
        
        final String DBUserName = "maruf.satir";
        final String DBPassword = "FSGHsg9S";
        final String DBName = "maruf_satir";
        final String DBURL = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr/" + DBName; // + "?user=" + DBUserName + "&password=" + DBPassword;

        Connection connection = null;

        try{
            connection = DriverManager.getConnection(DBURL, DBUserName, DBPassword);
        }
        catch(SQLException sqlE){
            System.out.println("Database connection has been failed.");
            System.out.println(sqlE.getMessage());
            
            int errorCode = sqlE.getErrorCode();
            if(errorCode == 1045)
                System.out.println("Wrong Password or Username");
            else if (errorCode == 1049)
            System.out.println("Logged in succesfully but wrong database name.");
            
            System.out.println(errorCode);
        }

        if(connection == null)
        {
            System.out.println("Could not connect the database;");
        }else
        {
            Statement stmt;
            try{
                stmt = connection.createStatement();
                stmt.executeUpdate("DROP TABLE IF EXISTS buy;");
                stmt.executeUpdate("DROP TABLE IF EXISTS customer;");
                stmt.executeUpdate("DROP TABLE IF EXISTS product;");


                stmt.executeUpdate("CREATE TABLE customer(" + 
                                    "cid CHAR(12)," +
                                    "cname VARCHAR(50)," +
                                    "bdate DATE," +
                                    "adress VARCHAR(50)," +
                                    "city VARCHAR(20)," +
                                    "wallet FLOAT," +
                                    "PRIMARY KEY(cid))" +
                                    "ENGINE=InnoDB;");

                stmt.executeUpdate("CREATE TABLE product(" + 
                                    "pid CHAR(8)," +
                                    "pname VARCHAR(20)," +
                                    "price FLOAT," +
                                    "stock INT," +
                                    "PRIMARY KEY(pid))" +
                                    "ENGINE=InnoDB;");
                
                stmt.executeUpdate("CREATE TABLE buy(" + 
                                    "cid CHAR(12)," +
                                    "pid CHAR(8)," +
                                    "quantity INT," +
                                    "PRIMARY KEY(cid, pid, quantity)," +
                                    "FOREIGN KEY (cid) REFERENCES customer(cid) ON DELETE CASCADE," +
                                    "FOREIGN KEY (pid) REFERENCES product(pid) ON DELETE CASCADE) " +
                                    "ENGINE=InnoDB;");

                stmt.executeUpdate("INSERT INTO customer VALUES" + 
                                "('C101', 'Ali', '1997.03.03', 'Besiktas', 'Istanbul', 114.50)," +
                                "('C102', 'Veli', '2001.05.19', 'Bilkent', 'Ankara', 200.00)," +
                                "('C103', 'Ayse', '1972.04.23', 'Tunali', 'Ankara', 15.00)," +
                                "('C104', 'Alice', '1990.10.29', 'Meltem', 'Antalya', 1024.00)," +
                                "('C105', 'Bob', '1987.08.30', 'Stretford', 'Manchester', 15.00);");

                stmt.executeUpdate("INSERT INTO product VALUES" + 
                                "('P101', 'powerbank', 300.00, 2)," +
                                "('P102', 'battery', 5.50, 5)," +
                                "('P103', 'laptop', 3500.00, 10)," +
                                "('P104', 'mirror', 10.75, 50)," +
                                "('P105', 'notebook', 3.85, 100)," +
                                "('P106', 'carpet', 50.99, 1)," +
                                "('P107', 'lawn mower', 1025.00, 3);");

                 stmt.executeUpdate("INSERT INTO buy VALUES" + 
                                "('C101', 'P105', 2)," +
                                "('C102', 'P105', 2)," +
                                "('C103', 'P105', 5)," +
                                "('C101', 'P101', 1)," +
                                "('C102', 'P102', 4)," +
                                "('C105', 'P104', 1);");
                                
                ResultSet rs = stmt.executeQuery("SELECT bdate, adress, city FROM customer WHERE wallet <= ALL(SELECT wallet FROM customer);");
                System.out.println("Customers with lowest wallet:");

                while(rs.next())
                {
                    System.out.println(rs.getString(1));
                    System.out.println(rs.getString(2));
                    System.out.println(rs.getString(3));
                }
                
                rs = stmt.executeQuery("SELECT C.cname FROM customer C, product P, buy B WHERE C.cid = B.cid AND B.pid = P.pid AND P.price < 10 " +
                                    "GROUP BY C.cid HAVING COUNT(DISTINCT P.pid) = (SELECT COUNT(*) FROM product PX WHERE PX.price < 10);");
                   //WHERE XY.pid1 IS NULL AND XY.cid = A.cid (SELECT * FROM product PX WHERE PX.price < 10)
                System.out.println("Customer that had bought all products which are cheaper than 10 dollar:");
                while(rs.next())
                {
                    System.out.println(rs.getString(1));
                }
                
                rs = stmt.executeQuery("SELECT P.pname AS O FROM product P, (SELECT B.pid AS pid FROM customer C, buy B WHERE C.cid = B.cid GROUP BY B.pid HAVING COUNT(distinct C.cid) >= 3) AS X WHERE P.pid = X.pid;");
                System.out.println("Product names which had been boughted by at least 3 customers:");
                while(rs.next())
                {
                    System.out.println(rs.getString(1));
                }

                rs = stmt.executeQuery("SELECT P.pname FROM product P, customer C, (SELECT MAX(A.bdate) AS minDate FROM customer A) AS MINCUSTOMER WHERE C.bdate = MINCUSTOMER.minDate AND P.price <= C.wallet;");
                System.out.println("Product names that can be bought by the youngest customer:");
                while(rs.next())
                {
                    System.out.println(rs.getString(1));
                }
                
                rs = stmt.executeQuery("SELECT A.cname from customer A, (SELECT X.cid as cid FROM (SELECT C.cid, SUM(P.price * B.quantity) as sum FROM customer C, product P, buy B WHERE C.cid = B.cid AND B.pid = P.pid GROUP BY C.cid) AS X HAVING MAX(X.sum)) AS Y WHERE Y.cid = A.cid");
                System.out.println("Customer names who spent maximum money:");
                while(rs.next())
                {
                    System.out.println(rs.getString(1));
                }
            }catch(SQLException sqle)
            {
                System.out.println(sqle.getMessage());
            }
        }
    }
}