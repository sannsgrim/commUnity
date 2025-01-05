<script setup>
const props = defineProps({
    adminUsers: Object,
});
</script>

<template>
    <div class="bg-white p-5 rounded-2xl drop-shadow-lg">
        <div class="flex items-center justify-between">
            <h1 class="font-sans text-2xl font-bold">Manage Roles</h1>
        </div>

        <div class="card pt-4">
            <DataTable :value="adminUsers" tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="admin.username" header="Username" class="text-sm"></Column>
                <Column field="permissions" header="Create Accounts" class="text-sm" :body="(data) => renderCheckbox(data.permissions, 'create accounts')"></Column>
                <Column field="permissions" header="Delete Accounts" class="text-sm" :body="(data) => renderCheckbox(data.permissions, 'delete accounts')"></Column>
                <Column field="permissions" header="Edit Accounts" class="text-sm" :body="(data) => renderCheckbox(data.permissions, 'edit accounts')"></Column>
                <Column field="permissions" header="Create Posts" class="text-sm" :body="(data) => renderCheckbox(data.permissions, 'create posts')"></Column>
                <Column field="permissions" header="Delete Posts" class="text-sm" :body="(data) => renderCheckbox(data.permissions, 'delete posts')"></Column>
            </DataTable>
        </div>
    </div>
</template>

<script>
function renderCheckbox(permissions, permissionName) {
    const hasPermission = permissions.some(permission => permission.name === permissionName);
    return `<input type="checkbox" ${hasPermission ? 'checked' : ''} disabled>`;
}
</script>
