<script setup>
import { ref } from "vue";
import Select from 'primevue/select';

const visible = ref(false);

const selectedRole = ref();
const roles = ref([
    { name: 'Super Admin' },
    { name: 'Admin' }
]);
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
                @click="visible = true"
                class="bg-violet-500"
            />
        </div>

        <Dialog v-model:visible="visible" header="Add Account" :style="{ width: '25.5rem' }" modal class="dialog-with-blur">
            <span class="text-surface-500 dark:text-surface-400 block mb-8">Enter account information.</span>
            <div class="flex items-center gap-4 mb-4">
                <label for="username" class="font-semibold w-24">Username</label>
                <InputText id="username" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex items-center gap-4 mb-4">
                <label for="email" class="font-semibold w-24">Email</label>
                <InputText id="email" class="flex-auto" autocomplete="off" />
            </div>
            <div class="flex items-center gap-6 mb-4">
                <label for="password" class="font-semibold w-24">Password</label>
                <Password id="password" class="flex-auto" toggleMask />
            </div>
            <div class="flex items-center gap-6 mb-8">
                <label for="roles" class="font-semibold w-24">Roles</label>
                <Select v-model="selectedRole" :options="roles" optionLabel="name" placeholder="Select a Role" checkmark :highlightOnSelect="false" class="w-full md:w-72" />
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
                <Button type="button" label="Add" @click="visible = false"></Button>
            </div>
        </Dialog>


        <div class="card pt-4">
            <DataTable :value="accounts" tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="username" header="Userame" class="text-sm"></Column>
                <Column field="email" header="Email" class="text-sm"></Column>
                <Column field="role" header="Role" class="text-sm"></Column>
                <Column field="actions" header="Actions" class="text-sm">
                    <template #body="{ data }">
                        <Button
                            icon="pi pi-exclamation-triangle"
                            severity="danger"
                            rounded
                            @click=""
                            aria-label="Delete"
                        />
                        <Button
                            icon="pi pi-user-edit"
                            severity="secondary"
                            rounded
                            @click=""
                            aria-label="Edit"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>



<style>
/* Backdrop blur for the modal */
.dialog-with-blur{
    backdrop-filter: blur(8px); /* Apply blur effect */
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
}
</style>
