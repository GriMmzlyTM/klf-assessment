<?php

/*
 * LORENZO TORELLI - 10/2018
 *
 * The user class used to Create, Read, Update, and Destroy.
 *
 * I'm sorry if it looks a bit... CRUDdy...
 *
 * I'm funny, shut up.
 *
 */

///////////////////////////////////////
/// Includes
///

include "connection.php";

///////////////////////////////////////
/// Class
///

    class User
    {
    ///////////////////////////////////////
    /// Variables
    ///
        public $database;
        public $table;

        public function __construct(Connection $database, $table)
        {
            $this->database = &$database;

            $this->table = $table;

        }

        ///////////////////////////////////////
        /// Functions
        ///

        /**
         * @param array $fields
         * @return string ID
         */
        public function Create(array $fields)
        {
            $list = [];

            foreach($fields as $key => $value)
            {
                $list[] = "{$key} = '{$value}'";
            }

            $list = implode(",",$list);

            $this->database->Query( "INSERT INTO {$this->table}
                                          SET {$list}");

            return $this->database->connectedDB->insert_id;

        }

        /**
         * @param string $id optional ID param
         * @return array|
         */
        public function Read(string $id = null)
        {

            return $id == null ?    $this->database->Query("SELECT * FROM {$this->table}")->fetch_all() :
                                    $this->database->Query("SELECT * FROM {$this->table} WHERE user_id = '{$id}'")->fetch_assoc();

        }

        /**
         * @param string $user_id The ID of the user to update
         * @param array $fields  a key-value pair array holding the data to update
         */
        public function Update(string $user_id, array $fields)
        {
            $list = [];

            foreach($fields as $key => $value)
            {
                $list[] = "{$key} = '{$value}'";
            }

            $list = implode(",",$list);

            $this->database->Query( "UPDATE {$this->table}
                                          SET {$list}
                                          WHERE user_id = '{$user_id}'");
        }

        /**
         * @param array $ids Array of ID's you wish to
         */
        public function Delete(array $ids)
        {
            foreach ($ids as $id)
            {

                $this->database->Query("DELETE FROM {$this->table}
                                             WHERE user_id = '{$id}'");

            }
        }

    }