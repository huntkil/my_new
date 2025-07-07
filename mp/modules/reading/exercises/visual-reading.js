// 시각적 읽기 연습 전용 JavaScript

// 전역 변수
let exerciseSession = {
    isActive: false,
    startTime: null,
    endTime: null,
    totalWords: 0,
    wpmHistory: [],
    subvocalizationCount: 0,
    subvocalizationDetected: false,
    readingProgress: 0
};

let timer = null;
let subvocalizationDetector = null;

// DOM 로드 완료 후 초기화
document.addEventListener('DOMContentLoaded', function() {
    initializeExercise();
    setupEventListeners();
});

// 연습 초기화
function initializeExercise() {
    console.log('시각적 읽기 연습 초기화 중...');
    
    // 속발음 감지 초기화 (실제 구현에서는 웹캠이나 마이크를 통한 미세한 움직임 감지)
    initializeSubvocalizationDetection();
    
    // 연습 세션 초기화
    exerciseSession = {
        isActive: false,
        startTime: null,
        endTime: null,
        totalWords: countWordsInText(),
        wpmHistory: [],
        subvocalizationCount: 0,
        subvocalizationDetected: false,
        readingProgress: 0
    };
    
    updateUI();
    console.log('초기화 완료');
}

// 이벤트 리스너 설정
function setupEventListeners() {
    document.getElementById('start-reading').addEventListener('click', startReading);
    document.getElementById('stop-reading').addEventListener('click', stopReading);
    document.getElementById('reset-reading').addEventListener('click', resetExercise);
    
    // 키보드 단축키
    document.addEventListener('keydown', function(e) {
        if (e.code === 'Space' && !exerciseSession.isActive) {
            e.preventDefault();
            startReading();
        } else if (e.code === 'Escape' && exerciseSession.isActive) {
            stopReading();
        }
    });
}

// 속발음 감지 초기화 (시뮬레이션)
function initializeSubvocalizationDetection() {
    console.log('속발음 감지 초기화...');
    
    // 실제 구현에서는 웹캠을 통한 입술/혀 움직임 감지
    // 또는 마이크를 통한 미세한 소리 감지
    // 현재는 시뮬레이션으로 구현
    
    subvocalizationDetector = {
        isDetecting: false,
        detectionInterval: null,
        
        start: function() {
            this.isDetecting = true;
            this.detectionInterval = setInterval(() => {
                this.simulateDetection();
            }, 2000); // 2초마다 감지
        },
        
        stop: function() {
            this.isDetecting = false;
            if (this.detectionInterval) {
                clearInterval(this.detectionInterval);
                this.detectionInterval = null;
            }
        },
        
        simulateDetection: function() {
            if (!exerciseSession.isActive) return;
            
            // 랜덤하게 속발음 감지 시뮬레이션
            const random = Math.random();
            if (random < 0.3) { // 30% 확률로 속발음 감지
                detectSubvocalization();
            }
        }
    };
}

// 속발음 감지
function detectSubvocalization() {
    exerciseSession.subvocalizationDetected = true;
    exerciseSession.subvocalizationCount++;
    
    updateSubvocalizationStatus(true);
    showNotification('속발음이 감지되었습니다. 입술과 혀를 고정해보세요.', 'warning');
    
    // 3초 후 상태 초기화
    setTimeout(() => {
        exerciseSession.subvocalizationDetected = false;
        updateSubvocalizationStatus(false);
    }, 3000);
}

// 읽기 시작
function startReading() {
    if (exerciseSession.isActive) return;
    
    try {
        // 연습 세션 초기화
        exerciseSession = {
            isActive: true,
            startTime: new Date(),
            endTime: null,
            totalWords: countWordsInText(),
            wpmHistory: [],
            subvocalizationCount: 0,
            subvocalizationDetected: false,
            readingProgress: 0
        };
        
        // 속발음 감지 시작
        if (subvocalizationDetector) {
            subvocalizationDetector.start();
        }
        
        startTimer();
        updateButtons(true);
        updateReadingStatus(true);
        showNotification('연습을 시작합니다. 단어를 덩어리로 인식하며 읽어보세요.', 'info');
        
        // 실시간 피드백 시작
        startRealTimeFeedback();
        
    } catch (error) {
        console.error('연습 시작 오류:', error);
        showNotification('연습을 시작할 수 없습니다. 다시 시도해주세요.', 'error');
    }
}

