<script setup>
import {onMounted, onUnmounted} from "vue";

onMounted(() => {
    document.documentElement.classList.add('h-full', 'bg-white');
    document.body.classList.add('h-full');
});

// Remove Tailwind CSS class from html and body when component is unmounted
onUnmounted(() => {
    document.documentElement.classList.remove('h-full', 'bg-white');
    document.body.classList.remove('h-full');
});
</script>

<template>
    <div class="flex min-h-screen flex-1">
        <div class="flex flex-1 flex-col justify-start px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <slot />
        </div>
        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover"
                 src="https://images.unsplash.com/photo-1665470909901-162912ec16f7?q=80&w=2012&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                 alt=""
            />
        </div>
    </div>
</template>
