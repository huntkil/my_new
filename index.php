<?php
require_once './system/includes/config.php';
$pageTitle = "Home";
include './system/includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
  <div class="max-w-4xl mx-auto space-y-12">
    <!-- Hero Section -->
    <section class="hidden md:block text-center space-y-4">
      <h1 class="text-4xl sm:text-5xl font-bold">Welcome to My Playground</h1>
      <p class="text-xl text-muted-foreground">A collection of web development experiments and tools</p>
    </section>

    <!-- Features Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Card Slideshow -->
      <a href="./modules/learning/card/slideshow.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-images"><path d="M18 22H4a2 2 0 0 1-2-2V4"/><path d="m2 15 3.086-3.086a2 2 0 0 1 2.828 0L12 14l4-4"/><path d="m14 8 2.086-2.086a2 2 0 0 1 2.828 0L20 8"/><circle cx="18" cy="18" r="2"/><path d="M8 22h8"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Card Slideshow</h2>
          </div>
          <p class="text-muted-foreground">Interactive card slideshow to visualize words.</p>
        </div>
      </a>

      <!-- Visualization EN -->
      <a href="./modules/learning/card/wordcard_en.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Word Visualization (EN)</h2>
          </div>
          <p class="text-muted-foreground">Word visualization in English.</p>
        </div>
      </a>

      <!-- Visualization KR -->
      <a href="./modules/learning/card/wordcard_ko.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-2"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Word Visualization (KR)</h2>
          </div>
          <p class="text-muted-foreground">Word visualization in Korean.</p>
        </div>
      </a>

      <!-- Vocabulary Tool -->
      <a href="./modules/learning/voca/voca.html" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Vocabulary</h2>
          </div>
          <p class="text-muted-foreground">Manage and learn new words with our vocabulary tool</p>
        </div>
      </a>

      <!-- News Search -->
                              <a href="./modules/tools/news/search_news_form.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M18 18h-8"/><path d="M18 10h-8"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">News Search</h2>
          </div>
          <p class="text-muted-foreground">Search and explore the latest news articles</p>
        </div>
      </a>

      <!-- Family Tour -->
      <a href="./modules/tools/tour/familytour.html" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Family Tour</h2>
          </div>
          <p class="text-muted-foreground">Explore Gyeongju with our family tour guide</p>
        </div>
      </a>

      <!-- CRUD Demo -->
      <a href="./modules/management/crud/data_list.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">CRUD Demo</h2>
          </div>
          <p class="text-muted-foreground">A simple CRUD application demonstration</p>
        </div>
      </a>

      <!-- Box Breathing -->
      <a href="./modules/tools/box/boxbreathe.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cards"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">Box Breathing</h2>
          </div>
          <p class="text-muted-foreground">Explore<br> our box breathing guide</p>
        </div>
      </a>

      <!-- My Health -->
      <a href="./modules/management/myhealth/health_list.php" class="group">
        <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-4 hover:border-primary transition-colors">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-pulse"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/></svg>
            <h2 class="text-xl font-semibold group-hover:text-primary transition-colors">My Health</h2>
          </div>
          <p class="text-muted-foreground">Track and manage your health information</p>
        </div>
      </a>
    </section>

    <!-- Private Section -->
    <section class="space-y-6">
      <div class="space-y-2">
        <h2 class="text-2xl font-bold">Private Section</h2>
        <p class="text-muted-foreground">Access to private resources and tools.</p>
      </div>

      <?php if(!isset($_SESSION['id'])): ?>
        <div class="bg-card text-card-foreground rounded-lg border shadow-sm p-6">
          <p class="text-muted-foreground mb-4">This section requires login.</p>
          <form action="system/auth/login_check.php" method="post" class="space-y-4">
            <div class="space-y-2">
              <label for="id" class="text-sm font-medium">ID</label>
              <input type="text" id="id" name="id" required
                     class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
            </div>
            <div class="space-y-2">
              <label for="password" class="text-sm font-medium">Password</label>
              <input type="password" id="password" name="password" required
                     class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
              Login
            </button>
          </form>
        </div>
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Hosting Index File -->
        <div class="bg-card text-card-foreground rounded-lg border shadow-sm p-6 space-y-4 <?php echo !isset($_SESSION['id']) ? 'opacity-50' : ''; ?>">
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-semibold">Hosting Index File</h3>
          </div>
          <p class="text-sm text-muted-foreground">Access to hosting configuration files and settings.</p>
          <?php if(isset($_SESSION['id'])): ?>
            <a href="./hosting_index.html" 
               class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
              Access
            </a>
          <?php else: ?>
            <button disabled
                    class="inline-flex items-center justify-center rounded-md bg-muted px-4 py-2 text-sm font-medium text-muted-foreground cursor-not-allowed">
              Login Required
            </button>
          <?php endif; ?>
        </div>

        <!-- phpMyAdmin -->
        <div class="bg-card text-card-foreground rounded-lg border shadow-sm p-6 space-y-4 <?php echo !isset($_SESSION['id']) ? 'opacity-50' : ''; ?>">
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
            </svg>
            <h3 class="text-lg font-semibold">phpMyAdmin</h3>
          </div>
          <p class="text-sm text-muted-foreground">Database management interface for MySQL.</p>
          <?php if(isset($_SESSION['id'])): ?>
            <a href="./phpMyAdmin" 
               class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
              Access
            </a>
          <?php else: ?>
            <button disabled
                    class="inline-flex items-center justify-center rounded-md bg-muted px-4 py-2 text-sm font-medium text-muted-foreground cursor-not-allowed">
              Login Required
            </button>
          <?php endif; ?>
        </div>

        <!-- My Health -->
        <div class="bg-card text-card-foreground rounded-lg border shadow-sm p-6 space-y-4 <?php echo !isset($_SESSION['id']) ? 'opacity-50' : ''; ?>">
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h3 class="text-lg font-semibold">My Health</h3>
          </div>
          <p class="text-sm text-muted-foreground">Track and manage your health information.</p>
          <?php if(isset($_SESSION['id'])): ?>
            <a href="./modules/management/myhealth/health_list.php" 
               class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
              Access
            </a>
          <?php else: ?>
            <button disabled
                    class="inline-flex items-center justify-center rounded-md bg-muted px-4 py-2 text-sm font-medium text-muted-foreground cursor-not-allowed">
              Login Required
            </button>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include './system/includes/footer.php'; ?>
