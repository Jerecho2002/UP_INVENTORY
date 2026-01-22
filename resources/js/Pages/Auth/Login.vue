<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TestButton from "@/Components/Buttons/TestButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <AuthLayout>
    <Head title="Login" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel class="text-bold text-lg" for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          placeholder="Enter your email"
          class="mt-1 block w-full py-3 text-md"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel text-bold text-lg for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          placeholder="Enter your password"
          class="mt-1 block w-full py-3 text-md"
          v-model="form.password"
          required
          autocomplete="current-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 flex items-center justify-between">
        <label class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember" />
          <span class="ms-2 text-md text-gray-600 dark:text-gray-400"
            >Remember me</span
          >
        </label>
        <Link
          class="rounded-md text-md text-[#850038] no-underline hover:underline focus:outline-none focus:ring-2 focus:ring-[#850038] focus:ring-offset-2"
        >
          Forgot your password?
        </Link>
      </div>

      <div class="mt-12 flex items-center justify-center">
        <TestButton
          class="w-full justify-center text-lg font-bold py-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Sign in
        </TestButton>
      </div>

      <div>
        <p class="mt-4 text-md items-center justify-center flex gap-2">
          Don't have an account?
          <Link
            class="text-[#850038] no-underline hover:underline focus:outline-none focus:ring-2 focus:ring-[#850038] focus:ring-offset-2"
            href="/register"
          >
            Register
          </Link>
        </p>
      </div>
    </form>
  </AuthLayout>
</template>
