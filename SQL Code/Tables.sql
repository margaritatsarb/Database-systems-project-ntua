create table Customer(
    NFC_ID int not NULL,
    FName varchar(20) not NULL,
    LName varchar(20) not NULL,
    BirthDate date not NULL,
    DocNumber varchar(20) not NULL,
    DocType varchar(20) not NULL,
    Issuer varchar(20) not NULL,
    primary key (NFC_ID),
    check (DocType in ('ID', 'Passport'))
);

create table Email (
    NFC_ID int not NULL,
    Email varchar(50) not NULL,
    primary key (Email),
    foreign key (NFC_ID) references Customer on delete cascade on update cascade
);

create table Phone (
    NFC_ID int not NULL,
    Phone numeric(10,0) not NULL,
    primary key (Phone) ,
    foreign key (NFC_ID) references Customer on update cascade on delete cascade
);

create table Spaces(
  Space_ID varchar(4) not NULL,
  Service_ID int not NULL,
  Space_Name varchar(20) not NULL,
  Beds int not NULL,
  Location varchar(2) not NULL,
  primary key (Space_ID),
  check (Space_Name in ('Room', 'Gym', 'Sauna', 'Conference Room', 'Lift', 'Reception', 'Hair Salon', 'Bar', 'Restaurant')),
  check ((Beds > 0 and Beds <= 4 and Space_Name = 'Room' and Service_ID = 00 and ((Location in ('1W', '1E', '1S', '1N')
                                                                                  or Location in ('2W', '2E', '2S', '2N')
                                                                                  or Location in ('3W', '3E', '3S', '3N')
                                                                                  or Location in ('4W', '4E', '4S', '4N')
                                                                                  or Location in ('5W', '5E', '5S', '5N')
                                                                                  ))
          )
        or (Beds = 0 and Service_ID in (01, 02, 03, 04, 05, 06, 07) and Location = '0')),
  check (Service_ID <= 07 and Service_ID >= 00)
);

create table Services (
    Service_ID int not NULL,
    Description varchar(50) not NULL,
    primary key (Service_ID),
    check (Service_ID <= 07 and Service_ID >= 00),
    check (Description in ('Room', 'Gym', 'Sauna', 'Conference Room', 'Hair Salon', 'Bar', 'Restaurant', 'Reception'))
);

create table Sub_Services (
    NFC_ID int not NULL,
    Service_ID int not NULL,
    Sub_date date not NULL,
    Sub_time time not NULL,
    foreign key (NFC_ID) references Customer on update cascade on delete cascade,
    foreign key (Service_ID) references Services on update cascade on delete cascade,
    check (Service_ID >= 0 and Service_ID <= 03)
);

create table Service_Pay (
    Pay_date date not NULL,
    Pay_time time not NULL,
    NFC_ID int not NULL,
    Service_ID int not NULL,
    Amount numeric(5,3) not NULL,
    Description varchar(50) not NULL,
    primary key (Pay_date, Pay_time),
    foreign key (NFC_ID) references Customer on update cascade on delete cascade,
    foreign key (Service_ID) references Services on update cascade on delete cascade
);

create table Has_Access (
  NFC_ID int not NULL,
  Space_ID varchar(4) not NULL,
  StartDate date not NULL,
  StartTime time not NULL,
  FinishDate date not NULL,
  FinishTime time not NULL,
  foreign key (NFC_ID) references Customer on update cascade on delete cascade,
  foreign key (Space_ID) references Spaces on update cascade on delete cascade,
  check ((StartDate < FinishDate) or (StartDate = FinishDate and StartTime < FinishTime))
);

create table Visits (
    NFC_ID int not NULL,
    Space_ID varchar(4) not NULL,
    Entrancedate date not NULL,
    Entrancetime time not NULL,
    Exitdate date not NULL,
    Exittime time not NULL,
    foreign key (NFC_ID) references Customer on update cascade on delete cascade,
    foreign key (Space_ID) references Spaces on update cascade on delete cascade,
      check ((Entrancedate < Exitdate) or (Entrancedate = Exitdate and Entrancetime < Exittime))
    -- check (Space_ID in (SELECT NFC_ID
    --                     from Has_Access
    --                     where ((CAST(Visits.Entrancedate as DateTime) + CAST(Visits.EntranceTime as DateTime))
    --                       between (CAST(Has_Access.StartDate as datetime) + CAST(Has_Access.StartTime as DateTime))
    --                       and (CAST(Has_Access.FinishDate as datetime) + CAST(Has_Access.FinishTime as DateTime)))
);
