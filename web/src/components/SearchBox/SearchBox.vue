<script setup lang="ts">
import { ref } from "vue";
import Lucide from "@/components/Base/Lucide";
import logoUrl from "@/assets/images/logo.svg";
import Breadcrumb from "@/components/Base/Breadcrumb";
import { FormInput } from "@/components/Base/Form";
import { Menu, Popover } from "@/components/Base/Headless";
import _ from "lodash";
import { TransitionRoot } from "@headlessui/vue";

const props = defineProps<{
  layout?: "side-menu" | "simple-menu" | "top-menu";
}>();

const searchDropdown = ref(false);
const showSearchDropdown = () => {
  searchDropdown.value = true;
};
const hideSearchDropdown = () => {
  searchDropdown.value = false;
};
</script>

<template>
    <div class="relative mr-3 intro-x sm:mr-6">
        <div class="relative hidden sm:block">
            <FormInput
            type="text"
            class="border-transparent w-56 shadow-none rounded-full bg-slate-200 pr-8 transition-[width] duration-300 ease-in-out focus:border-transparent focus:w-72 dark:bg-darkmode-400"
            placeholder="Search..."
            @focus="showSearchDropdown"
            @blur="hideSearchDropdown"
            />
            <Lucide
            icon="Search"
            class="absolute inset-y-0 right-0 w-5 h-5 my-auto mr-3 text-slate-600 dark:text-slate-500"
            />
        </div>
        <a class="relative text-white/70 sm:hidden" href="">
            <Lucide icon="Search" class="w-5 h-5 dark:text-slate-500" />
        </a>
        <TransitionRoot
            as="template"
            :show="searchDropdown"
            enter="transition-all ease-linear duration-150"
            enterFrom="mt-5 invisible opacity-0 translate-y-1"
            enterTo="mt-[3px] visible opacity-100 translate-y-0"
            entered="mt-[3px]"
            leave="transition-all ease-linear duration-150"
            leaveFrom="mt-[3px] visible opacity-100 translate-y-0"
            leaveTo="mt-5 invisible opacity-0 translate-y-1"
        >
            <div class="absolute right-0 z-10 mt-[3px]">
            <div class="w-[450px] p-5 box">
                <div class="mb-2 font-medium">Pages</div>
                <div class="mb-5">
                <a href="" class="flex items-center">
                    <div
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-success/20 dark:bg-success/10 text-success"
                    >
                    <Lucide icon="Inbox" class="w-4 h-4" />
                    </div>
                    <div class="ml-3">Mail Settings</div>
                </a>
                <a href="" class="flex items-center mt-2">
                    <div
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-pending/10 text-pending"
                    >
                    <Lucide icon="Users" class="w-4 h-4" />
                    </div>
                    <div class="ml-3">Users & Permissions</div>
                </a>
                <a href="" class="flex items-center mt-2">
                    <div
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 dark:bg-primary/20 text-primary/80"
                    >
                    <Lucide icon="CreditCard" class="w-4 h-4" />
                    </div>
                    <div class="ml-3">Transactions Report</div>
                </a>
                </div>
                <div class="mb-2 font-medium">Users</div>
                <div class="mb-5">
                </div>
                <div class="mb-2 font-medium">Products</div>
            </div>
        </div>
    </TransitionRoot>
    </div>
</template>