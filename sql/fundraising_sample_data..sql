USE community_fundraising;

INSERT INTO donors (first_name, last_name, email, phone)
VALUES
('Antoine', 'Brown', 'antoine@example.com', '718-555-0100'),
('Maya', 'Jones', 'maya@example.com', '718-555-0101'),
('Carlos', 'Rivera', 'carlos@example.com', '718-555-0102'),
('Jasmine', 'Williams', 'jasmine@example.com', NULL),
('David', 'Chen', 'david@example.com', '718-555-0104');

SELECT * FROM donors;

INSERT INTO campaigns
(campaign_name, description, goal_amount, start_date, end_date)
VALUES
('School Supply Drive',
 'Purchase school supplies for local students.',
 2000.00, '2026-10-01', '2026-11-30'),

('Community Meal Program',
 'Provide meals for local families.',
 1500.00, '2026-10-01', '2026-12-31'),

('Winter Clothing Drive',
 'Provide winter coats and clothing for families.',
 3000.00, '2026-10-15', '2026-12-15');

SELECT * FROM campaigns;

START TRANSACTION;

INSERT INTO donations
(donor_id, campaign_id, amount, payment_method)
VALUES (
    (SELECT donor_id
     FROM donors
     WHERE email = 'antoine@example.com'),

    (SELECT campaign_id
     FROM campaigns
     WHERE campaign_name = 'School Supply Drive'),

    50.00,
    'Cash'
);

SET @new_donation_id = LAST_INSERT_ID();

INSERT INTO donation_processing
(donation_id, confirmation_code)
VALUES (
    @new_donation_id,
    CONCAT('DEMO-', @new_donation_id)
);
COMMIT;

SELECT * FROM donations;
SELECT * FROM donation_processing;