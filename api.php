<?php
require 'database.php';

//Create a connection
$db = new Database();

//Get the token
$token = isset($_GET['token']) ? addSlashes($_GET['token']) : '';

if(empty($token))
{
    echo json_encode(['code' => 5, 'message' => 'Unauthorized to access this page.']);
    return;
}

//Validate the token in the database
$where = "token = '$token' AND is_active = 'Y'";
$result = $db->getWhere('tokens', $where);

if(empty($result))
{
    echo json_encode(['code' => 4, 'message' => 'Invalid token. Please contact us.']);
    return;
}

//Check if client send a post method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['code' => 3, 'message' => 'Invalid request. Please contact us.']);
    return;
}

$email = isset($_POST['email']) ? $_POST['email'] : '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['code' => 2, 'message' => 'Email address is required.']);
    return;
}

//Check if there's an existing email
$where = "email = '$email'";
$result = $db->getWhere('newsletters', $where);

if(!empty($result))
{
    echo json_encode(['code' => 4, 'message' => 'You already subscribe in our newsletter']);
    return;
}

$dateTime = date('Y-m-d H:i:s');


$fields = ["email" => $email, "created_at" => $dateTime, "updated_at" => $dateTime];
//Save data
$result = $db->insertData('newsletters', $fields);

if(!$result)
{
    echo json_encode(['code' => 1, 'message' => 'Internal error. Please contact us.']);
    return;
}

echo json_encode(['code' => 0, 'message' => 'Successful!']);
return;