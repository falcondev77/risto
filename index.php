<?php
require __DIR__.'/config.php';
require __DIR__.'/functions.php';
$siteName     = setting($pdo, 'site_name')     ?? 'La Mozzata';
$siteSubtitle = setting($pdo, 'site_subtitle')  ?? 'Sapori autentici italiani attorno all\'arte della mozzarella fresca.';
$siteBgUrl    = setting($pdo, 'site_bg_url')    ?? 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=1600';
$siteLocation = setting($pdo, 'site_location')  ?? 'Roma';
$siteHours    = setting($pdo, 'site_hours')     ?? '19:00 - 23:00';
?>
<!DOCTYPE html>
<html class="dark" lang="it">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= h($siteName) ?></title>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#ec4913",
            "background-light": "#f8f6f6",
            "background-dark": "#221510",
            "surface-dark": "#2e1e19",
          },
          fontFamily: {
            "display": ["Manrope", "sans-serif"],
            "serif": ["Playfair Display", "serif"],
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
          backgroundImage: {
            'hero-pattern': "linear-gradient(to bottom, rgba(34, 21, 16, 0.4), rgba(34, 21, 16, 0.9))",
          }
        },
      },
    }
  </script>
  <style>
    body { font-family: 'Manrope', "Noto Sans", sans-serif; }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp .7s ease both; }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-300">

  <div class="relative flex flex-grow w-full flex-col overflow-hidden">

    <!-- Hero Background -->
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-hero-pattern z-10"></div>
      <div class="w-full h-full bg-cover bg-center opacity-40 mix-blend-overlay"
           style="background-image: url('<?= h($siteBgUrl) ?>');"></div>
    </div>

    <div class="layout-container flex h-full grow flex-col relative z-20 justify-center items-center">

      <!-- Top Bar -->
      <div class="absolute top-0 w-full px-8 py-6 flex justify-between items-center opacity-80">
        <div class="flex gap-2">
          <span class="material-symbols-outlined text-white/70 hover:text-primary transition-colors cursor-pointer">menu</span>
        </div>
        <div class="flex gap-4">
          <a href="/admin/login.php" class="material-symbols-outlined text-white/70 hover:text-primary transition-colors cursor-pointer no-underline" style="text-decoration:none;font-family:'Material Symbols Outlined';">admin_panel_settings</a>
        </div>
      </div>

      <!-- Central Content -->
      <div class="px-4 md:px-40 flex flex-1 justify-center items-center py-5 w-full">
        <div class="flex flex-col max-w-[960px] flex-1 items-center animate-fade-in-up">

          <!-- Icon -->
          <div class="mb-6 text-primary opacity-90">
            <span class="material-symbols-outlined text-5xl">restaurant</span>
          </div>

          <!-- Title -->
          <h1 class="text-white font-serif text-[48px] md:text-[64px] font-bold leading-tight px-4 text-center pb-2 tracking-wide drop-shadow-sm">
            <?= h($siteName) ?>
          </h1>

          <!-- Subtitle -->
          <p class="text-slate-300 text-lg md:text-xl text-center font-display font-light mb-12 max-w-md mx-auto leading-relaxed">
            <?= h($siteSubtitle) ?>
          </p>

          <!-- Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 w-full justify-center max-w-[480px]">
            <a href="/prenota.php" class="group flex items-center justify-center overflow-hidden rounded-full h-14 px-8 bg-primary hover:bg-[#ff5a20] text-white text-lg font-bold leading-normal tracking-wide transition-all duration-300 hover:shadow-[0_0_20px_rgba(236,73,19,0.3)] w-full sm:w-auto min-w-[160px] no-underline" style="text-decoration:none;">
              <span class="mr-2">Prenota</span>
              <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">calendar_today</span>
            </a>
            <a href="/menu.php" class="group flex items-center justify-center overflow-hidden rounded-full h-14 px-8 bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/40 text-white text-lg font-bold leading-normal tracking-wide backdrop-blur-sm transition-all duration-300 w-full sm:w-auto min-w-[160px] no-underline" style="text-decoration:none;">
              <span class="mr-2">Menu</span>
              <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">restaurant_menu</span>
            </a>
          </div>

          <!-- Location / Hours -->
          <div class="mt-20 flex gap-8 items-center justify-center text-slate-400 text-sm font-medium tracking-widest uppercase">
            <a class="hover:text-primary transition-colors flex items-center gap-1 no-underline" href="#" style="text-decoration:none;">
              <span class="material-symbols-outlined text-[18px]">location_on</span> <?= h($siteLocation) ?>
            </a>
            <span class="w-1 h-1 rounded-full bg-slate-600"></span>
            <a class="hover:text-primary transition-colors flex items-center gap-1 no-underline" href="#" style="text-decoration:none;">
              <span class="material-symbols-outlined text-[18px]">schedule</span> <?= h($siteHours) ?>
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="relative z-20 w-full py-4 text-center">
      <p class="text-xs text-white/20 font-display">© 2024 La Mozzata Experience</p>
    </div>

  </div>
</body>
</html>
