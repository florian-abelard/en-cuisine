<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">
      Bienvenue sur l'application En Cuisine !
    </h1>

    <form @submit="onSubmit">
      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="text"
        v-model="login"
        v-bind="loginAttrs"
        placeholder="Identifiant"
      >
      <div>{{ errors.login }}</div>

      <input
        class="input input-bordered input-primary input-sm w-full max-w-xs mb-4"
        type="password"
        v-model="password"
        v-bind="passwordAttrs"
        placeholder="Mot de passe"
      >
      <div>{{ errors.password }}</div>

      <button
        type="submit"
        class="btn btn-primary mx-2"
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

  const { errors, handleSubmit, defineField } = useForm({
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
