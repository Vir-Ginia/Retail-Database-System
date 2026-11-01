# Database Project Report

 Report 

The database is structured around several key entities:

- Customer: Tracks customer details and personal information.

- Product: Contains information about items for sale, including their dimensions in meters cubed to show the total volume area it would cover in a delivery van.

- Purchase: Records details of each transaction, including customer and product information. As well as calculating postage cost according to the dimensions above 0.5 meters cubed because that is the average maximum dimension of a car boot meaning that Collecting such products would prove impossible in most cases. The initial default 5 pounds for postage cost was for taxing into consideration mileages from the store to the client’s address.

- Delivery and Collection: Columns were made according to instructions including the instruction that only delivery personnel are allowed to carry out deliveries which I coded for in SQL. triggers ensure things like making sure specific product dimensions are checked before a delivery record is inserted.

- Supplier and Employee: Maintain information about the product suppliers and the store employees, respectively though these were the least utilised information throughout other tables.

Application Features Appraisal

- Home screen: allowed you to browse the entire database for general browsing

- Every other page created a table browsing: You can view data by categories/columns of your choice and in the order of your choice too.

- Navigation bar to access all pages other than the general database search page which can only be accessed via search bar in the home page, 

Achievements

Comprehensive Database Schema: My database schema represents the IDEA company’s business model, with separate tables for Customers, Suppliers, Stores, Employees, Products, Purchases, Deliveries, and Collections as well as a general one. This structure allows general and specific searching.

Detailed Entity Relationships: I have established relationships through primary and foreign keys. For instance, foreign keys link purchases to customers and products, reflecting real-world transactions.

Automation through Triggers: Several triggers have been made to put in place business rules and calculations. Examples include:

Automatically updating purchase prices and postage costs upon inserting a new purchase record.

Ensuring that only delivery coordinators handle deliveries.

Validating that products with dimensions greater than 0.5 cubic meters are not available for collection.

Limitations

There is no refund system, because I didn’t know how to implement that into the entity.

The layout of my code is screen size dependant; Meaning, it would not retain its form in a smaller screen.

However, overall, my program and code does everything requested from the task.

Access credentials have been omitted from this public copy.
