<div
    wire:ignore
    x-data="{
        value: {{ $rawValue }},
        displayValue: {{ $rawValue }},
        duration: {{ $duration }},
        prefix: '{{ $prefix }}',
        suffix: '{{ $suffix }}',
        format: {{ $format ? 'true' : 'false' }},
        startTime: null,

        formatNumber(num) {
            if (!this.format) return num;
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toLocaleString('en-US');
        },

        animateCounter(timestamp) {
            if (!this.startTime) this.startTime = timestamp;
            const progress = timestamp - this.startTime;
            const percentage = Math.min(progress / this.duration, 1);

            // Use easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - percentage, 4);
            const currentValue = Math.floor(easeOutQuart * this.value);

            this.displayValue = currentValue;

            if (percentage < 1) {
                requestAnimationFrame(this.animateCounter.bind(this));
            }
        },

        init() {
            this.$watch('value', (newValue) => {
                this.startTime = null;
                requestAnimationFrame(this.animateCounter.bind(this));
            });

            // Start initial animation
            setTimeout(() => {
                requestAnimationFrame(this.animateCounter.bind(this));
            }, 100);
        }
    }"
    x-text="prefix + formatNumber(displayValue) + suffix"
    class="inline-block font-bold text-gray-800 dark:text-gray-100"
></div
