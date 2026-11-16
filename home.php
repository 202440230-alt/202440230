<?php include('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaveSpace - Home</title>
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
<a href="home.php" class="active-link">Home</a>
<a href="reservations.php">My Reservations</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Log Out</a>
</nav>
</div>
</header>
<main>
<section class="hero">
<div class="hero-text">
<h2>Welcome back, <?php echo htmlspecialchars($login_session); ?>!</h2>
<p>Reserve a new space below or check your existing reservations.</p>
</div>
</section>
<section class="content-block">
<h2>Quick Actions</h2>
<div class="action-list">
<div class="action-card"><p>Browse Spaces</p><a class="btn small" href="index.html#storage">Open</a></div>
<div class="action-card"><p>My Reservations</p><a class="btn small" href="reservations.php">Open</a></div>
<div class="action-card"><p>Profile</p><a class="btn small" href="profile.php">Open</a></div>
</div>
</section>
<section id="reserve" class="content-block info-box">
<h2>Reserve a Space</h2>
<form action="reserve.php" method="post">
<fieldset>
<legend>Choose your unit</legend>
<label for="size">Storage Size</label>
<select id="size" name="size">
<option value="Small Locker">Small Locker - &#8369;500 / month</option>
<option value="Medium Room">Medium Room - &#8369;1,500 / month</option>
<option value="Large Room">Large Room - &#8369;3,000 / month</option>
</select>
<label for="movein">Move-in Date</label>
<input type="date" id="movein" name="movein" min="<?php echo date('Y-m-d'); ?>" required>
<label for="months">Rental Length</label>
<select id="months" name="months">
<option value="1">1 month</option>
<option value="3">3 months</option>
<option value="6">6 months</option>
<option value="12">12 months</option>
</select>
</fieldset>
<fieldset>
<legend>Anything we should know?</legend>
<label for="notes">Notes</label>
<textarea id="notes" name="notes" rows="4" maxlength="300" placeholder="What will you store?"></textarea>
</fieldset>
<button type="submit" class="btn">Submit Request</button>
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
