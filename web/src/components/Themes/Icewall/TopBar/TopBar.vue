<script setup lang="ts">
import HomeLogo from "@/components/HomeLogo/HomeLogo.vue";
import UserLocation from "@/components/UserLocation/UserLocation.vue";
import ProfileMenu from "@/components/ProfileMenu/ProfileMenu.vue";
</script>

<template>
  <div
    class="top-bar-boxed relative z-[51] -mx-5 mb-12 mt-12 h-[70px] border-b border-white/[0.08] px-3 sm:-mx-8 sm:px-8 md:-mt-5 md:pt-0"
  >
    <div class="flex h-full items-center">
      <UserLocation />
      <ProfileMenu />
    </div>
  </div>
</template>
