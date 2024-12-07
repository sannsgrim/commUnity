<script setup>
import InputError from '@/Components/InputError.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import UserLoginLayout from "@/Layouts/UserLoginLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FloatLabel from "primevue/floatlabel";

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const loading = ref(false);

const load = () => {
    loading.value = true;
};

const stop_load = () => {
    loading.value = false;
};

const submit = () => {
    load();
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            stop_load();
        },
    });
};
</script>

<template>
    <UserLoginLayout>
        <Head title="Register"/>

        <div class="mx-auto w-full max-w-sm lg:w-96 pt-6">
            <div>
                <Link :href="route('welcome')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                </Link>
                <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Register to make your own
                    account</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Already Have an Account?
                    {{ ' ' }}
                    <Link :href="route('login')" class="font-semibold text-indigo-600 hover:text-indigo-500">
                        Sign In Here
                    </Link>
                </p>
            </div>

            <div class="mt-10">
                <div>
                    <form @submit.prevent="submit" class="space-y-6">

                        <div>
                            <FloatLabel variant="on">
                                <InputText
                                    id="first_name"
                                    v-model="form.first_name"
                                    fluid
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <label for="first_name">First Name</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.first_name"/>
                        </div>

                        <div>
                            <FloatLabel variant="on">
                                <InputText
                                    id="last_name"
                                    v-model="form.last_name"
                                    fluid
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <label for="last_name">Last Name</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.first_name"/>
                        </div>

                        <div>
                            <FloatLabel variant="on">
                                <InputText
                                    id="email"
                                    v-model="form.email"
                                    fluid
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <label for="email">Email</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.email"/>
                        </div>

                        <div>
                            <FloatLabel variant="on">
                                <Password
                                    id="password"
                                    v-model="form.password"
                                    fluid
                                    required
                                    autofocus
                                    toggleMask
                                    autocomplete="current-password"
                                >
                                    <template #header>
                                        <div class="font-semibold text-xm mb-4">Pick a password</div>
                                    </template>
                                    <template #footer>
                                        <Divider/>
                                        <ul class="pl-2 ml-2 my-0 leading-normal">
                                            <li>At least one lowercase</li>
                                            <li>At least one uppercase</li>
                                            <li>At least one numeric</li>
                                            <li>Minimum 8 characters</li>
                                        </ul>
                                    </template>
                                </Password>
                                <label for="password">Password</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.password"/>
                        </div>

                        <div>
                            <FloatLabel variant="on">
                                <Password
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    fluid
                                    required
                                    autofocus
                                    toggleMask
                                    :feedback="false"
                                    autocomplete="new-password"
                                />
                                <label for="password_confirmation">Confirm Password</label>
                            </FloatLabel>

                            <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                        </div>

                        <div>
                            <Button
                                fluid
                                type="submit"
                                label="Register"
                                :loading="loading"
                                icon="pi pi-sign-in"
                                :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLoginLayout>
</template>
