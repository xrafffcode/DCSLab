<script setup lang="ts">
import HomeLogo from "@/components/HomeLogo/HomeLogo.vue";
import UserLocation from "@/components/UserLocation/UserLocation.vue";
import SearchBox from "@/components/SearchBox/SearchBox.vue";
import ProfileMenu from "@/components/ProfileMenu/ProfileMenu.vue";
</script>

<template>
  <div
    class="relative z-[51] flex h-[67px] items-center border-b border-slate-200"
  >
    <UserLocation />
    <SearchBox />
    <ProfileMenu />
  </div>
</template>
