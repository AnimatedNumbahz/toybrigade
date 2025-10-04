<?php
// footer.php — shared scripts and closing tags
// Usage from a page (BEFORE including this file):
//   $page_scripts = ['../js/page-specific.js']; // optional
//   include __DIR__ . '/footer.php';

if (!isset($page_scripts) || !is_array($page_scripts)) {
  $page_scripts = [];
}

// Shared scripts (Bootstrap bundle includes Popper)
$shared_js = [
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
  '../js/nav.js'
];

// Merge: shared first → then page-specific
$all_js = array_merge($shared_js, $page_scripts);
?>
<?php foreach ($all_js as $src): ?>
  <script src="<?= htmlspecialchars($src) ?>"></script>
<?php endforeach; ?>
</body>
</html>
