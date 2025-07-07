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
    error_log("Database error in rhythm_reading.php: " . $e->getMessage());
    // 오류 발생 시 기본값 설정
    $user = [
        'name' => '사용자',
        'username' => $user_id
    ];
}

// 연습 텍스트 (난이도별)
$practice_texts = [
    'beginner' => [
        'title' => '초급: 기본 리듬 연습',
        'text' => '하나 둘 셋 넷. 다섯 여섯 일곱 여덟. 아홉 열 열하나 열둘. 열셋 열넷 열다섯 열여섯.',
        'description' => '기본적인 4박자 리듬에 맞춰 읽어보세요.',
        'tempo' => 60
    ],
    'intermediate' => [
        'title' => '중급: 문장 리듬 연습',
        'text' => '인공지능 기술은 현대 사회에서 매우 중요한 역할을 하고 있습니다. 머신러닝과 딥러닝 알고리즘은 다양한 분야에서 활용되고 있으며, 우리의 일상생활을 크게 변화시키고 있습니다.',
        'description' => '문장 단위로 리듬을 유지하며 읽어보세요.',
        'tempo' => 80
    ],
    'advanced' => [
        'title' => '고급: 빠른 리듬 연습',
        'text' => '속발음은 책을 읽을 때 머릿속에서 글자를 소리로 변환하여 들으며 읽는 현상을 말합니다. 이는 대부분의 사람들이 경험하는 자연스러운 현상이지만, 읽기 속도를 제한하고 복잡한 내용의 이해도를 떨어뜨릴 수 있습니다. 리듬감 있는 읽기는 이러한 속발음 문제를 해결하는 효과적인 방법 중 하나입니다.',
        'description' => '빠른 리듬에 맞춰 속발음 없이 읽어보세요.',
        'tempo' => 120
    ]
];

$current_level = $_GET['level'] ?? 'beginner';
$current_text = $practice_texts[$current_level];

// Header 포함
$page_title = "리듬감 있는 읽기 연습";

// CSS 경로 수정을 위한 임시 설정
$additional_css = '<link href="../../../resources/css/tailwind.output.css" rel="stylesheet">';

include '../../../system/includes/header.php';
?>

<main class="container mx-auto px-4 py-8">
    <!-- 페이지 헤더 -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">리듬감 있는 읽기 연습</h1>
                <p class="text-lg text-muted-foreground max-w-2xl">
                    메트로놈과 함께 일정한 리듬에 맞춰 읽어 속발음을 줄이고 읽기 속도를 향상시키세요
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
            <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-green-500">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold">난이도 선택</h2>
        </div>
        <div class="flex gap-3">
            <a href="?level=beginner" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'beginner' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                초급 (60 BPM)
            </a>
            <a href="?level=intermediate" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'intermediate' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                중급 (80 BPM)
            </a>
            <a href="?level=advanced" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'advanced' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                고급 (120 BPM)
            </a>
        </div>
    </div>

    <!-- 연습 영역 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 텍스트 표시 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-green-500">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
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
                        🎵 연습 시작
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

        <!-- 메트로놈 및 분석 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-purple-500">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">메트로놈 & 분석</h3>
            </div>
            
            <!-- 메트로놈 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">메트로놈</h4>
                <div class="flex items-center gap-4 mb-3">
                    <div class="text-3xl font-bold text-green-600" id="tempo-display"><?php echo $current_text['tempo']; ?></div>
                    <div class="text-sm text-muted-foreground">BPM</div>
                </div>
                <div id="metronome-visual" class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mx-auto">
                    <div id="metronome-beat" class="w-4 h-4 bg-green-500 rounded-full"></div>
                </div>
                <div class="text-center mt-2">
                    <span id="beat-count" class="text-sm text-muted-foreground">1</span>
                </div>
            </div>

            <!-- 리듬 일치도 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">리듬 일치도</h4>
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-bold text-blue-600" id="rhythm-score">0%</div>
                    <div class="text-sm text-muted-foreground">
                        목표: <span class="font-semibold">80%</span>
                    </div>
                </div>
                <div class="w-full bg-muted rounded-full h-2 mt-2">
                    <div id="rhythm-progress" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
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
                    리듬을 벗어나는 속발음 감지
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
                <h4 class="font-medium mb-2 text-sm">리듬 읽기 팁</h4>
                <div class="text-sm text-muted-foreground bg-green-500/10 p-3 rounded-lg border border-green-200">
                    <ul class="space-y-1 text-xs">
                        <li>• 메트로놈 소리에 맞춰 읽으세요</li>
                        <li>• 각 박자마다 단어나 구절을 읽으세요</li>
                        <li>• 속발음 없이 시각적으로 읽으세요</li>
                        <li>• 리듬을 벗어나면 다시 맞춰보세요</li>
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
                <div class="text-2xl font-bold text-green-600" id="rhythm-accuracy">0%</div>
                <div class="text-sm text-muted-foreground">리듬 정확도</div>
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

<script src="rhythm-reading.js"></script>

<?php
// Footer 포함
include '../../../system/includes/footer.php';
?> 