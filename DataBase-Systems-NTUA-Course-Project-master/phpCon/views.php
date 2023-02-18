<!-- Views for Sales and Customer Info.
Will Include question 8 -->

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

    <h1 style="font-size:130%"> Sales and Customer Info </h1>

    <br>
  </center>


  <h1 style="font-size:100%"> View of Sales per Service: </h1>
  <?php
  $query1 = "SELECT * FROM Sales";
  $sales = sqlsrv_query($conn, $query1);
  if( $sales === false) {
    die( print_r( sqlsrv_errors(), true) );
  }
  ?>


  <table width="20%", border="1", style="color:black">
    <tr> <th> Service Type </th> <th> Total Sales </th> </tr>
    <?php
    while
    ($row = sqlsrv_fetch_array($sales, SQLSRV_FETCH_ASSOC)) {
      ?>
      <tr>  <td> <?php echo $row['Description'] ?> </td>
        <td style="text-align:right"> <?php echo number_format($row["Total"], 2, '.', '') ?>€ </td></tr>

        <?php
      }
      ?>
    </table>


    <p> </p>


    <h1 style="font-size:100%"> Customer Info: </h1>
    <?php
    $query2 = "SELECT * FROM Customer_info";
    $customers = sqlsrv_query($conn, $query2);
    if( $customers === false) {
      die( print_r( sqlsrv_errors(), true) );
    }
    ?>


    <table width="65%", border="1", style="color:black">
      <tr> <th> NFC ID </th> <th> First Name </th> <th> Last Name </th> <th> BirthDate </th> <th> Document Number </th> <th> Document Type </th> <th> Issuer </th> <th> Email </th> <th> Phone </th> </tr>
      <?php
      while
      ($row = sqlsrv_fetch_array($customers, SQLSRV_FETCH_ASSOC)) {
        ?>
        <tr>  <td> <?php echo $row['NFC_ID'] ?> </td>
          <td> <?php echo $row['FName'] ?> </td>
          <td> <?php echo $row['LName'] ?> </td>
          <td> <?php echo ($row['BirthDate']->format('Y-m-d')) ?> </td>
          <td> <?php echo $row['DocNumber'] ?> </td>
          <td> <?php echo $row['DocType'] ?> </td>
          <td> <?php echo $row['Issuer'] ?> </td>
          <td> <?php echo $row['Email'] ?> </td>
          <td> <?php echo $row['Phone'] ?> </td></tr>

          <?php
        }
        ?>
      </table>
    </body>
    </html>
