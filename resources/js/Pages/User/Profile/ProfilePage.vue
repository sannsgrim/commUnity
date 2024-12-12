<script setup>
import { Head } from '@inertiajs/vue3';
import UserAuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";

defineProps({
    user: {
      type: Object,
    },
})
</script>

<template>
    <Head title="Profile Page" />
    <UserAuthenticatedLayout>

    </UserAuthenticatedLayout>
</template>
