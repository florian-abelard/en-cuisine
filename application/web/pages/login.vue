<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">
      Les moflo en cuisine !
    </h1>

    <form @submit="onSubmit">
      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="text"
        v-model="login"
        v-bind="loginAttrs"
        placeholder="Identifiant"
      >

      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="password"
        v-model="password"
        v-bind="passwordAttrs"
        placeholder="Mot de passe"
      >

      {{ meta.valid }}

      <button
        type="submit"
        class="btn btn-primary mx-2"
        :disabled="isSubmitting || !meta.valid"
      >
        Valider
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';

  const { meta, handleSubmit, defineField, isSubmitting } = useForm({
    validationSchema: toTypedSchema(
      object({
        login: string().required(),
        password: string().required(),
      }),
    ),
  });

  const [login, loginAttrs] = defineField('login');
  const [password, passwordAttrs] = defineField('password');

  const onSubmit = handleSubmit((values) => {
    console.log(values);
  });

</script>
