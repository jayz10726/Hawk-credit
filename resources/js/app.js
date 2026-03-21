import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import Chart from 'chart.js/auto';

Alpine.plugin(mask);
window.Chart = Chart;
Alpine.start();

// ── Notification Bell ─────────────────────────────────────
Alpine.data('notificationBell', () => ({
    open: false,
    unread: 0,
    notifications: [],
    async init() {
        const res = await fetch('/api/v1/notifications', {
            headers: { 'X-Requested-With': 'XMLHttpRequest',
                       'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]')?.content }
        });
        if (res.ok) {
            const data = await res.json();
            this.notifications = data.data;
            this.unread = data.data.filter(n => !n.read_at).length;
        }
    },
    toggle() { this.open = !this.open; },
    async markRead(id) {
        await fetch(`/api/v1/notifications/${id}/read`, { method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const n = this.notifications.find(n => n.id === id);
        if (n) { n.read_at = new Date().toISOString(); this.unread = Math.max(0, this.unread - 1); }
    },
    async markAllRead() {
        for (const n of this.notifications.filter(n => !n.read_at)) await this.markRead(n.id);
    }
}));

// ── Credit Application Form ───────────────────────────────
Alpine.data('creditForm', () => ({
    currentStep: 0,
    steps: ['Loan Details', 'Review', 'Documents'],
    submitting: false,
    form: { amount: '', tenure: '', purpose: '' },
    get monthlyPayment() {
        if (!this.form.amount || !this.form.tenure) return 0;
        const r = 0.15 / 12; // estimated 15% p.a.
        const n = parseInt(this.form.tenure);
        const p = parseFloat(this.form.amount);
        return Math.round(p * (r * Math.pow(1+r,n)) / (Math.pow(1+r,n)-1));
    },
    formatCurrency(v) { return Number(v).toLocaleString(); },
    nextStep() {
       if (this.currentStep === 0 && (!this.form.amount || !this.form.tenure || !this.form.purpose)) {
            alert('Please fill in all required fields.'); return;
        }
        this.currentStep++;
    },
    prevStep() { if (this.currentStep > 0) this.currentStep--; },
    async submitForm(form) {
        this.submitting = true;
        form.submit();
    }
}));

// ── File Upload ───────────────────────────────────────────
Alpine.data('fileUpload', () => ({
    files: [],
    dragging: false,
    handleFiles(e) { this.files = [...this.files, ...Array.from(e.target.files)]; },
    handleDrop(e) {
        this.dragging = false;
        this.files = [...this.files, ...Array.from(e.dataTransfer.files)];
    },
    removeFile(i) { this.files.splice(i, 1); }
}));

window.Alpine = Alpine;
Alpine.start();
