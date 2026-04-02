<?php
session_start();
include "config.php";

if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}

$user = $_SESSION['username'];
if(isset($_POST['save'])){
  $hr = $_POST['heart_rate'];
  $weight = $_POST['weight'];
  $steps = $_POST['steps'];

  mysqli_query($conn,
    "INSERT INTO health(user_id, heart_rate, weight, steps)
     VALUES('$user','$hr','$weight','$steps')");
}
$res = mysqli_query($conn,"SELECT * FROM health WHERE user_id='$user' ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_assoc($res);

$hr = $row['heart_rate'] ?? 0;
$weight = $row['weight'] ?? 0;
$steps = $row['steps'] ?? 0;

// ✅ CALCULATIONS
$goal_steps = 10000;
$step_percent = ($steps > 0) ? ($steps / $goal_steps) * 100 : 0;

// Heart status
$heart_status = ($hr >= 60 && $hr <= 100) ? "Healthy range" : "Check";

// Calories (from workouts)
$res2 = mysqli_query($conn,"SELECT SUM(calories) as total FROM workouts WHERE user_id='$user'");
$data2 = mysqli_fetch_assoc($res2);
$total_cal = $data2['total'] ?? 0;

// Avg Heart Rate
$res3 = mysqli_query($conn,"SELECT AVG(heart_rate) as avg_hr FROM health WHERE user_id='$user'");
$data3 = mysqli_fetch_assoc($res3);
$avg_hr = round($data3['avg_hr'] ?? 0);

// Avg Steps
$res4 = mysqli_query($conn,"SELECT AVG(steps) as avg_steps FROM health WHERE user_id='$user'");
$data4 = mysqli_fetch_assoc($res4);
$avg_steps = round($data4['avg_steps'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PulseFit | Health</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>

<aside class="sidebar">
  <h2 class="logo">PulseFit</h2>

  <nav>
    <a href="index.php">Dashboard</a>
    <a href="workouts.php">Workouts</a>
    <a href="goals.php">Goals</a>
    <a class="active" href="health.php">Health</a>
    <a href="logout.php" class="logout-btn">Logout</a>
  </nav>
</aside>

<main class="main">

  <header class="header">
    <div>
      <h1>Health Metrics</h1>
      <p>Monitor your health and daily activity</p>
    </div>
    <button id="themeToggle">🌙</button>
  </header>
  <section class="box">
    <h2>Add Health Data</h2>

    <form method="POST">
      <input type="number" name="heart_rate" placeholder="Heart Rate (bpm)" required>
      <input type="number" step="0.1" name="weight" placeholder="Weight (kg)" required>
      <input type="number" name="steps" placeholder="Steps" required>
      <button type="submit" name="save">Save</button>
    </form>
  </section>
  <section class="cards">

    <div class="card">
      <h3><i class="fa-solid fa-heart-pulse"></i> Heart Rate</h3>
      <p class="big"><?php echo $hr; ?> bpm</p>
      <span>Latest</span>
      <p class="insight good">🟢 <?php echo $heart_status; ?></p>
    </div>

    <div class="card">
      <h3><i class="fa-solid fa-weight-scale"></i> Weight</h3>
      <p class="big"><?php echo $weight; ?> kg</p>
      <span>Latest</span>
    </div>

    <div class="card">
      <h3><i class="fa-solid fa-shoe-prints"></i> Steps Today</h3>
      <p class="big"><?php echo $steps; ?></p>
      <span>Goal: 10000</span>

      <div class="progress">
        <div class="progress-bar" style="width: <?php echo $step_percent; ?>%"></div>
      </div>
    </div>

    <div class="card">
      <h3><i class="fa-solid fa-fire"></i> Calories Burned</h3>
      <p class="big"><?php echo round($total_cal); ?></p>
      <span>Total</span>
    </div>

  </section>
  <section class="box">

    <h2>Health Summary</h2>

    <div class="item">
      <strong>Average Heart Rate</strong>
      <span><?php echo $avg_hr; ?> bpm</span>
    </div>

    <div class="item">
      <strong>Average Steps</strong>
      <span><?php echo $avg_steps; ?> / day</span>
    </div>

    <div class="item">
      <strong>Total Calories</strong>
      <span><?php echo round($total_cal); ?></span>
    </div>

  </section>
</main>
<script src="script.js"></script>
<script src="js/theme.js"></script>
</body>
</html>
