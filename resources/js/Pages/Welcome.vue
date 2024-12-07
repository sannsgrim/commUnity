<script setup>
import { Head } from '@inertiajs/vue3';
import UserGuestLayout from "@/Layouts/UserGuestLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>

<template>
    <Head title="Welcome" />
    <UserGuestLayout :canLogin="canLogin" :canRegister="canRegister">
        <aside class="sticky top-8 hidden w-1/4 shrink-0 lg:block bg-[#F4F4F4] rounded-xl">
            <!-- Left column area -->
            <div class="pb-12">

            </div>
        </aside>

        <main class="flex-1 bg-[#F4F4F4] rounded-xl ">
            <!-- Main area -->
            <div class="pb-12">

            </div>
        </main>
    </UserGuestLayout>
</template>
