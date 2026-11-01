
CREATE TABLE Customer(
	Customer_no INT PRIMARY KEY,
	Customer_name VARCHAR (100),
	Customer_address VARCHAR (100),
	Email VARCHAR (100),
	Phone VARCHAR (11),
	Last_four_cardNumbers VARCHAR (4)
);
INSERT INTO Customer VALUES (1, 'John Alpha', '1 Alpha Street', 'alpha@gmail.com', '07123456789', '1234');
INSERT INTO Customer VALUES (2, 'Jane Bravo', '2 Bravo Lane', 'bravo@gmail.com', '07234567890', '2345');
INSERT INTO Customer VALUES (3, 'Jake Charlie', '3 Charlie Road', 'charlie@gmail.com', '07345678901', '3456');
INSERT INTO Customer VALUES (4, 'Jill Delta', '4 Delta Way', 'delta@gmail.com', '07456789012', '4567');
INSERT INTO Customer VALUES (5, 'Jim Echo', '5 Echo Avenue', 'echo@gmail.com', '07567890123', '5678');

CREATE TABLE Supplier(
Supplier_ID INT PRIMARY KEY,
Supplier_name VARCHAR (100),
Supplier_address VARCHAR (100),
Supplying_date DATE
);
INSERT INTO Supplier VALUES (1, 'Office Supplies Ltd.', '6 Foxtrot Road', '15-JAN-22');
INSERT INTO Supplier VALUES (2, 'Supplier Goods Inc.', '7 Golf Street', '20-FEB-22');
INSERT INTO Supplier VALUES (3, 'Supply Solutions Co', '8 Hotel Way', '25-MAR-22');
INSERT INTO Supplier VALUES (4, 'Supply Plus', '9 India Lane', '30-APR-22');
INSERT INTO Supplier VALUES (5, 'Super Supply Extra', '10 Juliet Avenue', '05-MAY-22');

CREATE TABLE Store(
Store_ID INT PRIMARY KEY,
Store_name VARCHAR (100),
Store_address VARCHAR (100)
);
INSERT INTO Store VALUES (1, 'IDEA Market', '11 Kilo Street');
INSERT INTO Store VALUES (2, 'IDEA Extra', '12 Lima Road');
INSERT INTO Store VALUES (3, 'IDEA Retail', '13 Mike Way');
INSERT INTO Store VALUES (4, 'IDEA Corner Shop', '14 November Avenue');
INSERT INTO Store VALUES (5, 'IDEA Superstore', '15 Oscar Street');


CREATE TABLE Employee(
Employee_ID INT PRIMARY KEY,
Employee_name VARCHAR (100),
Position VARCHAR (100),
DOB DATE
);
INSERT INTO Employee VALUES (1, 'Susan Foxtrot', 'Delivery Coordinator', '01-JAN-80');
INSERT INTO Employee VALUES (2, 'Robert Golf', 'Sales Assistant', '15-FEB-85');
INSERT INTO Employee VALUES (3, 'Mia Hotel', 'Clerk', '20-MAR-90');
INSERT INTO Employee VALUES (4, 'Tom Indigo', 'Clerk', '25-APR-95');
INSERT INTO Employee VALUES (5, 'Lila Juliet', 'Delivery Coordinator', '30-MAY-00');

CREATE TABLE Product(
Product_ID INT PRIMARY KEY,
Supplier_ID INT,
FOREIGN KEY (Supplier_ID) REFERENCES Supplier(Supplier_ID),
Product_Weight_KG DECIMAL(10,2),
Product_name VARCHAR(100),
Description VARCHAR (100),
Price DECIMAL(10,2),
Dimensions_meters_cubed DECIMAL(10,3) 
);
INSERT INTO Product VALUES (1, 1, 50.5, 'Couch', 'Comfortable three-seater', 200.50, 1.2);
INSERT INTO Product VALUES (2, 2, 10.0, 'Coffee Table', 'Glass top and wooden legs', 40.00, 0.3);
INSERT INTO Product VALUES (3, 1, 5.0, 'Lamp', 'Desk lamp with sensory lights', 50.50, 0.1);
INSERT INTO Product VALUES (4, 3, 100.0, 'Dining Set', 'Wooden table and four chairs', 400.00, 2.5);
INSERT INTO Product VALUES (5, 2, 10.0, 'Bar Stool', 'High stool with wooden back support', 40.50, 0.4);
INSERT INTO Product VALUES (6, 4, 0.8, 'Cushion', 'Soft throw pillow', 25.00, 0.2);
INSERT INTO Product VALUES (7, 3, 7.5, 'Rug', 'Square and thick', 90.50, 0.6);
INSERT INTO Product VALUES (8, 5, 20.0, 'Wardrobe', 'Two doors and walk-in', 300.00, 2.0);
INSERT INTO Product VALUES (9, 4, 5.0, 'Desk', 'Office desk with drawers', 200.50, 1.0);
INSERT INTO Product VALUES (10, 5, 10.0, 'Bookcase', 'Five shelves, wooden', 100.00, 1.5);

