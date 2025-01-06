<template>
    <Head title="Manage Roles" />
    <SuperAdminDashboardLayout>
            <ManageRolesPanel :admin-users="adminUsers" />
    </SuperAdminDashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import SuperAdminDashboardLayout from "@/Layouts/SuperAdminDashboardLayout.vue";
import ManageRolesPanel from "@/Components/ManageRolesPanel.vue";

const props = defineProps({
    adminUsers: Object,
});
</script>
