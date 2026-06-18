@php
    $toasts = [];
    if(session('success')) {
        $toasts[] = [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => session('success'),
            'theme' => [
                'border' => 'border-emerald-100',
                'shadow' => 'shadow-[0_10px_30px_rgba(16,185,129,0.08)]',
                'iconBg' => 'bg-emerald-50',
                'iconBorder' => 'border-emerald-100',
                'iconColor' => 'text-emerald-500',
                'iconClass' => 'fa-circle-check',
                'titleColor' => 'text-emerald-600'
            ]
        ];
    }
    if(session('error')) {
        $toasts[] = [
            'type' => 'error',
            'title' => 'Gagal Diproses',
            'message' => session('error'),
            'theme' => [
                'border' => 'border-rose-100',
                'shadow' => 'shadow-[0_10px_30px_rgba(244,63,94,0.08)]',
                'iconBg' => 'bg-rose-50',
                'iconBorder' => 'border-rose-100',
                'iconColor' => 'text-rose-500',
                'iconClass' => 'fa-circle-exclamation',
                'titleColor' => 'text-rose-600'
            ]
        ];
    }
    if(session('warning')) {
        $toasts[] = [
            'type' => 'warning',
            'title' => 'Peringatan',
            'message' => session('warning'),
            'theme' => [
                'border' => 'border-amber-100',
                'shadow' => 'shadow-[0_10px_30px_rgba(245,158,11,0.08)]',
                'iconBg' => 'bg-amber-50',
                'iconBorder' => 'border-amber-100',
                'iconColor' => 'text-amber-500',
                'iconClass' => 'fa-triangle-exclamation',
                'titleColor' => 'text-amber-600'
            ]
        ];
    }
    if(session('status') || session('info')) {
        // Skip status if it is simple password-updated or profile-updated to avoid double alert on views that have inline ones
        $statusMsg = session('status') ?? session('info');
        if (!in_array($statusMsg, ['password-updated', 'profile-updated', 'verification-link-sent'])) {
            $toasts[] = [
                'type' => 'info',
                'title' => 'Informasi',
                'message' => $statusMsg,
                'theme' => [
                    'border' => 'border-blue-100',
                    'shadow' => 'shadow-[0_10px_30px_rgba(59,130,246,0.08)]',
                    'iconBg' => 'bg-blue-50',
                    'iconBorder' => 'border-blue-100',
                    'iconColor' => 'text-blue-500',
                    'iconClass' => 'fa-circle-info',
                    'titleColor' => 'text-blue-600'
                ]
            ];
        }
    }
@endphp

<div id="toast-container" class="fixed top-4 inset-x-4 z-50 flex flex-col gap-3 pointer-events-none sm:top-6 md:top-6 md:right-6 md:left-auto md:inset-x-auto w-auto md:w-full md:max-w-sm">
    @foreach($toasts as $t)
        <div class="alert-toast bg-white/95 backdrop-blur-md border {{ $t['theme']['border'] }} {{ $t['theme']['shadow'] }} rounded-2xl p-4 flex items-start gap-3.5 pointer-events-auto" style="animation: toastIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
            <div class="w-9 h-9 rounded-xl {{ $t['theme']['iconBg'] }} border {{ $t['theme']['iconBorder'] }} flex items-center justify-center shrink-0 {{ $t['theme']['iconColor'] }} shadow-sm">
                <i class="fa-solid {{ $t['theme']['iconClass'] }} text-base"></i>
            </div>
            <div class="flex-1 pt-0.5 min-w-0">
                <h4 class="{{ $t['theme']['titleColor'] }} font-bold text-xs uppercase tracking-wider mb-0.5">{{ $t['title'] }}</h4>
                <p class="text-slate-650 text-xs font-semibold leading-relaxed break-words">{{ $t['message'] }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.parentElement)" class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-50 shrink-0">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endforeach
</div>

@once
<style>
    @keyframes toastIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes toastOut {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to { opacity: 0; transform: translateY(-16px) scale(0.95); }
    }
    .toast-fade-out {
        animation: toastOut 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
    }
</style>
<script>
    function dismissToast(toastElement) {
        if (!toastElement) return;
        toastElement.classList.add('toast-fade-out');
        setTimeout(() => {
            toastElement.remove();
        }, 300);
    }

    window.showToast = function(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const config = {
            success: {
                bg: 'bg-white/95',
                border: 'border-emerald-100',
                shadow: 'shadow-[0_10px_30px_rgba(16,185,129,0.08)]',
                iconBg: 'bg-emerald-50',
                iconBorder: 'border-emerald-100',
                iconColor: 'text-emerald-500',
                iconClass: 'fa-circle-check',
                titleColor: 'text-emerald-600',
                title: 'Berhasil'
            },
            error: {
                bg: 'bg-white/95',
                border: 'border-rose-100',
                shadow: 'shadow-[0_10px_30px_rgba(244,63,94,0.08)]',
                iconBg: 'bg-rose-50',
                iconBorder: 'border-rose-100',
                iconColor: 'text-rose-500',
                iconClass: 'fa-circle-exclamation',
                titleColor: 'text-rose-600',
                title: 'Gagal Diproses'
            },
            warning: {
                bg: 'bg-white/95',
                border: 'border-amber-100',
                shadow: 'shadow-[0_10px_30px_rgba(245,158,11,0.08)]',
                iconBg: 'bg-amber-50',
                iconBorder: 'border-amber-100',
                iconColor: 'text-amber-500',
                iconClass: 'fa-triangle-exclamation',
                titleColor: 'text-amber-600',
                title: 'Peringatan'
            },
            info: {
                bg: 'bg-white/95',
                border: 'border-blue-100',
                shadow: 'shadow-[0_10px_30px_rgba(59,130,246,0.08)]',
                iconBg: 'bg-blue-50',
                iconBorder: 'border-blue-100',
                iconColor: 'text-blue-500',
                iconClass: 'fa-circle-info',
                titleColor: 'text-blue-600',
                title: 'Informasi'
            }
        };

        const theme = config[type] || config.success;

        const toast = document.createElement('div');
        toast.className = `alert-toast ${theme.bg} backdrop-blur-md border ${theme.border} ${theme.shadow} rounded-2xl p-4 flex items-start gap-3.5 pointer-events-auto`;
        toast.style.animation = 'toastIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';
        toast.innerHTML = `
            <div class="w-9 h-9 rounded-xl ${theme.iconBg} border ${theme.iconBorder} flex items-center justify-center shrink-0 ${theme.iconColor} shadow-sm">
                <i class="fa-solid ${theme.iconClass} text-base"></i>
            </div>
            <div class="flex-1 pt-0.5 min-w-0">
                <h4 class="${theme.titleColor} font-bold text-xs uppercase tracking-wider mb-0.5">${theme.title}</h4>
                <p class="text-slate-650 text-xs font-semibold leading-relaxed break-words">${message}</p>
            </div>
            <button type="button" onclick="dismissToast(this.parentElement)" class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-50 shrink-0">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            dismissToast(toast);
        }, 5000);
    };

    document.addEventListener('DOMContentLoaded', () => {
        const activeToasts = document.querySelectorAll('.alert-toast');
        activeToasts.forEach((toast) => {
            setTimeout(() => {
                dismissToast(toast);
            }, 5000);
        });
    });
</script>
@endonce
