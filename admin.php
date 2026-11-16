<?php
include('session.php');
include('config.php');
if ($_SESSION['role'] != 'admin') {
    header("Location: home.php");
    die();
}
$total_units = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM units"))[0];
$occupied = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM units WHERE status = 'occupied'"))[0];
$pending = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM reservations WHERE status = 'pending'"))[0];
$income = mysqli_fetch_row(mysqli_query($db, "SELECT COALESCE(SUM(u.rate), 0) FROM reservations r JOIN units u ON r.unit_id = u.id WHERE r.status = 'confirmed'"))[0];
$requests = mysqli_query($db, "SELECT r.id, r.movein, r.status, c.fullname, u.code FROM reservations r JOIN users c ON r.user_id = c.id JOIN units u ON r.unit_id = u.id WHERE r.status IN ('pending', 'confirmed') ORDER BY r.status DESC, r.movein");
$units = mysqli_query($db, "SELECT id, code, size, rate, status FROM units ORDER BY code");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaveSpace - Admin</title>
<link rel="icon" type="image/svg+xml" href="images/savespace-logo.svg">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<div class="header-inner">
<div class="logo-block">
<img class="logo-img" src="images/savespace-logo.svg" alt="SaveSpace logo">
<div>
<h1><a href="admin.php"><span class="save">Save</span><span class="space">Space</span></a></h1>
<p class="tagline">Your Self-Storage Facility</p>
</div>
</div>
<button class="hamburger" onclick="document.getElementById('main-nav').classList.toggle('nav-open')">&#9776;</button>
<nav id="main-nav">
<a href="admin.php" class="active-link">Dashboard</a>
<a href="logout.php">Log Out</a>
</nav>
</div>
</header>
<main>
<section class="content-block">
<h2>Owner Dashboard</h2>
<div class="tiles">
<div class="tile"><p class="num"><?php echo $total_units; ?></p><p>Total Units</p></div>
<div class="tile"><p class="num"><?php echo $occupied; ?></p><p>Occupied</p></div>
<div class="tile"><p class="num"><?php echo $pending; ?></p><p>Pending Requests</p></div>
<div class="tile"><p class="num">&#8369;<?php echo number_format($income); ?></p><p>Monthly Income</p></div>
</div>
</section>
<section id="requests" class="content-block">
<h2>Reservation Requests</h2>
<div class="table-wrap">
<table>
<caption>Pending and confirmed reservations</caption>
<thead>
<tr><th>Customer</th><th>Unit</th><th>Move-in</th><th>Status</th><th>Action</th></tr>
</thead>
<tbody>
<?php while ($r = mysqli_fetch_assoc($requests)) { ?>
<tr>
<td><?php echo htmlspecialchars($r['fullname']); ?></td>
<td><?php echo htmlspecialchars($r['code']); ?></td>
<td><?php echo date("M j, Y", strtotime($r['movein'])); ?></td>
<td><mark<?php if ($r['status'] == 'pending') { echo ' class="pending"'; } ?>><?php echo ucfirst($r['status']); ?></mark></td>
<td>
<form action="admin_action.php" method="post" class="row-actions">
<input type="hidden" name="id" value="<?php echo $r['id']; ?>">
<?php if ($r['status'] == 'pending') { ?>
<button type="submit" name="action" value="confirm" class="btn small">Confirm</button>
<button type="submit" name="action" value="cancel" class="btn small muted">Cancel</button>
<?php } else { ?>
<button type="submit" name="action" value="complete" class="btn small">Mark Completed</button>
<?php } ?>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</section>
<section id="units" class="content-block">
<h2>Storage Units</h2>
<div class="table-wrap">
<table>
<caption>All units in the facility</caption>
<thead>
<tr><th>Unit</th><th>Size</th><th>Rate</th><th>Availability</th><th>Change</th></tr>
</thead>
<tbody>
<?php while ($u = mysqli_fetch_assoc($units)) { ?>
<tr>
<td><?php echo htmlspecialchars($u['code']); ?></td>
<td><?php echo htmlspecialchars($u['size']); ?></td>
<td>&#8369;<?php echo number_format($u['rate']); ?></td>
<td><mark<?php if ($u['status'] == 'occupied') { echo ' class="cancelled"'; } elseif ($u['status'] == 'reserved') { echo ' class="pending"'; } ?>><?php echo ucfirst($u['status']); ?></mark></td>
<td>
<form action="admin_action.php" method="post" class="row-actions">
<input type="hidden" name="action" value="unit">
<input type="hidden" name="id" value="<?php echo $u['id']; ?>">
<select name="status" aria-label="Set availability for <?php echo htmlspecialchars($u['code']); ?>">
<?php foreach (['available', 'limited', 'reserved', 'occupied'] as $s) { ?>
<option value="<?php echo $s; ?>"<?php if ($u['status'] == $s) { echo ' selected'; } ?>><?php echo ucfirst($s); ?></option>
<?php } ?>
</select>
<button type="submit" class="btn small">Update</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
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
