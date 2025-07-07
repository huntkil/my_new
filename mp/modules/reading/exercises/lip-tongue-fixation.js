// 입술/혀 고정 연습 JavaScript
class LipTongueFixationExercise {
    constructor() {
        this.isReading = false;
        this.startTime = null;
        this.timer = null;
        this.cameraStream = null;
        this.lipFixationRate = 0;
        this.lipMovementCount = 0;
        this.subvocalizationCount = 0;
        this.targetDuration = 60; // 초기값
        this.penMode = false;
        
        this.initializeElements();
        this.bindEvents();
        this.loadDurationFromPage();
    }

    initializeElements() {
        // 버튼들
        this.startBtn = document.getElementById('start-fixation');
        this.stopBtn = document.getElementById('stop-fixation');
        this.resetBtn = document.getElementById('reset-fixation');
        this.enableCameraBtn = document.getElementById('enable-camera');
        
        // 웹캠 요소들
        this.cameraFeed = document.getElementById('camera-feed');
        this.cameraCanvas = document.getElementById('camera-canvas');
        this.cameraStatus = document.getElementById('camera-status');
        this.penSimulation = document.getElementById('pen-simulation');
        this.lipDetectionArea = document.getElementById('lip-detection-area');
        
        // 상태 표시
        this.fixationIndicator = document.getElementById('fixation-indicator');
        this.fixationTime = document.getElementById('fixation-time');
        this.fixationProgress = document.getElementById('fixation-progress');
        this.lipFixationRateElement = document.getElementById('lip-fixation-rate');
        this.fixationProgressBar = document.getElementById('fixation-progress-bar');
        this.currentWpm = document.getElementById('current-wpm');
        this.wpmProgress = document.getElementById('wpm-progress');
        
        // 감지 상태
        this.lipMovementIndicator = document.getElementById('lip-movement-indicator');
        this.lipMovementText = document.getElementById('lip-movement-text');
        this.penIndicator = document.getElementById('pen-indicator');
        this.penText = document.getElementById('pen-text');
        
        // 피드백
        this.realTimeFeedback = document.getElementById('real-time-feedback');
        this.exerciseResults = document.getElementById('exercise-results');
        
        // 결과 표시
        this.finalFixation = document.getElementById('final-fixation');
        this.finalWpm = document.getElementById('final-wpm');
        this.practiceTime = document.getElementById('practice-time');
        this.aiFeedbackText = document.getElementById('ai-feedback-text');
    }

