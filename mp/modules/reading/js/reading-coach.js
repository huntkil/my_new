// AI 읽기 코치 모듈 JavaScript

// 전역 변수
let currentSession = null;
let isRecording = false;
let recognition = null;

// DOM 로드 완료 후 초기화
document.addEventListener('DOMContentLoaded', function() {
    initializeReadingCoach();
    setupEventListeners();
    loadUserProgress();
});

// 읽기 코치 초기화
function initializeReadingCoach() {
    console.log('AI 읽기 코치 초기화 중...');
    
    // Web Speech API 지원 확인
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        console.log('Web Speech API 지원됨');
        initializeSpeechRecognition();
    } else {
        console.warn('Web Speech API를 지원하지 않는 브라우저입니다.');
        showNotification('음성 인식 기능을 사용할 수 없습니다. Chrome 브라우저를 사용해주세요.', 'warning');
    }
    
    // 애니메이션 효과 추가
    addFadeInEffects();
}

// 음성 인식 초기화
function initializeSpeechRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'ko-KR';
    
    recognition.onstart = function() {
        console.log('음성 인식 시작');
        isRecording = true;
        updateRecordingStatus(true);
    };
    
    recognition.onresult = function(event) {
        let interimTranscript = '';
        let finalTranscript = '';
        
        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            if (event.results[i].isFinal) {
                finalTranscript += transcript;
            } else {
                interimTranscript += transcript;
            }
        }
        
        // 속도 분석 및 속발음 감지
        if (finalTranscript) {
            analyzeReadingSpeed(finalTranscript);
            detectSubvocalization(finalTranscript);
        }
    };
    
    recognition.onerror = function(event) {
        console.error('음성 인식 오류:', event.error);
        isRecording = false;
        updateRecordingStatus(false);
        showNotification('음성 인식 중 오류가 발생했습니다.', 'error');
    };
    
    recognition.onend = function() {
        console.log('음성 인식 종료');
        isRecording = false;
        updateRecordingStatus(false);
    };
}

// 이벤트 리스너 설정
function setupEventListeners() {
    // 빠른 시작 버튼들
    const quickStartButtons = document.querySelectorAll('[onclick^="startQuickSession"], [onclick^="showRecommendations"], [onclick^="viewProgress"]');
    quickStartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.getAttribute('onclick').match(/\w+/)[0];
            handleQuickAction(action);
        });
    });
    
    // 연습 모듈 카드들
    const exerciseCards = document.querySelectorAll('.exercise-card');
    exerciseCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                const link = this.querySelector('a');
                if (link) {
                    link.click();
                }
            }
        });
    });
}

// 빠른 액션 처리
function handleQuickAction(action) {
    switch(action) {
        case 'startQuickSession':
            startQuickSession();
            break;
        case 'showRecommendations':
            showRecommendations();
            break;
        case 'viewProgress':
            viewProgress();
            break;
    }
}

// 빠른 연습 시작
function startQuickSession() {
    showNotification('빠른 연습을 시작합니다...', 'info');
    
    // 랜덤 연습 모듈 선택
    const exercises = [
        'visual_reading.php',
        'rhythm_reading.php',
        'finger_reading.php'
    ];
    
    const randomExercise = exercises[Math.floor(Math.random() * exercises.length)];
    setTimeout(() => {
        window.location.href = `exercises/${randomExercise}`;
    }, 1000);
}

// AI 추천 연습
function showRecommendations() {
    const recommendations = [
        {
            title: '속발음 감지됨',
            description: '입술/혀 고정 연습을 추천합니다.',
            exercise: 'lip_tongue_fix.php',
            priority: 'high'
        },
        {
            title: '읽기 속도 향상 필요',
            description: '손가락 따라가기 연습을 추천합니다.',
            exercise: 'finger_reading.php',
            priority: 'medium'
        },
        {
            title: '의미 파악 능력 향상',
            description: '핵심 단어 찾기 연습을 추천합니다.',
            exercise: 'keyword_reading.php',
            priority: 'low'
        }
    ];
    
    showRecommendationsModal(recommendations);
}

// 추천 모달 표시
function showRecommendationsModal(recommendations) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">AI 추천 연습</h3>
            <div class="space-y-4">
                ${recommendations.map(rec => `
                    <div class="border rounded-lg p-4 ${rec.priority === 'high' ? 'border-red-200 bg-red-50' : rec.priority === 'medium' ? 'border-yellow-200 bg-yellow-50' : 'border-blue-200 bg-blue-50'}">
                        <h4 class="font-semibold mb-2">${rec.title}</h4>
                        <p class="text-sm text-gray-600 mb-3">${rec.description}</p>
                        <button onclick="startExercise('${rec.exercise}')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            연습 시작
                        </button>
                    </div>
                `).join('')}
            </div>
            <button onclick="closeModal()" class="mt-4 w-full bg-gray-300 text-gray-700 py-2 rounded hover:bg-gray-400">
                닫기
            </button>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// 연습 시작
function startExercise(exerciseFile) {
    closeModal();
    window.location.href = `exercises/${exerciseFile}`;
}

// 모달 닫기
function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
    }
}

