<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Use Recovery Code</h2>
                    <p class="mb-4">Please provide one of your emergency recovery codes.</p>

                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700">Recovery Code</label>
                        <input
                            type="text"
                            v-model="form.recovery_code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            autofocus
                            autocomplete="one-time-code"
                        />
                        <div v-if="form.errors.recovery_code" class="text-red-600 mt-1">
                            {{ form.errors.recovery_code }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <button
                            type="button"
                            @click="backToCode"
                            class="text-sm text-gray-600 hover:text-gray-900 underline"
                        >
                            ← Use an authentication code
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                            :disabled="form.processing"
                        >
                            Verify
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const form = useForm({
    recovery_code: '',
})

const backToCode = () => {
    router.visit(route('user.two-factor.challenge'))
}

const submit = () => {
    form.post(route('user.two-factor.recovery.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>
