<div x-data="{
    notifications: [],
    add(message, type = 'success') {
        if (!message) return;
        const id = Date.now();
        this.notifications.push({ id, message, type });
        setTimeout(() => this.remove(id), 4000);
    },
    remove(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
    },
    checkSession() {
        @if(session('success')) this.add(@js(session('success')), 'success'); @endif
        @if(session('error')) this.add(@js(session('error')), 'error'); @endif
        @if(session('message')) this.add(@js(session('message')), 'info'); @endif
        @if(session('warning')) this.add(@js(session('warning')), 'warning'); @endif
    }
}" 
@notify.window="add($event.detail.message, $event.detail.type)" 
@toast.window="add($event.detail.message, $event.detail.type)" 
x-init="
    checkSession();
    document.addEventListener('livewire:navigated', () => {
        // We can't easily check PHP session here, but Livewire usually handles 
        // the first render. This is a fallback for complex flows.
    });
"
    class="fixed bottom-5 right-5 z-50 space-y-3 pointer-events-none">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0 scale-90"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-y-0 opacity-100 scale-100"
            x-transition:leave-end="translate-y-2 opacity-0 scale-90"
            class="pointer-events-auto flex items-center p-4 rounded-xl shadow-2xl border w-80 backdrop-blur-md"
            :class="{
                'bg-bg-surface/80 border-success/20 text-success': notification.type === 'success',
                'bg-bg-surface/80 border-danger/20 text-danger': notification.type === 'error',
                'bg-bg-surface/80 border-primary/20 text-primary': notification.type === 'info',
                'bg-bg-surface/80 border-accent/20 text-accent': notification.type === 'warning'
            }">
            <div class="shrink-0">
                <i class="text-xl ti"
                    :class="{
                        'ti-circle-check-filled text-success': notification.type === 'success',
                        'ti-alert-circle-filled text-danger': notification.type === 'error',
                        'ti-info-circle-filled text-primary': notification.type === 'info',
                        'ti-alert-triangle-filled text-accent': notification.type === 'warning'
                    }"></i>
            </div>
            <div class="ml-3 font-medium text-sm" x-text="notification.message"></div>
            <button @click="remove(notification.id)" class="ml-auto text-text-muted hover:text-text-main">
                <i class="ti ti-x"></i>
            </button>
        </div>
    </template>
</div>
