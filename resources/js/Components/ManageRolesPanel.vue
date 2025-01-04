<script setup>

</script>

<template>
    <div class="bg-white p-5 rounded-2xl drop-shadow-lg">
        <div class="flex items-center justify-between">
            <h1 class="font-sans text-2xl font-bold">Manage Roles</h1>
        </div>

        <div class="card pt-4">
            <DataTable tableStyle="min-width: 50rem">
                <Column field="id" header="ID" class="text-sm"></Column>
                <Column field="username" header="Username" class="text-sm"></Column>
                <Column field="createAccounts" header="Create Accounts" class="text-sm"></Column>
                <Column field="deleteAccounts" header="Delete Accounts" class="text-sm"></Column>
                <Column field="editAccounts" header="Edit Accounts" class="text-sm"></Column>
                <Column field="createPosts" header="Create Posts" class="text-sm"></Column>
                <Column field="deletePosts" header="Delete Posts" class="text-sm"></Column>
            </DataTable>
        </div>
    </div>
</template>

