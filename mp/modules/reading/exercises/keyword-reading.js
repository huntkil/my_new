// 핵심 단어 찾기 연습 JavaScript
class KeywordReadingExercise {
    constructor() {
        this.isReading = false;
        this.startTime = null;
        this.timer = null;
        this.currentMode = 'highlight'; // 'highlight' or 'hide'
        this.keywordData = window.keywordData || {};
        this.recognizedKeywords = new Set();
        this.subvocalizationCount = 0;
        this.keywordRecognition = 0;
        
        this.initializeElements();
        this.bindEvents();
        this.setupKeywordHighlighting();
    }

    initializeElements() {
        // 버튼들
        this.startBtn = document.getElementById('start-keyword');
        this.stopBtn = document.getElementById('stop-keyword');
        this.resetBtn = document.getElementById('reset-keyword');
        this.highlightModeBtn = document.getElementById('highlight-mode');
        this.hideModeBtn = document.getElementById('hide-mode');
        
        // 텍스트 영역
        this.textContainer = document.getElementById('text-container');
        this.readingText = document.getElementById('reading-text');
        
        // 상태 표시
        this.keywordIndicator = document.getElementById('keyword-indicator');
        this.keywordTime = document.getElementById('keyword-time');
        this.keywordProgress = document.getElementById('keyword-progress');
        this.currentWpm = document.getElementById('current-wpm');
        this.wpmProgress = document.getElementById('wpm-progress');
        this.keywordRecognitionElement = document.getElementById('keyword-recognition');
        this.recognitionProgress = document.getElementById('recognition-progress');
        
        // 속발음 감지
        this.subvocalizationIndicator = document.getElementById('subvocalization-indicator');
        this.subvocalizationText = document.getElementById('subvocalization-text');
        
        // 피드백
        this.realTimeFeedback = document.getElementById('real-time-feedback');
        this.exerciseResults = document.getElementById('exercise-results');
        
        // 결과 표시
        this.finalWpm = document.getElementById('final-wpm');
        this.finalRecognition = document.getElementById('final-recognition');
        this.practiceTime = document.getElementById('practice-time');
        this.aiFeedbackText = document.getElementById('ai-feedback-text');
    }

