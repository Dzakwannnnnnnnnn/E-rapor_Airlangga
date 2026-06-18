{{--
    Custom Confirm Modal Component
    Usage: window.showConfirm({ title, message, confirmText, formId })
    The modal intercepts form submission and replaces native browser confirm().
--}}
@once
<div id="confirm-modal" class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center p-4 pointer-events-none opacity-0 transition-all duration-300" aria-hidden="true">

    {{-- Backdrop --}}
    <div id="confirm-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-none"></div>

    {{-- Dialog --}}
    {{-- Dialog --}}
    <div id="confirm-dialog" class="relative w-full max-w-sm bg-white rounded-3xl shadow-[0_32px_80px_-12px_rgba(0,0,0,0.25)] border border-slate-100 overflow-hidden translate-y-8 sm:translate-y-0 sm:scale-95 transition-all duration-300 pointer-events-none">
 
        {{-- Top danger stripe --}}
        <div id="confirm-stripe" class="h-1 w-full bg-gradient-to-r from-rose-400 via-red-500 to-rose-600"></div>
 
        <div class="p-6">
            {{-- Icon --}}
            <div class="flex justify-center mb-5">
                <div id="confirm-icon-container" class="w-16 h-16 rounded-2xl bg-rose-50 border-2 border-rose-100 flex items-center justify-center shadow-inner">
                    <i id="confirm-icon" class="fa-solid fa-trash-can text-2xl text-rose-500"></i>
                </div>
            </div>
 
            {{-- Text --}}
            <div class="text-center mb-6">
                <h3 id="confirm-title" class="text-lg font-black text-slate-900 tracking-tight mb-1.5">Konfirmasi Hapus</h3>
                <p id="confirm-message" class="text-sm text-slate-500 font-medium leading-relaxed">Apakah kamu yakin ingin menghapus item ini secara permanen?</p>
            </div>
 
            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <button
                    type="button"
                    id="confirm-cancel-btn"
                    class="w-full order-2 sm:order-1 py-3 px-5 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <i class="fa-solid fa-xmark mr-2 text-slate-400"></i>
                    Batal
                </button>
                <button
                    type="button"
                    id="confirm-ok-btn"
                    class="w-full order-1 sm:order-2 py-3 px-5 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-bold text-sm shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-400 border-b-2 border-red-700">
                    <i id="confirm-ok-icon" class="fa-solid fa-trash-can mr-2"></i>
                    <span id="confirm-btn-text">Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>
 
<style>
    #confirm-modal.is-open {
        pointer-events: auto;
        opacity: 1;
    }
    #confirm-modal.is-open #confirm-backdrop {
        opacity: 1;
        pointer-events: auto;
    }
    #confirm-modal.is-open #confirm-dialog {
        pointer-events: auto;
        translate: none;
        transform: translateY(0) scale(1);
    }
    @media (min-width: 640px) {
        #confirm-modal.is-open #confirm-dialog {
            transform: scale(1);
        }
    }
</style>
 
<script>
(function() {
    let _pendingFormId = null;
 
    const modal           = document.getElementById('confirm-modal');
    const backdrop        = document.getElementById('confirm-backdrop');
    const titleEl         = document.getElementById('confirm-title');
    const messageEl       = document.getElementById('confirm-message');
    const btnText         = document.getElementById('confirm-btn-text');
    const okBtn           = document.getElementById('confirm-ok-btn');
    const cancelBtn       = document.getElementById('confirm-cancel-btn');
    const stripeEl        = document.getElementById('confirm-stripe');
    const iconContainerEl = document.getElementById('confirm-icon-container');
    const iconEl          = document.getElementById('confirm-icon');
    const okIconEl        = document.getElementById('confirm-ok-icon');

    const themes = {
        danger: {
            stripe: 'bg-gradient-to-r from-rose-400 via-red-500 to-rose-600',
            iconContainer: 'bg-rose-50 border-rose-100',
            icon: 'fa-trash-can text-rose-500',
            okBtn: 'bg-gradient-to-br from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 shadow-rose-500/30 hover:shadow-rose-500/50 focus:ring-rose-400 border-red-700',
            okIcon: 'fa-trash-can'
        },
        warning: {
            stripe: 'bg-gradient-to-r from-amber-400 via-orange-500 to-amber-600',
            iconContainer: 'bg-amber-50 border-amber-100',
            icon: 'fa-triangle-exclamation text-amber-500',
            okBtn: 'bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 shadow-amber-500/30 hover:shadow-amber-500/50 focus:ring-amber-400 border-orange-700',
            okIcon: 'fa-check'
        }
    };

    function applyTheme(themeName) {
        const theme = themes[themeName] || themes.danger;

        stripeEl.className = 'h-1 w-full ' + theme.stripe;
        iconContainerEl.className = 'w-16 h-16 rounded-2xl flex items-center justify-center shadow-inner border-2 ' + theme.iconContainer;
        iconEl.className = 'fa-solid text-2xl ' + theme.icon;
        okBtn.className = 'w-full order-1 sm:order-2 py-3 px-5 rounded-2xl text-white font-bold text-sm transition-all duration-150 focus:outline-none focus:ring-2 border-b-2 ' + theme.okBtn;
        okIconEl.className = 'fa-solid mr-2 ' + theme.okIcon;
    }
 
    function openModal() {
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
 
    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        _pendingFormId = null;
    }
 
    // Close on backdrop click
    backdrop.addEventListener('click', closeModal);
 
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });
 
    // Cancel button
    cancelBtn.addEventListener('click', closeModal);
 
    // Confirm button — submit the stored form
    okBtn.addEventListener('click', function() {
        if (_pendingFormId) {
            const form = document.getElementById(_pendingFormId);
            if (form) {
                // Temporarily remove the interceptor so it submits cleanly
                form.removeAttribute('data-confirm-intercepted');
                form.submit();
            }
        }
        closeModal();
    });
 
    /**
     * window.showConfirm(options)
     *
     * @param {object} options
     * @param {string} options.title        - Modal title (default: 'Konfirmasi Hapus')
     * @param {string} options.message      - Modal body message
     * @param {string} options.confirmText  - OK button text (default: 'Ya, Hapus')
     * @param {string} options.formId       - ID of the form to submit on confirm
     * @param {string} options.type         - Theme type ('danger' | 'warning')
     */
    window.showConfirm = function(options) {
        const opts = options || {};
        titleEl.textContent   = opts.title       || 'Konfirmasi Hapus';
        messageEl.textContent = opts.message     || 'Apakah kamu yakin ingin menghapus item ini secara permanen?';
        btnText.textContent   = opts.confirmText || 'Ya, Hapus';
        _pendingFormId        = opts.formId      || null;
        applyTheme(opts.type || 'danger');
        openModal();
    };
 
    // Auto-intercept any form with data-confirm attribute
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form[data-confirm-title], form[data-confirm]').forEach(function(form) {
            if (form.getAttribute('data-confirm-intercepted')) return;
            form.setAttribute('data-confirm-intercepted', '1');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                window.showConfirm({
                    title:       form.getAttribute('data-confirm-title') || 'Konfirmasi',
                    message:     form.getAttribute('data-confirm')       || 'Apakah kamu yakin?',
                    confirmText: form.getAttribute('data-confirm-btn')   || 'Ya, Lanjutkan',
                    formId:      form.id,
                    type:        form.getAttribute('data-confirm-type')  || 'danger',
                });
            });
        });
    });
})();
</script>
@endonce
