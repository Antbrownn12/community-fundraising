<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_campaign.php');
    exit;
}

// Validate the security token.
$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('Invalid submission. Go back and reload the form.');
}

function readField($name) {
    $value = $_POST[$name] ?? '';
    return is_string($value) ? trim($value) : '';
}

function validDate($value) {
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

    return $date !== false
        && $date->format('Y-m-d') === $value
        && $value >= '1000-01-01'
        && $value <= '9999-12-31';
}

$name = readField('campaign_name');
$description = readField('description');
$goal = readField('goal_amount');
$startDate = readField('start_date');
$endDate = readField('end_date');

// Validate the campaign name and description.
if ($name === '' || strlen($name) > 100 ||
    strlen($description) > 5000) {
    http_response_code(400);
    exit('Go back and enter a campaign name of up to 100 characters and a description of up to 5000.');
}

// Accept positive amounts with up to two decimal places.
if (
    !preg_match('/^[0-9]{1,8}(\.[0-9]{1,2})?$/', $goal) ||
    (float) $goal <= 0
) {
    http_response_code(400);
    exit('Go back and enter a goal between $0.01 and $99,999,999.99, using no commas.');
}

// Validate both dates and their order.
if (
    !validDate($startDate) ||
    !validDate($endDate) ||
    $endDate < $startDate
) {
    http_response_code(400);
    exit('Go back and enter valid dates. The end date must be on or after the start date.');
}

require __DIR__ . '/db.php';

try {
    $statement = $pdo->prepare(
        'INSERT INTO campaigns
         (campaign_name, description, goal_amount, start_date, end_date)
         VALUES (?, ?, ?, ?, ?)'
    );

    $statement->execute([
        $name,
        $description === '' ? null : $description,
        $goal,
        $startDate,
        $endDate
    ]);

    header('Location: campaigns.php', true, 303);
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('The campaign could not be saved. Please try again later.');
}