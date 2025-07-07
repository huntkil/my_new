// 단계적 연습 모듈 JavaScript
class ProgressiveReadingExercise {
    constructor() {
        this.isReading = false;
        this.startTime = null;
        this.timer = null;
        this.currentWpm = 0;
        this.subvocalizationDetected = false;
        this.subvocalizationTimer = null;
        
        this.initializeElements();
        this.bindEvents();
    }

    initializeElements() {
        this.startButton = document.getElementById('start-reading');
        this.stopButton = document.getElementById('stop-reading');
        this.resetButton = document.getElementById('reset-reading');
        this.readingIndicator = document.getElementById('reading-indicator');
        this.readingTime = document.getElementById('reading-time');
        this.currentWpmElement = document.getElementById('current-wpm');
        this.wpmProgress = document.getElementById('wpm-progress');
        this.subvocalizationIndicator = document.getElementById('subvocalization-indicator');
        this.subvocalizationText = document.getElementById('subvocalization-text');
        this.realTimeFeedback = document.getElementById('real-time-feedback');
        this.readingText = document.getElementById('reading-text');
    }

    bindEvents() {
        this.startButton.addEventListener('click', () => this.startReading());
        this.stopButton.addEventListener('click', () => this.stopReading());
        this.resetButton.addEventListener('click', () => this.resetReading());
    }

    startReading() {
        if (this.isReading) return;

        this.isReading = true;
        this.startTime = Date.now();
        this.startButton.disabled = true;
        this.stopButton.disabled = false;
        this.resetButton.disabled = true;

        // UI 업데이트
        this.readingIndicator.textContent = '🔴 연습 중';
        this.readingIndicator.className = 'text-red-600';
        this.readingText.classList.add('bg-yellow-50', 'border-yellow-200');

        // 타이머 시작
        this.timer = setInterval(() => {
            this.updateTimer();
            this.updateWpm();
            this.checkSubvocalization();
            this.updateFeedback();
        }, 1000);

        // 속발음 감지 시뮬레이션 시작
        this.startSubvocalizationDetection();

        this.realTimeFeedback.textContent = '연습이 시작되었습니다. 텍스트를 빠르게 읽어보세요!';
    }

    stopReading() {
        if (!this.isReading) return;

        this.isReading = false;
        clearInterval(this.timer);
        clearInterval(this.subvocalizationTimer);

        this.startButton.disabled = false;
        this.stopButton.disabled = true;
        this.resetButton.disabled = false;

        // UI 업데이트
        this.readingIndicator.textContent = '⏸️ 일시정지';
        this.readingIndicator.className = 'text-yellow-600';
        this.readingText.classList.remove('bg-yellow-50', 'border-yellow-200');

        this.showFinalResults();
    }

    resetReading() {
        this.isReading = false;
        clearInterval(this.timer);
        clearInterval(this.subvocalizationTimer);

        this.startButton.disabled = false;
        this.stopButton.disabled = true;
        this.resetButton.disabled = true;

        // UI 초기화
        this.readingIndicator.textContent = '⚪ 대기 중';
        this.readingIndicator.className = 'text-muted-foreground';
        this.readingTime.textContent = '00:00';
        this.currentWpmElement.textContent = '0';
        this.wpmProgress.style.width = '0%';
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.subvocalizationText.textContent = '대기 중';
        this.realTimeFeedback.textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
        this.readingText.classList.remove('bg-yellow-50', 'border-yellow-200');

        this.currentWpm = 0;
        this.subvocalizationDetected = false;
    }

    updateTimer() {
        const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        this.readingTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    updateWpm() {
        // 시뮬레이션된 WPM 계산 (실제로는 텍스트 길이와 시간을 기반으로 계산)
        const elapsed = (Date.now() - this.startTime) / 1000 / 60; // 분 단위
        const wordCount = this.readingText.textContent.trim().split(/\s+/).length;
        
        if (elapsed > 0) {
            this.currentWpm = Math.round(wordCount / elapsed);
        }

        this.currentWpmElement.textContent = this.currentWpm;

        // 목표 WPM 대비 진행률 계산 (예시: 목표 300 WPM)
        const targetWpm = 300;
        const progress = Math.min((this.currentWpm / targetWpm) * 100, 100);
        this.wpmProgress.style.width = `${progress}%`;
    }

    startSubvocalizationDetection() {
        this.subvocalizationTimer = setInterval(() => {
            // 랜덤하게 속발음 감지 시뮬레이션
            if (Math.random() < 0.3) { // 30% 확률로 속발음 감지
                this.detectSubvocalization();
            } else {
                this.clearSubvocalization();
            }
        }, 2000 + Math.random() * 3000); // 2-5초 간격
    }

    detectSubvocalization() {
        this.subvocalizationDetected = true;
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-red-500 rounded-full animate-pulse';
        this.subvocalizationText.textContent = '속발음 감지됨';
        this.subvocalizationText.className = 'text-sm text-red-600';
    }

    clearSubvocalization() {
        this.subvocalizationDetected = false;
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
        this.subvocalizationText.textContent = '정상';
        this.subvocalizationText.className = 'text-sm text-green-600';
    }

    updateFeedback() {
        const feedbacks = [
            '좋습니다! 계속 빠르게 읽어보세요.',
            '속발음이 감지되었습니다. 입술을 고정해보세요.',
            '의미 단위로 읽는 것을 시도해보세요.',
            '단어 덩어리를 인식하며 읽어보세요.',
            '호흡을 조절하며 읽어보세요.',
            '목표 속도에 가까워지고 있습니다!',
            '집중력을 유지하세요.',
            '텍스트를 시각적으로 인식해보세요.'
        ];

        if (this.subvocalizationDetected) {
            this.realTimeFeedback.textContent = '⚠️ 속발음이 감지되었습니다. 입술과 혀를 고정하고 시각적으로만 읽어보세요.';
            this.realTimeFeedback.className = 'text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200';
        } else {
            const randomFeedback = feedbacks[Math.floor(Math.random() * feedbacks.length)];
            this.realTimeFeedback.textContent = randomFeedback;
            this.realTimeFeedback.className = 'text-sm text-muted-foreground bg-muted/50 p-3 rounded-lg border';
        }
    }

    showFinalResults() {
        const elapsed = (Date.now() - this.startTime) / 1000 / 60; // 분 단위
        const wordCount = this.readingText.textContent.trim().split(/\s+/).length;
        const finalWpm = Math.round(wordCount / elapsed);

        let resultMessage = `연습 완료!\n\n`;
        resultMessage += `📊 결과:\n`;
        resultMessage += `• 읽기 속도: ${finalWpm} WPM\n`;
        resultMessage += `• 소요 시간: ${Math.floor(elapsed * 60)}초\n`;
        resultMessage += `• 읽은 단어: ${wordCount}개\n\n`;

        if (finalWpm >= 300) {
            resultMessage += `🎉 훌륭합니다! 목표 속도를 달성했습니다.`;
        } else if (finalWpm >= 200) {
            resultMessage += `👍 좋은 성과입니다! 조금 더 연습하면 목표에 도달할 수 있습니다.`;
        } else {
            resultMessage += `💪 꾸준한 연습이 필요합니다. 다음 단계에서 더 나은 결과를 기대해보세요.`;
        }

        this.realTimeFeedback.textContent = resultMessage;
        this.realTimeFeedback.className = 'text-sm text-blue-600 bg-blue-50 p-3 rounded-lg border border-blue-200 whitespace-pre-line';
    }
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', () => {
    new ProgressiveReadingExercise();
}); 