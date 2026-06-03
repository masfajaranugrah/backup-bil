@extends('layouts/layoutMaster')

@section('title', 'Console Pushjob')

@section('vendor-style')
<style>
.log-console-shell {
  background: #09090b;
  border: 1px solid #27272a;
  border-radius: 8px;
  box-shadow: 0 12px 30px rgba(9, 9, 11, 0.18);
  overflow: hidden;
}

.log-console-topbar {
  align-items: center;
  background: #18181b;
  border-bottom: 1px solid #27272a;
  color: #e4e4e7;
  display: flex;
  gap: 12px;
  justify-content: space-between;
  padding: 12px 16px;
}

.log-console-command {
  color: #a1a1aa;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
  font-size: 0.82rem;
  overflow-wrap: anywhere;
}

.log-console-status {
  align-items: center;
  color: #a1a1aa;
  display: inline-flex;
  font-size: 0.78rem;
  gap: 8px;
  white-space: nowrap;
}

.log-console-status-dot {
  background: #22c55e;
  border-radius: 999px;
  box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.16);
  height: 8px;
  width: 8px;
}

.log-console-toolbar {
  align-items: center;
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.log-console-btn {
  align-items: center;
  background: #ffffff;
  border: 1px solid #e4e4e7;
  border-radius: 8px;
  color: #18181b;
  display: inline-flex;
  font-weight: 600;
  gap: 7px;
  line-height: 1;
  padding: 9px 12px;
}

.log-console-btn:hover {
  background: #f4f4f5;
  color: #18181b;
}

.log-console-output {
  color: #d4d4d8;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
  font-size: 0.82rem;
  line-height: 1.55;
  margin: 0;
  min-height: 560px;
  overflow: auto;
  padding: 16px;
  white-space: pre-wrap;
  word-break: break-word;
}

.log-console-empty {
  color: #71717a;
}

@media (max-width: 767.98px) {
  .log-console-topbar {
    align-items: flex-start;
    flex-direction: column;
  }

  .log-console-output {
    min-height: 460px;
  }
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1 fw-bold">
        <i class="ri-terminal-box-line me-2"></i>Console Pushjob
      </h4>
      <div class="text-muted small">{{ $logPath }}</div>
    </div>

    <div class="log-console-toolbar">
      <button type="button" class="log-console-btn" id="logPauseBtn">
        <i class="ri-pause-line"></i>
        Pause
      </button>
      <button type="button" class="log-console-btn" id="logClearBtn">
        <i class="ri-delete-bin-6-line"></i>
        Clear
      </button>
      <button type="button" class="log-console-btn" id="logBottomBtn">
        <i class="ri-arrow-down-line"></i>
        Bottom
      </button>
    </div>
  </div>

  <div class="log-console-shell">
    <div class="log-console-topbar">
      <div class="log-console-command">{{ $command }}</div>
      <div class="log-console-status">
        <span class="log-console-status-dot" id="logStatusDot"></span>
        <span id="logStatusText">Connecting...</span>
      </div>
    </div>
    <pre class="log-console-output" id="logOutput"><span class="log-console-empty">Menunggu log...</span></pre>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const output = document.getElementById('logOutput');
  const pauseBtn = document.getElementById('logPauseBtn');
  const clearBtn = document.getElementById('logClearBtn');
  const bottomBtn = document.getElementById('logBottomBtn');
  const statusText = document.getElementById('logStatusText');
  const statusDot = document.getElementById('logStatusDot');
  const tailUrl = @json(route('system-log.tail'));

  let cursor = 0;
  let paused = false;
  let hasContent = false;
  let pollTimer = null;

  function isNearBottom() {
    return output.scrollHeight - output.scrollTop - output.clientHeight < 80;
  }

  function scrollBottom() {
    output.scrollTop = output.scrollHeight;
  }

  function setStatus(text, ok) {
    statusText.textContent = text;
    statusDot.style.background = ok ? '#22c55e' : '#ef4444';
    statusDot.style.boxShadow = ok ? '0 0 0 4px rgba(34, 197, 94, 0.16)' : '0 0 0 4px rgba(239, 68, 68, 0.16)';
  }

  function appendLog(text) {
    if (!text) return;

    const shouldScroll = isNearBottom();
    if (!hasContent) {
      output.textContent = '';
      hasContent = true;
    }

    output.textContent += text;

    if (output.textContent.length > 600000) {
      output.textContent = output.textContent.slice(-450000);
    }

    if (shouldScroll) {
      scrollBottom();
    }
  }

  async function pollLog() {
    if (paused) return;

    try {
      const response = await fetch(`${tailUrl}?cursor=${cursor}`, {
        headers: { 'Accept': 'application/json' },
        cache: 'no-store'
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const data = await response.json();
      cursor = Number(data.cursor || 0);

      if (!data.exists) {
        setStatus(data.message || 'Log tidak ditemukan', false);
        return;
      }

      appendLog(data.content || '');
      setStatus(`Live - ${data.updated_at || 'ready'}`, true);
    } catch (error) {
      setStatus(`Disconnected - ${error.message}`, false);
    }
  }

  pauseBtn.addEventListener('click', function () {
    paused = !paused;
    pauseBtn.innerHTML = paused
      ? '<i class="ri-play-line"></i> Resume'
      : '<i class="ri-pause-line"></i> Pause';
    setStatus(paused ? 'Paused' : 'Live', !paused);

    if (!paused) {
      pollLog();
    }
  });

  clearBtn.addEventListener('click', function () {
    output.textContent = '';
    hasContent = false;
  });

  bottomBtn.addEventListener('click', scrollBottom);

  pollLog();
  pollTimer = window.setInterval(pollLog, 2000);

  window.addEventListener('beforeunload', function () {
    if (pollTimer) {
      window.clearInterval(pollTimer);
    }
  });
});
</script>
@endsection