    bindEvents() {
        this.startBtn.addEventListener('click', () => this.startReading());
        this.stopBtn.addEventListener('click', () => this.stopReading());
        this.resetBtn.addEventListener('click', () => this.resetExercise());
        this.enableCameraBtn.addEventListener('click', () => this.enableCamera());
        
        // 키보드 단축키
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !this.isReading) {
                e.preventDefault();
                this.startReading();
            } else if (e.code === 'Escape' && this.isReading) {
                this.stopReading();
            } else if (e.code === 'KeyP') {
                this.togglePenMode();
            }
        });
    }

    loadDurationFromPage() {
        // 페이지에서 목표 시간 가져오기
        const durationText = document.querySelector('.text-muted-foreground .font-semibold');
        if (durationText) {
            const duration = parseInt(durationText.textContent);
            this.targetDuration = duration || 60;
        }
    }

    async enableCamera() {
        try {
            this.cameraStatus.textContent = '📷 카메라 연결 중...';
            
            // 웹캠 접근
            this.cameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                }
            });
            
            this.cameraFeed.srcObject = this.cameraStream;
            this.cameraStatus.textContent = '📷 카메라 활성화됨';
            this.enableCameraBtn.style.display = 'none';
            
            // 입술 감지 시뮬레이션 시작
            this.startLipDetection();
            
            console.log('카메라 활성화 성공');
        } catch (error) {
            console.error('카메라 접근 실패:', error);
            this.cameraStatus.textContent = '📷 카메라 접근 실패';
            this.enableCameraBtn.textContent = '다시 시도';
        }
    }

    startLipDetection() {
        // 입술 움직임 감지 시뮬레이션 (랜덤 간격으로 감지)
        this.lipDetectionInterval = setInterval(() => {
            if (this.isReading && Math.random() < 0.3) { // 30% 확률로 입술 움직임 감지
                this.detectLipMovement();
            }
        }, 3000 + Math.random() * 4000); // 3-7초 간격
    }

    stopLipDetection() {
        if (this.lipDetectionInterval) {
            clearInterval(this.lipDetectionInterval);
            this.lipDetectionInterval = null;
        }
    }

    detectLipMovement() {
        this.lipMovementCount++;
        
        // UI 업데이트
        this.lipMovementIndicator.className = 'w-3 h-3 bg-red-500 rounded-full';
        this.lipMovementText.textContent = '입술 움직임 감지됨';
        this.lipMovementText.className = 'text-sm text-red-600';
        
        // 입술 감지 영역 하이라이트
        this.lipDetectionArea.className = 'absolute bottom-4 left-4 w-16 h-8 border-2 border-red-400 rounded-full opacity-75 bg-red-200';
        
        // 2초 후 원래 상태로 복원
        setTimeout(() => {
            this.lipMovementIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
            this.lipMovementText.textContent = '입술 고정됨';
            this.lipMovementText.className = 'text-sm text-green-600';
            this.lipDetectionArea.className = 'absolute bottom-4 left-4 w-16 h-8 border-2 border-pink-400 rounded-full opacity-50';
        }, 2000);
    }

    togglePenMode() {
        this.penMode = !this.penMode;
        
        if (this.penMode) {
            this.penSimulation.classList.remove('hidden');
            this.penIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
            this.penText.textContent = '펜 물기 모드 활성화';
            this.penText.className = 'text-sm text-green-600';
            this.realTimeFeedback.textContent = '펜을 물고 입술 움직임을 최소화하세요.';
        } else {
            this.penSimulation.classList.add('hidden');
            this.penIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
            this.penText.textContent = '펜을 준비하세요';
            this.penText.className = 'text-sm text-muted-foreground';
            this.realTimeFeedback.textContent = '펜 물기 모드를 비활성화했습니다.';
        }
    }

    async startReading() {
        if (this.isReading) return;
        
        this.isReading = true;
        this.startTime = Date.now();
        this.lipMovementCount = 0;
        this.subvocalizationCount = 0;
        this.lipFixationRate = 0;
        
        // UI 업데이트
        this.startBtn.disabled = true;
        this.stopBtn.disabled = false;
        this.fixationIndicator.textContent = '🔴 연습 중';
        this.fixationIndicator.className = 'text-red-600';
        
        // 타이머 시작
        this.startTimer();
        
        // 입술 고정률 계산 시작
        this.startFixationCalculation();
        
        // 속발음 감지 시뮬레이션 시작
        this.startSubvocalizationDetection();
        
        // 실시간 피드백 시작
        this.startRealTimeFeedback();
        
        console.log('입술/혀 고정 연습 시작');
    }

    stopReading() {
        if (!this.isReading) return;
        
        this.isReading = false;
        
        // 타이머 중지
        this.stopTimer();
        
        // 입술 고정률 계산 중지
        this.stopFixationCalculation();
        
        // 속발음 감지 중지
        this.stopSubvocalizationDetection();
        
        // UI 업데이트
        this.startBtn.disabled = false;
        this.stopBtn.disabled = true;
        this.fixationIndicator.textContent = '⚪ 대기 중';
        this.fixationIndicator.className = 'text-muted-foreground';
        
        // 결과 계산 및 표시
        this.calculateResults();
        this.showResults();
        
        console.log('입술/혀 고정 연습 종료');
    }

    resetExercise() {
        this.stopReading();
        
        // 모든 상태 초기화
        this.lipMovementCount = 0;
        this.subvocalizationCount = 0;
        this.lipFixationRate = 0;
        
        // UI 초기화
        this.fixationProgress.textContent = '0%';
        this.lipFixationRateElement.textContent = '0%';
        this.fixationProgressBar.style.width = '0%';
        this.currentWpm.textContent = '0';
        this.wpmProgress.style.width = '0%';
        this.lipMovementIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.lipMovementText.textContent = '대기 중';
        this.penIndicator.className = 'w-3 h-3 bg-muted-foreground rounded-full';
        this.penText.textContent = '펜을 준비하세요';
        this.realTimeFeedback.textContent = '연습을 시작하면 실시간 피드백이 표시됩니다.';
        this.exerciseResults.classList.add('hidden');
        
        // 펜 모드 초기화
        this.penMode = false;
        this.penSimulation.classList.add('hidden');
        
        console.log('입술/혀 고정 연습 초기화');
    }

    startFixationCalculation() {
        // 입술 고정률 계산 시뮬레이션 (점진적 증가)
        this.fixationInterval = setInterval(() => {
            if (this.isReading) {
                const baseRate = Math.min(95, 70 + (Date.now() - this.startTime) / 1000 * 2);
                const penalty = this.lipMovementCount * 10;
                this.lipFixationRate = Math.max(0, baseRate - penalty);
                
                this.lipFixationRateElement.textContent = `${Math.round(this.lipFixationRate)}%`;
                this.fixationProgressBar.style.width = `${this.lipFixationRate}%`;
            }
        }, 1000);
    }

    stopFixationCalculation() {
        if (this.fixationInterval) {
            clearInterval(this.fixationInterval);
            this.fixationInterval = null;
        }
    }

    startTimer() {
        this.timer = setInterval(() => {
            if (this.startTime) {
                const elapsed = Date.now() - this.startTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                this.fixationTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // 진행률 계산
                const progress = Math.min(100, (elapsed / (this.targetDuration * 1000)) * 100);
                this.fixationProgress.textContent = `${Math.round(progress)}%`;
                
                // WPM 계산 (시뮬레이션)
                const wordCount = Math.round(elapsed / 1000 * 3); // 초당 3단어 가정
                const wpm = Math.round(wordCount / (elapsed / 60000));
                this.currentWpm.textContent = wpm;
                
                const wpmProgress = Math.min(100, (wpm / 200) * 100);
                this.wpmProgress.style.width = `${wpmProgress}%`;
                
                // 목표 시간 도달 시 자동 종료
                if (elapsed >= this.targetDuration * 1000) {
                    this.stopReading();
                }
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
            if (Math.random() < 0.15) { // 15% 확률로 속발음 감지
                this.detectSubvocalization();
            }
        }, 5000 + Math.random() * 6000); // 5-11초 간격
    }

    stopSubvocalizationDetection() {
        if (this.subvocalizationInterval) {
            clearInterval(this.subvocalizationInterval);
            this.subvocalizationInterval = null;
        }
    }

    detectSubvocalization() {
        this.subvocalizationCount++;
        
        // 실시간 피드백에 속발음 경고 표시
        this.realTimeFeedback.textContent = '⚠️ 속발음이 감지되었습니다. 입술을 더 고정하세요.';
        this.realTimeFeedback.className = 'text-sm text-red-600 bg-red-50 p-3 rounded-lg min-h-[60px] border border-red-200';
        
        // 3초 후 원래 상태로 복원
        setTimeout(() => {
            this.realTimeFeedback.className = 'text-sm text-muted-foreground bg-muted/50 p-3 rounded-lg min-h-[60px] border';
        }, 3000);
    }

    startRealTimeFeedback() {
        const feedbackMessages = [
            '좋습니다! 입술이 잘 고정되어 있습니다.',
            '혀를 윗니에 대고 고정하세요.',
            '입술 움직임을 최소화하세요.',
            '시각적으로만 읽어보세요.',
            '펜을 물면 더 효과적입니다.',
            '입술 고정률이 높아지고 있습니다.'
        ];
        
        this.feedbackInterval = setInterval(() => {
            if (this.isReading) {
                const randomMessage = feedbackMessages[Math.floor(Math.random() * feedbackMessages.length)];
                if (this.realTimeFeedback.className.includes('text-red-600')) {
                    return; // 속발음 경고 중에는 메시지 변경하지 않음
                }
                this.realTimeFeedback.textContent = randomMessage;
            }
        }, 6000);
    }

    calculateResults() {
        const elapsed = Date.now() - this.startTime;
        const minutes = elapsed / 60000;
        
        // WPM 계산 (시뮬레이션)
        const wordCount = Math.round(elapsed / 1000 * 3); // 초당 3단어 가정
        const wpm = Math.round(wordCount / minutes);
        
        // 입술 고정률
        const finalFixation = Math.round(this.lipFixationRate);
        
        // 연습 시간
        const practiceMinutes = Math.floor(elapsed / 60000);
        const practiceSeconds = Math.floor((elapsed % 60000) / 1000);
        const practiceTimeStr = `${practiceMinutes.toString().padStart(2, '0')}:${practiceSeconds.toString().padStart(2, '0')}`;
        
        // 결과 저장
        this.results = {
            fixation: finalFixation,
            wpm: wpm,
            practiceTime: practiceTimeStr,
            lipMovementCount: this.lipMovementCount,
            subvocalizationCount: this.subvocalizationCount
        };
    }

    showResults() {
        // 결과 표시
        this.finalFixation.textContent = `${this.results.fixation}%`;
        this.finalWpm.textContent = this.results.wpm;
        this.practiceTime.textContent = this.results.practiceTime;
        
        // AI 피드백 생성
        this.generateAIFeedback();
        
        // 결과 섹션 표시
        this.exerciseResults.classList.remove('hidden');
    }

    generateAIFeedback() {
        const { fixation, wpm, lipMovementCount, subvocalizationCount } = this.results;
        
        let feedback = '';
        
        if (fixation >= 85) {
            feedback = '훌륭합니다! 입술 고정률이 매우 높습니다. 이제 더 긴 시간 동안 연습해보세요.';
        } else if (fixation >= 70) {
            feedback = '좋은 시작입니다! 입술 고정률을 더 높이기 위해 집중해보세요.';
        } else {
            feedback = '입술 고정을 더 연습해보세요. 입술을 살짝 벌리고 혀를 윗니에 대고 고정하세요.';
        }
        
        if (lipMovementCount > 3) {
            feedback += ' 입술 움직임이 많이 감지되었습니다. 더 집중해서 고정해보세요.';
        }
        
        if (subvocalizationCount > 2) {
            feedback += ' 속발음이 많이 감지되었습니다. 시각적으로 읽는 연습을 더 해보세요.';
        }
        
        if (wpm < 150) {
            feedback += ' 읽기 속도를 높이기 위해 더 많은 연습이 필요합니다.';
        }
        
        if (this.penMode) {
            feedback += ' 펜 물기 모드에서 좋은 성과를 보였습니다. 계속 사용해보세요.';
        } else {
            feedback += ' 펜 물기 모드를 시도해보면 더 효과적일 수 있습니다.';
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
    window.lipFixationExercise = new LipTongueFixationExercise();
    
    // 페이지 언로드 시 정리
    window.addEventListener('beforeunload', () => {
        if (window.lipFixationExercise) {
            window.lipFixationExercise.stopReading();
            // 카메라 스트림 정리
            if (window.lipFixationExercise.cameraStream) {
                window.lipFixationExercise.cameraStream.getTracks().forEach(track => track.stop());
            }
        }
    });
});

// 키보드 단축키 안내
document.addEventListener('keydown', (e) => {
    if (e.code === 'KeyH' && e.ctrlKey) {
        e.preventDefault();
        alert('키보드 단축키:\n- 스페이스바: 연습 시작/중지\n- ESC: 연습 중지\n- P: 펜 물기 모드 토글');
    }
}); 