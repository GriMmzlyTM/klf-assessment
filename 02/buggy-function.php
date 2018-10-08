<?php

/*
 *  LORENZO TORELLI - 10/2018
 *
 *  My main goal here was to fix the bugs, as well as clean the code.
 *  I added the necessary includes/requires for the PHPMailer.
 *
 *  I created the following functions:
 *      ChargeCard($order_id) - function that is called to instantiate a new mysqli instance, and connect to the DB
 *      CompleteOrder($transactionData, $connection) - Sets the transaction card info, and charges the card. Then calls
 *          SendConfirmation();
 *      SendConfirmation($transactionData, $connection) - Sends confirmation email, and closes the mysqli connection
 *
 *  I created the following class:
 *      TransactionData - Holds pointers to transaction and order_id
 *
 */

///////////////////////////////////////
/// Includes
///

    //PHPMailer is being used in the script
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include "db.php";

    require 'vendor/autoload.php';

///////////////////////////////////////
/// Functions
///

    //Function called when charging card. This function then completes the order, and sends the confirmation
    //if the connection to the database succeeded.
    function ChargeCard($order_ID){

        //Connect to DB. ConnectToDb throws exception if unable to connect
        try
        {
            $connection = ConnectToDB("host", "username", "password", "db");

            $transaction = new Transaction();

            $transactionData = new TransactionData($transaction, $order_ID);

            CompleteOrder($transactionData, $connection);

        }
        catch (Exception $ex)
        {

            echo $ex;
            header("Location: 500.html");

        }

    }

    //Complete the order, and send the confirmation
    /**
     * @param TransactionData $data transaction object
     * @param mysqli $connection database connection
     */
    function CompleteOrder(TransactionData $data, mysqli $connection) {

        //Order status 2 = 'complete';
        $sql    =   "UPDATE order SET status_id = 2 WHERE order_id = {$data->order_id}";
        $result =   $connection->query($sql);

        //Charge credit card
        $data->transaction->cardholder    =   $_POST['cardholder'];
        $data->transaction->number        =   $_POST['number'];
        $data->transaction->exp_month     =   $_POST['exp_month'];
        $data->transaction->exp_year      =   $_POST['exp_year'];
        $data->transaction->cvv           =   $_POST['cvv'];
        $data->transaction->type          =   $_POST['type'];

        $transaction_id = $data->transaction->charge();//Function returns transaction_id

        $sql2 = "UPDATE order SET transaction_id = '{$transaction_id}'";
        $connection->query($sql2);

        SendConfirmation($data, $connection);

    }

    /**
     * @param TransactionData $data transaction object
     * @param mysqli $connection database connection
     */
    function SendConfirmation(TransactionData $data, mysqli $connection)
    {

        //PHPMailer setup
        $mail = new PHPMailer(true);

        $mail->isSMTP();

        $mail->Host         =   'smtp1.example.com;smtp2.example.com';
        $mail->SMTPAuth     =   true;
        $mail->Username     =   'user@example.com';
        $mail->Password     =   'secret';
        $mail->SMTPSecure   =   'tls';
        $mail->Port         =   587;

        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');

        $sql3   =   "SELECT * FROM order WHERE order_id =  '{$data->order_id}'";
        $connection->query($sql3);

        $order  =   $connection->use_result();

        $mail->addAddress($order['email'], $order['customer_name']);

        $mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(true);
        $mail->Subject  = 'Your order is complete!';
        $mail->Body     = "Thank you for completing your order with us! Here's your transaction ID: {$data->transaction->getId()}";

        $mail->send();

        $connection->close();

        echo "Okay!";
    }

///////////////////////////////////////
/// Classes
///

    class TransactionData
    {
        public $transaction;
        public $order_id;

        public function __construct($transaction, $order_id)
        {
            $this->transaction  =   &$transaction;
            $this->order_id     =   &$order_id;
        }

    }

///////////////////////////////////////
/// Connection function
///

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
