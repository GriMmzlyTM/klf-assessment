<?php

///////////////////////////////////////
/// Includes
///

    include_once "user.php";

    $connection = new Connection();

    $user = new User($connection,"users");

    //Create a new user
    $id = $user->Create([
        'first_name'    =>  'testName',
        'last_name'     =>  'lastTest',
        'salt'          =>  'pepper',
        'job_title'     =>  'Certified Badass',
        'email'         =>  'MyAwesomeEmail@gmail.com'
    ]);

    //Update the user by ID
    $user->Update($id,[
        'first_name'    =>  'ultra cool edited name',
        'last_name'     =>  'lul',
        'salt'          =>  'Salt && PEPPA',
        'job_title'     =>  'Professional Certified Badass'
    ]);

    //Read the user data by ID (If no ID is provided, it will return all users)
    print_r($user->Read($id));

    //Delete created user by ID
    $user->Delete([$id]);