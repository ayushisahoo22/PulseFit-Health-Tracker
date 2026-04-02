<?php
session_start();
include "config.php";

if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}
$user = $_SESSION['username'];
if(isset($_POST['save'])){
  $cal = $_POST['calories'];
  $workouts = $_POST['workouts'];

  $sql = "INSERT INTO goals(user_id, target_calories, target_workouts)
          VALUES('$user','$cal','$workouts')";
  mysqli_query($conn, $sql);
}
$goal_cal = 0;
$goal_workouts = 0;

$res = mysqli_query($conn,"SELECT * FROM goals WHERE user_id='$user' ORDER BY id DESC LIMIT 1");
if($row = mysqli_fetch_assoc($res)){
  $goal_cal = $row['target_calories'];
  $goal_workouts = $row['target_workouts'];
}
$res2 = mysqli_query($conn,"SELECT SUM(calories) as total_cal, COUNT(*) as total_workouts FROM workouts WHERE user_id='$user'");
$data = mysqli_fetch_assoc($res2);

$total_cal = $data['total_cal'] ?? 0;
$total_workouts = $data['total_workouts'] ?? 0;
$cal_progress = ($goal_cal > 0) ? ($total_cal / $goal_cal) * 100 : 0;
$workout_progress = ($goal_workouts > 0) ? ($total_workouts / $goal_workouts) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PulseFit | Goals</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>

<aside class="sidebar">
  <h2 class="logo">PulseFit</h2>

  <nav>
    <a href="index.php">Dashboard</a>
    <a href="workouts.php">Workouts</a>
    <a class="active" href="goals.php">Goals</a>
    <a href="health.php">Health</a>
    <a href="logout.php" class="logout-btn">Logout</a>
  </nav>
</aside>

<main class="main">

  <header class="header">
    <div>
      <h1>Fitness Goals</h1>
      <p>Track your progress</p>
    </div>
    <button id="themeToggle">🌙</button>
  </header>
  <section class="box">
    <h2>Set Your Goals</h2>

    <form method="POST">
      <input type="number" name="calories" placeholder="Target Calories" required>
      <input type="number" name="workouts" placeholder="Target Workouts" required>
      <button type="submit" name="save">Save Goal</button>
    </form>
  </section>
  <section class="cards">

    <div class="card">
      <h3><i class="fa-solid fa-bullseye"></i> Target Calories</h3>
      <p class="big"><?php echo $goal_cal; ?></p>
    </div>

    <div class="card">
      <h3><i class="fa-solid fa-fire"></i> Achieved Calories</h3>
      <p class="big"><?php echo round($total_cal); ?></p>
    </div>

    <div class="card">
      <h3><i class="fa-solid fa-list"></i> Workouts Done</h3>
      <p class="big"><?php echo $total_workouts; ?></p>
    </div>

  </section>
  <section class="box">
    <h2>Goal Progress</h2>

    <div class="item">
      <strong>Calories Goal</strong>
      <span><?php echo round($cal_progress); ?>%</span>
    </div>

    <div class="item">
      <strong>Workout Goal</strong>
      <span><?php echo round($workout_progress); ?>%</span>
    </div>

  </section>

</main>

<script src="script.js"></script>
<script src="js/theme.js"></script>

</body>
</html>
