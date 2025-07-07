<?php
// 즉시 오류 표시 활성화
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Main application entry point for /mp
    // 호스팅 환경에서는 production config 사용
    if (file_exists(__DIR__ . '/../../system/includes/config_production.php')) {
        require_once __DIR__ . '/../../system/includes/config_production.php';
    } else {
        require_once __DIR__ . '/../../system/includes/config.php';
    }
} catch (Exception $e) {
    die("Config error: " . $e->getMessage());
}

// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 사용자 로그인 확인
if (!isset($_SESSION['id'])) {
    header('Location: ../../system/auth/login.php');
    exit();
}

try {
    require_once __DIR__ . '/../../system/includes/Database.php';
    $db = Database::getInstance();
    $user_id = $_SESSION['id'];
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// 사용자 정보 가져오기
try {
    $user = $db->selectOne("SELECT * FROM users WHERE username = ?", [$user_id]);
    if (!$user) {
        // 사용자가 없으면 기본값 설정
        $user = [
            'name' => '사용자',
            'username' => $user_id
        ];
    }
} catch (Exception $e) {
    error_log("Database error in ai_reading_coach.php: " . $e->getMessage());
    // 오류 발생 시 기본값 설정
    $user = [
        'name' => '사용자',
        'username' => $user_id
    ];
}

// 사용자 진도 데이터 가져오기 (나중에 구현)
$progress_data = [
    'current_wpm' => 180,
    'target_wpm' => 300,
    'subvocalization_detected' => true,
    'daily_goal' => 80,
    'daily_progress' => 65
];

try {
    $page_title = "AI Reading Coach";
    include '../../system/includes/header.php';
} catch (Exception $e) {
    die("Header error: " . $e->getMessage());
}
?>

        <!-- 메인 대시보드 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- 현재 상태 카드 -->
            <div class="bg-card border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">현재 읽기 상태</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">현재 속도 (WPM)</span>
                        <span class="text-2xl font-bold text-blue-600"><?php echo $progress_data['current_wpm']; ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">목표 속도</span>
                        <span class="text-lg font-semibold text-green-600"><?php echo $progress_data['target_wpm']; ?> WPM</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">속발음 감지</span>
                        <span class="px-2 py-1 rounded text-sm <?php echo $progress_data['subvocalization_detected'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                            <?php echo $progress_data['subvocalization_detected'] ? '감지됨' : '정상'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 일일 목표 카드 -->
            <div class="bg-card border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">오늘의 목표</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-muted-foreground">진행률</span>
                        <span class="text-lg font-semibold"><?php echo $progress_data['daily_progress']; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress_data['daily_progress']; ?>%"></div>
                    </div>
                    <div class="text-sm text-muted-foreground">
                        목표: <?php echo $progress_data['daily_goal']; ?>분 연습
                    </div>
                </div>
            </div>

            <!-- 빠른 시작 카드 -->
            <div class="bg-card border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">빠른 시작</h3>
                <div class="space-y-3">
                    <button onclick="startQuickSession()" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                        🚀 빠른 연습 시작
                    </button>
                    <button onclick="showRecommendations()" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition-colors">
                        💡 AI 추천 연습
                    </button>
                    <button onclick="viewProgress()" class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 transition-colors">
                        📊 진도 확인
                    </button>
                </div>
            </div>
        </div>

        <!-- 연습 모듈 그리드 -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-6">연습 모듈</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- 시각적 읽기 연습 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-500/20 transition-colors">
                            <span class="text-2xl">👁️</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">시각적 읽기 연습</h3>
                            <p class="text-sm text-muted-foreground">단어 덩어리 인식 훈련</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">단어나 문장을 덩어리로 인식하여 읽기 속도를 향상시킵니다.</p>
                    <a href="exercises/visual_reading.php" class="inline-block bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                        연습 시작
                    </a>
                </div>

                <!-- 리듬감 있는 읽기 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-500/20 transition-colors">
                            <span class="text-2xl">🎵</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">리듬감 있는 읽기</h3>
                            <p class="text-sm text-muted-foreground">메트로놈과 함께 읽기</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">일정한 리듬에 맞춰 읽어 속발음을 줄이고 속도를 높입니다.</p>
                    <a href="exercises/rhythm_reading.php" class="inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition-colors">
                        연습 시작
                    </a>
                </div>

                <!-- 손가락 따라가기 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-500/20 transition-colors">
                            <span class="text-2xl">👆</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">손가락 따라가기</h3>
                            <p class="text-sm text-muted-foreground">가상 손가락으로 가이드</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">가상 손가락을 따라가며 자연스럽게 읽기 속도를 높입니다.</p>
                    <a href="exercises/finger_reading.php" class="inline-block bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 transition-colors">
                        연습 시작
                    </a>
                </div>

                <!-- 핵심 단어 찾기 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-orange-500/20 transition-colors">
                            <span class="text-2xl">🎯</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">핵심 단어 찾기</h3>
                            <p class="text-sm text-muted-foreground">의미 중심 읽기</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">핵심 단어만 골라서 읽어 의미 파악 능력을 향상시킵니다.</p>
                    <a href="exercises/keyword_reading.php" class="inline-block bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 transition-colors">
                        연습 시작
                    </a>
                </div>

                <!-- 입술/혀 고정 연습 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-500/20 transition-colors">
                            <span class="text-2xl">🤐</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">입술/혀 고정 연습</h3>
                            <p class="text-sm text-muted-foreground">물리적 속발음 방지</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">입술과 혀를 고정하여 물리적으로 속발음을 방지합니다.</p>
                    <a href="exercises/lip_tongue_fix.php" class="inline-block bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition-colors">
                        연습 시작
                    </a>
                </div>

                <!-- 단계적 연습 -->
                <div class="group bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-indigo-500/20 transition-colors">
                            <span class="text-2xl">📈</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">단계적 연습</h3>
                            <p class="text-sm text-muted-foreground">난이도별 점진적 훈련</p>
                        </div>
                    </div>
                    <p class="text-muted-foreground mb-4">난이도별로 점진적으로 훈련하여 체계적으로 실력을 향상시킵니다.</p>
                    <a href="exercises/progressive_reading.php" class="inline-block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition-colors">
                        연습 시작
                    </a>
                </div>
            </div>
        </div>

        <!-- AI 피드백 섹션 -->
        <div class="bg-card border rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">AI 피드백</h2>
            <div id="ai-feedback" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3 mt-1">
                        <span class="text-white text-sm">🤖</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">AI 코치의 조언</h4>
                        <p class="text-blue-700">
                            현재 읽기 속도가 180 WPM으로 목표인 300 WPM에 비해 낮습니다. 
                            속발음이 감지되고 있으므로 "입술/혀 고정 연습"과 "손가락 따라가기" 연습을 
                            우선적으로 진행하시는 것을 추천합니다.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/reading-coach.js"></script>

<?php include '../../system/includes/footer.php'; ?> 