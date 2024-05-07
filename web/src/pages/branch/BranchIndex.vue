<script setup lang="ts">
// #region Imports
import { ref } from "vue";
import LoadingOverlay from "@/components/LoadingOverlay";
import { TitleLayout } from "@/components/Base/Form/FormLayout";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import Button from "@/components/Base/Button";
import Lucide from "@/components/Base/Lucide";
import { ViewMode } from "@/types/enums/ViewMode";
import CacheService from "@/services/CacheService";
import AlertPlaceholder from "@/components/AlertPlaceholder/AlertPlaceholder.vue";
import { useUserContextStore } from "@/stores/user-context";
import ProfileService from "@/services/ProfileService";
import { UserProfile } from "@/types/models/UserProfile";
// #endregion

// #region Interfaces
// #endregion

// #region Declarations
const { t } = useI18n();
const router = useRouter();

const userContextStore = useUserContextStore();

const cacheService = new CacheService();
const profileService = new ProfileService();
// #endregion

// #region Props, Emits
// #endregion

// #region Refs
const mode = ref<ViewMode>(ViewMode.INDEX);
const loading = ref<boolean>(false);
const titleView = ref<string>('views.branch.page_title');

const errorMessages = ref<Record<string, Array<string>> | null>(null);
// #endregion

// #region Computed
// #endregion

// #region Lifecycle Hooks
// #endregion

// #region Methods
const createNew = () => {
    mode.value = ViewMode.FORM_CREATE;
    router.push({ name: 'side-menu-company-branch-create' });
};

const backToList = async () => {
    clearCache(mode.value);
    mode.value = ViewMode.LIST;

    router.push({ name: 'side-menu-company-branch-list' });
};

const onLoadingStateChanged = (state: boolean) => {
    loading.value = state;
};

const onModeStateChanged = (state: ViewMode) => {
    mode.value = state;

    switch (state) {
        case ViewMode.FORM_CREATE:
            titleView.value = 'views.branch.actions.create';
            break;
        case ViewMode.FORM_EDIT:
            titleView.value = 'views.branch.actions.edit';
            break;
        case ViewMode.INDEX:
        case ViewMode.LIST:
        default:
            titleView.value = 'views.branch.page_title';
            break;
    }
};

const clearCache = (mode: ViewMode) => {
    switch (mode) {
        case ViewMode.FORM_CREATE:
            cacheService.removeLastEntity('BRANCH_CREATE');
            break;
        case ViewMode.FORM_EDIT:
            cacheService.removeLastEntity('BRANCH_EDIT');
            break;
        default:
            break;
    }
};

const onUpdateProfileTriggered = async () => {
    let userprofile = await profileService.readProfile();
    if (userprofile.success) {
        userContextStore.setUserContext(userprofile.data as UserProfile);
    }
};

const onAlertPlaceholderTriggered = (errors: Record<string, Array<string>>) => {
    errorMessages.value = errors;
};
// #endregion

// #region Watchers
// #endregion
</script>

<template>
    <div class="mt-8">
        <LoadingOverlay :visible="loading">
            <TitleLayout>
                <template #title>
                    {{ t(titleView) }}
                </template>
                <template #optional>
                    <div v-if="mode != ViewMode.INDEX" class="flex w-full mt-4 sm:w-auto sm:mt-0">
                        <Button v-if="mode == ViewMode.LIST" as="a" href="#" variant="primary" class="shadow-md"
                            @click="createNew">
                            <Lucide icon="Plus" class="w-4 h-4" />&nbsp;{{ t("components.buttons.create_new") }}
                        </Button>
                        <Button v-else as="a" href="#" variant="primary" class="shadow-md" @click="backToList">
                            <Lucide icon="ArrowLeft" class="w-4 h-4" />&nbsp;{{ t("components.buttons.back") }}
                        </Button>
                    </div>
                </template>
            </TitleLayout>

            <AlertPlaceholder :errors="errorMessages" />
            <RouterView @loading-state="onLoadingStateChanged" @mode-state="onModeStateChanged" @update-profile="onUpdateProfileTriggered" @show-alertplaceholder="onAlertPlaceholderTriggered" />
        </LoadingOverlay>
    </div>
</template>