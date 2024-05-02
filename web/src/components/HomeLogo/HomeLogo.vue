<script setup lang="ts">
import { ref } from "vue";
import logoUrl from "@/assets/images/logo.svg";

const props = defineProps<{
  layout?: "side-menu" | "simple-menu" | "top-menu";
}>();

const appName = import.meta.env.VITE_APP_NAME;

const toUrl = ref<string>('side-menu-dashboard-maindashboard')
</script>

<template>
    <RouterLink
        :to="{ name: toUrl }"
        :class="[
                '-intro-x hidden md:flex',
                props.layout == 'side-menu' && 'xl:w-[180px]',
                props.layout == 'simple-menu' && 'xl:w-auto',
                props.layout == 'top-menu' && 'w-auto',
        ]"
    >
        <img
            alt="DCSLab"
            class="w-6"
            :src="logoUrl"
        />
        <span
            :class="[
                    'ml-3 text-lg text-white',
                    props.layout == 'side-menu' && 'hidden xl:block',
                    props.layout == 'simple-menu' && 'hidden',
            ]"
        >
    {{ appName }}
    </span>
</RouterLink>
</template>