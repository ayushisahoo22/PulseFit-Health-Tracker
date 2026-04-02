<?php
session_start();
include "config.php";

if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}

$user = $_SESSION['username'];

// ✅ workouts count
$res1 = mysqli_query($conn,"SELECT COUNT(*) as total FROM workouts WHERE user_id='$user'");
$workouts = mysqli_fetch_assoc($res1)['total'] ?? 0;

// ✅ calories
$res2 = mysqli_query($conn,"SELECT SUM(calories) as total FROM workouts WHERE user_id='$user'");
$calories = mysqli_fetch_assoc($res2)['total'] ?? 0;

// ✅ goals
$res3 = mysqli_query($conn,"SELECT COUNT(*) as total FROM goals WHERE user_id='$user'");
$goals = mysqli_fetch_assoc($res3)['total'] ?? 0;

// ✅ avg heart rate
$res4 = mysqli_query($conn,"SELECT AVG(heart_rate) as avg_hr FROM health WHERE user_id='$user'");
$avg_hr = round(mysqli_fetch_assoc($res4)['avg_hr'] ?? 0);
$status = ($avg_hr >= 60 && $avg_hr <= 100) ? "Healthy" : "Check";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PulseFit | Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <aside class="sidebar">
    <h2 class="logo">PulseFit</h2>

    <nav>
      <a class="active" href="index.php">Dashboard</a>
      <a href="workouts.php">Workouts</a>
      <a href="goals.php">Goals</a>
      <a href="health.php">Health</a>

      <a href="logout.php" class="logout-btn">Logout</a>
    </nav>
  </aside>

  <main class="main">

    <header class="header">
      <div>
        <h1>Welcome Back 👋</h1>
        <p>Your fitness overview</p>
      </div>

      <button id="themeToggle">🌙</button>
    </header>

    <section class="motivation">
      <p class="quote">"Small progress is still progress."</p>
      <p class="streak">🔥 7-day workout streak</p>
    </section>

    <section class="cards">

      <div class="card">
        <h3><i class="fa-solid fa-dumbbell"></i> Workouts</h3>
        <p class="big"><?php echo $workouts; ?></p>
        <span>This week</span>
      </div>

      <div class="card">
        <h3><i class="fa-solid fa-fire"></i> Calories</h3>
        <p class="big"><?php echo round($calories); ?></p>
        <span>Burned</span>
      </div>

      <div class="card">
        <h3><i class="fa-solid fa-bullseye"></i> Active Goals</h3>
        <p class="big"><?php echo $goals; ?></p>
        <span>In progress</span>
      </div>

      <div class="card">
        <h3><i class="fa-solid fa-heart-pulse"></i> Avg Heart Rate</h3>
        <p class="big"><?php echo $avg_hr; ?> bpm</p>
<span><?php echo $status; ?></span>
      </div>

    </section>

  </main>

  <script src="script.js"></script>
  <script src="js/theme.js"></script>

</body>
</html>