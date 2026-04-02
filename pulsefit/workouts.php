<?php
session_start();
include "config.php";
if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}
$user = $_SESSION['username'];
$calories = null;
if(isset($_POST['calculate'])){
  $activity_raw = $_POST['activity'];     
  $activity = urlencode($activity_raw);   
  $duration = $_POST['duration'];

  $url = "https://api.api-ninjas.com/v1/caloriesburned?activity=$activity";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "X-Api-Key: PtwZXZxl8BUKP2liIz9D9VanSzR5Rb6fNrOdSrNN" 
  ]);

  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response, true);

  if(!empty($data)){
      $cal_per_hour = $data[0]['calories_per_hour'];
      $calories = ($cal_per_hour / 60) * $duration;
      $sql = "INSERT INTO workouts (user_id, activity, duration, calories)
              VALUES ('$user','$activity_raw','$duration','$calories')";
      mysqli_query($conn, $sql);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PulseFit | Workouts</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <aside class="sidebar">
    <h2 class="logo">PulseFit</h2>

    <nav>
      <a href="index.php">Dashboard</a>
      <a class="active" href="workouts.php">Workouts</a>
      <a href="goals.php">Goals</a>
      <a href="health.php">Health</a>
      <a href="logout.php" class="logout-btn">Logout</a>
    </nav>
  </aside>

  <main class="main">

    <header class="header">
      <div>
        <h1>Workouts</h1>
        <p>Your recent activity</p>
      </div>
      <button id="themeToggle">🌙</button>
    </header>
    <section class="cards">
      <div class="card">
        <h3><i class="fa-solid fa-calendar-week"></i> Total Workouts</h3>
        <?php
        $sql = "SELECT COUNT(*) as total FROM workouts WHERE user_id='$user'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        echo "<p class='big'>" . ($row['total'] ?? 0) . "</p>";
        ?>
        <span>This week</span>
      </div>
      <div class="card">
        <h3><i class="fa-solid fa-clock"></i> Duration</h3>
        <?php
        $sql = "SELECT SUM(duration) as total FROM workouts WHERE user_id='$user'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        echo "<p class='big'>" . ($row['total'] ?? 0) . " min</p>";
        ?>
        <span>Total</span>
      </div>
      <div class="card">
        <h3><i class="fa-solid fa-fire"></i> Calories</h3>
        <?php
        $sql = "SELECT SUM(calories) as total FROM workouts WHERE user_id='$user'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        echo "<p class='big'>" . round($row['total'] ?? 0) . "</p>";
        ?>
        <span>Burned</span>
      </div>

    </section>
    <section class="box">
      <h2>Add Workout</h2>

      <form method="POST">
        <input type="text" name="activity" placeholder="Activity (running, yoga)" required>
        <input type="number" name="duration" placeholder="Duration (minutes)" required>
        <button type="submit" name="calculate">Calculate</button>
      </form>

      <?php
      if($calories !== null){
        echo "<p><strong>Calories Burned: " . round($calories) . "</strong></p>";
      }
      ?>
    </section>
    <section class="box">
      <h2>Workout History</h2>

      <?php
      $sql = "SELECT * FROM workouts WHERE user_id='$user' ORDER BY id DESC LIMIT 5";
      $result = mysqli_query($conn,$sql);

      while($row = mysqli_fetch_assoc($result)){
        echo "<div class='item'>
                <strong>{$row['activity']}</strong>
                <span>{$row['duration']} min · " . round($row['calories']) . " cal</span>
              </div>";
      }
      ?>
    </section>

  </main>

  <script src="script.js"></script>
  <script src="js/theme.js"></script>

</body>
</html>
```
