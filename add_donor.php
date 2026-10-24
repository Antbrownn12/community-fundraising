<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Donor | Bronx Community Helping Hands</title>
    <title>Add Donor | Bronx Community Helping Hands</title>
<link rel="stylesheet" href="style.css">
</head>
</head>

<body>
    <main class="container">
    <h1>Add a New Donor</h1>
    <p><a href="index.php">Back to Home</a></p>

    <form action="save_donor.php" method="post">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <p>
            <label for="first_name">First Name *</label><br>
            <input type="text" id="first_name"
                   name="first_name" maxlength="50" required>
        </p>

        <p>
            <label for="last_name">Last Name *</label><br>
            <input type="text" id="last_name"
                   name="last_name" maxlength="50" required>
        </p>

        <p>
            <label for="email">Email *</label><br>
            <input type="email" id="email"
                   name="email" maxlength="100" required>
        </p>

        <p>
            <label for="phone">Phone Number</label><br>
            <input type="tel" id="phone"
                   name="phone" maxlength="20">
        </p>

        <p>* Required fields</p>

        <button type="submit">Save Donor</button>
    </form>
</main>
</body>
</html>