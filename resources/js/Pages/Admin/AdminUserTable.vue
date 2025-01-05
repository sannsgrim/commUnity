<template>
    <Head title="Account Manager"/>
    <AdminDashboardLayout>
        <AccountsLayout :admin-users="adminUsers"/>
    </AdminDashboardLayout>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import AccountsLayout from "@/Components/AccountsPanel.vue";

const props = defineProps({
    adminUsers: Object,
});
</script>