// 읽기 중지
function stopReading() {
    if (!exerciseSession.isActive) return;
    
    exerciseSession.isActive = false;
    exerciseSession.endTime = new Date();
    
    // 속발음 감지 중지
    if (subvocalizationDetector) {
        subvocalizationDetector.stop();
    }
    
    stopTimer();
    updateButtons(false);
    updateReadingStatus(false);
    
    // 최종 결과 계산
    calculateFinalResults();
    showExerciseResults();
    
    showNotification('연습이 완료되었습니다. 결과를 확인해주세요.', 'success');
}

// 연습 리셋
function resetExercise() {
    exerciseSession = {
        isActive: false,
        startTime: null,
        endTime: null,
        totalWords: 0,
        wpmHistory: [],
        subvocalizationCount: 0,
        subvocalizationDetected: false,
        readingProgress: 0
    };
    
    if (subvocalizationDetector) {
        subvocalizationDetector.stop();
    }
    
    stopTimer();
    updateButtons(false);
    updateReadingStatus(false);
    resetUI();
    
    // 결과 숨기기
    document.getElementById('exercise-results').classList.add('hidden');
    
    showNotification('연습이 리셋되었습니다.', 'info');
}

// 타이머 시작
function startTimer() {
    const startTime = new Date();
    
    timer = setInterval(() => {
        const currentTime = new Date();
        const elapsed = Math.floor((currentTime - startTime) / 1000);
        
        // 시간 표시 업데이트
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        document.getElementById('reading-time').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // WPM 계산 (시뮬레이션)
        if (exerciseSession.isActive) {
            calculateWPM(elapsed);
        }
        
    }, 1000);
}

// 타이머 중지
function stopTimer() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
}

// WPM 계산 (시뮬레이션)
function calculateWPM(elapsedSeconds) {
    if (elapsedSeconds === 0) return;
    
    // 실제로는 사용자의 읽기 진행도를 추적해야 함
    // 현재는 시뮬레이션으로 구현
    const progress = Math.min(exerciseSession.readingProgress + Math.random() * 10, 100);
    exerciseSession.readingProgress = progress;
    
    const wordsRead = Math.floor((progress / 100) * exerciseSession.totalWords);
    const wpm = Math.floor((wordsRead / elapsedSeconds) * 60);
    
    // WPM 히스토리에 추가
    exerciseSession.wpmHistory.push(wpm);
    
    // UI 업데이트
    document.getElementById('current-wpm').textContent = wpm;
    
    // 진행률 업데이트
    const progressPercent = Math.min((wpm / 300) * 100, 100);
    document.getElementById('wpm-progress').style.width = progressPercent + '%';
}

// 실시간 피드백 시작
function startRealTimeFeedback() {
    const feedbackMessages = [
        '좋습니다! 단어를 덩어리로 인식하고 있네요.',
        '속도를 조금 더 높여보세요.',
        '입술과 혀를 고정하고 읽어보세요.',
        '의미 단위로 빠르게 읽어보세요.',
        '속발음 없이 시각적으로 읽어보세요.'
    ];
    
    let messageIndex = 0;
    
    const feedbackInterval = setInterval(() => {
        if (!exerciseSession.isActive) {
            clearInterval(feedbackInterval);
            return;
        }
        
        const message = feedbackMessages[messageIndex % feedbackMessages.length];
        document.getElementById('real-time-feedback').textContent = message;
        messageIndex++;
        
    }, 5000); // 5초마다 피드백 변경
}

// 최종 결과 계산
function calculateFinalResults() {
    if (!exerciseSession.endTime || !exerciseSession.startTime) return;
    
    const duration = (exerciseSession.endTime - exerciseSession.startTime) / 1000; // 초
    const minutes = duration / 60;
    
    // 평균 WPM 계산
    const avgWpm = exerciseSession.wpmHistory.length > 0 
        ? Math.floor(exerciseSession.wpmHistory.reduce((a, b) => a + b, 0) / exerciseSession.wpmHistory.length)
        : 0;
    
    // 속발음 제어율 계산 (시뮬레이션)
    const totalDetections = exerciseSession.subvocalizationCount;
    const controlRate = Math.max(0, 100 - (totalDetections * 10));
    
    // 결과 저장
    exerciseSession.finalResults = {
        avgWpm: avgWpm,
        subvocalizationControlRate: controlRate,
        practiceTime: duration,
        totalDetections: totalDetections
    };
}

