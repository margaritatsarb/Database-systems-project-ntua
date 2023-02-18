<!-- Statisctics per age group.
Will Include question 11 -->

<?php
include 'connect.php'
?>
<!DOCTYPE html>
<html>
<head >
  <style>

  .myDiv {
    border: 5px outset #00ccff;
    background-color: #1fdbf0;
  }
  a:link, a:visited {
    background-color: #1fdbf0;
    color: #fffff7;
    padding: 15px 50px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size:120%
  }

  a:hover, a:active {
    background-color: #17a8f7;
    font-size:120%
  }

  body {
    background-color:#fffff7;
    font-size: 150%;
    font-family:'Helvetica', sans-serif;
  }
  </style>

  <title>ASDF Palace</title>
  <div class="myDiv" >
    <a style="font-family:'Brush Script MT',cursive;font-size:300%; color:#fffff7;" href="index.php"> ASDF Palace </a>
    <a href="stats.php">Service Info</a>
    <a href="views.php">Sales and Customer Info</a>
    <a href="covidtrac.php">Covid Case Tracing</a>
    <a href="age.php">Age Group Statistics</a>
    <br> <br>
  </div>

</head>

<body>

  <center>


    <h1 style="font-size:130%"> Age Group Statistics </h1>

    <br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Show Stats for Last:
      <input type="radio" name="Span" value="1">Month
      <input type="radio" name="Span" value="12">Year
      <br><br>
      <input type="submit">

    </form>



  </center>
  <?php
  $span = '';
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $span = intval($_POST["Span"]);


    $youngSpaces = "SELECT Visits.Space_ID, count(*) as Instances, Space_Name
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 40)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 20)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Visits.Space_ID, Space_Name";

    $middleSpaces = "SELECT Visits.Space_ID, count(*) as Instances, Space_Name
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 60)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 41)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Visits.Space_ID, Space_Name";

    $elderlySpaces = "SELECT Visits.Space_ID, count(*) as Instances, Space_Name
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 61)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Visits.Space_ID, Space_Name";

    $notroom = "Space_ID not like '___N' and Space_ID not like '___S' and Space_ID not like '___W' and Space_ID not like '___E'";


    $Pspaces = " SELECT '20-40' as age_group, Instances, Space_ID, Space_Name
    FROM ($youngSpaces) as young
    where Instances >= all(select Instances from ($youngSpaces) as young1 where $notroom)
    and $notroom
    union
    SELECT '41-60' as age_group, Instances, Space_ID, Space_Name
    FROM ($middleSpaces) as middle
    where Instances >= all(select Instances from ($middleSpaces) as middle where $notroom)
    and $notroom
    union
    SELECT '61+' as age_group, Instances, Space_ID, Space_Name
    FROM ($elderlySpaces) as old
    where Instances >= all(select Instances from ($elderlySpaces) as old1 where $notroom)
    and $notroom";


    $youngServices = "SELECT Space_Name, count(*) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 40)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 20)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $middleServices = "SELECT Space_Name, count(*) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 60)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 41)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $elderlyServices = "SELECT Space_Name, count(*) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 61)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $isService ="Space_Name not in ('Room', 'Reception', 'Lift')";

    $Pservices = " SELECT '20-40' as age_group, Instances, Space_Name
    FROM ($youngServices) as young
    where Instances >= all(select Instances from ($youngServices) as young1 where $isService)
    and $isService
    union
    SELECT '41-60' as age_group, Instances, Space_Name
    FROM ($middleServices) as middle
    where Instances >= all(select Instances from ($middleServices) as middle where $isService)
    and $isService
    union
    SELECT '61+' as age_group, Instances, Space_Name
    FROM ($elderlyServices) as old
    where Instances >= all(select Instances from ($elderlyServices) as old1 where $isService)
    and $isService";

    $youngDServices = "SELECT Space_Name, count(distinct Visits.NFC_ID) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 40)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 20)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $middleDServices = "SELECT Space_Name, count(distinct Visits.NFC_ID) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) <= 60)
    and ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 41)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $elderlyDServices = "SELECT Space_Name, count(distinct Visits.NFC_ID) as Instances
    FROM Visits, Customer, Spaces
    where ((DATEPART(yyyy, current_timestamp)) - (DATEPART(yyyy, BirthDate)) >= 61)
    and Visits.NFC_ID = Customer.NFC_ID
    and DATEDIFF(month, CAST(Entrancedate as Date), CAST(current_timestamp as Date)) <= $span
    and Spaces.Space_ID = Visits.Space_ID
    group by Space_Name";

    $PDservices = " SELECT '20-40' as age_group, Instances, Space_Name
    FROM ($youngDServices) as young
    where Instances >= all(select Instances from ($youngDServices) as young1 where $isService)
    and $isService
    union
    SELECT '41-60' as age_group, Instances, Space_Name
    FROM ($middleDServices) as middle
    where Instances >= all(select Instances from ($middleDServices) as middle where $isService)
    and $isService
    union
    SELECT '61+' as age_group, Instances, Space_Name
    FROM ($elderlyDServices) as old
    where Instances >= all(select Instances from ($elderlyDServices) as old1 where $isService)
    and $isService";


    $r1 = sqlsrv_query($conn, $Pspaces);
    if( $r1 === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    $r2 = sqlsrv_query($conn, $Pservices);
    if( $r2 === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    $r3 = sqlsrv_query($conn, $PDservices);
    if( $r3 === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    ?>


    <h1 style="font-size:100%"> Most Visited Places: </h1>
    <table width="30%", border="1" style="color:black">
      <tr> <th> Age Group </th><th> Most Popular Space ID </th><th> Space Type  </th> <th> Visits </th> </tr>
      <?php
      while ($row = sqlsrv_fetch_array($r1, SQLSRV_FETCH_ASSOC)) {
        ?>
        <tr>  <td> <?php echo $row['age_group'] ?> </td>
          <td> <?php echo $row['Space_ID'] ?> </td>
          <td> <?php echo $row['Space_Name'] ?> </td>
          <td> <?php echo $row['Instances'] ?> </td></tr>

          <?php
        }
        echo "</table> <br>";

        ?>
      </table>


      <h1 style="font-size:100%"> Most Used Services: </h1>
      <table width="30%", border="1" style="color:black">
        <tr> <th> Age Group </th><th> Most Used Service </th> <th> Uses </th> </tr>
        <?php
        while ($row = sqlsrv_fetch_array($r2, SQLSRV_FETCH_ASSOC)) {
          ?>
          <tr>  <td> <?php echo $row['age_group'] ?> </td>
            <td> <?php echo $row['Space_Name'] ?> </td>
            <td> <?php echo $row['Instances'] ?> </td></tr>

            <?php
          }
          echo "</table> <br>";

          ?>
        </table>


        <h1 style="font-size:100%"> Services with the most Users: </h1>
        <table width="30%", border="1" style="color:black">
          <tr> <th> Age Group </th><th> Most Popular Service </th> <th> Users </th> </tr>
          <?php
          while ($row = sqlsrv_fetch_array($r3, SQLSRV_FETCH_ASSOC)) {
            ?>
            <tr>  <td> <?php echo $row['age_group'] ?> </td>
              <td> <?php echo $row['Space_Name'] ?> </td>
              <td> <?php echo $row['Instances'] ?> </td></tr>

              <?php
            }
            echo "</table> <br>";
          }
          ?>
        </table>


      </body>
      </html>
