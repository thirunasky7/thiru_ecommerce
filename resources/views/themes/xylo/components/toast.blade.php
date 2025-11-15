<div 
    x-data="{ show: false, message: '' }"
    x-on:toast.window="
        message = $event.detail.message;
        show = true; 
        setTimeout(() => show = false, 3000);
    "
    class="fixed bottom-24 left-1/2 transform -translate-x-1/2 z-50"
>
    <div 
        x-show="show"
        x-transition
        class="bg-black text-white px-4 py-2 rounded-lg shadow-lg text-sm"
    >
        <span x-text="message"></span>
    </div>
</div>
