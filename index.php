<?php
session_start();
try {
  $db = new PDO('sqlite:APIFolder/database.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS messages (    
    id INTEGER PRIMARY KEY,
    name TEXT, 
    userID TEXT,
    cardNumber1 INTEGER,
    suit1 TEXT,
    cardNumber2 INTEGER,
    suit2 TEXT,
    ready TEXT,
    turn TEXT,
    chips INTEGER,
    status TEXT
  )"
  );
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS globalV (    
    id INTEGER PRIMARY KEY,
    round INTEGER,
    turns INTEGER,
    pot INTEGER
  )"
  );
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS betTrack (    
    id INTEGER PRIMARY KEY,
    name TEXT,
    amount INTEGER,
    totalAm INTEGER
  )"
  );
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS cards(    
    id INTEGER PRIMARY KEY,
    cardNumber INTEGER,
    suit TEXT
  )"
  );
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS totCards(    
    id INTEGER PRIMARY KEY,
    cardNumber INTEGER,
    suit INTEGER
  )"
  );
  $res = $db->exec(
  "CREATE TABLE IF NOT EXISTS winners(    
    id INTEGER PRIMARY KEY,
    name TEXT,
    score DECIMAL(13, 12)
  )"
  );
  $stmt = $db->query("SELECT COUNT(*) FROM globalV");
  $count = $stmt->fetchColumn();
  if ($count == 0) {
    $stmt = $db->prepare("INSERT INTO globalV (round, turns, pot) VALUES (:round, :turns, :pot)");
    $stmt->execute(['round' => 1, 'turns' => 0, 'pot' => 0]);
  }
  $insert = $db->prepare("INSERT INTO totCards (cardNumber, suit) VALUES (?, ?)");
  for ($suit = 1; $suit <= 4; $suit++) {
    for ($cardNumber = 1; $cardNumber <= 13; $cardNumber++) {
      $insert->execute([$cardNumber, $suit]);
    }
  }
  $_SESSION['messages'] = $db->query("SELECT * FROM messages")->fetchAll(PDO::FETCH_ASSOC);
  $_SESSION['messages'];
  $_SESSION['name'] = '';
  $db = null;
} catch (PDOException $ex) {
  echo $ex->getMessage();
}
if (!isset($_SESSION['myId'])) {
    $_SESSION['myId'] = uniqId();
}
?>
<html>
  <head>
    <title>PHP Test</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        color: #333;
        text-align: center;
        margin: 0;
        padding: 0;
        position: relative;
      }

      #myheader {
        color: #4CAF50;
        margin-top:80px;
        font-size: 5em;
        margin-bottom: 100px;
      }

      button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 10px 2px;
        cursor: pointer;
        border-radius: 5px;
      }

      button:hover {
        background-color: #45a049;
      }

      h4 {
        color: #555;
      }

      input[type="text"] {
        width: 200px;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
      }

      .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
      }
      #resetButton {
        position: absolute;
        top: 5px;
        left: 100px;
      }
      .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 500px; 
      }
    </style>
  </head>
  <body>
    <h1 id="myheader">Home</h1>
    <button id="resetButton" type = "button" onclick = 
        "clearDatabase()">
        Reset
    </button>
    <br>
    <h4>Type your username</h4>
    <input type="text" id="usernameIn" style="width: 75px;">
    <br>
    <br>
    <button type = "button" onclick = "addrw()">Submit</button>
    <script>
      function addrw(){
        let usrinput = document.getElementById("usernameIn").value.trim();
        let myId = <?php echo json_encode($_SESSION['myId']); ?>;
        if(usrinput!=""){
          const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            window.location.href = "gameroom.php";
          }
          xhttp.open("GET", "APIFolder/addTables.php?name=" + usrinput + "&userid=" + myId, true);
          xhttp.send();
        }
      }
      function clearDatabase(){
        const xhttp = new XMLHttpRequest();
        xhttp.open("GET", "APIFolder/clear.php", true);
        xhttp.send();
      }
    </script>
  </body>
</html>