<?php include('session.php'); ?>
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
<div class="table-wrap">
<table>
<caption>Your reservations</caption>
<thead>
<tr><th>Unit</th><th>Size</th><th>Move-in</th><th>Monthly Rate</th><th>Status</th><th>Action</th></tr>
</thead>
<tbody>
<tr><td>B-12</td><td>Medium Room</td><td>Nov 1, 2026</td><td>&#8369;1,500</td><td><mark class="pending">Pending</mark></td><td><a class="btn small muted" href="reservations.php">Cancel Request</a></td></tr>
<tr><td>B-10</td><td>Medium Room</td><td>Sep 1, 2026</td><td>&#8369;1,500</td><td><mark>Confirmed</mark></td><td>None</td></tr>
<tr><td>A-07</td><td>Small Locker</td><td>Aug 15, 2026</td><td>&#8369;500</td><td><mark>Confirmed</mark></td><td>None</td></tr>
<tr><td>A-05</td><td>Small Locker</td><td>Feb 1, 2026</td><td>&#8369;500</td><td><mark class="completed">Completed</mark></td><td>None</td></tr>
<tr><td>C-21</td><td>Large Room</td><td>Jan 10, 2026</td><td>&#8369;3,000</td><td><mark class="cancelled">Cancelled</mark></td><td>None</td></tr>
</tbody>
</table>
</div>
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