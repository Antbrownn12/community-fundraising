<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_donation.php');
    exit;
}

// Check the form's security token.
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

$donorId = filter_var(
    readField('donor_id'),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$campaignId = filter_var(
    readField('campaign_id'),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$amount = readField('amount');
$paymentMethod = readField('payment_method');

// Validate the selected IDs, amount, and payment method.
if ($donorId === false || $campaignId === false) {
    http_response_code(400);
    exit('Go back and choose a valid donor and campaign.');
}

if (
    !preg_match('/^[0-9]{1,8}(\.[0-9]{1,2})?$/', $amount) ||
    (float) $amount <= 0
) {
    http_response_code(400);
    exit('Go back and enter an amount between $0.01 and $99,999,999.99, using no commas.');
}

if (!in_array($paymentMethod, ['Cash', 'Check', 'Demo Card'], true)) {
    http_response_code(400);
    exit('Go back and select a valid payment method.');
}

require __DIR__ . '/db.php';

try {
    $pdo->beginTransaction();

    // Save the donation.
    $statement = $pdo->prepare(
        'INSERT INTO donations
         (donor_id, campaign_id, amount, payment_method)
         VALUES (?, ?, ?, ?)'
    );

    $statement->execute([
        $donorId,
        $campaignId,
        $amount,
        $paymentMethod
    ]);

    $donationId = $pdo->lastInsertId();
    $confirmationCode = 'DEMO-' . $donationId;

    // Save its matching confirmation.
    $statement = $pdo->prepare(
        'INSERT INTO donation_processing
         (donation_id, confirmation_code)
         VALUES (?, ?)'
    );

    $statement->execute([
        $donationId,
        $confirmationCode
    ]);

    // Save both records together.
    $pdo->commit();

    header('Location: donations.php', true, 303);
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    http_response_code(500);
    exit('The donation could not be saved. No part of this transaction was saved. Check the donor and campaign, then try again.');
}