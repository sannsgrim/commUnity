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
        <aside class="sticky top-8 hidden w-2/4 shrink-0 lg:block  rounded-xl">
            <!-- Left column area -->
            <div class="px-12 py-40 text-3xl">
                <b>Welcome to Grinn</b> <br>
                Where smiles spark connections.
                <br><br>

                <div class="pb-10 text-base">Grinn is the social platform designed to make sharing moments fun, lighthearted, and meaningful. Whether you're catching up with friends, sharing your best moments, or discovering new communities, Grinn is your happy place online.</div>

            </div>
        </aside>

        <main class="flex-1 bg-[#a385ff]  rounded-t-[50%] relative">
            <!-- Main area -->
            <div class="">
                <img src="storage/welcome-photo/person.svg" alt="Your Company" style="width: 85%; margin: auto; display: block;"/>
            </div>
        </main>
    </UserGuestLayout>
</template>
