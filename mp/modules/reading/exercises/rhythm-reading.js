// 리듬 읽기 연습 JavaScript
class RhythmReadingExercise {
    constructor() {
        this.isReading = false;
        this.startTime = null;
        this.timer = null;
        this.metronomeInterval = null;
        this.beatCount = 0;
        this.rhythmScore = 0;
        this.subvocalizationCount = 0;
        this.totalBeats = 0;
        this.currentTempo = 60;
        this.audioContext = null;
        this.oscillator = null;
        
        this.initializeElements();
        this.bindEvents();
        this.loadTempoFromPage();
    }

    initializeElements() {
        // 버튼들
        this.startBtn = document.getElementById('start-reading');
        this.stopBtn = document.getElementById('stop-reading');
        this.resetBtn = document.getElementById('reset-reading');
        
        // 상태 표시
        this.readingIndicator = document.getElementById('reading-indicator');
        this.readingTime = document.getElementById('reading-time');
        this.tempoDisplay = document.getElementById('tempo-display');
        this.beatCountElement = document.getElementById('beat-count');
        this.metronomeBeat = document.getElementById('metronome-beat');
        this.metronomeVisual = document.getElementById('metronome-visual');
        
        // 점수 및 진행률
        this.rhythmScoreElement = document.getElementById('rhythm-score');
        this.rhythmProgress = document.getElementById('rhythm-progress');
        
        // 속발음 감지
        this.subvocalizationIndicator = document.getElementById('subvocalization-indicator');
        this.subvocalizationText = document.getElementById('subvocalization-text');
        
        // 피드백
        this.realTimeFeedback = document.getElementById('real-time-feedback');
        this.exerciseResults = document.getElementById('exercise-results');
        
        // 결과 표시
        this.finalWpm = document.getElementById('final-wpm');
        this.rhythmAccuracy = document.getElementById('rhythm-accuracy');
        this.practiceTime = document.getElementById('practice-time');
        this.aiFeedbackText = document.getElementById('ai-feedback-text');
    }

