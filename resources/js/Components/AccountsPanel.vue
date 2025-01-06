<script setup>
import { ref, watch, computed } from "vue";
import axios from "axios";
import Select from 'primevue/select';

const props = defineProps({
    adminUsers: Object,
});

const defaultRoles = ref([
    { name: 'Super Admin' },
    { name: 'Admin' }
]);

const adminUsers = ref(props.adminUsers);


watch(() => props.adminUsers, (newVal) => {
    adminUsers.value = newVal;
});

const visible1 = ref(false);
const visible2 = ref(false);
const visible3 = ref(false);

const selectedRole = ref();

const roles = computed(() => {
    const allRoles = props.adminUsers.flatMap(user => user.roles);
    const uniqueRoles = [...new Set(allRoles.map(role => role.name))];
    return uniqueRoles.map(roleName => {
        if (roleName === 'super-admin') {
            return { name: 'Super Admin' };
        } else if (roleName === 'admin') {
            return { name: 'Admin' };
        }
        return { name: roleName };
    });
});



const selectedUser = ref({
    id: '',
    email: '',
    password: '',
    roles: defaultRoles.value.map(role => role.name)
});

const editUser = (user) => {
    selectedUser.value = {
        id: user.id,
        email: user.email,
        password: '',
        roles: user.roles.map(role => {
            if (role.name === 'super-admin') {
                return 'Super Admin';
            } else if (role.name === 'admin') {
                return 'Admin';
            }
            return role.name;
        })
    };
    visible2.value = true;
};

const deleteUser = async (id) => {
    console.log('Delete User ID:', id);

    try {
        const response = await axios.post(route('admin.deleteAccount', { id }));
        adminUsers.value = response.data;
    } catch (error) {
        console.log(error)
    }

    visible3.value = false;
};
// Save the user information
const saveUser = async () => {
    try {
        const response = await axios.post(route('admin.updateAccount', { id: selectedUser.value.id }), {
            username: selectedUser.value.username,
            email: selectedUser.value.email,
        });
        console.log('User Updated:', response.data);
        adminUsers.value = response.data;
        visible2.value = false; // Close the dialog
    } catch (error) {
        console.error('Failed to update user:', error.response?.data || error.message);
    }
};

</script>

<template>
    <div class="bg-white p-5 rounded-2xl drop-shadow-lg">
        <div class="flex items-center justify-between">
            <h1 class="font-sans text-2xl font-bold">Accounts</h1>
            <Button
                icon="pi pi-plus"
                severity="help"
                raised
                rounded
                aria-label="Add Account"
                @click="visible1 = true"
                class="bg-violet-500"
            />
        </div>

        <Dialog v-model:visible="visible1" header="Add Account" modal class="dialog-with-blur" :draggable="false">
            <span class="text-surface-500 dark:text-surface-400 block mb-8">Enter edited account information.</span>
            <form>
                <div class="flex items-center gap-4 mb-4">
                    <label for="email" class="font-semibold w-24">Email</label>
                    <InputText id="email" class="flex-auto" autocomplete="off" />
                </div>
                <div class="flex items-center gap-4 mb-8">
                    <label for="roles" class="font-semibold w-24">Roles</label>
                    <Select v-model="selectedRole" :options="roles" optionLabel="name" placeholder="Select a Role" checkmark :highlightOnSelect="false" class="w-full md:w-72" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button
                        type="button"
                        label="Cancel"
                        severity="secondary"
                        @click="visible1 = false">
                    </Button>
                    <Button
                        type="button"
                        label="Add"
                        @click="visible1 = false">
                    </Button>
                </div>
            </form>
        </Dialog>

        <Dialog v-model:visible="visible2" header="Edit Account" modal class="dialog-with-blur">
            <span class="text-surface-500 dark:text-surface-400 block mb-8">Enter account information.</span>
            <form>
                <div class="flex items-center gap-4 mb-4">
                    <label for="id" class="font-semibold w-24">ID</label>
                    <InputText id="id" v-model="selectedUser.id" class="flex-auto" disabled />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="email" class="font-semibold w-24">Email</label>
                    <InputText id="email" v-model="selectedUser.email" class="flex-auto" autocomplete="off" />
                </div>

                <div class="flex justify-end gap-2">
                    <Button
                        type="button"
                        label="Cancel"
                        severity="secondary"
                        @click="visible2 = false">
                    </Button>

                    <Button
                        type="button"
                        label="Save"
                        severity="success"
                        @click="saveUser"
                    >
                    </Button>

                </div>
            </form>
        </Dialog>

        <Dialog v-model:visible="visible3" header="Delete Account" :style="{ width: '25.5rem' }" modal class="dialog-with-blur">
            <span class="text-surface-500 dark:text-surface-400 block mb-8">Are you sure to delete this account?</span>
            <div class="flex justify-end gap-2">
                <Button
                    type="button"
                    label="Cancel"
                    severity="secondary"
                    @click="visible3 = false">
                </Button>
                <Button
                    type="button"
                    label="Delete"
                    severity="danger"
                    @click="deleteUser(selectedUser.id)">
                </Button>
            </div>
        </Dialog>

        <div class="card pt-4 py-4">
            <DataTable :value="adminUsers" tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="email" header="Email" class="text-sm"></Column>
                <Column field="roles" header="Role" class="text-sm">
                    <template #body="{ data }">
                        <span>
                            {{ data.roles && data.roles.length > 0 ? data.roles[0].name : 'No Role Assigned' }}
                        </span>
                    </template>
                </Column>
                <Column field="actions" header="Actions" class="text-sm">
                    <template #body="{ data }">
                        <div class="flex gap-4">
                            <ConfirmDialog></ConfirmDialog>
                            <Button
                                icon="pi pi-trash"
                                severity="danger"
                                rounded
                                raised
                                aria-label="Delete"
                                @click="() => { selectedUser.id = data.id; visible3 = true; }">
                            </Button>
                            <Button
                                icon="pi pi-user-edit"
                                severity="secondary"
                                rounded
                                raised
                                aria-label="Edit"
                                @click="editUser(data)">
                            </Button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<style>
.dialog-with-blur {
    backdrop-filter: blur(8px);
}
</style>
