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
    error_log("Database error in progressive_reading.php: " . $e->getMessage());
    // 오류 발생 시 기본값 설정
    $user = [
        'name' => '사용자',
        'username' => $user_id
    ];
}

// 단계별 연습 텍스트 (난이도별)
$progressive_texts = [
    'level1' => [
        'title' => '1단계: 기본 단어 인식',
        'text' => '고양이 강아지 새 물고기 나무 꽃 집 학교 친구 가족',
        'description' => '기본적인 단어들을 빠르게 인식하는 연습입니다.',
        'target_wpm' => 150,
        'focus' => '단어 단위 인식'
    ],
    'level2' => [
        'title' => '2단계: 짧은 문장 읽기',
        'text' => '고양이가 잠을 잔다. 강아지가 뛰어다닌다. 새가 노래를 부른다. 꽃이 예쁘게 피었다.',
        'description' => '짧은 문장을 의미 단위로 읽는 연습입니다.',
        'target_wpm' => 200,
        'focus' => '문장 단위 인식'
    ],
    'level3' => [
        'title' => '3단계: 중간 길이 문장',
        'text' => '오늘은 날씨가 정말 좋습니다. 공원에서 산책을 하면서 새들의 노래를 들었습니다. 꽃들이 예쁘게 피어있어서 기분이 좋아졌습니다.',
        'description' => '중간 길이의 문장을 빠르게 읽는 연습입니다.',
        'target_wpm' => 250,
        'focus' => '의미 덩어리 인식'
    ],
    'level4' => [
        'title' => '4단계: 복잡한 문장',
        'text' => '인공지능 기술의 발전으로 우리의 일상생활이 크게 변화하고 있습니다. 스마트폰을 통해 정보를 얻고, 자동차는 자율주행을 하고, 집에서는 스마트홈 시스템이 우리를 도와줍니다.',
        'description' => '복잡한 내용을 빠르게 이해하는 연습입니다.',
        'target_wpm' => 300,
        'focus' => '개념 단위 인식'
    ],
    'level5' => [
        'title' => '5단계: 전문적 내용',
        'text' => '속발음은 책을 읽을 때 머릿속에서 글자를 소리로 변환하여 들으며 읽는 현상을 말합니다. 이는 대부분의 사람들이 경험하는 자연스러운 현상이지만, 읽기 속도를 제한하고 복잡한 내용의 이해도를 떨어뜨릴 수 있습니다. 따라서 속발음을 줄이고 시각적 인식 능력을 향상시키는 것이 중요합니다.',
        'description' => '전문적이고 어려운 내용을 빠르게 읽는 연습입니다.',
        'target_wpm' => 350,
        'focus' => '고급 읽기 기술'
    ]
];

$current_level = $_GET['level'] ?? 'level1';
$current_text = $progressive_texts[$current_level];

// Header 포함
$page_title = "단계적 연습";

// CSS 경로 수정을 위한 임시 설정
$additional_css = '<link href="../../../resources/css/tailwind.output.css" rel="stylesheet">';

include '../../../system/includes/header.php';
?>

