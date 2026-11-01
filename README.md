# Retail Database Application

A PHP and Oracle SQL coursework project for browsing retail data and managing customers, suppliers, stores, employees, products, purchases, deliveries and collections.

## Included work

- `schema.sql`: table definitions, sample records and database logic from the original SQL file.
- `Home.html`: application home page.
- Numbered PHP pages: entity-specific browsing and application operations.
- `search.php`: cross-entity search page.
- `PROJECT_REPORT.md`: the supplied project report, with its access-information section omitted.

## Technology

PHP with the OCI8 extension, Oracle Database, SQL and HTML.

## Local configuration

Create an Oracle database schema and load `schema.sql` using your Oracle SQL client. Configure these environment variables for the PHP process:

```text
ORACLE_USER
ORACLE_PASSWORD
ORACLE_CONNECTION_STRING
```

The connection string should identify your own Oracle service. All nine PHP database connections read these values from the environment in the public copy.

With PHP and OCI8 configured, serve the project directory through a local PHP-enabled web server and open `Home.html`.

## Project focus

The work covers relational modelling, entity relationships, SQL constraints/triggers, data browsing, searching and delivery/collection logic. The SQL script contains fictional coursework sample records.

This is the original academic implementation, with connection credentials removed. PHP/Oracle integration was not executed in the upload environment.

Virginia Eduwa — academic database project.
