<!-- Service Stats.
Will Include question 7 -->

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

    <h1 style="font-size:130%"> Service Info </h1>

    <br>

  </center>


  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Service Type:
    <input type="radio" name="Service" value="5">Bar
    <input type="radio" name="Service" value="6">Restaurant
    <input type="radio" name="Service" value="4">Hair Salon
    <input type="radio" name="Service" value="1">Gym
    <input type="radio" name="Service" value="2">Sauna
    <input type="radio" name="Service" value="3">Conference Room
    <br><br>
    Date:
    <input type="date" id="wanted_date" name="wanted_date">
    <br><br>
    Min Price:
    <input type="number" id="minprice" name="min_price" min="0" max="100">
    Max Price:
    <input type="number" id="maxprice" name="max_price" min="0" max="100">
    <br><br>
    <input type="submit">
  </form>


  <!-- PHP FUNCTIONS -->


  <?php
  $Service = '';
  $Date = '';
  $minprice = '';
  $maxprice = '';
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Service = empty($_POST["Service"]) ? $Service = 'NULL' : $Service = intval($_POST["Service"]);

    $Date = empty($_POST["wanted_date"]) ? $Date = 'NULL' : $_POST["wanted_date"];

    $minprice = empty($_POST["min_price"]) ? $minprice = 'NULL' : floatval($_POST["min_price"]);

    $maxprice = empty($_POST["max_price"]) ? $maxprice = 'NULL' : floatval($_POST["max_price"]);

    if($test = test_input($minprice, $maxprice) == 0){
      echo "<p> Wrong Input, Min Price must be lower than Max Price </p>";
    }
    else{
      if (($minprice == 'NULL' && $maxprice != 'NULL')||($minprice == 0 && $maxprice != 'NULL')) $minprice = 0.001;
      else if ($maxprice == 'NULL' && $minprice != 'NULL') $maxprice = 100;

      if ($minprice == 'NULL'){
        if ($Date == 'NULL' and $Service == 'NULL'){
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces
          where Visits.Space_ID = Spaces.Space_ID
          and Space_Name not in ('Lift', 'Reception')
          group by Space_Name " ;
        }
        else if ($Date == 'NULL'){
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces
          where Spaces.Service_ID = $Service
          and Visits.Space_ID = Spaces.Space_ID
          group by Space_Name " ;
        }
        else if ($Service == 'NULL') {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces
          where Entrancedate = '$Date'
          and Visits.Space_ID = Spaces.Space_ID
          group by Space_Name ";
        }
        else {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces
          where Entrancedate = '$Date'
          and Spaces.Service_ID = $Service
          and Visits.Space_ID = Spaces.Space_ID
          group by Space_Name";
        }
      }
      else {
        if ($Date == 'NULL' and $Service == 'NULL') {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces, Service_Pay
          where Visits.Space_ID = Spaces.Space_ID
          and Visits.NFC_ID = Service_Pay.NFC_ID
          and (Amount between $minprice and $maxprice)
          and Service_Pay.Service_ID = Spaces.Service_ID
          group by Space_Name " ;
        }
        else if ($Date == 'NULL') {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces, Service_Pay
          where Spaces.Service_ID = $Service
          and Visits.Space_ID = Spaces.Space_ID
          and Visits.NFC_ID = Service_Pay.NFC_ID
          and (Amount between $minprice and $maxprice)
          and Service_Pay.Service_ID = Spaces.Service_ID
          group by Space_Name " ;
        }
        else if ($Service == 'NULL') {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces, Service_Pay
          where Entrancedate = '$Date'
          and Visits.Space_ID = Spaces.Space_ID
          and Visits.NFC_ID = Service_Pay.NFC_ID
          and (Amount between $minprice and $maxprice)
          and Service_Pay.Service_ID = Spaces.Service_ID
          group by Space_Name ";
        }
        else {
          $query = "SELECT Space_Name, count(*) as total
          from Visits, Spaces, Service_Pay
          where Entrancedate = '$Date'
          and Spaces.Service_ID = $Service
          and Visits.Space_ID = Spaces.Space_ID
          and Visits.NFC_ID = Service_Pay.NFC_ID
          and (Amount between $minprice and $maxprice)
          and Service_Pay.Service_ID = Spaces.Service_ID
          group by Space_Name";
        }
      }
      $r = sqlsrv_query($conn, $query);
      if( $r === false) {
        die( print_r( sqlsrv_errors(), true) );
      }

      ?>

      <div style="float: left; width:25%">

        <table width="20%", border="1">
          <tr> <th> Service </th> <th> Total Visits </th> </tr>
          <?php
          while ($row = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)) {
            ?>
            <tr>  <td> <?php echo $row['Space_Name'] ?> </td>
              <td> <?php echo $row['total'] ?> </td></tr>

              <?php
            }
            echo "</table> </div>";

          }
        }

        function test_input($minprice, $maxprice)
        {
          if (($minprice == 'NULL')||($maxprice == 'NULL')||($minprice < $maxprice)){
            return 1;
          }
          else {
            return 0;
          }
        }

        ?>

      </body>
      </html>
