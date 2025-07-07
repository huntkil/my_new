// 손가락 따라가기 연습 JavaScript
class FingerFollowingExercise {
    constructor() {
        this.isFollowing = false;
        this.startTime = null;
        this.timer = null;
        this.autoMode = false;
        this.autoInterval = null;
        this.currentPosition = 0;
        this.totalPositions = 0;
        this.accuracyScore = 0;
        this.subvocalizationCount = 0;
        this.targetSpeed = 100;
        this.fingerAccuracy = 0;
        
        this.initializeElements();
        this.bindEvents();
        this.loadSpeedFromPage();
        this.setupTextPositions();
    }

    initializeElements() {
        // 버튼들
        this.startBtn = document.getElementById('start-following');
        this.stopBtn = document.getElementById('stop-following');
        this.resetBtn = document.getElementById('reset-following');
        this.manualModeBtn = document.getElementById('manual-mode');
        this.autoModeBtn = document.getElementById('auto-mode');
        
        // 텍스트 영역
        this.textContainer = document.getElementById('text-container');
        this.readingText = document.getElementById('reading-text');
        this.virtualFinger = document.getElementById('virtual-finger');
        this.highlightArea = document.getElementById('highlight-area');
        
        // 상태 표시
        this.followingIndicator = document.getElementById('following-indicator');
        this.followingTime = document.getElementById('following-time');
        this.followingProgress = document.getElementById('following-progress');
        this.currentWpm = document.getElementById('current-wpm');
        this.wpmProgress = document.getElementById('wpm-progress');
        this.fingerAccuracyElement = document.getElementById('finger-accuracy');
        this.accuracyProgress = document.getElementById('accuracy-progress');
        
        // 속발음 감지
        this.subvocalizationIndicator = document.getElementById('subvocalization-indicator');
        this.subvocalizationText = document.getElementById('subvocalization-text');
        
        // 피드백
        this.realTimeFeedback = document.getElementById('real-time-feedback');
        this.exerciseResults = document.getElementById('exercise-results');
        
        // 결과 표시
        this.finalWpm = document.getElementById('final-wpm');
        this.finalAccuracy = document.getElementById('final-accuracy');
        this.practiceTime = document.getElementById('practice-time');
        this.aiFeedbackText = document.getElementById('ai-feedback-text');
    }

