<?php
// 즉시 오류 표시 활성화
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "=== Simple Test ===\n";

// 1. 세션 테스트
echo "1. Session test...\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "Session status: " . session_status() . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";

// 2. 로그인 확인
echo "\n2. Login check...\n";
if (!isset($_SESSION['id'])) {
    echo "Not logged in, redirecting...\n";
    header('Location: ../../system/auth/login.php');
    exit();
}
echo "Logged in as: " . $_SESSION['id'] . "\n";

// 3. Config 로드
echo "\n3. Config load...\n";
$config_path = __DIR__ . '/../../system/includes/config.php';
echo "Config path: " . $config_path . "\n";
if (file_exists($config_path)) {
    require_once $config_path;
    echo "Config loaded successfully\n";
} else {
    die("Config file not found!");
}

// 4. Database 연결
echo "\n4. Database connection...\n";
try {
    require_once __DIR__ . '/../../system/includes/Database.php';
    $db = Database::getInstance();
    echo "Database connected successfully\n";
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// 5. 사용자 정보 조회
echo "\n5. User info...\n";
try {
    $user_id = $_SESSION['id'];
    $user = $db->selectOne("SELECT * FROM users WHERE username = ?", [$user_id]);
    if ($user) {
        echo "User found: " . $user['name'] . "\n";
    } else {
        echo "User not found in database, using default\n";
        $user = ['name' => '사용자', 'username' => $user_id];
    }
} catch (Exception $e) {
    echo "User query error: " . $e->getMessage() . "\n";
    $user = ['name' => '사용자', 'username' => $user_id];
}

// 6. Header 포함
echo "\n6. Header include...\n";
try {
    $page_title = "AI Reading Coach";
    include '../../system/includes/header.php';
    echo "Header included successfully\n";
} catch (Exception $e) {
    die("Header error: " . $e->getMessage());
}

echo "\n=== Simple Test Complete ===\n";
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">AI Reading Coach - Simple Test</h1>
    <p>Hello, <?php echo htmlspecialchars($user['name']); ?>!</p>
    <p>This is a simple test page.</p>
</div>

<?php include '../../system/includes/footer.php'; ?> 