
<?php

/*
 *  LORENZO TORELLI - 10/2018
 *
 * I mainly just cleaned up the syntax, replaced deprecated code, and tried to make the code overall more
 * secure with checks, and try/catch wrapping. I did not check validity, and I assume const.php and db.php hold
 * all the necessary information such as connection info.
 *
 */

///////////////////////////////////////
/// Includes
///

    include "const.php";
    include "db.php";

///////////////////////////////////////
/// SQL queries
///

    if (!empty($_POST['company_id'])) {

        //Create mysqli instance | Assuming db.php holds connection data.
        //Wrapped in a try/catch to redirect in the event that the connection to the db failed
        try
        {

            $connection = ConnectToDB("host", "username", "password", "db");
        }
        catch (Exception $ex)
        {
            echo $ex;
            header("Location: 500.html");

        }

        //Ignore sql queries if there was a connection error
        if (!$connection->connect_errno) {

            if ($_POST['company_id'] <> 0) {

                $sql = "UPDATE company 
                    SET name = '{$_POST['name']}', address = '{$_POST['address']}'
                    WHERE company_id = '{$_POST['company_id']}'";

                $result = $connection->query($sql);

            } else {

                $sql = "INSERT INTO company 
                    SET name = '{$_POST['name']}', address = '{$_POST['address']}'";

                $result = $connection->query($sql);

            }

            if (!empty($_GET['company_id']))
            {

                $sql = "SELECT * 
                FROM company 
                WHERE company_id = '{$_GET['company_id']}'";

                $result = $connection->query($sql);

            }

        }
    }

    /**
     * @param string $host ip
     * @param string $username username
     * @param string $password password
     * @param string $db database name
     * @return mysqli object
     * @throws Exception
     */
    function ConnectToDB(string $host, string $username, string $password, string $db )
    {

        $tempConn =  new mysqli($host, $username, $password, $db);

        if ($tempConn->connect_errno)
        {
            throw new Exception("Connection to server failed");
        }
        else
        {
            return $tempConn;
        }
    }

?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Company</title>
            <meta name="description" content="This is an example of HTML5 header element. header element is the head of a new section." />
        </head>
        <body>

            <?= (empty ($_GET['company_id'])) ? "<h1>Please add your company</h1>" : "<h1>Edit your company!</h1>"?>

            <form action="messy-code.php" action="post">
                <label>Name</label>
                <input id="name" type="text" name="name" value="<?php echo $result['name']?>" />
                <br />
                <label>Address</label>
                <input id="address" type="text" name="address" value="<?php echo $result['address']?>" />
                <br />
                <label>Company</label>
                <input id="company_id" type="text" name="company_id" value="<?php echo $result['company_id']?>" />
                <br />
                <input type="submit" name="submit" value="submit" />
            </form>
        </body>
    </html>