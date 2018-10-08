<?php

/*
 * LORENZO TORELLI - 10/2018
 *
 * Connection class which handles connection to the SQL database server, as well as queries.
 *
 */

///////////////////////////////////////
/// Class
///

    class Connection
    {
    ///////////////////////////////////////
    /// Variables
    ///
        private $host       =   "db4free.net";
        private $username   =   "MyAwesomeUsername";
        private $password   =   "YoullNeverGuessMyPassword";
        private $database   =   "assignmentdb";

        public $connectedDB;

        public function __construct(string $host = null, string $username = null, string $password = null, string $database = null)
        {

            $this->host     =   $host       ??  $this->host;
            $this->username =   $username   ??  $this->username;
            $this->password =   $password   ??  $this->password;
            $this->database =   $database   ??  $this->database;


            $this->connectedDB = $this->ConnectToDB($this->host, $this->username, $this->password, $this->database);

        }

        ///////////////////////////////////////
        /// Functions
        ///

        public function Query(string $sql)
        {
            return $this->connectedDB->query($sql);
        }

        /**
         * @param string $host hostname
         * @param string $username
         * @param string $password
         * @param string $db database name
         * @return mysqli
         * @throws Exception
         */
        private function ConnectToDB(string $host, string $username, string $password, string $db )
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

    }