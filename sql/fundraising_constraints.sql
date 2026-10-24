USE community_fundraising;

ALTER TABLE campaigns
ADD CONSTRAINT chk_campaign_goal
CHECK (goal_amount > 0),
ADD CONSTRAINT chk_campaign_dates
CHECK (end_date >= start_date);

ALTER TABLE donations
ADD CONSTRAINT chk_donation_amount
CHECK (amount > 0);