// 진도 확인
function viewProgress() {
    showNotification('진도 확인 페이지로 이동합니다...', 'info');
    setTimeout(() => {
        window.location.href = 'progress.php';
    }, 1000);
}

// 읽기 속도 분석
function analyzeReadingSpeed(text) {
    const words = text.trim().split(/\s+/).length;
    const timeInMinutes = 1; // 1분 기준 (실제로는 측정된 시간 사용)
    const wpm = Math.round(words / timeInMinutes);
    
    console.log(`읽기 속도: ${wpm} WPM`);
    
    // 실시간 WPM 업데이트
    updateWPMDisplay(wpm);
    
    return wpm;
}

// 속발음 감지
function detectSubvocalization(text) {
    // 간단한 속발음 감지 로직 (실제로는 더 정교한 알고리즘 필요)
    const subvocalizationPatterns = [
        /[가-힣]/g, // 한글 패턴
        /\b(그리고|하지만|또는|그런데)\b/g, // 연결어 패턴
        /[.!?]/g // 문장 부호 패턴
    ];
    
    let subvocalizationScore = 0;
    subvocalizationPatterns.forEach(pattern => {
        const matches = text.match(pattern);
        if (matches) {
            subvocalizationScore += matches.length;
        }
    });
    
    const isSubvocalizing = subvocalizationScore > text.length * 0.3;
    
    console.log(`속발음 감지: ${isSubvocalizing ? '감지됨' : '정상'}`);
    
    // 실시간 속발음 상태 업데이트
    updateSubvocalizationStatus(isSubvocalizing);
    
    return isSubvocalizing;
}

// WPM 디스플레이 업데이트
function updateWPMDisplay(wpm) {
    const wpmElement = document.querySelector('.text-2xl.font-bold.text-blue-600');
    if (wpmElement) {
        wpmElement.textContent = wpm;
        wpmElement.classList.add('pulse');
        setTimeout(() => {
            wpmElement.classList.remove('pulse');
        }, 1000);
    }
}

// 속발음 상태 업데이트
function updateSubvocalizationStatus(isDetected) {
    const statusElement = document.querySelector('.px-2.py-1.rounded.text-sm');
    if (statusElement) {
        if (isDetected) {
            statusElement.textContent = '감지됨';
            statusElement.className = 'px-2 py-1 rounded text-sm bg-red-100 text-red-800';
        } else {
            statusElement.textContent = '정상';
            statusElement.className = 'px-2 py-1 rounded text-sm bg-green-100 text-green-800';
        }
    }
}

// 녹음 상태 업데이트
function updateRecordingStatus(isRecording) {
    const recordingIndicator = document.getElementById('recording-indicator');
    if (recordingIndicator) {
        if (isRecording) {
            recordingIndicator.classList.add('pulse');
            recordingIndicator.textContent = '🔴 녹음 중...';
        } else {
            recordingIndicator.classList.remove('pulse');
            recordingIndicator.textContent = '⚪ 대기 중';
        }
    }
}

// 사용자 진도 로드
function loadUserProgress() {
    // 실제로는 서버에서 데이터를 가져옴
    const progressData = {
        currentWPM: 180,
        targetWPM: 300,
        dailyGoal: 80,
        dailyProgress: 65,
        weeklySessions: 5,
        totalSessions: 23
    };
    
    updateProgressDisplay(progressData);
}

// 진도 디스플레이 업데이트
function updateProgressDisplay(data) {
    // 진행률 바 업데이트
    const progressBar = document.querySelector('.bg-blue-600.h-2.rounded-full');
    if (progressBar) {
        progressBar.style.width = `${data.dailyProgress}%`;
    }
    
    // 진행률 퍼센트 업데이트
    const progressPercent = document.querySelector('.text-lg.font-semibold');
    if (progressPercent) {
        progressPercent.textContent = `${data.dailyProgress}%`;
    }
}

// 알림 표시
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        type === 'success' ? 'bg-green-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">${getNotificationIcon(type)}</span>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // 3초 후 자동 제거
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// 알림 아이콘 가져오기
function getNotificationIcon(type) {
    switch(type) {
        case 'error': return '❌';
        case 'warning': return '⚠️';
        case 'success': return '✅';
        default: return 'ℹ️';
    }
}

// 페이드인 효과 추가
function addFadeInEffects() {
    const cards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// 음성 인식 시작
function startSpeechRecognition() {
    if (recognition && !isRecording) {
        recognition.start();
    }
}

// 음성 인식 중지
function stopSpeechRecognition() {
    if (recognition && isRecording) {
        recognition.stop();
    }
}

// 페이지 언로드 시 정리
window.addEventListener('beforeunload', function() {
    if (isRecording) {
        stopSpeechRecognition();
    }
});

// 키보드 단축키
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R: 음성 인식 시작/중지
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        if (isRecording) {
            stopSpeechRecognition();
        } else {
            startSpeechRecognition();
        }
    }
    
    // ESC: 모달 닫기
    if (e.key === 'Escape') {
        closeModal();
    }
});

// 성능 모니터링
function logPerformance() {
    if ('performance' in window) {
        const perfData = performance.getEntriesByType('navigation')[0];
        console.log('페이지 로드 시간:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
    }
}

// 페이지 로드 완료 후 성능 로깅
window.addEventListener('load', logPerformance); 