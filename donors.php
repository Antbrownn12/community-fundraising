<?php
require __DIR__ . '/db.php';

$donors = $pdo->query(
    'SELECT donor_id, first_name, last_name, email, phone
     FROM donors
     ORDER BY donor_id'
)->fetchAll();

function escape($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donors | Bronx Community Helping Hands</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
    <h1>Our Donors</h1>
    <p><a href="index.php">Back to Home</a></p>

   <div class="table-wrap">
<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donors as $donor): ?>
                <tr>
                    <td><?= escape($donor['donor_id']) ?></td>
                    <td><?= escape($donor['first_name']) ?></td>
                    <td><?= escape($donor['last_name']) ?></td>
                    <td><?= escape($donor['email']) ?></td>
                    <td><?= escape($donor['phone'] ?? 'Not provided') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
            </main>
</body>
</html>