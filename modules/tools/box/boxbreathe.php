<?php
session_start();
$pageTitle = "Box‑Breathing Trainer";
include "../../../system/includes/header.php";
?>

<div class="container mx-auto px-4 py-8">
  <div class="max-w-md mx-auto space-y-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-center">
      Box‑Breathing Trainer
    </h1>

    <div class="bg-card text-card-foreground rounded-lg border p-6 space-y-6">
      <!-- Controls -->
      <div id="controls" class="space-y-4">
        <!-- Rounds -->
        <div data-key="rounds" class="flex items-center justify-between gap-2">
          <span class="font-medium w-24">Rounds</span>
          <button class="p-2 rounded-lg hover:bg-accent transition-colors" data-action="dec" aria-label="rounds minus">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><line x1="5" x2="19" y1="12" y2="12"/></svg>
          </button>
          <span class="value w-8 text-lg text-center select-none">4</span>
          <button class="p-2 rounded-lg hover:bg-accent transition-colors" data-action="inc" aria-label="rounds plus">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
          </button>
        </div>

        <!-- Each Phase -->
        <?php
          $rows = [
            ["key"=>"inhale",    "label"=>"Inhale"],
            ["key"=>"holdFull",  "label"=>"Hold ↑"],
            ["key"=>"exhale",    "label"=>"Exhale"],
            ["key"=>"holdEmpty", "label"=>"Hold ↓"],
          ];
          foreach ($rows as $r) {
            echo <<<HTML
              <div data-key="{$r['key']}" class="flex items-center justify-between gap-2">
                <span class="font-medium w-24">{$r['label']}</span>
                <button class="p-2 rounded-lg hover:bg-accent transition-colors" data-action="dec" aria-label="{$r['label']} minus">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><line x1="5" x2="19" y1="12" y2="12"/></svg>
                </button>
                <span class="value w-8 text-lg text-center select-none">4</span>
                <button class="p-2 rounded-lg hover:bg-accent transition-colors" data-action="inc" aria-label="{$r['label']} plus">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                </button>
              </div>
            HTML;
          }
        ?>
      </div>

      <!-- Timer Display -->
      <div class="flex flex-col items-center gap-3">
        <div id="circle" class="w-32 h-32 rounded-full border-4 border-primary flex items-center justify-center transition-transform duration-300 scale-100">
          <span id="timer" class="text-xl sm:text-2xl font-semibold select-none">4s</span>
        </div>
        <p class="text-lg sm:text-xl">
          Phase: <span id="phase" class="font-bold">Inhale</span>
        </p>
        <p>
          Remaining: <span id="remaining" class="font-bold">4</span>
        </p>
      </div>

      <!-- Control Buttons -->
      <div class="flex justify-center gap-4">
        <button id="startPause"
                class="flex-1 bg-primary text-primary-foreground py-3 rounded-lg hover:bg-primary/90 transition-colors">
          Start&nbsp;(S)
        </button>
        <button id="reset"
                class="flex-1 bg-secondary text-secondary-foreground py-3 rounded-lg hover:bg-secondary/90 transition-colors">
          Reset&nbsp;(R)
        </button>
      </div>
    </div>
  </div>
</div>

<script>
(() => {
  /* 데이터 초기값 */
  const phases     = ["Inhale", "Hold ↑", "Exhale", "Hold ↓"];
  const phaseKeys  = ["inhale", "holdFull", "exhale", "holdEmpty"];
  let durations    = { inhale: 4, holdFull: 4, exhale: 4, holdEmpty: 4 };
  let rounds       = 4;

  /* DOM 요소 */
  const controlsEl   = document.getElementById("controls");
  const circle       = document.getElementById("circle");
  const timerSpan    = document.getElementById("timer");
  const phaseSpan    = document.getElementById("phase");
  const remainSpan   = document.getElementById("remaining");
  const startBtn     = document.getElementById("startPause");
  const resetBtn     = document.getElementById("reset");

  /* 상태 변수 */
  let running         = false;
  let currentPhaseIdx = 0;
  let timeLeft        = durations[phaseKeys[0]];
  let remainRounds    = rounds;
  let timerId         = null;

  /* 보조 함수 */
  const clamp = (n) => Math.max(1, n);

  function redraw() {
    timerSpan.textContent  = `${timeLeft}s`;
    phaseSpan.textContent  = phases[currentPhaseIdx];
    remainSpan.textContent = Math.max(remainRounds, 0);

    // 간단한 수축→팽창 효과
    circle.classList.remove("scale-75", "scale-100");
    void circle.offsetWidth;            // 강제 리플로우
    circle.classList.add("scale-75");
    setTimeout(() => circle.classList.replace("scale-75", "scale-100"), 40);
  }

  function tick() {
    timeLeft--;
    if (timeLeft <= 0) {
      currentPhaseIdx = (currentPhaseIdx + 1) % phases.length;
      if (currentPhaseIdx === 0) remainRounds--;

      if (remainRounds <= 0) { handleReset(); return; }
      timeLeft = durations[phaseKeys[currentPhaseIdx]];
    }
    redraw();
  }

  function handleStartPause() {
    running = !running;
    startBtn.textContent = running ? "Pause (S)" : "Start (S)";
    running ? timerId = setInterval(tick, 1000)
            : clearInterval(timerId);
  }

  function handleReset() {
    clearInterval(timerId);
    running         = false;
    startBtn.textContent = "Start (S)";
    currentPhaseIdx = 0;
    timeLeft        = durations.inhale;
    remainRounds    = rounds;
    redraw();
  }

  /* ① 버튼(＋/−)으로 값 변경 */
  controlsEl.addEventListener("click", (e) => {
    if (!e.target.closest("[data-action]")) return;
    const action = e.target.closest("[data-action]").dataset.action;
    const row      = e.target.closest("[data-key]");
    const key      = row.dataset.key;
    const valueEl  = row.querySelector(".value");
    let val        = parseInt(valueEl.textContent, 10);

    val += action === "inc" ? 1 : -1;
    val  = clamp(val);
    valueEl.textContent = val;

    if (key === "rounds") {
      rounds = val;
      if (!running) remainSpan.textContent = val;
    } else {
      durations[key] = val;
      if (!running && phaseKeys[currentPhaseIdx] === key) timeLeft = val;
    }
    if (!running) redraw();
  });

  /* ② 시작/멈춤·리셋 버튼 */
  startBtn.addEventListener("click", handleStartPause);
  resetBtn.addEventListener("click", handleReset);

  /* ③ S / R 단축키 */
  window.addEventListener("keydown", (e) => {
    if (e.key.toLowerCase() === "s") handleStartPause();
    if (e.key.toLowerCase() === "r") handleReset();
  });

  redraw();   // 초기 한 번 그리기
})();
</script>

<?php include "../../../system/includes/footer.php"; ?>