    bindEvents() {
        this.startBtn.addEventListener('click', () => this.startFollowing());
        this.stopBtn.addEventListener('click', () => this.stopFollowing());
        this.resetBtn.addEventListener('click', () => this.resetExercise());
        this.manualModeBtn.addEventListener('click', () => this.setManualMode());
        this.autoModeBtn.addEventListener('click', () => this.setAutoMode());
        
        // 마우스/터치 이벤트
        this.textContainer.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        this.textContainer.addEventListener('touchmove', (e) => this.handleTouchMove(e));
        this.textContainer.addEventListener('click', (e) => this.handleClick(e));
        
        // 키보드 단축키
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !this.isFollowing) {
                e.preventDefault();
                this.startFollowing();
            } else if (e.code === 'Escape' && this.isFollowing) {
                this.stopFollowing();
            } else if (e.code === 'KeyM') {
                this.setManualMode();
            } else if (e.code === 'KeyA') {
                this.setAutoMode();
            }
        });
    }

    loadSpeedFromPage() {
        // 페이지에서 목표 속도 가져오기
        const speedText = document.querySelector('.text-muted-foreground .font-semibold');
        if (speedText) {
            this.targetSpeed = parseInt(speedText.textContent) || 100;
        }
    }

    setupTextPositions() {
        // 텍스트의 각 줄과 단어 위치 계산
        this.textPositions = [];
        const lines = this.readingText.children;
        
        for (let i = 0; i < lines.length; i++) {
            const line = lines[i];
            const rect = line.getBoundingClientRect();
            const containerRect = this.textContainer.getBoundingClientRect();
            
            this.textPositions.push({
                y: rect.top - containerRect.top + rect.height / 2,
                x: rect.left - containerRect.left,
                width: rect.width,
                height: rect.height,
                lineIndex: i
            });
        }
        
        this.totalPositions = this.textPositions.length;
    }

    setManualMode() {
        this.autoMode = false;
        this.manualModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-blue-600 text-white';
        this.autoModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-muted text-muted-foreground';
        
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
            this.autoInterval = null;
        }
        
        this.realTimeFeedback.textContent = '수동 모드: 마우스나 터치로 손가락을 제어하세요.';
    }

    setAutoMode() {
        this.autoMode = true;
        this.manualModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-muted text-muted-foreground';
        this.autoModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-blue-600 text-white';
        
        this.realTimeFeedback.textContent = '자동 모드: 손가락이 자동으로 움직입니다. 따라가며 읽으세요.';
        
        if (this.isFollowing) {
            this.startAutoMode();
        }
    }

    async startFollowing() {
        if (this.isFollowing) return;
        
        this.isFollowing = true;
        this.startTime = Date.now();
        this.currentPosition = 0;
        this.accuracyScore = 0;
        this.subvocalizationCount = 0;
        this.fingerAccuracy = 0;
        
        // UI 업데이트
        this.startBtn.disabled = true;
        this.stopBtn.disabled = false;
        this.followingIndicator.textContent = '🔴 연습 중';
        this.followingIndicator.className = 'text-red-600';
        
        // 가상 손가락 표시
        this.virtualFinger.classList.remove('hidden');
        this.highlightArea.classList.remove('hidden');
        
        // 자동 모드 시작
        if (this.autoMode) {
            this.startAutoMode();
        }
        
        // 타이머 시작
        this.startTimer();
        
        // 속발음 감지 시뮬레이션 시작
        this.startSubvocalizationDetection();
        
        // 실시간 피드백 시작
        this.startRealTimeFeedback();
        
        console.log('손가락 따라가기 연습 시작 - 모드:', this.autoMode ? '자동' : '수동');
    }

    stopFollowing() {
        if (!this.isFollowing) return;
        
        this.isFollowing = false;
        
        // 자동 모드 중지
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
            this.autoInterval = null;
        }
        
        // 타이머 중지
        this.stopTimer();
        
        // 속발음 감지 중지
        this.stopSubvocalizationDetection();
        
        // UI 업데이트
        this.startBtn.disabled = false;
        this.stopBtn.disabled = true;
        this.followingIndicator.textContent = '⚪ 대기 중';
        this.followingIndicator.className = 'text-muted-foreground';
        
        // 가상 손가락 숨기기
        this.virtualFinger.classList.add('hidden');
        this.highlightArea.classList.add('hidden');
        
        // 결과 계산 및 표시
        this.calculateResults();
        this.showResults();
        
        console.log('손가락 따라가기 연습 종료');
    }

    resetExercise() {
        this.stopFollowing();
        
        // 모든 상태 초기화
        this.currentPosition = 0;
        this.accuracyScore = 0;
        this.subvocalizationCount = 0;
        this.fingerAccuracy = 0;
        
        // UI 초기화
        this.followingProgress.textContent = '0%';
        this.currentWpm.textContent = '0';
        this.wpmProgress.style.width = '0%';
        this.fingerAccuracyElement.textContent = '0%';
        this.accuracyProgress.style.width = '0%';
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.subvocalizationText.textContent = '대기 중';
        this.realTimeFeedback.textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
        this.exerciseResults.classList.add('hidden');
        
        // 가상 손가락 위치 초기화
        this.virtualFinger.style.left = '0px';
        this.virtualFinger.style.top = '0px';
        this.highlightArea.style.left = '0px';
        this.highlightArea.style.top = '0px';
        this.highlightArea.style.width = '0px';
        this.highlightArea.style.height = '0px';
        
        console.log('손가락 따라가기 연습 초기화');
    }

    startAutoMode() {
        if (!this.isFollowing || !this.autoMode) return;
        
        const moveInterval = (60 / this.targetSpeed) * 1000; // WPM 기반 간격
        
        this.autoInterval = setInterval(() => {
            if (this.currentPosition < this.totalPositions - 1) {
                this.currentPosition++;
                this.moveFingerToPosition(this.currentPosition);
                this.updateProgress();
            } else {
                this.stopFollowing();
            }
        }, moveInterval);
    }

    moveFingerToPosition(position) {
        if (position >= this.textPositions.length) return;
        
        const pos = this.textPositions[position];
        const containerRect = this.textContainer.getBoundingClientRect();
        
        // 가상 손가락 위치 업데이트
        this.virtualFinger.style.left = `${pos.x}px`;
        this.virtualFinger.style.top = `${pos.y - 4}px`; // 손가락 크기 조정
        
        // 하이라이트 영역 업데이트
        this.highlightArea.style.left = `${pos.x}px`;
        this.highlightArea.style.top = `${pos.y - pos.height / 2}px`;
        this.highlightArea.style.width = `${pos.width}px`;
        this.highlightArea.style.height = `${pos.height}px`;
        
        // 정확도 계산 (시뮬레이션)
        this.updateAccuracy();
    }

    handleMouseMove(e) {
        if (!this.isFollowing || this.autoMode) return;
        
        const rect = this.textContainer.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // 가장 가까운 텍스트 위치 찾기
        const closestPosition = this.findClosestPosition(x, y);
        
        if (closestPosition !== this.currentPosition) {
            this.currentPosition = closestPosition;
            this.moveFingerToPosition(this.currentPosition);
            this.updateProgress();
        }
        
        // 가상 손가락 위치 업데이트
        this.virtualFinger.style.left = `${x - 16}px`; // 손가락 크기 조정
        this.virtualFinger.style.top = `${y - 16}px`;
    }

    handleTouchMove(e) {
        if (!this.isFollowing || this.autoMode) return;
        
        e.preventDefault();
        const touch = e.touches[0];
        const rect = this.textContainer.getBoundingClientRect();
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        
        // 가장 가까운 텍스트 위치 찾기
        const closestPosition = this.findClosestPosition(x, y);
        
        if (closestPosition !== this.currentPosition) {
            this.currentPosition = closestPosition;
            this.moveFingerToPosition(this.currentPosition);
            this.updateProgress();
        }
        
        // 가상 손가락 위치 업데이트
        this.virtualFinger.style.left = `${x - 16}px`;
        this.virtualFinger.style.top = `${y - 16}px`;
    }

    handleClick(e) {
        if (!this.isFollowing || this.autoMode) return;
        
        const rect = this.textContainer.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // 클릭한 위치로 이동
        const closestPosition = this.findClosestPosition(x, y);
        this.currentPosition = closestPosition;
        this.moveFingerToPosition(this.currentPosition);
        this.updateProgress();
    }

    findClosestPosition(x, y) {
        let closest = 0;
        let minDistance = Infinity;
        
        for (let i = 0; i < this.textPositions.length; i++) {
            const pos = this.textPositions[i];
            const distance = Math.sqrt(
                Math.pow(x - (pos.x + pos.width / 2), 2) + 
                Math.pow(y - pos.y, 2)
            );
            
            if (distance < minDistance) {
                minDistance = distance;
                closest = i;
            }
        }
        
        return closest;
    }

    updateProgress() {
        const progress = Math.round((this.currentPosition / this.totalPositions) * 100);
        this.followingProgress.textContent = `${progress}%`;
        
        // WPM 계산 (시뮬레이션)
        if (this.startTime) {
            const elapsed = Date.now() - this.startTime;
            const minutes = elapsed / 60000;
            const wordCount = Math.round(this.currentPosition * 5); // 예상 단어 수
            const wpm = Math.round(wordCount / minutes);
            
            this.currentWpm.textContent = wpm;
            const wpmProgress = Math.min(100, (wpm / this.targetSpeed) * 100);
            this.wpmProgress.style.width = `${wpmProgress}%`;
        }
    }

    updateAccuracy() {
        // 손가락 정확도 시뮬레이션 (점진적 증가)
        if (this.currentPosition > 0) {
            const baseAccuracy = Math.min(95, 70 + (this.currentPosition * 2));
            const penalty = this.subvocalizationCount * 3;
            this.fingerAccuracy = Math.max(0, baseAccuracy - penalty);
            
            this.fingerAccuracyElement.textContent = `${Math.round(this.fingerAccuracy)}%`;
            this.accuracyProgress.style.width = `${this.fingerAccuracy}%`;
        }
    }

    startTimer() {
        this.timer = setInterval(() => {
            if (this.startTime) {
                const elapsed = Date.now() - this.startTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                this.followingTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }

    stopTimer() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }

    startSubvocalizationDetection() {
        // 속발음 감지 시뮬레이션 (랜덤 간격으로 감지)
        this.subvocalizationInterval = setInterval(() => {
            if (Math.random() < 0.25) { // 25% 확률로 속발음 감지
                this.detectSubvocalization();
            }
        }, 3000 + Math.random() * 4000); // 3-7초 간격
    }

    stopSubvocalizationDetection() {
        if (this.subvocalizationInterval) {
            clearInterval(this.subvocalizationInterval);
            this.subvocalizationInterval = null;
        }
    }

    detectSubvocalization() {
        this.subvocalizationCount++;
        
        // UI 업데이트
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-red-500 rounded-full';
        this.subvocalizationText.textContent = '속발음 감지됨';
        this.subvocalizationText.className = 'text-sm text-red-600';
        
        // 2초 후 원래 상태로 복원
        setTimeout(() => {
            this.subvocalizationIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
            this.subvocalizationText.textContent = '정상';
            this.subvocalizationText.className = 'text-sm text-green-600';
        }, 2000);
    }

    startRealTimeFeedback() {
        const feedbackMessages = [
            '좋습니다! 손가락을 잘 따라가고 있습니다.',
            '일정한 속도로 천천히 따라가세요.',
            '속발음 없이 시각적으로 읽으세요.',
            '손가락을 벗어나지 않도록 집중하세요.',
            '텍스트에 집중하여 읽어보세요.',
            '손가락 움직임이 부드럽습니다.'
        ];
        
        this.feedbackInterval = setInterval(() => {
            if (this.isFollowing) {
                const randomMessage = feedbackMessages[Math.floor(Math.random() * feedbackMessages.length)];
                this.realTimeFeedback.textContent = randomMessage;
            }
        }, 4000);
    }

    calculateResults() {
        const elapsed = Date.now() - this.startTime;
        const minutes = elapsed / 60000;
        
        // WPM 계산 (시뮬레이션)
        const wordCount = Math.round(this.currentPosition * 5); // 예상 단어 수
        const wpm = Math.round(wordCount / minutes);
        
        // 손가락 정확도
        const finalAccuracy = Math.round(this.fingerAccuracy);
        
        // 연습 시간
        const practiceMinutes = Math.floor(elapsed / 60000);
        const practiceSeconds = Math.floor((elapsed % 60000) / 1000);
        const practiceTimeStr = `${practiceMinutes.toString().padStart(2, '0')}:${practiceSeconds.toString().padStart(2, '0')}`;
        
        // 결과 저장
        this.results = {
            wpm: wpm,
            accuracy: finalAccuracy,
            practiceTime: practiceTimeStr,
            subvocalizationCount: this.subvocalizationCount,
            currentPosition: this.currentPosition
        };
    }

    showResults() {
        // 결과 표시
        this.finalWpm.textContent = this.results.wpm;
        this.finalAccuracy.textContent = `${this.results.accuracy}%`;
        this.practiceTime.textContent = this.results.practiceTime;
        
        // AI 피드백 생성
        this.generateAIFeedback();
        
        // 결과 섹션 표시
        this.exerciseResults.classList.remove('hidden');
    }

    generateAIFeedback() {
        const { wpm, accuracy, subvocalizationCount } = this.results;
        
        let feedback = '';
        
        if (accuracy >= 85) {
            feedback = '훌륭합니다! 손가락 정확도가 매우 높습니다. 이제 더 빠른 속도로 도전해보세요.';
        } else if (accuracy >= 70) {
            feedback = '좋은 시작입니다! 손가락 정확도를 더 높이기 위해 집중해보세요.';
        } else {
            feedback = '손가락 따라가기를 더 연습해보세요. 텍스트에 집중하고 일정한 속도를 유지하세요.';
        }
        
        if (subvocalizationCount > 3) {
            feedback += ' 속발음이 많이 감지되었습니다. 시각적으로 읽는 연습을 더 해보세요.';
        }
        
        if (wpm < this.targetSpeed * 0.8) {
            feedback += ' 읽기 속도를 높이기 위해 더 많은 연습이 필요합니다.';
        }
        
        this.aiFeedbackText.textContent = feedback;
    }
}

// 전역 함수들
function saveResults() {
    // 결과 저장 기능 (향후 구현)
    alert('결과가 저장되었습니다!');
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', () => {
    window.fingerExercise = new FingerFollowingExercise();
    
    // 페이지 언로드 시 정리
    window.addEventListener('beforeunload', () => {
        if (window.fingerExercise) {
            window.fingerExercise.stopFollowing();
        }
    });
});

// 키보드 단축키 안내
document.addEventListener('keydown', (e) => {
    if (e.code === 'KeyH' && e.ctrlKey) {
        e.preventDefault();
        alert('키보드 단축키:\n- 스페이스바: 연습 시작/중지\n- ESC: 연습 중지\n- M: 수동 모드\n- A: 자동 모드');
    }
}); 