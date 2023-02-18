create view Customer_info as
  select Customer.NFC_ID, FName, LName, BirthDate, DocNumber, DocType, Issuer, Email, Phone
  from Customer, Phone, Email
  where (Customer.NFC_ID = Phone.NFC_ID and Customer.NFC_ID = Email.NFC_ID)
