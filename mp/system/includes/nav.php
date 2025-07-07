<?php
$current_page = basename($_SERVER['PHP_SELF']);
$nav = NavigationHelper::getInstance();
?>

<nav class="border-b">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <a href="<?php echo $nav->getHomeUrl(); ?>" class="text-xl font-bold">My Playground</a>
            
            <!-- Mobile menu button -->
            <button type="button" class="md:hidden p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-muted" 
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu">
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
            </button>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="<?php echo $nav->getHomeUrl(); ?>" 
                   class="text-sm font-medium <?php echo $current_page === 'index.php' ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Home
                </a>
                <a href="<?php echo $nav->getModuleUrl('learning/card/slideshow.php'); ?>" 
                   class="text-sm font-medium <?php echo strpos($current_page, 'slideshow') !== false ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Learning
                </a>
                <a href="<?php echo $nav->getModuleUrl('management/crud/data_list.php'); ?>" 
                   class="text-sm font-medium <?php echo strpos($current_page, 'crud') !== false ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Management
                </a>
                <a href="<?php echo $nav->getModuleUrl('reading/ai_reading_coach.php'); ?>" 
                   class="text-sm font-medium <?php echo strpos($current_page, 'reading') !== false ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Reading Coach
                </a>
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="<?php echo $nav->getSystemUrl('auth/logout.php'); ?>" 
                       class="text-sm font-medium text-muted-foreground hover:text-destructive transition-colors">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo $nav->getSystemUrl('auth/login.php'); ?>" 
                       class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden py-4 space-y-4">
            <a href="<?php echo $nav->getHomeUrl(); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Home</a>
            <a href="<?php echo $nav->getModuleUrl('learning/card/slideshow.php'); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Learning</a>
            <a href="<?php echo $nav->getModuleUrl('management/crud/data_list.php'); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Management</a>
            <a href="<?php echo $nav->getModuleUrl('reading/ai_reading_coach.php'); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Reading Coach</a>
            <?php if(isset($_SESSION['id'])): ?>
                <a href="<?php echo $nav->getSystemUrl('auth/logout.php'); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Logout</a>
            <?php else: ?>
                <a href="<?php echo $nav->getSystemUrl('auth/login.php'); ?>" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav> 