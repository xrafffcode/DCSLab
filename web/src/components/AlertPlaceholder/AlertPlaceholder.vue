<script setup lang="ts">
import { toRef, computed } from "vue";
import Alert from "@/components/Base/Alert";
import Lucide from "@/components/Base/Lucide";
import { useI18n } from "vue-i18n";

export interface AlertPlaceholderProps {
    alertType: 'hidden' | 'danger' | 'success' | 'warning' | 'pending' | 'dark',
    alertList: Record<string, Array<string>> | null,
    title: string,
}

const { t } = useI18n();

const props = withDefaults(defineProps<AlertPlaceholderProps>(), {
    alertType: 'hidden',
    alertList: null,
    title: '',
});

const emits = defineEmits(['dismiss']);

const alertType = toRef(props, 'alertType');
const alertList = toRef(props, 'alertList');
const title = toRef(props, 'title');

const computedVariant = computed(() => {
    if (alertType.value == 'danger') return 'soft-danger';
    if (alertType.value == 'success') return 'soft-success';
    if (alertType.value == 'warning') return 'soft-warning';
    if (alertType.value == 'pending') return 'soft-pending';
    if (alertType.value == 'dark') return 'soft-dark';
    
    return 'soft-danger';
});

const dismiss = () => {
    emits('dismiss');
}
</script>

<template>
    <div v-if="alertType != 'hidden'" class="mt-4">
        <Alert :variant="computedVariant" class="flex items-center mb-2">
            <div class="flex flex-col">
                <div class="flex items-center">
                    <Lucide icon="AlertCircle" class="w-6 h-6 mr-2" />
                    {{ title != '' ? title : t('components.alert-placeholder.default_title') }}
                    <Alert.DismissButton type="button" class="text-white" aria-label="Close" @click="dismiss">
                        <Lucide icon="X" class="w-4 h-4" />
                    </Alert.DismissButton>
                </div>
                <div class="mt-3 ml-12">
                    <ul class="list-disc">
                        <template v-for="(e, eIdx) in alertList" :key="eIdx">
                            <li v-if="(typeof e === 'string')">{{ e }}</li>
                            <li v-for="(ee, eeIdx) in e" v-else-if="(typeof e === 'object' && Array.isArray(e))"
                                :key="eeIdx" class="ml-5">{{ ee }}</li>
                        </template>
                    </ul>
                </div>
            </div>
        </Alert>
    </div>
</template>