<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    otp: '',
});

const submit = () => {
    form.post(route('verification.verify'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Verify OTP" />

        <div class="mb-4 text-sm text-gray-600">
            Please enter the OTP sent to your email to verify your account.
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
                <InputOtp id="otp" v-model="form.otp" class="mt-1 block w-full" :length="6" required autofocus />
            </div>

            <div class="mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Verify OTP
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
