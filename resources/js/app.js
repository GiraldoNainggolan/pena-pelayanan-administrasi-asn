document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast]').forEach((toast, index) => {
        const timeout = Number(toast.dataset.timeout || 6500);
        const progress = toast.querySelector('[data-toast-progress]');
        let timer;
        let startedAt;
        let remaining = timeout;

        const dismiss = () => {
            window.clearTimeout(timer);
            toast.classList.remove('toast-visible');
            toast.classList.add('toast-leaving');
            window.setTimeout(() => toast.remove(), 260);
        };

        const startTimer = () => {
            startedAt = Date.now();
            timer = window.setTimeout(dismiss, remaining);
            if (progress) {
                progress.style.animationDuration = `${remaining}ms`;
                progress.classList.add('toast-progress-running');
            }
        };

        const pauseTimer = () => {
            window.clearTimeout(timer);
            remaining = Math.max(500, remaining - (Date.now() - startedAt));
            if (progress) {
                const currentScale = getComputedStyle(progress).transform;
                progress.style.transform = currentScale === 'none' ? 'scaleX(1)' : currentScale;
                progress.classList.remove('toast-progress-running');
            }
        };

        window.setTimeout(() => {
            toast.classList.add('toast-visible');
            startTimer();
        }, 80 + index * 120);

        toast.querySelector('[data-toast-close]')?.addEventListener('click', dismiss);
        toast.addEventListener('mouseenter', pauseTimer);
        toast.addEventListener('mouseleave', () => {
            if (progress) {
                progress.style.transform = '';
                progress.style.animationDuration = `${remaining}ms`;
            }
            startTimer();
        });
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (event.defaultPrevented || !form.checkValidity()) return;

            const button = form.querySelector('button[type="submit"], button:not([type])');
            if (!button || button.dataset.loading === 'true') return;

            button.dataset.loading = 'true';
            button.disabled = true;
            button.classList.add('cursor-wait', 'opacity-75');

            const label = button.textContent.trim();
            button.dataset.originalLabel = label;
            button.innerHTML = '<span class="inline-flex items-center justify-center gap-2"><span class="submit-spinner" aria-hidden="true"></span><span>Memproses...</span></span>';
        });
    });
});
