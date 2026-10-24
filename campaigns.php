<?php
require __DIR__ . '/db.php';

$sql = "
    SELECT
        c.campaign_id,
        c.campaign_name,
        c.goal_amount,
        COALESCE(SUM(d.amount), 0.00) AS total_raised,
        GREATEST(
            c.goal_amount - COALESCE(SUM(d.amount), 0.00),
            0.00
        ) AS amount_remaining
    FROM campaigns AS c
    LEFT JOIN donations AS d
        ON c.campaign_id = d.campaign_id
    GROUP BY
        c.campaign_id,
        c.campaign_name,
        c.goal_amount
    ORDER BY c.campaign_id
";

$campaigns = $pdo->query($sql)->fetchAll();

function escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaigns | Bronx Community Helping Hands</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
    <h1>Fundraising Campaigns</h1>
    <p><a href="index.php">Back to Home</a></p>

   <div class="table-wrap">
<table>
        <thead>
            <tr>
                <th>Campaign</th>
                <th>Goal</th>
                <th>Total Raised</th>
                <th>Amount Remaining</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($campaigns as $campaign): ?>
                <tr>
                    <td><?= escape($campaign['campaign_name']) ?></td>
                    <td>$<?= number_format((float) $campaign['goal_amount'], 2) ?></td>
                    <td>$<?= number_format((float) $campaign['total_raised'], 2) ?></td>
                    <td>$<?= number_format((float) $campaign['amount_remaining'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
            </main>
</body>
</html>