<script setup lang="ts">
import ThemeSwitcher from "@/components/ThemeSwitcher";
import logoUrl from "@/assets/images/logo.svg";
import illustrationUrl from "@/assets/images/illustration.svg";
import { FormInput, FormCheck, FormErrorMessages } from "@/components/Base/Form";
import Button from "@/components/Base/Button";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import AuthService from "@/services/AuthServices";
import { onMounted, ref } from "vue";
import { LoginResponse } from "@/types/models/Auth";
import Alert from "@/components/Base/Alert";

const { t } = useI18n();
const router = useRouter();

const authService = new AuthService();

const appName = import.meta.env.VITE_APP_NAME;
const loading = ref<boolean>(false);
const status = ref<'onLoad' | 'success' | 'error'>('onLoad');
const alertMessage = ref<string>('');
const requireTwoFactor = ref<boolean>(false);
const twoFactorRecoveryCodesMode = ref<boolean>(false);

const loginForm = authService.useLoginForm();
const twoFactorLoginForm = authService.useTwoFactorLoginForm();

onMounted(async () => {
  authService.ensureCSRF();
});

const onSubmit = async () => {
  loading.value = true;

  loginForm.submit().then((response: unknown) => {
    let loginResp = response as LoginResponse;
    if (loginResp.data.two_factor) {
      requireTwoFactor.value = true;
    } else {
      router.push({ name: 'side-menu-dashboard-maindashboard' });
    }
  }).catch(error => {
    status.value = 'error';
    alertMessage.value = error.response.data.message;
  }).finally(() => {
    loading.value = false;
  });
};

const onTwoFactorLoginSubmit = async () => {
  loading.value = true;

  twoFactorLoginForm.submit().then(() => {
    router.push({ name: 'side-menu-dashboard-maindashboard' });
  }).catch(error => {
    status.value = 'error';
    alertMessage.value = error.response.data.message;
  }).finally(() => {
    loading.value = false;
  });
}
</script>

