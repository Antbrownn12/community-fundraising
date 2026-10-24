# Bronx Community Helping Hands

A CIS 344 group project for a fictional community fundraising
organization. The application manages donors, campaigns, and sample
donations.

## Features

- Add donors and view donor records.
- Prevent duplicate donor email addresses.
- Create campaigns with positive fundraising goals and valid dates.
- Record sample donations and generate confirmation codes.
- View donation history using joins across related tables.
- View campaign totals, including campaigns with no donations.
- Display forms and reports with a shared responsive stylesheet.

## Technologies

- PHP with PDO MySQL
- MySQL 8.0.16 or later
- HTML and CSS
- JavaScript
- Apache through XAMPP
- MySQL Workbench for database modeling

## Database Design

The database is named community_fundraising.

It contains four tables:

- donors
- campaigns
- donations
- donation_processing

Each donation references one donor and one campaign. A unique
donation_id in donation_processing allows at most one processing
record per donation.

The database model is in:
docs/Community_Fundraising_Model.mwb

## Local Setup

1. Install PHP with PDO MySQL, Apache, and MySQL.
   This project was developed using XAMPP for Apache/PHP
   and a separate MySQL 8.0.46 server.

2. Copy the project folder into Apache's document root.
   With the standard macOS XAMPP installation, use:
   /Applications/XAMPP/xamppfiles/htdocs/community_fundraising

3. Connect to the intended MySQL server using MySQL Workbench.

4. For a fresh installation, run these scripts in order:
   - sql/community_fundraising.sql
   - sql/fundraising_constraints.sql
   - sql/fundraising_sample_data.sql

   Run the sample data script once. If any statement fails during
   its donation transaction, run ROLLBACK instead of COMMIT and
   resolve the error before retrying.

5. Copy db.example.php to db.php.

6. In db.php, enter your local MySQL username and password.
   Confirm the hostname, port, and database name.

7. Start Apache and ensure the intended MySQL server is running.
   When using a separate MySQL server, leave XAMPP's bundled
   database server stopped to avoid a port conflict.

8. Open:
   http://localhost/community_fundraising/

## Reports

The report queries are saved in:
sql/fundraising_reports.sql

The donation history report combines donors, campaigns,
donations, and processing confirmations.

The campaign progress report uses LEFT JOIN, SUM, and COALESCE
to show all campaigns and calculate their totals.

## Validation and Security

- Prepared statements handle form inserts.
- PHP validates submitted values.
- Session tokens help protect forms against CSRF.
- Output escaping protects displayed database text.
- Unique email addresses prevent duplicate donor emails.
- Foreign keys require valid related records.
- CHECK constraints require positive goals and donation amounts
  and valid campaign date order.
- A transaction saves a donation and confirmation together.
- Database credentials are excluded through .gitignore.

## Testing

To check the application:

1. Open all six links on the home page.
2. Add a fictional donor and confirm the donor appears.
3. Try the same email again and confirm it is rejected.
4. Create a campaign and confirm it appears with zero raised.
5. Try an end date before the start date and confirm it is blocked.
6. Record a sample donation and confirm its history entry and code.
7. Confirm the campaign total increases by the donation amount.
8. Narrow the browser and check form layout and table scrolling.

Successful donation saving has been tested. A forced failure of
the confirmation insert still needs a separate rollback test.

## Project Scope

This is a local classroom demonstration using fictional data.
It records sample contributions and does not process real payments
or store card or bank account details.

The application currently has no user login or access controls
and is intended for local demonstration.

## Credentials

Do not upload db.php or real passwords.

db.example.php contains placeholders for local configuration.
Each person running the project must create their own db.php.