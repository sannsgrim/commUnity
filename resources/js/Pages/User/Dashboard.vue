<script setup>
import { Head } from '@inertiajs/vue3';
import UserAuthenticatedLayout from "@/Layouts/UserAuthenticatedLayout.vue";
import Textarea from 'primevue/textarea';
import Chip from 'primevue/chip';
import IftaLabel from 'primevue/iftalabel';
</script>

<template>
    <Head title="Dashboard" />
    <UserAuthenticatedLayout>
        <aside class="sticky top-8 hidden w-1/4 shrink-0 lg:block bg-[#F4F4F4] rounded-xl">
            <!-- Left column area -->
            <div class="pb-12">

            </div>
        </aside>

        <main class="flex-1 bg-[#F4F4F4] rounded-xl ">
            <!-- Main area -->
            <div class="pb-12 flex justify-center py-5">
                <IftaLabel>
                    <Textarea id="description" v-model="value" rows="1" cols="90" style="resize: none" />
                    <label for="description">Write something.....</label>
                </IftaLabel>

                <div class="pb-13">
                    <Chip label="Amy Elsner" image="/images/avatar/amyelsner.png" />
                </div>

            </div>
        </main>
    </UserAuthenticatedLayout>
</template>
