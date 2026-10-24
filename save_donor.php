<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_donor.php');
    exit;
}

// Check that the submission came with a valid security token.
$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('Invalid submission. Go back and reload the form.');
}

// Read each field and remove spaces from its ends.
function readField($name) {
    $value = $_POST[$name] ?? '';
    return is_string($value) ? trim($value) : '';
}

$firstName = readField('first_name');
$lastName = readField('last_name');
$email = readField('email');
$phone = readField('phone');

// Validate on the server, even if the browser already checked.
if (
    $firstName === '' ||
    $lastName === '' ||
    strlen($firstName) > 50 ||
    strlen($lastName) > 50 ||
    strlen($email) > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($phone) > 20
) {
    http_response_code(400);
    exit('Please go back and enter valid names, email, and phone details.');
}

require __DIR__ . '/db.php';

try {
    $statement = $pdo->prepare(
        'INSERT INTO donors
         (first_name, last_name, email, phone)
         VALUES (?, ?, ?, ?)'
    );

    $statement->execute([
        $firstName,
        $lastName,
        $email,
        $phone === '' ? null : $phone
    ]);

    // Open the donor list after a successful save.
    header('Location: donors.php', true, 303);
    exit;

} catch (PDOException $e) {
    if (($e->errorInfo[1] ?? null) == 1062) {
        http_response_code(409);
        exit('That email is already registered. Go back and use another email.');
    }

    error_log($e->getMessage());
    http_response_code(500);
    exit('The donor could not be saved. Please try again later.');
}