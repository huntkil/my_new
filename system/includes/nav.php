<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="border-b">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <a href="/" class="text-xl font-bold">My Playground</a>
            
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
                <a href="/" 
                   class="text-sm font-medium <?php echo $current_page === 'index.html' ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Home
                </a>
                <a href="/about.html" 
                   class="text-sm font-medium <?php echo $current_page === 'about.html' ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    About
                </a>
                <a href="/contact.html" 
                   class="text-sm font-medium <?php echo $current_page === 'contact.html' ? 'text-primary' : 'text-muted-foreground hover:text-primary'; ?> transition-colors">
                    Contact
                </a>
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="/system/auth/logout.php" 
                       class="text-sm font-medium text-muted-foreground hover:text-destructive transition-colors">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/system/auth/login.php" 
                       class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden py-4 space-y-4">
            <a href="/" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Home</a>
            <a href="/about.html" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">About</a>
            <a href="/contact.html" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Contact</a>
            <?php if(isset($_SESSION['id'])): ?>
                <a href="/system/auth/logout.php" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Logout</a>
            <?php else: ?>
                <a href="/system/auth/login.php" class="block text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav> 