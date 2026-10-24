USE community_fundraising;

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
JOIN donation_processing AS p
    ON d.donation_id = p.donation_id;
    
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
ORDER BY c.campaign_id;