<main class="container mx-auto px-4 py-8">
    <!-- 페이지 헤더 -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">단계적 연습</h1>
                <p class="text-lg text-muted-foreground max-w-2xl">
                    난이도별 점진적 훈련으로 체계적으로 실력을 향상시키세요
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="../ai_reading_coach.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">← AI 코치로</a>
            </div>
        </div>
    </div>

    <!-- 단계 선택 -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-indigo-500/10 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-indigo-500">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold">단계 선택</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="?level=level1" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'level1' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                1단계 - 기본
            </a>
            <a href="?level=level2" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'level2' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                2단계 - 초급
            </a>
            <a href="?level=level3" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'level3' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                3단계 - 중급
            </a>
            <a href="?level=level4" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'level4' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                4단계 - 고급
            </a>
            <a href="?level=level5" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $current_level === 'level5' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-muted text-muted-foreground hover:bg-muted/80'; ?>">
                5단계 - 전문
            </a>
        </div>
    </div>

    <!-- 연습 영역 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 텍스트 표시 영역 -->
        <div class="bg-card border rounded-lg p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-500/10 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-indigo-500">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold"><?php echo $current_text['title']; ?></h3>
            </div>
            
            <div class="mb-4 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                <p class="text-sm text-indigo-800 mb-2"><strong>목표:</strong> <?php echo $current_text['target_wpm']; ?> WPM</p>
                <p class="text-sm text-indigo-700"><strong>중점:</strong> <?php echo $current_text['focus']; ?></p>
            </div>
            
            <p class="text-sm text-muted-foreground mb-4"><?php echo $current_text['description']; ?></p>
            
            <div id="reading-text" class="text-lg leading-relaxed mb-6 p-4 bg-muted/50 rounded-lg min-h-[200px] border">
                <?php echo nl2br(htmlspecialchars($current_text['text'])); ?>
            </div>
            
            <!-- 연습 컨트롤 -->
            <div class="space-y-4">
                <div class="flex gap-3">
                    <button id="start-reading" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
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
                    <div class="text-3xl font-bold text-indigo-600" id="current-wpm">0</div>
                    <div class="text-sm text-muted-foreground">
                        목표: <span class="font-semibold"><?php echo $current_text['target_wpm']; ?> WPM</span>
                    </div>
                </div>
                <div class="w-full bg-muted rounded-full h-2 mt-2">
                    <div id="wpm-progress" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- 단계별 진행률 -->
            <div class="mb-6">
                <h4 class="font-medium mb-2 text-sm">단계별 진행률</h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm">1단계</span>
                        <span class="text-sm font-medium text-green-600">완료</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">2단계</span>
                        <span class="text-sm font-medium text-green-600">완료</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">3단계</span>
                        <span class="text-sm font-medium <?php echo in_array($current_level, ['level3', 'level4', 'level5']) ? 'text-green-600' : 'text-muted-foreground'; ?>">
                            <?php echo in_array($current_level, ['level3', 'level4', 'level5']) ? '완료' : '대기'; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">4단계</span>
                        <span class="text-sm font-medium <?php echo in_array($current_level, ['level4', 'level5']) ? 'text-green-600' : 'text-muted-foreground'; ?>">
                            <?php echo in_array($current_level, ['level4', 'level5']) ? '완료' : '대기'; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">5단계</span>
                        <span class="text-sm font-medium <?php echo $current_level === 'level5' ? 'text-green-600' : 'text-muted-foreground'; ?>">
                            <?php echo $current_level === 'level5' ? '완료' : '대기'; ?>
                        </span>
                    </div>
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

            <!-- 단계별 팁 -->
            <div>
                <h4 class="font-medium mb-2 text-sm">단계별 팁</h4>
                <div class="text-sm text-muted-foreground bg-indigo-500/10 p-3 rounded-lg border border-indigo-200">
                    <p class="mb-2"><strong><?php echo $current_text['focus']; ?>:</strong></p>
                    <p><?php echo $current_text['description']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 다음 단계 추천 -->
    <div class="mt-8 bg-card border rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">다음 단계 추천</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            $next_levels = [
                'level1' => ['level2', '2단계 - 초급', '짧은 문장 읽기'],
                'level2' => ['level3', '3단계 - 중급', '중간 길이 문장'],
                'level3' => ['level4', '4단계 - 고급', '복잡한 문장'],
                'level4' => ['level5', '5단계 - 전문', '전문적 내용'],
                'level5' => ['level1', '1단계 - 기본', '기본 단어 인식']
            ];
            
            $current_next = $next_levels[$current_level];
            ?>
            <a href="?level=<?php echo $current_next[0]; ?>" class="group bg-indigo-50 border border-indigo-200 rounded-lg p-4 hover:bg-indigo-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">📈</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-indigo-800"><?php echo $current_next[1]; ?></h4>
                        <p class="text-sm text-indigo-600"><?php echo $current_next[2]; ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</main>

<script src="progressive-reading.js"></script>

<?php include '../../../system/includes/footer.php'; ?> 