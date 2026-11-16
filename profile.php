<?php
include('session.php');
include('config.php');
$images = ["Small Locker" => "savespace-small-locker.svg", "Medium Room" => "savespace-medium-storage-room.svg", "Large Room" => "savespace-large-storage-room.svg"];

$stmt = mysqli_prepare($db, "SELECT r.movein, r.months, r.status, u.code, u.size, u.rate FROM reservations r JOIN units u ON r.unit_id = u.id WHERE r.user_id = ? AND r.status IN ('confirmed', 'completed') ORDER BY r.movein DESC");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$active = [];
$history = [];
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] == 'confirmed') {
        $active[] = $row;
    } else {
        $history[] = $row;
    }
}
$today = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaveSpace - Profile</title>
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
<a href="reservations.php">My Reservations</a>
<a href="profile.php" class="active-link">Profile</a>
<a href="logout.php">Log Out</a>
</nav>
</div>
</header>
<main>
<section class="content-block info-box">
<h2>Account Summary</h2>
<ul>
<li>Name: <?php echo htmlspecialchars($_SESSION['fullname']); ?></li>
<li>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></li>
<li>Contact: <?php echo htmlspecialchars($_SESSION['contact']); ?></li>
<li>Member Since: <?php echo date("F Y", strtotime($_SESSION['created_at'])); ?></li>
</ul>
</section>
<section class="content-block info-box">
<h2>Rental Tracking</h2>
<?php if (count($active) == 0) { ?>
<p>You have no active rentals yet. Once the owner confirms a reservation, it shows up here.</p>
<?php } else { ?>
<p>You have <?php echo count($active); ?> active <?php echo count($active) == 1 ? 'rental' : 'rentals'; ?>.</p>
<?php foreach ($active as $r) { $moved_in = $r['movein'] <= $today; ?>
<article class="rental">
<h3>Unit <?php echo htmlspecialchars($r['code']); ?> &middot; <?php echo htmlspecialchars($r['size']); ?></h3>
<ol class="stages">
<li class="done">Requested</li>
<li class="done">Confirmed</li>
<li<?php if ($moved_in) { echo ' class="done"'; } ?>>Moved In</li>
<li<?php if ($moved_in) { echo ' class="done current"'; } ?>>Active</li>
</ol>
<div class="tracking">
<figure>
<img src="images/<?php echo $images[$r['size']]; ?>" alt="Your rented unit <?php echo htmlspecialchars($r['code']); ?>">
<figcaption>Unit <?php echo htmlspecialchars($r['code']); ?></figcaption>
</figure>
<dl>
<dt>Rental Status</dt>
<dd><mark><?php echo $moved_in ? 'Active' : 'Confirmed'; ?></mark></dd>
<dt>Monthly Rent</dt>
<dd>&#8369;<?php echo number_format($r['rate']); ?></dd>
<dt>Rental Length</dt>
<dd><?php echo $r['months']; ?> <?php echo $r['months'] == 1 ? 'month' : 'months'; ?></dd>
<dt>Move-in Date</dt>
<dd><?php echo date("F j, Y", strtotime($r['movein'])); ?></dd>
<dt>Rental Ends</dt>
<dd><?php echo date("F j, Y", strtotime($r['movein'] . " +" . $r['months'] . " months")); ?></dd>
</dl>
</div>
</article>
<?php } ?>
<?php } ?>
</section>
<section class="content-block info-box">
<h2>Rental History</h2>
<p>Rentals that have ended are moved here, so Rental Tracking only shows your active units.</p>
<?php if (count($history) == 0) { ?>
<p>No finished rentals yet.</p>
<?php } else { ?>
<div class="table-wrap">
<table>
<thead>
<tr><th>Unit</th><th>Size</th><th>Rental Period</th><th>Total Paid</th><th>Status</th></tr>
</thead>
<tbody>
<?php foreach ($history as $r) { ?>
<tr><td><?php echo htmlspecialchars($r['code']); ?></td><td><?php echo htmlspecialchars($r['size']); ?></td><td><?php echo date("M j, Y", strtotime($r['movein'])); ?> to <?php echo date("M j, Y", strtotime($r['movein'] . " +" . $r['months'] . " months -1 day")); ?></td><td>&#8369;<?php echo number_format($r['rate'] * $r['months']); ?></td><td><mark class="completed">Completed</mark></td></tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>
</section>
<section class="content-block info-box">
<h2>Update Your Details</h2>
<form action="update_profile.php" method="post">
<label for="pname">Full Name</label>
<input type="text" id="pname" name="pname" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>" required>
<label for="pemail">Email</label>
<input type="email" id="pemail" name="pemail" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
<label for="pcontact">Contact Number</label>
<input type="tel" id="pcontact" name="pcontact" value="<?php echo htmlspecialchars($_SESSION['contact']); ?>" pattern="09[0-9]{9}" title="11 digits starting with 09" required>
<button type="submit" class="btn">Save Changes</button>
</form>
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