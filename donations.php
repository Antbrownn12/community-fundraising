<?php
require __DIR__ . '/db.php';

$sql = "
    SELECT
        d.donation_id,
        CONCAT(dr.first_name, ' ', dr.last_name) AS donor_name,
        c.campaign_name,
        d.amount,
        d.payment_method,
        d.donation_date,
        p.confirmation_code
    FROM donations AS d
    JOIN donors AS dr
        ON d.donor_id = dr.donor_id
    JOIN campaigns AS c
        ON d.campaign_id = c.campaign_id
    LEFT JOIN donation_processing AS p
        ON d.donation_id = p.donation_id
    ORDER BY d.donation_date DESC, d.donation_id DESC
";

$donations = $pdo->query($sql)->fetchAll();

function escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations | Bronx Community Helping Hands</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
    <h1>Donation History</h1>
    <p><a href="index.php">Back to Home</a></p>

    <div class="table-wrap">
<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Donor</th>
                <th>Campaign</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Date and Time</th>
                <th>Confirmation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?= escape($donation['donation_id']) ?></td>
                    <td><?= escape($donation['donor_name']) ?></td>
                    <td><?= escape($donation['campaign_name']) ?></td>
                    <td>$<?= number_format((float) $donation['amount'], 2) ?></td>
                    <td><?= escape($donation['payment_method']) ?></td>
                    <td><?= escape($donation['donation_date']) ?></td>
                    <td><?= escape($donation['confirmation_code'] ?? 'Not recorded') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
            </main>
</body>
</html>