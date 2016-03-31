Foreign Key Validation
======================

This library will generate sql to test for invalid data based on relationships between related tables.  This can happen if you overlay an Doctrine ORM on an existing database then use that metadata to create the database and import the old data in.

At import foreign key checks will be ignored.  This corrects for that inability to check relationships upon import.
