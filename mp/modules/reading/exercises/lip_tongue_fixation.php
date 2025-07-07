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
    error_log("Database error in lip_tongue_fixation.php: " . $e->getMessage());
    // 오류 발생 시 기본값 설정
    $user = [
        'name' => '사용자',
        'username' => $user_id
    ];
}

// 연습 텍스트 (난이도별)
$practice_texts = [
    'beginner' => [
        'title' => '초급: 기본 입술 고정 연습',
        'text' => '인공지능 기술은 현대 사회에서 매우 중요한 역할을 하고 있습니다. 머신러닝과 딥러닝 알고리즘은 다양한 분야에서 활용되고 있으며, 우리의 일상생활을 크게 변화시키고 있습니다.',
        'description' => '입술을 살짝 벌리고 혀를 윗니에 대고 고정한 상태로 읽어보세요.',
        'duration' => 60
    ],
    'intermediate' => [
        'title' => '중급: 펜 물기 연습',
        'text' => '속발음은 책을 읽을 때 머릿속에서 글자를 소리로 변환하여 들으며 읽는 현상을 말합니다. 이는 대부분의 사람들이 경험하는 자연스러운 현상이지만, 읽기 속도를 제한하고 복잡한 내용의 이해도를 떨어뜨릴 수 있습니다. 입술 고정 연습은 이러한 속발음 문제를 해결하는 효과적인 방법 중 하나입니다.',
        'description' => '펜을 물고 입술 움직임을 최소화하며 읽어보세요.',
        'duration' => 90
    ],
    'advanced' => [
        'title' => '고급: 고급 입술 제어 연습',
        'text' => '효율적인 읽기 기술을 습득하기 위해서는 지속적인 연습과 올바른 방법이 필요합니다. 입술 고정 연습은 시각적 집중력을 향상시키고, 읽기 속도를 점진적으로 높이는 데 도움이 됩니다. 이 연습을 통해 단어를 덩어리로 인식하는 능력과 시각적 처리 속도를 향상시킬 수 있습니다.',
        'description' => '입술과 혀의 움직임을 완전히 제어하며 읽어보세요.',
        'duration' => 120
    ]
];

$current_level = $_GET['level'] ?? 'beginner';
$current_text = $practice_texts[$current_level];

// Header 포함
$page_title = "입술/혀 고정 연습";

// CSS 경로 수정을 위한 임시 설정
$additional_css = '<link href="../../../resources/css/tailwind.output.css" rel="stylesheet">';

include '../../../system/includes/header.php';
?>