    bindEvents() {
        this.startBtn.addEventListener('click', () => this.startReading());
        this.stopBtn.addEventListener('click', () => this.stopReading());
        this.resetBtn.addEventListener('click', () => this.resetExercise());
        this.highlightModeBtn.addEventListener('click', () => this.setHighlightMode());
        this.hideModeBtn.addEventListener('click', () => this.setHideMode());
        
        // 키보드 단축키
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !this.isReading) {
                e.preventDefault();
                this.startReading();
            } else if (e.code === 'Escape' && this.isReading) {
                this.stopReading();
            } else if (e.code === 'KeyH') {
                this.setHighlightMode();
            } else if (e.code === 'KeyD') {
                this.setHideMode();
            }
        });
    }

    setupKeywordHighlighting() {
        // 초기 하이라이트 모드 설정
        this.setHighlightMode();
    }

    setHighlightMode() {
        this.currentMode = 'highlight';
        this.highlightModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-blue-600 text-white';
        this.hideModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-muted text-muted-foreground';
        
        this.applyHighlightMode();
        this.realTimeFeedback.textContent = '하이라이트 모드: 핵심 단어가 강조되어 표시됩니다.';
    }

    setHideMode() {
        this.currentMode = 'hide';
        this.highlightModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-muted text-muted-foreground';
        this.hideModeBtn.className = 'px-3 py-1 rounded text-sm font-medium bg-blue-600 text-white';
        
        this.applyHideMode();
        this.realTimeFeedback.textContent = '숨김 모드: 기능어가 숨겨져 핵심 단어만 표시됩니다.';
    }

    applyHighlightMode() {
        let text = this.keywordData.text || '';
        
        // 키워드 하이라이트
        this.keywordData.keywords.forEach(keyword => {
            const regex = new RegExp(`(${keyword})`, 'g');
            text = text.replace(regex, '<span class="bg-orange-300 text-orange-900 font-semibold px-1 rounded">$1</span>');
        });
        
        // 기능어 스타일링
        this.keywordData.functionWords.forEach(word => {
            const regex = new RegExp(`(${word})`, 'g');
            text = text.replace(regex, '<span class="text-gray-500">$1</span>');
        });
        
        this.readingText.innerHTML = text.replace(/\n/g, '<br>');
    }

    applyHideMode() {
        let text = this.keywordData.text || '';
        
        // 키워드는 그대로 유지
        this.keywordData.keywords.forEach(keyword => {
            const regex = new RegExp(`(${keyword})`, 'g');
            text = text.replace(regex, '<span class="bg-orange-300 text-orange-900 font-semibold px-1 rounded">$1</span>');
        });
        
        // 기능어 숨기기 (점으로 대체)
        this.keywordData.functionWords.forEach(word => {
            const regex = new RegExp(`(${word})`, 'g');
            text = text.replace(regex, '<span class="text-gray-300">' + '.'.repeat(word.length) + '</span>');
        });
        
        this.readingText.innerHTML = text.replace(/\n/g, '<br>');
    }

    async startReading() {
        if (this.isReading) return;
        
        this.isReading = true;
        this.startTime = Date.now();
        this.recognizedKeywords.clear();
        this.subvocalizationCount = 0;
        this.keywordRecognition = 0;
        
        // UI 업데이트
        this.startBtn.disabled = true;
        this.stopBtn.disabled = false;
        this.keywordIndicator.textContent = '🔴 연습 중';
        this.keywordIndicator.className = 'text-red-600';
        
        // 타이머 시작
        this.startTimer();
        
        // 키워드 인식 시뮬레이션 시작
        this.startKeywordRecognition();
        
        // 속발음 감지 시뮬레이션 시작
        this.startSubvocalizationDetection();
        
        // 실시간 피드백 시작
        this.startRealTimeFeedback();
        
        console.log('핵심 단어 찾기 연습 시작 - 모드:', this.currentMode);
    }

    stopReading() {
        if (!this.isReading) return;
        
        this.isReading = false;
        
        // 타이머 중지
        this.stopTimer();
        
        // 키워드 인식 중지
        this.stopKeywordRecognition();
        
        // 속발음 감지 중지
        this.stopSubvocalizationDetection();
        
        // UI 업데이트
        this.startBtn.disabled = false;
        this.stopBtn.disabled = true;
        this.keywordIndicator.textContent = '⚪ 대기 중';
        this.keywordIndicator.className = 'text-muted-foreground';
        
        // 결과 계산 및 표시
        this.calculateResults();
        this.showResults();
        
        console.log('핵심 단어 찾기 연습 종료');
    }

    resetExercise() {
        this.stopReading();
        
        // 모든 상태 초기화
        this.recognizedKeywords.clear();
        this.subvocalizationCount = 0;
        this.keywordRecognition = 0;
        
        // UI 초기화
        this.keywordProgress.textContent = '0%';
        this.currentWpm.textContent = '0';
        this.wpmProgress.style.width = '0%';
        this.keywordRecognitionElement.textContent = '0%';
        this.recognitionProgress.style.width = '0%';
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.subvocalizationText.textContent = '대기 중';
        this.realTimeFeedback.textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
        this.exerciseResults.classList.add('hidden');
        
        // 키워드 태그 초기화
        document.querySelectorAll('.keyword-tag').forEach(tag => {
            tag.className = 'px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded border border-orange-200 keyword-tag';
        });
        
        console.log('핵심 단어 찾기 연습 초기화');
    }

    startKeywordRecognition() {
        // 키워드 인식 시뮬레이션 (랜덤 간격으로 키워드 인식)
        this.keywordInterval = setInterval(() => {
            if (this.isReading) {
                const remainingKeywords = this.keywordData.keywords.filter(
                    keyword => !this.recognizedKeywords.has(keyword)
                );
                
                if (remainingKeywords.length > 0) {
                    const randomKeyword = remainingKeywords[Math.floor(Math.random() * remainingKeywords.length)];
                    this.recognizeKeyword(randomKeyword);
                }
            }
        }, 2000 + Math.random() * 3000); // 2-5초 간격
    }

    stopKeywordRecognition() {
        if (this.keywordInterval) {
            clearInterval(this.keywordInterval);
            this.keywordInterval = null;
        }
    }

    recognizeKeyword(keyword) {
        this.recognizedKeywords.add(keyword);
        
        // 키워드 태그 하이라이트
        const keywordTags = document.querySelectorAll('.keyword-tag');
        keywordTags.forEach(tag => {
            if (tag.textContent.trim() === keyword) {
                tag.className = 'px-2 py-1 bg-green-100 text-green-800 text-xs rounded border border-green-200 keyword-tag font-semibold';
            }
        });
        
        // 인식률 업데이트
        this.updateKeywordRecognition();
    }

    updateKeywordRecognition() {
        const totalKeywords = this.keywordData.keywords.length;
        const recognizedCount = this.recognizedKeywords.size;
        this.keywordRecognition = Math.round((recognizedCount / totalKeywords) * 100);
        
        this.keywordRecognitionElement.textContent = `${this.keywordRecognition}%`;
        this.recognitionProgress.style.width = `${this.keywordRecognition}%`;
        
        // 진행률 업데이트
        this.keywordProgress.textContent = `${this.keywordRecognition}%`;
    }

    startTimer() {
        this.timer = setInterval(() => {
            if (this.startTime) {
                const elapsed = Date.now() - this.startTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                this.keywordTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // WPM 계산 (시뮬레이션)
                const wordCount = Math.round(this.recognizedKeywords.size * 8); // 예상 단어 수
                const wpm = Math.round(wordCount / (elapsed / 60000));
                this.currentWpm.textContent = wpm;
                
                const wpmProgress = Math.min(100, (wpm / 250) * 100);
                this.wpmProgress.style.width = `${wpmProgress}%`;
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
            if (Math.random() < 0.2) { // 20% 확률로 속발음 감지
                this.detectSubvocalization();
            }
        }, 4000 + Math.random() * 5000); // 4-9초 간격
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
            '좋습니다! 키워드에 집중하고 있습니다.',
            '핵심 단어를 빠르게 인식하세요.',
            '기능어는 빠르게 건너뛰세요.',
            '의미 중심으로 읽어보세요.',
            '속발음 없이 시각적으로 읽으세요.',
            '키워드 인식률이 높아지고 있습니다.'
        ];
        
        this.feedbackInterval = setInterval(() => {
            if (this.isReading) {
                const randomMessage = feedbackMessages[Math.floor(Math.random() * feedbackMessages.length)];
                this.realTimeFeedback.textContent = randomMessage;
            }
        }, 5000);
    }

    calculateResults() {
        const elapsed = Date.now() - this.startTime;
        const minutes = elapsed / 60000;
        
        // WPM 계산 (시뮬레이션)
        const wordCount = Math.round(this.recognizedKeywords.size * 8); // 예상 단어 수
        const wpm = Math.round(wordCount / minutes);
        
        // 키워드 인식률
        const finalRecognition = this.keywordRecognition;
        
        // 연습 시간
        const practiceMinutes = Math.floor(elapsed / 60000);
        const practiceSeconds = Math.floor((elapsed % 60000) / 1000);
        const practiceTimeStr = `${practiceMinutes.toString().padStart(2, '0')}:${practiceSeconds.toString().padStart(2, '0')}`;
        
        // 결과 저장
        this.results = {
            wpm: wpm,
            recognition: finalRecognition,
            practiceTime: practiceTimeStr,
            subvocalizationCount: this.subvocalizationCount,
            recognizedKeywords: this.recognizedKeywords.size
        };
    }

    showResults() {
        // 결과 표시
        this.finalWpm.textContent = this.results.wpm;
        this.finalRecognition.textContent = `${this.results.recognition}%`;
        this.practiceTime.textContent = this.results.practiceTime;
        
        // AI 피드백 생성
        this.generateAIFeedback();
        
        // 결과 섹션 표시
        this.exerciseResults.classList.remove('hidden');
    }

    generateAIFeedback() {
        const { wpm, recognition, subvocalizationCount } = this.results;
        
        let feedback = '';
        
        if (recognition >= 90) {
            feedback = '훌륭합니다! 키워드 인식률이 매우 높습니다. 이제 더 빠른 속도로 도전해보세요.';
        } else if (recognition >= 70) {
            feedback = '좋은 시작입니다! 키워드 인식률을 더 높이기 위해 집중해보세요.';
        } else {
            feedback = '키워드 인식을 더 연습해보세요. 핵심 단어에 집중하고 의미 중심으로 읽으세요.';
        }
        
        if (subvocalizationCount > 2) {
            feedback += ' 속발음이 많이 감지되었습니다. 시각적으로 읽는 연습을 더 해보세요.';
        }
        
        if (wpm < 200) {
            feedback += ' 읽기 속도를 높이기 위해 더 많은 연습이 필요합니다.';
        }
        
        if (this.currentMode === 'hide') {
            feedback += ' 숨김 모드에서 좋은 성과를 보였습니다. 하이라이트 모드도 시도해보세요.';
        } else {
            feedback += ' 하이라이트 모드에서 좋은 성과를 보였습니다. 숨김 모드도 시도해보세요.';
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
    window.keywordExercise = new KeywordReadingExercise();
    
    // 페이지 언로드 시 정리
    window.addEventListener('beforeunload', () => {
        if (window.keywordExercise) {
            window.keywordExercise.stopReading();
        }
    });
});

// 키보드 단축키 안내
document.addEventListener('keydown', (e) => {
    if (e.code === 'KeyH' && e.ctrlKey) {
        e.preventDefault();
        alert('키보드 단축키:\n- 스페이스바: 연습 시작/중지\n- ESC: 연습 중지\n- H: 하이라이트 모드\n- D: 숨김 모드');
    }
}); 