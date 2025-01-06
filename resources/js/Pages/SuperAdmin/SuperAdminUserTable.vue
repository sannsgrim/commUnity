<template>
    <Head title="Account Manager"/>
    <SuperAdminDashboardLayout >
        <AccountsLayout :admin-users="adminUsers"/>
    </SuperAdminDashboardLayout>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import SuperAdminDashboardLayout from "@/Layouts/SuperAdminDashboardLayout.vue";
import AccountsLayout from "@/Components/AccountsPanel.vue";

const props = defineProps({
    adminUsers: Object,
});
</script>