<main class="container mx-auto px-4 py-8">
    <!-- 페이지 헤더 -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">입술/혀 고정 연습</h1>
                <p class="text-lg text-muted-foreground max-w-2xl">
                    입술과 혀의 움직임을 제어하여 물리적으로 속발음을 방지하고 읽기 효율을 향상시키세요
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
            <div class="w-8 h-8 bg-pink-500/10 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-pink-500">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold">난이도 선택</h2>
        </div>
        <div class="flex gap-3">
            <a href="?level=beginner" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'beginner' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                초급 (1분)
            </a>
            <a href="?level=intermediate" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'intermediate' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                중급 (1.5분)
            </a>
            <a href="?level=advanced" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'advanced' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                고급 (2분)
            </a>
        </div>
    </div>

    <!-- 연습 영역 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 웹캠 및 텍스트 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-pink-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-pink-500">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold"><?php echo $current_text['title']; ?></h3>
            </div>
            
            <p class="text-sm text-muted-foreground mb-4"><?php echo $current_text['description']; ?></p>
            
            <!-- 웹캠 영역 -->
            <div class="mb-4">
                <h4 class="font-medium mb-2 text-sm">입술 움직임 감지</h4>
                <div id="camera-container" class="relative bg-black rounded-lg overflow-hidden">
                    <video id="camera-feed" class="w-full h-48 object-cover" autoplay muted playsinline></video>
                    <canvas id="camera-canvas" class="absolute top-0 left-0 w-full h-full opacity-0"></canvas>
                    
                    <!-- 펜 시뮬레이션 -->
                    <div id="pen-simulation" class="absolute top-4 right-4 w-8 h-16 bg-gray-300 rounded-full border-2 border-gray-400 hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-600 text-xs">✏️</span>
                        </div>
                    </div>
                    
                    <!-- 입술 감지 영역 -->
                    <div id="lip-detection-area" class="absolute bottom-4 left-4 w-16 h-8 border-2 border-pink-400 rounded-full opacity-50"></div>
                </div>
                
                <!-- 카메라 상태 -->
                <div class="flex items-center gap-2 mt-2 text-sm text-muted-foreground">
                    <span id="camera-status">📷 카메라 준비 중...</span>
                    <button id="enable-camera" class="text-blue-600 hover:text-blue-800 text-xs">카메라 활성화</button>
                </div>
            </div>
            
            <!-- 텍스트 영역 -->
            <div id="text-container" class="bg-muted/50 rounded-lg p-4 min-h-[200px] border">
                <div id="reading-text" class="text-lg leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($current_text['text'])); ?>
                </div>
            </div>
            
            <!-- 연습 컨트롤 -->
            <div class="space-y-4 mt-4">
                <div class="flex gap-3">
                    <button id="start-fixation" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        🎯 연습 시작
                    </button>
                    <button id="stop-fixation" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium" disabled>
                        ⏹️ 연습 중지
                    </button>
                    <button id="reset-fixation" class="bg-muted text-muted-foreground px-4 py-2 rounded-lg hover:bg-muted/80 transition-colors text-sm font-medium">
                        🔄 다시 시작
                    </button>
                </div>
                
                <!-- 연습 상태 -->
                <div id="fixation-status" class="flex items-center gap-2 text-sm text-muted-foreground">
                    <span id="fixation-indicator">⚪ 대기 중</span>
                    <span id="fixation-time">00:00</span>
                    <span id="fixation-progress">0%</span>
                </div>
            </div>
        </div>

        <!-- 분석 및 피드백 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-purple-500">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">분석 & 피드백</h3>
            </div>
            
            <!-- 입술 고정률 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">입술 고정률</h4>
                <div class="flex items-center gap-4 mb-3">
                    <div class="text-3xl font-bold text-pink-600" id="lip-fixation-rate">0%</div>
                    <div class="text-sm text-muted-foreground">
                        목표: <span class="font-semibold">85%</span>
                    </div>
                </div>
                <div class="w-full bg-muted rounded-full h-2">
                    <div id="fixation-progress-bar" class="bg-pink-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- 읽기 속도 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">읽기 속도</h4>
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-bold text-blue-600" id="current-wpm">0</div>
                    <div class="text-sm text-muted-foreground">WPM</div>
                </div>
                <div class="w-full bg-muted rounded-full h-2 mt-2">
                    <div id="wpm-progress" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="text-xs text-muted-foreground mt-1">
                    목표: <span class="font-semibold">200+ WPM</span>
                </div>
            </div>

            <!-- 입술 움직임 감지 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">입술 움직임 감지</h4>
                <div id="lip-movement-status" class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-muted-foreground rounded-full" id="lip-movement-indicator"></span>
                    <span id="lip-movement-text" class="text-sm text-muted-foreground">대기 중</span>
                </div>
                <p class="text-xs text-muted-foreground mt-1">
                    입술 움직임이 감지되면 경고가 표시됩니다
                </p>
            </div>

            <!-- 펜 물기 상태 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">펜 물기 상태</h4>
                <div id="pen-status" class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-muted-foreground rounded-full" id="pen-indicator"></span>
                    <span id="pen-text" class="text-sm text-muted-foreground">펜을 준비하세요</span>
                </div>
                <p class="text-xs text-muted-foreground mt-1">
                    펜을 물고 입술 움직임을 최소화하세요
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
                <h4 class="font-medium mb-2 text-sm">입술 고정 팁</h4>
                <div class="text-sm text-muted-foreground bg-pink-500/10 p-3 rounded-lg border border-pink-200">
                    <ul class="space-y-1 text-xs">
                        <li>• 입술을 살짝 벌리고 고정하세요</li>
                        <li>• 혀를 윗니에 대고 고정하세요</li>
                        <li>• 펜을 물고 연습하면 더 효과적입니다</li>
                        <li>• 시각적으로만 읽으세요</li>
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
                <div class="text-2xl font-bold text-pink-600" id="final-fixation">0%</div>
                <div class="text-sm text-muted-foreground">입술 고정률</div>
            </div>
            <div class="text-center p-4 bg-muted/30 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600" id="final-wpm">0</div>
                <div class="text-sm text-muted-foreground">최종 WPM</div>
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

<script src="lip-tongue-fixation.js"></script>

<?php
// Footer 포함
include '../../../system/includes/footer.php';
?> 