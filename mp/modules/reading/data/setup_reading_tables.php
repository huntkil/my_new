<?php
require_once __DIR__ . '/../../../system/includes/config.php';
require_once __DIR__ . '/../../../system/includes/Database.php';

$db = Database::getInstance();

// 사용자 읽기 진도 테이블
$createUserProgressTable = "
CREATE TABLE IF NOT EXISTS reading_progress (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    exercise_type TEXT NOT NULL,
    wpm INTEGER NOT NULL,
    accuracy INTEGER NOT NULL,
    practice_time REAL NOT NULL,
    subvocalization_rate REAL NOT NULL,
    difficulty_level TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES myUser(id)
)";

// 연습 세션 테이블
$createSessionTable = "
CREATE TABLE IF NOT EXISTS reading_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    exercise_type TEXT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    total_words INTEGER NOT NULL,
    recognized_words INTEGER NOT NULL,
    final_wpm INTEGER NOT NULL,
    final_accuracy INTEGER NOT NULL,
    subvocalization_count INTEGER NOT NULL,
    difficulty_level TEXT NOT NULL,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES myUser(id)
)";

// WPM 히스토리 테이블
$createWPMHistoryTable = "
CREATE TABLE IF NOT EXISTS wpm_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id INTEGER NOT NULL,
    elapsed_time REAL NOT NULL,
    wpm INTEGER NOT NULL,
    subvocalization_detected BOOLEAN NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES reading_sessions(id)
)";

// 사용자 목표 설정 테이블
$createUserGoalsTable = "
CREATE TABLE IF NOT EXISTS reading_goals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    target_wpm INTEGER NOT NULL DEFAULT 300,
    daily_practice_minutes INTEGER NOT NULL DEFAULT 30,
    weekly_sessions INTEGER NOT NULL DEFAULT 5,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES myUser(id)
)";

// 연습 텍스트 테이블
$createPracticeTextsTable = "
CREATE TABLE IF NOT EXISTS practice_texts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    difficulty_level TEXT NOT NULL,
    category TEXT NOT NULL,
    word_count INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    // 테이블 생성
    $db->query($createUserProgressTable);
    $db->query($createSessionTable);
    $db->query($createWPMHistoryTable);
    $db->query($createUserGoalsTable);
    $db->query($createPracticeTextsTable);
    
    // 샘플 연습 텍스트 삽입
    $sampleTexts = [
        [
            'title' => '초급: 간단한 문장',
            'content' => '오늘은 날씨가 좋습니다. 공원에서 산책을 했습니다. 새들이 노래를 부릅니다. 꽃들이 예쁘게 피어있습니다. 바람이 부드럽게 불어옵니다.',
            'difficulty_level' => 'beginner',
            'category' => 'daily_life',
            'word_count' => 25
        ],
        [
            'title' => '중급: 설명문',
            'content' => '인공지능은 컴퓨터가 인간의 학습능력과 추론능력, 지각능력, 자연언어의 이해능력 등을 인공적으로 구현한 기술입니다. 머신러닝과 딥러닝을 포함하며, 다양한 분야에서 활용되고 있습니다.',
            'difficulty_level' => 'intermediate',
            'category' => 'technology',
            'word_count' => 35
        ],
        [
            'title' => '고급: 전문 지식',
            'content' => '속발음은 책을 읽을 때 머릿속에서 글자를 소리로 변환하여 들으며 읽는 현상을 말합니다. 이는 대부분의 사람들이 경험하는 자연스러운 현상이지만, 읽기 속도를 제한하고 복잡한 내용의 이해도를 떨어뜨릴 수 있습니다.',
            'difficulty_level' => 'advanced',
            'category' => 'education',
            'word_count' => 45
        ],
        [
            'title' => '초급: 동물 이야기',
            'content' => '강아지는 사람의 가장 좋은 친구입니다. 고양이는 독립적인 성격을 가지고 있습니다. 토끼는 귀엽고 조용한 동물입니다. 새들은 자유롭게 하늘을 날아다닙니다.',
            'difficulty_level' => 'beginner',
            'category' => 'animals',
            'word_count' => 30
        ],
        [
            'title' => '중급: 환경 보호',
            'content' => '환경 보호는 우리 모두의 책임입니다. 재활용을 통해 자원을 절약할 수 있습니다. 대중교통을 이용하면 탄소 배출을 줄일 수 있습니다. 친환경 제품을 사용하는 것이 중요합니다.',
            'difficulty_level' => 'intermediate',
            'category' => 'environment',
            'word_count' => 32
        ]
    ];
    
    foreach ($sampleTexts as $text) {
        $db->query(
            "INSERT OR IGNORE INTO practice_texts (title, content, difficulty_level, category, word_count) VALUES (?, ?, ?, ?, ?)",
            [$text['title'], $text['content'], $text['difficulty_level'], $text['category'], $text['word_count']]
        );
    }
    
    echo "✅ AI 읽기 코치 데이터베이스 테이블이 성공적으로 생성되었습니다.\n";
    echo "📊 생성된 테이블:\n";
    echo "   - reading_progress (사용자 진도)\n";
    echo "   - reading_sessions (연습 세션)\n";
    echo "   - wpm_history (WPM 히스토리)\n";
    echo "   - reading_goals (사용자 목표)\n";
    echo "   - practice_texts (연습 텍스트)\n";
    echo "📝 샘플 연습 텍스트 " . count($sampleTexts) . "개가 추가되었습니다.\n";
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
}
?> 