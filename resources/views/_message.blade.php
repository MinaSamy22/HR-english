@if(!empty(session('success')))
<div class="custom-alert success-alert"
     role="alert"
     style="z-index: 9999;">
    <div class="alert-content">
        <div class="alert-icon">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alert-text">
            <div class="alert-title">
                @if(app()->getLocale() == 'ar')
                    نجح!
                @elseif(app()->getLocale() == 'au')
                    کامیاب!
                @else
                    Success!
                @endif
            </div>
            <div class="alert-message">{{ session('success') }}</div>
        </div>
        <button type="button" class="alert-close" onclick="this.closest('.custom-alert').remove()">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="alert-progress">
        <div class="alert-progress-bar success-bar"></div>
    </div>
</div>
@endif

@if(!empty(session('error')))
<div class="custom-alert error-alert"
     role="alert"
     style="z-index: 9999;">
    <div class="alert-content">
        <div class="alert-icon">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
        </div>
        <div class="alert-text">
            <div class="alert-title">Error!</div>
            <div class="alert-message">{{ session('error') }}</div>
        </div>
        <button type="button" class="alert-close" onclick="this.closest('.custom-alert').remove()">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="alert-progress">
        <div class="alert-progress-bar error-bar"></div>
    </div>
</div>
@endif

<style>
.custom-alert {
    position: fixed;
    top: 80px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    min-width: 350px;
    max-width: 500px;
    animation: slideInDown 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

html[dir="ltr"] .custom-alert,
html:not([dir]) .custom-alert {
    right: 20px;
}

html[dir="rtl"] .custom-alert {
    left: 20px;
}

.alert-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
}

html[dir="rtl"] .alert-content {
    direction: rtl;
}

.alert-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.success-alert .alert-icon {
    background: #d1fae5;
    color: #059669;
}

.error-alert .alert-icon {
    background: #fee2e2;
    color: #dc2626;
}

.alert-text {
    flex: 1;
    min-width: 0;
}

.alert-title {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 4px;
}

.success-alert .alert-title {
    color: #059669;
}

.error-alert .alert-title {
    color: #dc2626;
}

.alert-message {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.5;
}

.alert-close {
    flex-shrink: 0;
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #9ca3af;
    transition: all 0.2s;
    border-radius: 6px;
}

.alert-close:hover {
    background: #f3f4f6;
    color: #6b7280;
}

.alert-progress {
    height: 4px;
    background: #f3f4f6;
}

.alert-progress-bar {
    height: 100%;
    width: 100%;
}

html[dir="ltr"] .alert-progress-bar,
html:not([dir]) .alert-progress-bar {
    animation: progressShrinkLTR 5s linear forwards;
}

html[dir="rtl"] .alert-progress-bar {
    animation: progressShrinkRTL 5s linear forwards;
}

.success-bar {
    background: linear-gradient(90deg, #10b981, #059669);
}

.error-bar {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

html[dir="rtl"] .custom-alert {
    animation: slideInDownRTL 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes slideInDownRTL {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOutUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-100%);
    }
}

html[dir="rtl"] @keyframes slideOutUpRTL {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-100%);
    }
}

@keyframes progressShrinkLTR {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

@keyframes progressShrinkRTL {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

@media (max-width: 480px) {
    .custom-alert {
        min-width: 90%;
        max-width: 90%;
        top: 20px;
        left: 5% !important;
        right: auto !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.custom-alert');
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = isRTL ? 'slideOutUpRTL 0.5s ease-out forwards' : 'slideOutUp 0.5s ease-out forwards';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
