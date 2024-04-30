<script setup lang="ts">
import { toRef, onMounted, computed } from "vue";
import { Menu } from "../Base/Headless";
import { useUserContextStore } from '../../stores/user-context';
import defaultProfilePic from "@/assets/images/def-user.png";

interface ProfileMenuProps {
    visible: boolean,
}

const props = withDefaults(defineProps<ProfileMenuProps>(), {
    visible: false
});

const visible = toRef(props, 'visible');

const userContextStore = useUserContextStore();
const userContext = computed(() => userContextStore.userContext);

const profilePicture = computed(() => {
    let defaultPic = defaultProfilePic;

    return defaultPic;
});

const profilePictureAlt = computed(() => {
    let defaultPicAlt = 'DCSLab';

    return defaultPicAlt;
});
</script>

<template>
    <Menu>
        <Menu.Button
        class="block w-8 h-8 overflow-hidden rounded-full shadow-lg image-fit zoom-in intro-x"
        >
            <img
            :alt="profilePictureAlt"
            :src="profilePicture"
            />
        </Menu.Button>
        <Menu.Items
            class="w-56 mt-px relative bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white"
        >
            <Menu.Header class="font-normal">
                <div class="font-medium"></div>
                <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">
                    
                </div>
            </Menu.Header>
            <Menu.Divider class="bg-white/[0.08]" />
            <Menu.Item class="hover:bg-white/5">
                <Lucide icon="User" class="w-4 h-4 mr-2" />
            </Menu.Item>
            <Menu.Item class="hover:bg-white/5">
                <Lucide icon="Edit" class="w-4 h-4 mr-2" />
            </Menu.Item>
            <Menu.Item class="hover:bg-white/5">
                <Lucide icon="Lock" class="w-4 h-4 mr-2" />
            </Menu.Item>
            <Menu.Item class="hover:bg-white/5">
                <Lucide icon="HelpCircle" class="w-4 h-4 mr-2" />
            </Menu.Item>
            <Menu.Divider class="bg-white/[0.08]" />
            <Menu.Item class="hover:bg-white/5">
                <Lucide icon="ToggleRight" class="w-4 h-4 mr-2" />
            </Menu.Item>
        </Menu.Items>
    </Menu>
</template>