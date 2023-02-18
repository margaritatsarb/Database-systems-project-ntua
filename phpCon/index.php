<!-- Starting page.
Will Include:
1. HyperLinks to
i.Visits (multiple ctiteria, drop down lists etc) and Statisctics
ii.Covid Case tracing, Possible other Cases
iii.Statisctics per age group
2.Hotel Description -->

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
    color:#d6831e;
    font-family:'Helvetica', sans-serif;
  }
  </style>

  <title>ASDF Palace</title>
  <center>
    <div class="myDiv" >
      <a style="font-family:'Brush Script MT',cursive;font-size:300%; color:#fffff7;" href="index.php"> ASDF Palace </a>
      <br>
      <a href="stats.php">Service Info</a>
      <a href="views.php">Sales and Customer Info</a>
      <a href="covidtrac.php">Covid Case Tracing</a>
      <a href="age.php">Age Group Statistics</a>
      <br> <br>
    </div>

  </head>

  <body>

  </center>

  <p> &emsp; Following the instructions of the Ministry of Tourism, ASDF Hotel is implementing a new health protocol.
    This Protocol includes the development of the NFC Bracelet Plan and the development of a Suspected Case Management Plan.
    The aim of those plans is to prevent the occurence and effective management of suspicious cases in order to limit the exposure of staff and guests,
    always in accordance with the current guidelines of the National Public Health Organization.
    <br>
    &emsp; The measures described in the NFC Bracelet Plan and the Suspected Case Management Plan are meant to protect our staff
    and guests and to outline the necessary measures to prevent and protect against COVID-19 disease.
  </p>

</body>
</html>
