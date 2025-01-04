<script setup>
import {Link} from "@inertiajs/vue3";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";




import { ref } from "vue";

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
            <h1 class="font-sans text-2xl font-bold">Manage Roles</h1>
        </div>

        <div class="card pt-4">
            <DataTable :value="accounts" tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="username" header="Userame" class="text-sm"></Column>
                <Column field="email" header="Email" class="text-sm"></Column>
                <Column field="role" header="Role" class="text-sm"></Column>
                <Column field="quantity" header="Quantity" class="text-sm">
                    <template #body="{ data }">
                        <Button
                            icon="pi pi-search"
                            severity="secondary"
                            rounded
                            @click="handleRowAction(data)"
                            aria-label="Search"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>



<style>
/* Backdrop blur for the modal */
.dialog-with-blur .p-dialog-mask {
    backdrop-filter: blur(8px); /* Apply blur effect */
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
}
</style>
