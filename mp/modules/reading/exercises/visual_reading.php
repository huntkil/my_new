<?php
// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 사용자 로그인 확인
if (!isset($_SESSION['id'])) {
    header('Location: ../../../system/auth/login.php');
    exit();
}

// Config 파일 로드
require_once __DIR__ . '/../../../system/includes/config.php';

// Database 연결
require_once __DIR__ . '/../../../system/includes/Database.php';
$db = Database::getInstance();
$user_id = $_SESSION['id'];

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
    error_log("Database error in visual_reading.php: " . $e->getMessage());
    // 오류 발생 시 기본값 설정
    $user = [
        'name' => '사용자',
        'username' => $user_id
    ];
}

// 연습 텍스트 (난이도별)
$practice_texts = [
    'beginner' => [
        'title' => '초급: 단어 덩어리 인식',
        'text' => '오늘은 날씨가 좋습니다. 공원에서 산책을 했습니다. 새들이 노래를 부릅니다. 꽃들이 예쁘게 피어있습니다. 바람이 부드럽게 불어옵니다.',
        'description' => '간단한 문장으로 단어 덩어리 인식 연습을 해보세요.'
    ],
    'intermediate' => [
        'title' => '중급: 의미 단위 읽기',
        'text' => '인공지능은 컴퓨터가 인간의 학습능력과 추론능력, 지각능력, 자연언어의 이해능력 등을 인공적으로 구현한 기술입니다. 머신러닝과 딥러닝을 포함하며, 다양한 분야에서 활용되고 있습니다.',
        'description' => '복잡한 문장을 의미 단위로 나누어 읽는 연습을 해보세요.'
    ],
    'advanced' => [
        'title' => '고급: 속발음 제어 연습',
        'text' => '속발음은 책을 읽을 때 머릿속에서 글자를 소리로 변환하여 들으며 읽는 현상을 말합니다. 이는 대부분의 사람들이 경험하는 자연스러운 현상이지만, 읽기 속도를 제한하고 복잡한 내용의 이해도를 떨어뜨릴 수 있습니다.',
        'description' => '어려운 내용을 속발음 없이 빠르게 읽는 연습을 해보세요.'
    ]
];

$current_level = $_GET['level'] ?? 'beginner';
$current_text = $practice_texts[$current_level];

// Header 포함
$page_title = "시각적 읽기 연습";

// CSS 경로 수정을 위한 임시 설정
$additional_css = '<link href="../../../resources/css/tailwind.output.css" rel="stylesheet">';

include '../../../system/includes/header.php';
?>

