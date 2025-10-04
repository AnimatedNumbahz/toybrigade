<?php
// header.php — shared HTML <head> and opening <body>
// Usage in a page:
//   $page_title = "Toy Brigade | Page Title";
//   $page_css = ['../css/page.css?v=1'];   // optional, page-specific CSS
//   include __DIR__ . '/header.php';

if (!isset($page_title)) $page_title = 'Toy Brigade';
if (!isset($page_css))   $page_css = [];

// Shared site CSS (always first, before page CSS)
$shared_css = [
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  '../css/style.css',
  '../css/navbar.css',
  '../css/footer.css',
  '../css/cart.css',
  '../css/verification.css',
];

// Optional icon font (your pages use <i class="fas ...">)
$icon_css = [
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'
];

// Merge in order: Bootstrap → shared → icons → page CSS
$all_css = array_merge($shared_css, $icon_css, $page_css);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Baloo+2:wght@400;600&display=swap" rel="stylesheet">

<?php foreach ($all_css as $href): ?>
  <link rel="stylesheet" href="<?= $href ?>">
<?php endforeach; ?>
</head>
<body>
