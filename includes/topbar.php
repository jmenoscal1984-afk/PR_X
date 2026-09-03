<?php
$page_title_header = $page_title_header ?? 'Dashboard';
$page_subtitle = $page_subtitle ?? '';
?>
<!-- Topbar -->
<header class="topbar">
  <div class="topbar-left">
    <button class="hamburger-btn" id="hamburger-btn"><i class="fas fa-bars"></i></button>
    <div>
      <div class="topbar-title"><?= htmlspecialchars($page_title_header) ?></div>
      <div class="topbar-subtitle" id="topbar-greeting"><?= htmlspecialchars($page_subtitle) ?></div>
    </div>
  </div>
  <div class="topbar-right">
    <div class="topbar-chip xp" id="topbar-xp">⭐ 0 XP</div>
    <div class="topbar-chip streak" id="topbar-streak">🔥 0 días</div>
  </div>
</header>