<main class="container mx-auto px-4 py-8">
    <!-- 페이지 헤더 -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">시각적 읽기 연습</h1>
                <p class="text-lg text-muted-foreground max-w-2xl">
                    단어 덩어리 인식 훈련으로 속발음을 줄이고 읽기 속도를 향상시키세요
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="../ai_reading_coach.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">← AI 코치로</a>
            </div>
        </div>
    </div>

    <!-- 난이도 선택 -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-orange-500">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold">난이도 선택</h2>
        </div>
        <div class="flex gap-3">
            <a href="?level=beginner" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'beginner' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                초급
            </a>
            <a href="?level=intermediate" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'intermediate' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                중급
            </a>
            <a href="?level=advanced" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'advanced' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                고급
            </a>
        </div>
    </div>

    <!-- 연습 영역 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 텍스트 표시 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-blue-500">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold"><?php echo $current_text['title']; ?></h3>
            </div>
            
            <p class="text-sm text-muted-foreground mb-4"><?php echo $current_text['description']; ?></p>
            
            <div id="reading-text" class="text-lg leading-relaxed mb-6 p-4 bg-muted/50 rounded-lg min-h-[200px] border">
                <?php echo nl2br(htmlspecialchars($current_text['text'])); ?>
            </div>
            
            <!-- 연습 컨트롤 -->
            <div class="space-y-4">
                <div class="flex gap-3">
                    <button id="start-reading" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        🚀 연습 시작
                    </button>
                    <button id="stop-reading" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium" disabled>
                        ⏹️ 연습 중지
                    </button>
                    <button id="reset-reading" class="bg-muted text-muted-foreground px-4 py-2 rounded-lg hover:bg-muted/80 transition-colors text-sm font-medium">
                        🔄 다시 시작
                    </button>
                </div>
                
                <!-- 연습 상태 -->
                <div id="reading-status" class="flex items-center gap-2 text-sm text-muted-foreground">
                    <span id="reading-indicator">⚪ 대기 중</span>
                    <span id="reading-time">00:00</span>
                </div>
            </div>
        </div>

        <!-- 실시간 분석 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-purple-500">
                        <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">실시간 분석</h3>
            </div>
            
            <!-- 읽기 속도 측정 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">읽기 속도 (WPM)</h4>
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-bold text-blue-600" id="current-wpm">0</div>
                    <div class="text-sm text-muted-foreground">
                        목표: <span class="font-semibold">300 WPM</span>
                    </div>
                </div>
                <div class="w-full bg-muted rounded-full h-2 mt-2">
                    <div id="wpm-progress" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- 속발음 감지 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">속발음 감지</h4>
                <div id="subvocalization-status" class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-muted-foreground rounded-full" id="subvocalization-indicator"></span>
                    <span id="subvocalization-text" class="text-sm text-muted-foreground">대기 중</span>
                </div>
                <p class="text-xs text-muted-foreground mt-1">
                    입술과 혀의 미세한 움직임을 감지합니다
                </p>
            </div>

            <!-- 실시간 피드백 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">실시간 피드백</h4>
                <div id="real-time-feedback" class="text-sm text-muted-foreground bg-muted/50 p-3 rounded-lg min-h-[60px] border">
                    연습을 시작하면 실시간 피드백이 표시됩니다.
                </div>
            </div>

            <!-- 읽기 팁 -->
            <div>
                <h4 class="font-medium mb-2 text-sm">읽기 팁</h4>
                <div class="text-sm text-muted-foreground bg-blue-500/10 p-3 rounded-lg border border-blue-200">
                    <ul class="space-y-1 text-xs">
                        <li>• 단어를 덩어리로 인식하세요</li>
                        <li>• 입술을 살짝 벌리고 혀를 윗니에 대세요</li>
                        <li>• 의미 단위로 빠르게 읽으세요</li>
                        <li>• 속발음이 감지되면 잠시 멈추고 다시 시작하세요</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 연습 결과 -->
    <div id="exercise-results" class="mt-8 bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hidden">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-green-500">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold">연습 결과</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-muted/30 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600" id="final-wpm">0</div>
                <div class="text-sm text-muted-foreground">최종 WPM</div>
            </div>
            <div class="text-center p-4 bg-muted/30 rounded-lg border">
                <div class="text-2xl font-bold text-green-600" id="subvocalization-score">0%</div>
                <div class="text-sm text-muted-foreground">속발음 제어율</div>
            </div>
            <div class="text-center p-4 bg-muted/30 rounded-lg border">
                <div class="text-2xl font-bold text-purple-600" id="practice-time">00:00</div>
                <div class="text-sm text-muted-foreground">연습 시간</div>
            </div>
        </div>
        
        <!-- AI 피드백 -->
        <div class="mt-6 p-4 bg-blue-500/10 border border-blue-200 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2 text-sm">AI 코치의 조언</h4>
            <p id="ai-feedback-text" class="text-blue-700 text-sm">
                연습을 완료하면 개인화된 조언을 받을 수 있습니다.
            </p>
        </div>
        
        <div class="mt-4 flex gap-3">
            <button onclick="saveResults()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                결과 저장
            </button>
            <button onclick="location.reload()" class="bg-muted text-muted-foreground px-4 py-2 rounded-lg hover:bg-muted/80 transition-colors text-sm font-medium">
                다시 연습
            </button>
        </div>
    </div>
</main>

<script src="visual-reading.js"></script>

<?php
// Footer 포함
include '../../../system/includes/footer.php';
?> 