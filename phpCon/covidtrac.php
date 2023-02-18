<!-- Covid Case tracing, Possible other Cases.
Will Include questions 9 and 10 -->

<?php
include 'connect.php'
?>
<!DOCTYPE html>
<html>
<head>
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
    font-size:150%;
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
    <h1 style="font-size:130%"> Covid Case Tracing </h1>
    <br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      COVID Case NFC ID:
      <input type="number" required id="case" name="Case" min="1">
      <br><br>
      <input type="submit">

    </form>
  </center>



  <!-- PHP Funtions -->

  <?php
  $Case = '';
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Case = intval($_POST["Case"]);
    $query1 = "SELECT Space_Name, Visits.Space_ID, Entrancedate, Entrancetime, Exitdate, Exittime, Location
    from Visits, Spaces
    where Visits.NFC_ID = $Case
    and Spaces.Space_ID = Visits.Space_ID ";

    $query2 = "SELECT distinct Visits.NFC_ID, Hit.Space_Name, Hit.Space_ID, Email, Phone
    from Visits, ($query1) as Hit, Spaces, Phone, Email
    where ((Visits.Space_ID = Spaces.Space_ID and Spaces.Location != '0' and Spaces.Location = Hit.Location) or (Visits.Space_ID = Hit.Space_ID))
    and Visits.NFC_ID != $Case
    and Visits.NFC_ID = Phone.NFC_ID
    and Visits.NFC_ID = Email.NFC_ID
    and (((CAST(Visits.Entrancedate as DateTime) + CAST(Visits.EntranceTime as DateTime))
    between (CAST(Hit.Entrancedate as datetime) + CAST(Hit.EntranceTime as DateTime)) and DATEADD(hh, 1,CAST(Hit.Exitdate as DateTime) + CAST(Hit.ExitTime as DateTime)))
    or ((CAST(Visits.Exitdate as DateTime) + CAST(Visits.ExitTime as DateTime))
    between (CAST(Hit.Entrancedate as datetime) + CAST(Hit.EntranceTime as DateTime)) and DATEADD(hh, 1,CAST(Hit.Exitdate as DateTime) + CAST(Hit.ExitTime as DateTime)) ))";

    $visits = sqlsrv_query($conn, $query1);
    if( $visits === false) {
      die( print_r( sqlsrv_errors(), true) );
    }

    $possible = sqlsrv_query($conn, $query2);
    if( $possible === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    ?>


    <h1 style="font-size:100%"> Case's Visited Spaces: </h1>
    <table width="50%", border="1", style="color:black; margin:0 0 50px 0;">
      <tr><th> Space </th><th> Space ID</th><th> Entrance Date </th><th> Entrance Time </th><th> Exit Date </th> <th> Exit Time </th></tr>
      <?php
      while ($row = sqlsrv_fetch_array($visits, SQLSRV_FETCH_ASSOC)) {
        ?>
        <tr>  <td> <?php echo $row['Space_Name'] ?> </td>
          <td> <?php echo $row['Space_ID'] ?> </td>
          <td> <?php echo ($row['Entrancedate']->format('Y-m-d')) ?> </td>
          <td> <?php echo ($row['Entrancetime']->format('H:i:s')) ?> </td>
          <td> <?php echo ($row['Exitdate']->format('Y-m-d')) ?> </td>
          <td> <?php echo ($row['Exittime']->format('H:i:s')) ?> </td>

        </tr>
        <?php
      }
      echo "</table><br> <br><br>";
      ?>
      <h1 style="font-size:100%"> Encounters: </h1>
      <table width="50%", border="1", style="color:black; margin:0 0 50px 0;">
        <tr><th> NFC_ID </th><th> Space ID</th><th> Space Name </th><th> Phone Number </th><th> Email </th></tr>
        <?php
        while ($row = sqlsrv_fetch_array($possible, SQLSRV_FETCH_ASSOC)) {
          ?>
          <tr>  <td> <?php echo $row['NFC_ID'] ?> </td>
            <td> <?php echo $row['Space_ID'] ?> </td>
            <td> <?php echo $row['Space_Name'] ?> </td>
            <td> <?php echo $row['Phone'] ?> </td>
            <td> <?php echo $row['Email'] ?> </td>
          </tr>

          <?php
        }
        echo "</table><br> <br><br>";



      }

      ?>


    </body>
    </html>
