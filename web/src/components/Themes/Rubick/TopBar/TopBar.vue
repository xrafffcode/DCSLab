<script setup lang="ts">
import { ref } from "vue";
import Lucide from "@/components/Base/Lucide";
import Breadcrumb from "@/components/Base/Breadcrumb";
import { FormInput } from "@/components/Base/Form";
import { Menu, Popover } from "@/components/Base/Headless";
import _ from "lodash";
import { TransitionRoot } from "@headlessui/vue";
import ProfileMenu from "@/components/ProfileMenu/ProfileMenu.vue";

const searchDropdown = ref(false);
const showSearchDropdown = () => {
  searchDropdown.value = true;
};
const hideSearchDropdown = () => {
  searchDropdown.value = false;
};
</script>

<template>
  <div
    class="relative z-[51] flex h-[67px] items-center border-b border-slate-200"
  >

  </div>
</template>