CREATE TABLE Purchase(
Purchase_ID INT PRIMARY KEY,
Customer_no INT,
FOREIGN KEY (Customer_no) REFERENCES Customer(Customer_no),
Product_ID INT,
FOREIGN KEY (Product_ID) REFERENCES Product(Product_ID),
Purchase_date DATE,
Purchase_Price DECIMAL(10, 2),
Postage_cost_GBP DECIMAL(10, 2) DEFAULT 0
);
CREATE TRIGGER Product_Purchase_Price
BEFORE INSERT ON Purchase
FOR EACH ROW
BEGIN
SELECT Price INTO :new.Purchase_Price FROM Product WHERE Product_ID = :new.Product_ID;
END;
/
CREATE TRIGGER Calculate_postage_fee
BEFORE INSERT ON Purchase
FOR EACH ROW
DECLARE
Purchased_dimensions DECIMAL(10,3);
BEGIN
SELECT Dimensions_meters_cubed INTO Purchased_dimensions FROM Product WHERE Product_ID = :new.Product_ID;
IF Purchased_dimensions > 0.5 THEN :new.Postage_cost_GBP := (Purchased_dimensions - 0.5) * 5 + 5;
ELSE :new.Postage_cost_GBP := 0;
END IF;
END;
/
INSERT INTO Purchase (Purchase_ID, Customer_no, Product_ID, Purchase_date)
VALUES 
(1, 1, 1, '05-APR-24');
INSERT INTO Purchase (Purchase_ID, Customer_no, Product_ID, Purchase_date)
VALUES 
(2, 2, 2, '06-APR-24');
INSERT INTO Purchase (Purchase_ID, Customer_no, Product_ID, Purchase_date)
VALUES 
(3, 3, 3, '07-APR-24');
INSERT INTO Purchase (Purchase_ID, Customer_no, Product_ID, Purchase_date)
VALUES 
(4, 4, 4, '08-APR-24');
INSERT INTO Purchase (Purchase_ID, Customer_no, Product_ID, Purchase_date)
VALUES 
(5, 5, 5, '09-APR-24');

CREATE TABLE Delivery(
Delivery_ID INT PRIMARY KEY,
Delivery_time DATE,
Employee_ID INT,
FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID),
Purchase_ID INT,
FOREIGN KEY (Purchase_ID) REFERENCES Purchase(Purchase_ID),
Product_ID INT,
FOREIGN KEY (Product_ID) REFERENCES Product(Product_ID),
Customer_no INT,
FOREIGN KEY (Customer_no) REFERENCES Customer(Customer_no),
Customer_address VARCHAR(100),
Customer_name VARCHAR(100),
Dimensions_meters_cubed DECIMAL(10,3),
CHECK (Dimensions_meters_cubed > 0.5)
);

CREATE TRIGGER Delivery_address
BEFORE INSERT ON Delivery
FOR EACH ROW
BEGIN
SELECT Customer_address INTO :new.Customer_address FROM Customer WHERE Customer_no = :new.Customer_no; 
END;
/

