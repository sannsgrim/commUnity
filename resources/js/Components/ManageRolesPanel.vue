<script setup>
import { ref, computed } from "vue";
import axios from 'axios';

const props = defineProps({
    adminUsers: Array,
});

const searchQuery = ref('');
const adminUsers = ref(props.adminUsers);
const totalRecords = ref(adminUsers.value.length);

function hasPermission(permissions, permissionName) {
    return permissions.some(permission => permission.name === permissionName);
}

const filteredUsers = computed(() => {
    if (!searchQuery.value) {
        return adminUsers.value;
    }
    return adminUsers.value.filter(user =>
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const onPage = async (event) => {
    try {
        const response = await axios.get('/api/admin-users', {
            params: {
                page: event.page + 1,
                rows: event.rows
            }
        });
        adminUsers.value = response.data.data;
        totalRecords.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch data:', error);
    }
};

function updatePermission(userId, permissionName, hasPermission) {
    try {
        const response = axios.post(route('admin.updatePermissions', { id: userId }), {
            permission: permissionName,
            hasPermission: hasPermission
        });
    }catch (error) {
        console.log(error);
    }
}

</script>

<template>
    <div class="bg-white p-5 rounded-2xl drop-shadow-lg">
        <div class="flex items-center justify-between">
            <h1 class="font-sans text-2xl font-bold">Manage Roles</h1>
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by email"
                class="p-2 border rounded-md"
            />
        </div>

        <div class="card pt-4 py-4">

            <DataTable :value="filteredUsers" tableStyle="min-width: 50rem" :paginator="true" :rows="10" :totalRecords="totalRecords" @page="onPage">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="email" header="Username" class="text-sm"></Column>
                <Column field="permissions" header="Create View Post" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox"
                            :checked="hasPermission(slotProps.data.permissions, 'Can View Post')"
                            @change="updatePermission(slotProps.data.id, 'Can View Post', $event.target.checked)">
                    </template>
                </Column>
                <Column field="permissions" header="Can Create Own Post" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox"
                            :checked="hasPermission(slotProps.data.permissions, 'Can Create Own Post')"
                            @change="updatePermission(slotProps.data.id, 'Can Create Own Post', $event.target.checked)">
                    </template>
                </Column>
                <Column field="permissions" header="Create Comment Own Post" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox"
                            :checked="hasPermission(slotProps.data.permissions, 'Can Comment Own Post')"
                            @change="updatePermission(slotProps.data.id, 'Can Comment Own Post', $event.target.checked)">
                    </template>
                </Column>
                <Column field="permissions" header="Comment Others Post" class="text-sm">
                    <template #body="slotProps">
                        <input
                            class="rounded-md"
                            type="checkbox"
                            :checked="hasPermission(slotProps.data.permissions, 'Can Comment Others Post')"
                            @change="updatePermission(slotProps.data.id, 'Can Comment Others Post', $event.target.checked)">
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>
