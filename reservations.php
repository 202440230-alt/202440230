<?php
include('session.php');
include('config.php');
$stmt = mysqli_prepare($db, "SELECT r.id, r.movein, r.months, r.status, u.code, u.size, u.rate FROM reservations r JOIN units u ON r.unit_id = u.id WHERE r.user_id = ? ORDER BY r.id DESC");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaveSpace - My Reservations</title>
<link rel="icon" type="image/svg+xml" href="images/savespace-logo.svg">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<div class="header-inner">
<div class="logo-block">
<img class="logo-img" src="images/savespace-logo.svg" alt="SaveSpace logo">
<div>
<h1><a href="home.php"><span class="save">Save</span><span class="space">Space</span></a></h1>
<p class="tagline">Your Self-Storage Facility</p>
</div>
</div>
<button class="hamburger" onclick="document.getElementById('main-nav').classList.toggle('nav-open')">&#9776;</button>
<nav id="main-nav">
<a href="home.php">Home</a>
<a href="reservations.php" class="active-link">My Reservations</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Log Out</a>
</nav>
</div>
</header>
<main>
<section class="content-block">
<h2>My Reservations</h2>
<?php if ($total == 0) { ?>
<p>You have no reservations yet.</p>
<?php } else { ?>
<div class="table-wrap">
<table>
<caption>Your reservations</caption>
<thead>
<tr><th>Unit</th><th>Size</th><th>Move-in</th><th>Length</th><th>Monthly Rate</th><th>Status</th><th>Action</th></tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo htmlspecialchars($row['code']); ?></td>
<td><?php echo htmlspecialchars($row['size']); ?></td>
<td><?php echo date("M j, Y", strtotime($row['movein'])); ?></td>
<td><?php echo $row['months']; ?> <?php echo $row['months'] == 1 ? 'month' : 'months'; ?></td>
<td>&#8369;<?php echo number_format($row['rate']); ?></td>
<td><mark<?php if ($row['status'] != 'confirmed') { echo ' class="' . $row['status'] . '"'; } ?>><?php echo ucfirst($row['status']); ?></mark></td>
<td>
<?php if ($row['status'] == 'pending') { ?>
<form action="cancel.php" method="post">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" class="btn small muted">Cancel Request</button>
</form>
<?php } else { ?>
None
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>
<a class="btn" href="home.php#reserve">Reserve Another Space</a>
</section>
</main>
<footer>
<div class="footer-inner">
<div class="footer-brand"><img class="footer-logo" src="images/savespace-logo.svg" alt="SaveSpace logo"><span>SaveSpace</span></div>
<div class="footer-links">
<a href="mailto:savespace@gmail.com" title="Email SaveSpace"><img class="footer-icon" src="images/savespace-icon-email.svg" alt="Email SaveSpace"></a>
<a href="tel:09950939228" title="Call SaveSpace"><img class="footer-icon" src="images/savespace-icon-phone.svg" alt="Call SaveSpace"></a>
</div>
<div class="footer-legal">
<a href="terms.html">Terms</a>
<a href="privacy.html">Privacy</a>
</div>
</div>
<p class="footer-copyright">&copy; 2026 SaveSpace. All rights reserved.</p>
</footer>
</body>
</html>