// 연습 결과 표시
function showExerciseResults() {
    if (!exerciseSession.finalResults) return;
    
    const results = exerciseSession.finalResults;
    
    // 결과 업데이트
    document.getElementById('final-wpm').textContent = results.avgWpm;
    document.getElementById('subvocalization-score').textContent = results.subvocalizationControlRate + '%';
    
    const minutes = Math.floor(results.practiceTime / 60);
    const seconds = Math.floor(results.practiceTime % 60);
    document.getElementById('practice-time').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // AI 피드백 생성
    generateAIFeedback(results);
    
    // 결과 표시
    document.getElementById('exercise-results').classList.remove('hidden');
}

// AI 피드백 생성
function generateAIFeedback(results) {
    let feedback = '';
    
    if (results.avgWpm < 150) {
        feedback = '읽기 속도가 다소 느립니다. 단어를 덩어리로 인식하는 연습을 더 해보세요.';
    } else if (results.avgWpm < 250) {
        feedback = '좋은 속도입니다! 속발음 제어를 더 연습하면 더 빨라질 수 있어요.';
    } else {
        feedback = '훌륭한 속도입니다! 속발음 제어도 잘 되고 있어요.';
    }
    
    if (results.subvocalizationControlRate < 70) {
        feedback += ' 입술과 혀를 고정하는 연습을 더 해보세요.';
    }
    
    document.getElementById('ai-feedback-text').textContent = feedback;
}

// UI 업데이트 함수들
function updateButtons(isActive) {
    document.getElementById('start-reading').disabled = isActive;
    document.getElementById('stop-reading').disabled = !isActive;
}

function updateReadingStatus(isActive) {
    const indicator = document.getElementById('reading-indicator');
    if (isActive) {
        indicator.textContent = '🔴 연습 중';
        indicator.className = 'text-red-600';
    } else {
        indicator.textContent = '⚪ 대기 중';
        indicator.className = 'text-muted-foreground';
    }
}

function updateSubvocalizationStatus(isDetected) {
    const indicator = document.getElementById('subvocalization-indicator');
    const text = document.getElementById('subvocalization-text');
    
    if (isDetected) {
        indicator.className = 'w-3 h-3 bg-red-500 rounded-full';
        text.textContent = '속발음 감지됨';
        text.className = 'text-sm text-red-600';
    } else {
        indicator.className = 'w-3 h-3 bg-green-500 rounded-full';
        text.textContent = '정상';
        text.className = 'text-sm text-green-600';
    }
}

function resetUI() {
    document.getElementById('current-wpm').textContent = '0';
    document.getElementById('wpm-progress').style.width = '0%';
    document.getElementById('reading-time').textContent = '00:00';
    document.getElementById('real-time-feedback').textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
    updateSubvocalizationStatus(false);
}

function updateUI() {
    updateButtons(false);
    updateReadingStatus(false);
    updateSubvocalizationStatus(false);
}

// 유틸리티 함수들
function countWordsInText() {
    const textElement = document.getElementById('reading-text');
    const text = textElement.textContent || textElement.innerText;
    return text.trim().split(/\s+/).length;
}

function showNotification(message, type = 'info') {
    // 간단한 알림 표시 (실제로는 더 정교한 알림 시스템 사용)
    console.log(`${type.toUpperCase()}: ${message}`);
    
    // 임시로 alert 사용 (실제로는 토스트 알림 등 사용)
    if (type === 'error') {
        alert('오류: ' + message);
    } else if (type === 'warning') {
        alert('주의: ' + message);
    }
}

// 결과 저장 (나중에 구현)
function saveResults() {
    if (!exerciseSession.finalResults) return;
    
    // 여기에 데이터베이스 저장 로직 구현
    console.log('결과 저장:', exerciseSession.finalResults);
    showNotification('결과가 저장되었습니다.', 'success');
}

// 페이지 로드 시간 측정
const loadTime = performance.now();
console.log('페이지 로드 시간:', loadTime, 'ms'); 