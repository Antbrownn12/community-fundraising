<?php
session_start();
require __DIR__ . '/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$donors = $pdo->query(
    'SELECT donor_id, first_name, last_name, email
     FROM donors
     ORDER BY last_name, first_name'
)->fetchAll();

$campaigns = $pdo->query(
    'SELECT campaign_id, campaign_name
     FROM campaigns
     ORDER BY campaign_name'
)->fetchAll();

function escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Donation | Bronx Community Helping Hands</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="container">
    <h1>Record a Donation</h1>
    <p><a href="index.php">Back to Home</a></p>
    <p>Enter a sample contribution for this project.</p>

    <form action="save_donation.php" method="post">
        <input type="hidden" name="csrf_token"
               value="<?= escape($_SESSION['csrf_token']) ?>">

        <p>
            <label for="donor_id">Donor *</label><br>
            <select id="donor_id" name="donor_id" required>
                <option value="">Choose a donor</option>

                <?php foreach ($donors as $donor): ?>
                    <option value="<?= escape($donor['donor_id']) ?>">
                        <?= escape(
                            $donor['first_name'] . ' ' .
                            $donor['last_name'] . ' — ' .
                            $donor['email']
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="campaign_id">Campaign *</label><br>
            <select id="campaign_id" name="campaign_id" required>
                <option value="">Choose a campaign</option>

                <?php foreach ($campaigns as $campaign): ?>
                    <option value="<?= escape($campaign['campaign_id']) ?>">
                        <?= escape($campaign['campaign_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="amount">Donation Amount in Dollars *</label><br>
            <input type="number" id="amount" name="amount"
                   min="0.01" max="99999999.99"
                   step="0.01" required>
        </p>

        <p>
            <label for="payment_method">Payment Method *</label><br>
            <select id="payment_method" name="payment_method" required>
                <option value="">Choose a payment method</option>
                <option value="Cash">Cash</option>
                <option value="Check">Check</option>
                <option value="Demo Card">Demo Card</option>
            </select>
        </p>

        <p>* Required fields</p>

        <button type="submit">Record Donation</button>
    </form>
</main>
</body>
</html>