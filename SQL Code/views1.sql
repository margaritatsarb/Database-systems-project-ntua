create view Sales as
  select Description, sum(Amount) as Total
  from Service_Pay
  group by Description
