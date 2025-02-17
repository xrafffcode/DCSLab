<script setup lang="ts">
// #region Imports
import { onMounted, ref, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import WarehouseService from "@/services/WarehouseService";
import DashboardService from "@/services/DashboardService";
import CacheService from "@/services/CacheService";
import { DropDownOption } from "@/types/models/DropDownOption";
import { TwoColumnsLayoutCards } from "@/components/Base/Form/FormLayout/TwoColumnsLayout.vue";
import { CardState } from "@/types/enums/CardState";
import Button from "@/components/Base/Button";
import { ViewMode } from "@/types/enums/ViewMode";
import { debounce } from "lodash";
import { FormInput, FormLabel, FormTextarea, FormSelect, FormSwitch, FormErrorMessages } from "@/components/Base/Form";
import { useRouter } from "vue-router";
import { ErrorCode } from "@/types/enums/ErrorCode";
import { type AlertPlaceholderProps } from "@/components/AlertPlaceholder/AlertPlaceholder.vue";
// #endregion

// #region Declarations
const { t } = useI18n();
const router = useRouter();
const warehouseService = new WarehouseService();
const dashboardService = new DashboardService();
const cacheService = new CacheService();

const emits = defineEmits(['mode-state', 'loading-state', 'update-profile', 'show-alertplaceholder']);
// #endregion

// #region Refs
const cards = ref<Array<TwoColumnsLayoutCards>>([
    { title: 'views.warehouse.field_groups.general_info', state: CardState.Expanded },
    { title: 'views.warehouse.field_groups.location_info', state: CardState.Expanded }
]);

const statusDDL = ref<Array<DropDownOption> | null>(null);

const warehouseForm = warehouseService.useWarehouseCreateForm();
// #endregion

// #region Computed
const isFormValid = computed(() => !warehouseForm.hasErrors);
// #endregion

// #region Lifecycle Hooks
onMounted(async () => {
    emits('mode-state', ViewMode.FORM_CREATE);

    getDDL();
    loadFromCache();
});
// #endregion

// #region Methods
const getDDL = (): void => {
    dashboardService.getStatusDDL().then((result: Array<DropDownOption> | null) => {
        statusDDL.value = result;
    });
};

const loadFromCache = () => {
    const data = cacheService.getLastEntity('WAREHOUSE_CREATE') as Record<string, unknown>;
    if (!data) return;
    warehouseForm.setData(data);
};

const onSubmit = async () => {
    if (warehouseForm.hasErrors) {
        scrollToError(Object.keys(warehouseForm.errors)[0]);
    }

    emits('loading-state', true);
    await warehouseForm.submit().then(() => {
        resetForm();
        emits('update-profile');
        router.push({ name: 'warehouse-list' });
    }).catch(error => {
        const errorList: Record<string, Array<string>> = convertErrorTypeToAlertListType(error as Error);
        showAlertPlaceholder('danger', '', errorList);
    }).finally(() => {
        emits('loading-state', false);
    });
};

const resetForm = () => {
    warehouseForm.reset();
    warehouseForm.setErrors({});
};

const scrollToError = (id: string): void => {
    const el = document.getElementById(id);
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

const showAlertPlaceholder = (type: 'hidden' | 'danger' | 'success' | 'warning' | 'pending' | 'dark', title: string, alertList: Record<string, Array<string>> | null) => {
    const alertProps: AlertPlaceholderProps = {
        alertType: type,
        title,
        alertList,
    };
    emits('show-alertplaceholder', alertProps);
};

const convertErrorTypeToAlertListType = (error: Error) => {
    const record: Record<string, Array<string>> = {};
    record.error = [error.message];
    return record;
};
// #endregion

// #region Watchers
watch(
    warehouseForm,
    debounce((newValue): void => {
        cacheService.setLastEntity('WAREHOUSE_CREATE', newValue.data());
    }, 500),
    { deep: true }
);
// #endregion
</script>

<template>
    <form id="warehouseForm" @submit.prevent="onSubmit">
        <TwoColumnsLayout :cards="cards" :using-side-tab="false">
            <!-- Card 1: General Info -->
            <template #card-items-0>
                <div class="p-5">
                    <!-- Company ID -->
                    <FormLabel>{{ t('views.warehouse.fields.company_id') }}</FormLabel>
                    <FormInput type="hidden" v-model="warehouseForm.company_id" />

                    <!-- Branch ID -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.branch_id') }}</FormLabel>
                        <FormInput v-model="warehouseForm.branch_id" type="text" placeholder="Enter Branch ID" />
                        <FormErrorMessages :messages="warehouseForm.errors.branch_id" />
                    </div>

                    <!-- Code -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.code') }}</FormLabel>
                        <FormInput v-model="warehouseForm.code" type="text" placeholder="Enter Code" />
                        <FormErrorMessages :messages="warehouseForm.errors.code" />
                    </div>
                </div>
            </template>

            <!-- Card 2: Location Info -->
            <template #card-items-1>
                <div class="p-5">
                    <!-- Name -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.name') }}</FormLabel>
                        <FormInput v-model="warehouseForm.name" type="text" placeholder="Enter Name" />
                        <FormErrorMessages :messages="warehouseForm.errors.name" />
                    </div>

                    <!-- Address -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.address') }}</FormLabel>
                        <FormTextarea v-model="warehouseForm.address" placeholder="Enter Address" />
                        <FormErrorMessages :messages="warehouseForm.errors.address" />
                    </div>

                    <!-- City -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.city') }}</FormLabel>
                        <FormInput v-model="warehouseForm.city" type="text" placeholder="Enter City" />
                        <FormErrorMessages :messages="warehouseForm.errors.city" />
                    </div>

                    <!-- Contact -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.contact') }}</FormLabel>
                        <FormInput v-model="warehouseForm.contact" type="text" placeholder="Enter Contact" />
                        <FormErrorMessages :messages="warehouseForm.errors.contact" />
                    </div>
                </div>
            </template>

            <!-- Card 3: Additional Info -->
            <template #card-items-2>
                <div class="p-5">
                    <!-- Remarks -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.remarks') }}</FormLabel>
                        <FormTextarea v-model="warehouseForm.remarks" placeholder="Enter Remarks" rows="3" />
                        <FormErrorMessages :messages="warehouseForm.errors.remarks" />
                    </div>

                    <!-- Status -->
                    <div class="pb-4">
                        <FormLabel>{{ t('views.warehouse.fields.status') }}</FormLabel>
                        <FormSelect v-model="warehouseForm.status">
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </FormSelect>
                        <FormErrorMessages :messages="warehouseForm.errors.status" />
                    </div>
                </div>
            </template>

            <!-- Submit and Reset Buttons -->
            <template #card-items-button>
                <div class="flex gap-4">
                    <Button type="submit" href="#" variant="primary" class="w-28 shadow-md" :disabled="warehouseForm.validating || warehouseForm.hasErrors">
                        <Lucide v-if="warehouseForm.validating" icon="Loader" class="animate-spin" />
                        <template v-else>
                            {{ t("components.buttons.submit") }}
                        </template>
                    </Button>
                    <Button type="button" href="#" variant="soft-secondary" class="w-28 shadow-md" @click="resetForm">
                        {{ t("components.buttons.reset") }}
                    </Button>
                </div>
            </template>
        </TwoColumnsLayout>
    </form>
</template>

