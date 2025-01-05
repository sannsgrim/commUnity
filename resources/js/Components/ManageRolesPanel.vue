<script setup>
const props = defineProps({
    adminUsers: Array,
});

function hasPermission(permissions, permissionName) {
    return permissions.some(permission => permission.name === permissionName);
}
</script>

<template>
    <div class="bg-white p-5 rounded-2xl drop-shadow-lg">
        <div class="flex items-center justify-between">
            <h1 class="font-sans text-2xl font-bold">Manage Roles</h1>
        </div>

        <div class="card pt-4 py-4">
            <DataTable :value="adminUsers" tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="admin.username" header="Username" class="text-sm"></Column>
                <Column field="permissions" header="Create Accounts" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox" :checked="hasPermission(slotProps.data.permissions, 'Can Create Account')">
                    </template>
                </Column>
                <Column field="permissions" header="Delete Accounts" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox" :checked="hasPermission(slotProps.data.permissions, 'Can Delete Account')">
                    </template>
                </Column>
                <Column field="permissions" header="Edit Accounts" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox" :checked="hasPermission(slotProps.data.permissions, 'Can Edit Account')">
                    </template>
                </Column>
                <Column field="permissions" header="Create Posts" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox" :checked="hasPermission(slotProps.data.permissions, 'Can Create Post')">
                    </template>
                </Column>
                <Column field="permissions" header="Delete Posts" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox" :checked="hasPermission(slotProps.data.permissions, 'Can Delete Post')">
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>
