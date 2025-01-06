<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Two Factor Authentication</h2>
                    <p class="mb-4">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>

                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700">Code</label>
                        <input
                            type="text"
                            v-model="form.code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                        />
                        <div v-if="form.errors.code" class="text-red-600 mt-1">
                            {{ form.errors.code }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <button
                            type="button"
                            @click="navigateToRecovery"
                            class="text-sm text-gray-600 hover:text-gray-900 underline"
                        >
                            Use a recovery code
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
    code: '',
})

const navigateToRecovery = () => {
    router.visit(route('user.two-factor.recovery'))
}

const submit = () => {
    form.post(route('user.two-factor.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>
