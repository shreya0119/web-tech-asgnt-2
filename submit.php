<?php
// Server-side: re-validate (never trust the browser), then display the data.
$h = fn($k) => htmlspecialchars(trim($_POST[$k] ?? ''));   // escape to prevent XSS
$interests = array_map('htmlspecialchars', $_POST['interests'] ?? []);

$ok = $_SERVER['REQUEST_METHOD'] === 'POST'
   && strlen($h('name')) >= 3
   && filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)
   && preg_match('/^\d{10}$/', $_POST['phone'] ?? '')
   && $h('dob') && $h('gender') && $h('course') && strlen($h('address')) >= 10;

$rows = [
  'Application No.' => 'APP-' . date('Y') . '-' . random_int(1000, 9999),
  'Full name'       => $h('name'),
  'Date of birth'   => $h('dob'),
  'Gender'          => $h('gender'),
  'Email'           => $h('email'),
  'Phone'           => $h('phone'),
  'Course'          => $h('course'),
  'Interests'       => $interests ? implode(', ', $interests) : '—',
  'Address'         => nl2br($h('address')),
  'Submitted on'    => date('d M Y, h:i A'),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Application Result</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="card">
  <header><h1>Application <?= $ok ? 'Received' : 'Incomplete' ?></h1><p>Course Application Form</p></header>
  <div class="body">
  <?php if ($ok): ?>
    <div class="ok">&#10003; Your application was submitted successfully.</div>
    <table>
      <?php foreach ($rows as $k => $v): ?><tr><th><?= $k ?></th><td><?= $v ?></td></tr><?php endforeach; ?>
    </table>
    <a class="btn" href="#" onclick="window.print();return false">Print</a>
  <?php else: ?>
    <p>Some details are missing or invalid. Please go back and check the form.</p>
  <?php endif; ?>
    <a class="btn" href="index.html">&larr; Back to form</a>
  </div>
</div>
</body>
</html>