CREATE TRIGGER Delivering_to
BEFORE INSERT ON Delivery
FOR EACH ROW
BEGIN
SELECT Customer_name INTO :new.Customer_name FROM Customer WHERE Customer_no = :new.Customer_no;
END;
/
CREATE TRIGGER Delivery_Employee
BEFORE INSERT ON Delivery
FOR EACH ROW
DECLARE
Delivery_position VARCHAR(100);
BEGIN
SELECT Position INTO Delivery_position FROM Employee WHERE Employee_ID = :new.Employee_ID;
IF Delivery_position != 'Delivery Coordinator' THEN RAISE_APPLICATION_ERROR(-20001, 'Only Delivery Coordinators can carry out deliveries');
END IF;
END;
/
CREATE TRIGGER Dimension_space
BEFORE INSERT ON Delivery
FOR EACH ROW
BEGIN
SELECT Dimensions_meters_cubed INTO :new.Dimensions_meters_cubed FROM Product WHERE Product_ID = :new.Product_ID;
END;
/
INSERT INTO Delivery(Delivery_ID, Delivery_time, Employee_ID, Purchase_ID, Product_ID, Customer_no) VALUES (1, TO_DATE('10-MAY-24 09:00:00', 'DD-MON-YY HH24:MI:SS'), 1, 1, 1, 1);
INSERT INTO Delivery(Delivery_ID, Delivery_time, Employee_ID, Purchase_ID, Product_ID, Customer_no) VALUES (2, TO_DATE('11-MAY-24 10:00:00', 'DD-MON-YY HH24:MI:SS'), 5, 4, 4, 4);

CREATE TABLE Collection(
Collection_ID INT PRIMARY KEY,
Employee_ID INT,
FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID),
Purchase_ID INT,
FOREIGN KEY (Purchase_ID) REFERENCES Purchase(Purchase_ID),
Product_ID INT,
FOREIGN KEY (Product_ID) REFERENCES Product(Product_ID),
Customer_no INT,
FOREIGN KEY (Customer_no) REFERENCES Customer(Customer_no),
Store_ID INT,
FOREIGN KEY (Store_ID) REFERENCES Store(Store_ID),
Collection_date DATE,
Purchase_Price DECIMAL(10, 2),
Last_four_cardNumbers VARCHAR (4),
Product_Weight_KG DECIMAL(10,2)
);
CREATE TRIGGER Check_Collection_Dimension
BEFORE INSERT ON Collection
FOR EACH ROW
DECLARE
Collection_dimensions DECIMAL(10,3);
BEGIN
SELECT Dimensions_meters_cubed INTO Collection_dimensions FROM Product WHERE Product_ID = :new.Product_ID;
IF Collection_dimensions > 0.5 THEN
RAISE_APPLICATION_ERROR(-20002, 'Only products with dimensions 0.5 meters cubed or less can be available for collection.');
END IF;
END;
/

CREATE TRIGGER Collection_receipt
BEFORE INSERT ON Collection
FOR EACH ROW
BEGIN
SELECT Last_four_cardNumbers INTO :new.Last_four_cardNumbers FROM Customer WHERE Customer_no = :new.Customer_no; 
SELECT Purchase_Price INTO :new.Purchase_Price FROM Purchase WHERE Purchase_ID = :new.Purchase_ID;
END;
/
CREATE TRIGGER Collection_weight
BEFORE INSERT ON Collection
FOR EACH ROW
BEGIN
SELECT Product_Weight_KG INTO :new.Product_Weight_KG FROM Product WHERE Product_ID = :new.Product_ID; 
END;
/

INSERT INTO Collection(Collection_ID, Employee_ID, Purchase_ID, Product_ID, Customer_no, Store_ID, Collection_date) VALUES (1, 2, 3, 3, 3, 1, TO_DATE('13-MAY-24 09:00:00', 'DD-MON-YY HH24:MI:SS'));
INSERT INTO Collection(Collection_ID, Employee_ID, Purchase_ID, Product_ID, Customer_no, Store_ID, Collection_date) VALUES (2, 3, 2, 2, 2, 2, TO_DATE('14-MAY-24 09:00:00', 'DD-MON-YY HH24:MI:SS'));
INSERT INTO Collection(Collection_ID, Employee_ID, Purchase_ID, Product_ID, Customer_no, Store_ID, Collection_date) VALUES (3, 4, 5, 5, 5, 3, TO_DATE('15-MAY-24 09:00:00', 'DD-MON-YY HH24:MI:SS'));
