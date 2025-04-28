<template>
  <div class="p-4 text-center">
    <h1 class="text-xl font-bold mb-4">
      Les moflo en cuisine !
    </h1>

    <p v-if="error" class="bg-red-500 text-white font-bold rounded-md py-2 px-4">
      {{ error }}
    </p>

    <form @submit="onSubmit" class="flex flex-col items-center">
      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="text"
        v-model="username"
        v-bind="usernameAttrs"
        placeholder="Identifiant"
      />

      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="password"
        v-model="password"
        v-bind="passwordAttrs"
        placeholder="Mot de passe"
      />

      <button
        type="submit"
        class="btn btn-primary btn-sm mx-2"
        :disabled="isSubmitting || !meta.valid"
      >
        Valider
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm, useApiAuth, navigateTo } from '#imports';
  import { ref } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';

  interface LoginForm {
    username?: string | null;
    password?: string | null;
  }

  const error = ref<string>('');

  const { meta, handleSubmit, defineField, isSubmitting } = useForm<LoginForm>({
    validationSchema: toTypedSchema(
      object({
        username: string().required(),
        password: string().required(),
      }),
    ),
  });

  const [username, usernameAttrs] = defineField('username');
  const [password, passwordAttrs] = defineField('password');

  const onSubmit = handleSubmit(async (values) => {
    try {
      await useApiAuth().login(values.username, values.password);
      navigateTo('/');
    } catch (e) {
      error.value = 'Identifiant ou mot de passe incorrect.';
    }
  });

</script>
