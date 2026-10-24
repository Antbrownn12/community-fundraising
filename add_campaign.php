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
    <title>Create Campaign | Bronx Community Helping Hands</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="container">
    <h1>Create a Fundraising Campaign</h1>
    <p><a href="index.php">Back to Home</a></p>

    <form action="save_campaign.php" method="post">
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
            <label for="campaign_name">Campaign Name *</label><br>
            <input type="text" id="campaign_name"
                   name="campaign_name" maxlength="100" required>
        </p>

        <p>
            <label for="description">Description</label><br>
            <textarea id="description" name="description"
                      rows="4" cols="40" maxlength="5000"></textarea>
        </p>

        <p>
            <label for="goal_amount">Fundraising Goal in Dollars *</label><br>
            <input type="number" id="goal_amount"
                   name="goal_amount" min="0.01"
                   max="99999999.99" step="0.01" required>
        </p>

        <p>
            <label for="start_date">Start Date *</label><br>
            <input type="date" id="start_date"
                   name="start_date" required>
        </p>

        <p>
            <label for="end_date">End Date *</label><br>
            <input type="date" id="end_date"
                   name="end_date" required>
        </p>

        <p>* Required fields</p>

        <button type="submit">Create Campaign</button>
    </form>
</main>

<script>
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function checkDates() {
        endDate.min = startDate.value;
        endDate.setCustomValidity('');

        if (
            startDate.value &&
            endDate.value &&
            endDate.value < startDate.value
        ) {
            endDate.setCustomValidity(
                'The end date must be on or after the start date.'
            );
        }
    }

    startDate.addEventListener('change', checkDates);
    endDate.addEventListener('change', checkDates);

    checkDates();
</script>
</body>
</html>