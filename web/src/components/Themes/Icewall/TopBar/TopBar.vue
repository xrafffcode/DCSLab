<script setup lang="ts">
import { ref } from "vue";
import logoUrl from "@/assets/images/logo.svg";
import Lucide from "@/components/Base/Lucide";
import Breadcrumb from "@/components/Base/Breadcrumb";
import { FormInput } from "@/components/Base/Form";
import { Menu, Popover } from "@/components/Base/Headless";
import _ from "lodash";
import { TransitionRoot } from "@headlessui/vue";

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
    class="top-bar-boxed relative z-[51] -mx-5 mb-12 mt-12 h-[70px] border-b border-white/[0.08] px-3 sm:-mx-8 sm:px-8 md:-mt-5 md:pt-0"
  >
    <div class="flex h-full items-center">
    </div>
  </div>
</template>