<template>
  <div
    :class="[
      '-m-3 sm:-mx-8 p-3 sm:px-8 relative h-screen lg:overflow-hidden bg-primary xl:bg-white dark:bg-darkmode-800 xl:dark:bg-darkmode-600',
      'before:hidden before:xl:block before:content-[\'\'] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[13%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-4.5deg] before:bg-primary/20 before:rounded-[100%] before:dark:bg-darkmode-400',
      'after:hidden after:xl:block after:content-[\'\'] after:w-[57%] after:-mt-[20%] after:-mb-[13%] after:-ml-[13%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[-4.5deg] after:bg-primary after:rounded-[100%] after:dark:bg-darkmode-700',
    ]"
  >
    <ThemeSwitcher />
    <div class="container relative z-10 sm:px-10">
      <div class="block grid-cols-2 gap-4 xl:grid">
        <div class="flex-col hidden min-h-screen xl:flex">
          <a href="" class="flex items-center pt-5 -intro-x">
            <img
              alt="DCSLab"
              class="w-6"
              :src="logoUrl"
            />
            <span class="ml-3 text-lg text-white"> {{ appName }} </span>
          </a>
          <div class="my-auto">
            <img
              alt="DCSLab"
              class="w-1/2 -mt-16 -intro-x"
              :src="illustrationUrl"
            />
            <div
              class="mt-10 text-4xl font-medium leading-tight text-white -intro-x"
            >
              <br />
            </div>
            <div
              class="mt-5 text-lg text-white -intro-x text-opacity-70 dark:text-slate-400"
            >              
            </div>
          </div>
        </div>
        <div class="flex h-screen py-5 my-10 xl:h-auto xl:py-0 xl:my-0">
          <div
            class="w-full px-5 py-8 mx-auto my-auto bg-white rounded-md shadow-md xl:ml-20 dark:bg-darkmode-600 xl:bg-transparent sm:px-8 xl:p-0 xl:shadow-none sm:w-3/4 lg:w-2/4 xl:w-auto"
          >
            <LoadingOverlay :visible="loading" :transparent="true">
              <h2 class="text-2xl font-bold text-center intro-x xl:text-3xl xl:text-left">
              {{ t("views.login.title") }}
              </h2>
              <div class="mt-2 text-center intro-x text-slate-400 xl:hidden">
                &nbsp;
              </div>
              <Alert v-if="status != 'onLoad'" :variant="status == 'success' ? 'success' : 'danger'" class="mt-2">{{ alertMessage }}</Alert>
              <form v-if="!requireTwoFactor" id="loginForm" @submit.prevent="onSubmit">
                <div class="mt-8 intro-x">
                  <FormInput
                    v-model="loginForm.email"
                    type="text"
                    class="block px-4 py-3 intro-x login__input min-w-full xl:min-w-[350px]"
                    :class="{ 'border-danger': loginForm.invalid('email') }"
                    :placeholder="t('views.login.fields.email')"
                    @focus="loginForm.forgetError('email')"
                  />
                  <FormErrorMessages :messages="loginForm.errors.email" />
                  <FormInput
                    v-model="loginForm.password"
                    type="password"
                    class="block px-4 py-3 mt-4 intro-x login__input min-w-full xl:min-w-[350px]"
                    :class="{ 'border-danger': loginForm.invalid('password') }"
                    :placeholder="t('views.login.fields.password')"
                    @focus="loginForm.forgetError('password')"
                  />
                  <FormErrorMessages :messages="loginForm.errors.password" />
                </div>
                <div
                  class="flex mt-4 text-xs intro-x text-slate-600 dark:text-slate-500 sm:text-sm"
                >
                  <div class="flex items-center mr-auto">
                    <FormCheck.Input
                      v-model="loginForm.remember"
                      type="checkbox"
                      class="mr-2 border"
                    />
                    <label class="cursor-pointer select-none" htmlFor="remember-me">
                      {{ t("views.login.fields.remember_me") }}
                    </label>
                  </div>
                  <RouterLink to="/auth/forgot-password">
                    {{ t("views.login.fields.forgot_pass") }}
                  </RouterLink>
                </div>
                <div class="mt-5 text-center intro-x xl:mt-8 xl:text-left">
                  <Button
                    variant="primary"
                    class="w-full px-4 py-3 align-top xl:w-32 xl:mr-3"
                  >
                    {{ t("components.buttons.login") }}
                  </Button>
                  <Button
                    variant="outline-secondary"
                    class="w-full px-4 py-3 mt-3 align-top xl:w-32 xl:mt-0"
                    @click="router.push({ name: 'register' })"
                  >
                    {{ t("components.buttons.register") }}
                  </Button>
                </div>
              </form>
              <form v-else id="twoFactorLoginForm" @submit.prevent="onTwoFactorLoginSubmit">
                <div v-if="twoFactorRecoveryCodesMode" class="mt-8 intro-x">
                  <FormLabel>
                    {{ t('views.login.fields.2fa.recovery_code') }}
                  </FormLabel>
                  <FormInput v-model="twoFactorLoginForm.recovery_code" type="text"
                            class="block px-4 py-3 intro-x min-w-full xl:min-w-[350px]"
                            :class="{ 'border-danger': twoFactorLoginForm.invalid('recovery_code') }"
                            :placeholder="t('views.login.fields.2fa.recovery_code')"
                            @focus="twoFactorLoginForm.forgetError('recovery_code')" />
                  <FormErrorMessages :messages="twoFactorLoginForm.errors.recovery_code" />
                </div>
                <div v-else class="mt-8 intro-x">
                  <FormLabel>
                    {{ t('views.login.fields.2fa.label') }}
                  </FormLabel>
                  <FormInput v-model="twoFactorLoginForm.code" type="text"
                    class="block px-4 py-3 intro-x min-w-full xl:min-w-[350px]"
                    :class="{ 'border-danger': twoFactorLoginForm.invalid('code') }"
                    :placeholder="t('views.login.fields.2fa.code')" @focus="twoFactorLoginForm.forgetError('code')" />
                  <FormErrorMessages :messages="twoFactorLoginForm.errors.code" />
                </div>
                <div class="flex mt-4 text-xs intro-x text-slate-600 dark:text-slate-500 sm:text-sm">
                  <div class="flex items-center mr-auto">
                    <FormCheck>
                      <FormCheck.Input v-model="twoFactorRecoveryCodesMode" type="checkbox" />
                      <FormCheck.Label>
                        {{ t('views.login.fields.2fa.use_recovery_codes') }}
                      </FormCheck.Label>
                    </FormCheck>
                  </div>
                </div>
                <div class="mt-5 text-center intro-x xl:mt-8 xl:text-left">
                  <Button variant="primary" class="w-full px-4 py-3 align-top xl:w-32 xl:mr-3">
                    {{ t("components.buttons.login") }}
                  </Button>
                </div>
              </form>
              <div
                class="mt-10 text-center intro-x xl:mt-24 text-slate-600 dark:text-slate-500 xl:text-left"
              >
                By signin up, you agree to our
                <a class="text-primary dark:text-slate-200" href="">
                  Terms and Conditions
                </a>
                &
                <a class="text-primary dark:text-slate-200" href="">
                  Privacy Policy
                </a>
              </div>
            </LoadingOverlay>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>