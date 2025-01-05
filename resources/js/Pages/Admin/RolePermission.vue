<template>
    <Head title="Manage Roles" />
    <AdminDashboardLayout>
        <ManageRolesPanel :admin-users="adminUsers" />
    </AdminDashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import ManageRolesPanel from "@/Components/ManageRolesPanel.vue";

const props = defineProps({
    adminUsers: Object,
});

</script>