    bindEvents() {
        this.startBtn.addEventListener('click', () => this.startReading());
        this.stopBtn.addEventListener('click', () => this.stopReading());
        this.resetBtn.addEventListener('click', () => this.resetExercise());
        
        // 키보드 단축키
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !this.isReading) {
                e.preventDefault();
                this.startReading();
            } else if (e.code === 'Escape' && this.isReading) {
                this.stopReading();
            }
        });
    }

    loadTempoFromPage() {
        // 페이지에서 현재 템포 가져오기
        const tempoText = this.tempoDisplay.textContent;
        this.currentTempo = parseInt(tempoText) || 60;
    }

    async startReading() {
        if (this.isReading) return;
        
        this.isReading = true;
        this.startTime = Date.now();
        this.beatCount = 0;
        this.totalBeats = 0;
        this.rhythmScore = 0;
        this.subvocalizationCount = 0;
        
        // UI 업데이트
        this.startBtn.disabled = true;
        this.stopBtn.disabled = false;
        this.readingIndicator.textContent = '🔴 연습 중';
        this.readingIndicator.className = 'text-red-600';
        
        // 메트로놈 시작
        this.startMetronome();
        
        // 타이머 시작
        this.startTimer();
        
        // 속발음 감지 시뮬레이션 시작
        this.startSubvocalizationDetection();
        
        // 실시간 피드백 시작
        this.startRealTimeFeedback();
        
        // 오디오 컨텍스트 초기화 (메트로놈 소리용)
        await this.initializeAudio();
        
        console.log('리듬 읽기 연습 시작 - 템포:', this.currentTempo, 'BPM');
    }

    stopReading() {
        if (!this.isReading) return;
        
        this.isReading = false;
        
        // 메트로놈 중지
        this.stopMetronome();
        
        // 타이머 중지
        this.stopTimer();
        
        // 속발음 감지 중지
        this.stopSubvocalizationDetection();
        
        // UI 업데이트
        this.startBtn.disabled = false;
        this.stopBtn.disabled = true;
        this.readingIndicator.textContent = '⚪ 대기 중';
        this.readingIndicator.className = 'text-muted-foreground';
        
        // 결과 계산 및 표시
        this.calculateResults();
        this.showResults();
        
        console.log('리듬 읽기 연습 종료');
    }

    resetExercise() {
        this.stopReading();
        
        // 모든 상태 초기화
        this.beatCount = 0;
        this.totalBeats = 0;
        this.rhythmScore = 0;
        this.subvocalizationCount = 0;
        
        // UI 초기화
        this.beatCountElement.textContent = '1';
        this.rhythmScoreElement.textContent = '0%';
        this.rhythmProgress.style.width = '0%';
        this.subvocalizationIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.subvocalizationText.textContent = '대기 중';
        this.realTimeFeedback.textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
        this.exerciseResults.classList.add('hidden');
        
        // 메트로놈 시각 효과 초기화
        this.metronomeBeat.className = 'w-4 h-4 bg-green-500 rounded-full';
        
        console.log('리듬 읽기 연습 초기화');
    }

    startMetronome() {
        const beatInterval = (60 / this.currentTempo) * 1000; // 밀리초 단위
        
        this.metronomeInterval = setInterval(() => {
            this.beatCount++;
            this.totalBeats++;
            
            // 비트 카운트 업데이트 (1-4 반복)
            const displayBeat = ((this.beatCount - 1) % 4) + 1;
            this.beatCountElement.textContent = displayBeat;
            
            // 메트로놈 시각 효과
            this.metronomeBeat.className = 'w-4 h-4 bg-red-500 rounded-full';
            setTimeout(() => {
                this.metronomeBeat.className = 'w-4 h-4 bg-green-500 rounded-full';
            }, 100);
            
            // 메트로놈 소리 재생
            this.playMetronomeSound();
            
            // 리듬 점수 계산 (시뮬레이션)
            this.updateRhythmScore();
            
        }, beatInterval);
    }

    stopMetronome() {
        if (this.metronomeInterval) {
            clearInterval(this.metronomeInterval);
            this.metronomeInterval = null;
        }
    }

    async initializeAudio() {
        try {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        } catch (error) {
            console.log('오디오 컨텍스트 초기화 실패:', error);
        }
    }

    playMetronomeSound() {
        if (!this.audioContext) return;
        
        try {
            // 간단한 비프음 생성
            const oscillator = this.audioContext.createOscillator();
            const gainNode = this.audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(this.audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, this.audioContext.currentTime);
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.1, this.audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.1);
            
            oscillator.start(this.audioContext.currentTime);
            oscillator.stop(this.audioContext.currentTime + 0.1);
        } catch (error) {
            console.log('메트로놈 소리 재생 실패:', error);
        }
    }

    startTimer() {
        this.timer = setInterval(() => {
            if (this.startTime) {
                const elapsed = Date.now() - this.startTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                this.readingTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
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
            if (Math.random() < 0.3) { // 30% 확률로 속발음 감지
                this.detectSubvocalization();
            }
        }, 2000 + Math.random() * 3000); // 2-5초 간격
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

    updateRhythmScore() {
        // 리듬 점수 시뮬레이션 (점진적 증가)
        if (this.totalBeats > 0) {
            const baseScore = Math.min(90, 60 + (this.totalBeats * 2));
            const penalty = this.subvocalizationCount * 5;
            this.rhythmScore = Math.max(0, baseScore - penalty);
            
            this.rhythmScoreElement.textContent = `${Math.round(this.rhythmScore)}%`;
            this.rhythmProgress.style.width = `${this.rhythmScore}%`;
        }
    }

    startRealTimeFeedback() {
        const feedbackMessages = [
            '좋습니다! 리듬을 잘 맞추고 있습니다.',
            '메트로놈에 맞춰 읽어보세요.',
            '속발음 없이 시각적으로 읽으세요.',
            '리듬이 일정하게 유지되고 있습니다.',
            '조금 더 빠르게 읽어보세요.',
            '박자에 맞춰 단어를 읽으세요.'
        ];
        
        this.feedbackInterval = setInterval(() => {
            if (this.isReading) {
                const randomMessage = feedbackMessages[Math.floor(Math.random() * feedbackMessages.length)];
                this.realTimeFeedback.textContent = randomMessage;
            }
        }, 3000);
    }

    calculateResults() {
        const elapsed = Date.now() - this.startTime;
        const minutes = elapsed / 60000;
        
        // WPM 계산 (시뮬레이션)
        const wordCount = 50; // 예상 단어 수
        const wpm = Math.round(wordCount / minutes);
        
        // 리듬 정확도
        const rhythmAccuracy = Math.round(this.rhythmScore);
        
        // 연습 시간
        const practiceMinutes = Math.floor(elapsed / 60000);
        const practiceSeconds = Math.floor((elapsed % 60000) / 1000);
        const practiceTimeStr = `${practiceMinutes.toString().padStart(2, '0')}:${practiceSeconds.toString().padStart(2, '0')}`;
        
        // 결과 저장
        this.results = {
            wpm: wpm,
            rhythmAccuracy: rhythmAccuracy,
            practiceTime: practiceTimeStr,
            subvocalizationCount: this.subvocalizationCount,
            totalBeats: this.totalBeats
        };
    }

    showResults() {
        // 결과 표시
        this.finalWpm.textContent = this.results.wpm;
        this.rhythmAccuracy.textContent = `${this.results.rhythmAccuracy}%`;
        this.practiceTime.textContent = this.results.practiceTime;
        
        // AI 피드백 생성
        this.generateAIFeedback();
        
        // 결과 섹션 표시
        this.exerciseResults.classList.remove('hidden');
    }

    generateAIFeedback() {
        const { wpm, rhythmAccuracy, subvocalizationCount } = this.results;
        
        let feedback = '';
        
        if (rhythmAccuracy >= 80) {
            feedback = '훌륭합니다! 리듬을 매우 잘 맞추고 있습니다. 이제 더 빠른 템포로 도전해보세요.';
        } else if (rhythmAccuracy >= 60) {
            feedback = '좋은 시작입니다! 리듬 정확도를 더 높이기 위해 메트로놈에 집중해보세요.';
        } else {
            feedback = '리듬을 더 연습해보세요. 메트로놈 소리에 귀를 기울이고 박자에 맞춰 읽는 연습을 계속하세요.';
        }
        
        if (subvocalizationCount > 5) {
            feedback += ' 속발음이 많이 감지되었습니다. 시각적으로 읽는 연습을 더 해보세요.';
        }
        
        if (wpm < 150) {
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
    window.rhythmExercise = new RhythmReadingExercise();
    
    // 페이지 언로드 시 정리
    window.addEventListener('beforeunload', () => {
        if (window.rhythmExercise) {
            window.rhythmExercise.stopReading();
        }
    });
});

// 키보드 단축키 안내
document.addEventListener('keydown', (e) => {
    if (e.code === 'KeyH' && e.ctrlKey) {
        e.preventDefault();
        alert('키보드 단축키:\n- 스페이스바: 연습 시작/중지\n- ESC: 연습 중지\n- R: 다시 시작');
    }
}); 