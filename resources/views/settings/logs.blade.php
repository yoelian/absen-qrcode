@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="text-dark fw-bold"><i class="bi bi-terminal-fill me-2 text-primary"></i>Log System & Debugging</h3>
        <p class="text-muted m-0">Pantau seluruh catatan sistem, error, dan aktivitas debug secara langsung.</p>
    </div>
    
    @if($currentFile)
    <div class="d-flex gap-2">
        <form action="{{ route('settings.clearLogs') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan file log ini?')">
            @csrf
            <input type="hidden" name="file" value="{{ $currentFile }}">
            <button type="submit" class="btn btn-outline-danger px-3 py-2 rounded-3 fw-semibold">
                <i class="bi bi-trash-fill me-1"></i> Bersihkan Log
            </button>
        </form>
        <a href="{{ route('settings.logs', ['file' => $currentFile]) }}" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Log
        </a>
    </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#065f46;box-shadow:0 4px 15px rgba(16,185,129,.15);">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background:linear-gradient(135deg,#fee2e2,#fecaca);color:#991b1b;box-shadow:0 4px 15px rgba(239,68,68,.15);">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Sisi Kiri: Daftar File Log -->
    <div class="col-lg-3">
        <div class="custom-card mb-4">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2"><i class="bi bi-files me-2 text-secondary"></i>File Log</h5>
            @if(count($logFiles) > 0)
                <div class="list-group list-group-flush" style="max-height: 450px; overflow-y: auto;">
                    @foreach($logFiles as $log)
                        @php
                            $isActive = $currentFile === $log['name'];
                        @endphp
                        <a href="{{ route('settings.logs', ['file' => $log['name']]) }}" 
                           class="list-group-item list-group-item-action d-flex flex-column align-items-start py-3 px-2 border-0 rounded-3 mb-2 {{ $isActive ? 'active bg-primary text-white' : '' }}" 
                           style="transition: all 0.2s;">
                            <div class="d-flex w-100 justify-content-between">
                                <span class="fw-bold text-truncate" style="font-size: 0.9rem; max-width: 170px;">{{ $log['name'] }}</span>
                            </div>
                            <small class="{{ $isActive ? 'text-white text-opacity-75' : 'text-muted' }} mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-hdd-fill me-1"></i> Ukuran: {{ $log['size'] }}
                            </small>
                            <small class="{{ $isActive ? 'text-white text-opacity-75' : 'text-muted' }}" style="font-size: 0.75rem;">
                                <i class="bi bi-clock-fill me-1"></i> {{ $log['date'] }}
                            </small>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-4 mb-0">Belum ada file log tercatat.</p>
            @endif
        </div>
    </div>

    <!-- Sisi Kanan: Konten Log -->
    <div class="col-lg-9">
        <div class="custom-card p-0 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.1);">
            <div class="bg-light d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                <span class="text-dark fw-bold" style="font-size: 0.9rem;">
                    <i class="bi bi-file-earmark-code text-primary me-2"></i>{{ $currentFile ?: 'Tidak ada file terpilih' }}
                </span>
                <span class="badge bg-secondary px-2 py-1" style="font-size: 0.7rem;">Last 500 lines</span>
            </div>
            
            <div class="position-relative">
                @if($logContent)
                    <pre class="m-0 text-white" id="terminalLog" 
                         style="background: #0f172a; font-family: 'Courier New', Courier, monospace; font-size: 0.82rem; line-height: 1.5; padding: 20px; max-height: 550px; overflow-y: auto; white-space: pre-wrap; word-break: break-all;"></pre>
                @else
                    <div class="text-center py-5 text-muted" style="background: #0f172a; min-height: 250px;">
                        <i class="bi bi-terminal-x d-block mb-3" style="font-size: 3rem; color: #334155;"></i>
                        File log ini kosong atau tidak mengandung data error.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($logContent)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawLog = {!! json_encode($logContent) !!};
        const term = document.getElementById("terminalLog");
        
        if (term && rawLog) {
            // Highlighting log levels and key terms
            let highlighted = rawLog
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/("userId":\d+)/g, '<span style="color:#fbbf24;">$1</span>')
                .replace(/(\.ERROR:)/g, '<span style="color:#ef4444; font-weight: bold;">$1</span>')
                .replace(/(\.CRITICAL:)/g, '<span style="color:#f43f5e; font-weight: bold;">$1</span>')
                .replace(/(\.WARNING:)/g, '<span style="color:#f59e0b; font-weight: bold;">$1</span>')
                .replace(/(\.INFO:)/g, '<span style="color:#10b981;">$1</span>')
                .replace(/(#\d+\s+[^:\n]+:\d+)/g, '<span style="color:#94a3b8;">$1</span>')
                .replace(/(rename\(.*?\): Access is denied)/g, '<span style="color:#f43f5e; font-weight: bold;">$1</span>')
                .replace(/(CSRF token mismatch\.)/g, '<span style="color:#fbbf24; font-weight: bold;">$1</span>')
                .replace(/(TokenMismatchException)/g, '<span style="color:#fbbf24;">$1</span>')
                .replace(/(HttpException)/g, '<span style="color:#f43f5e;">$1</span>');
                
            term.innerHTML = highlighted;
            
            // Scroll to bottom of terminal automatically
            term.scrollTop = term.scrollHeight;
        }
    });
</script>
@endif
@endsection
