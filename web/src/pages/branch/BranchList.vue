<script setup lang="ts">
// #region Imports
import { onMounted, ref, computed } from "vue";
import DataList from "@/components/DataList";
import { useI18n } from "vue-i18n";
import Button from "@/components/Base/Button";
import Lucide from "@/components/Base/Lucide";
import Table from "@/components/Base/Table";
import BranchService from "@/services/BranchService";
import { Branch } from "@/types/models/Branch";
import { Collection } from "@/types/resources/Collection";
import { DataListEmittedData } from "@/components/DataList/DataList.vue";
import { ServiceResponse } from "@/types/services/ServiceResponse";
import { Resource } from "@/types/resources/Resource";
import { ReadAnyRequest } from "@/types/services/ServiceRequest";
import { useRouter } from "vue-router";
import { Dialog } from "@/components/Base/Headless";
import { useSelectedUserLocationStore } from "@/stores/selected-user-location";
import { ErrorCode } from "@/types/enums/ErrorCode";
import { ViewMode } from "@/types/enums/ViewMode";
import { NotificationData } from "@/types/models/NotificationData";
import { type AlertPlaceholderProps } from "@/components/AlertPlaceholder/AlertPlaceholder.vue";
// #endregion

// #region Interfaces
// #endregion

// #region Declarations
const { t } = useI18n();
const router = useRouter();
const branchServices = new BranchService();
const selectedUserLocationStore = useSelectedUserLocationStore();
// #endregion

// #region Props, Emits
const emits = defineEmits(['mode-state', 'loading-state', 'update-profile', 'show-alertplaceholder', 'show-notification']);
// #endregion

// #region Refs
const datalistErrors = ref<Record<string, Array<string>> | null>(null);
const deleteUlid = ref<string>('');
const deleteModalShow = ref<boolean>(false);
const expandDetail = ref<number | null>(null);
const branchLists = ref<Collection<Array<Branch>> | null>({
  data: [],
  meta: {
    current_page: 0,
    from: null,
    last_page: 0,
    path: '',
    per_page: 0,
    to: null,
    total: 0,
  },
  links: {
    first: '',
    last: '',
    prev: null,
    next: null,
  }
});
// #endregion

// #region Computed
const isUserLocationSelected = computed(() => selectedUserLocationStore.isUserLocationSelected);
const selectedUserLocation = computed(() => selectedUserLocationStore.selectedUserLocation);
// #endregion

// #region Lifecycle Hooks
onMounted(async () => {
  emits('mode-state', ViewMode.LIST);

  if (!isUserLocationSelected.value) {
    router.push({ name: 'side-menu-error-code', params: { code: ErrorCode.USERLOCATION_REQUIRED } });
  }

  await getBranches('', true, true, 1, 10);
});
// #endregion

// #region Methods
const getBranches = async (search: string, refresh: boolean, paginate: boolean, page: number, per_page: number) => {
  emits('loading-state', true);

  let company_id = selectedUserLocation.value.company.id;

  const searchReq: ReadAnyRequest = {
    company_id: company_id,
    search: search,
    refresh: refresh,
    paginate: paginate,
    page: page,
    per_page: per_page
  };

  let result: ServiceResponse<Collection<Array<Branch>> | Resource<Array<Branch>> | null> = await branchServices.readAny(searchReq);

  if (result.success && result.data) {
    branchLists.value = result.data as Collection<Array<Branch>>;
  } else {
    showAlertPlaceholder('danger', '', result.errors as Record<string, Array<string>>);
  }

  emits('loading-state', false);
};

const onDataListChanged = async (data: DataListEmittedData) => {
  await getBranches(data.search.text, false, true, data.pagination.page, data.pagination.per_page);
}

const viewSelected = (idx: number) => {
  if (expandDetail.value === idx) {
    expandDetail.value = null;
  } else {
    expandDetail.value = idx;
  }
};

const editSelected = (itemIdx: number) => {
  if (!branchLists.value) return;

  let ulid = branchLists.value.data[itemIdx].ulid;
  router.push({ name: 'side-menu-company-branch-edit', params: { ulid: ulid } });
};

const deleteSelected = (itemIdx: number) => {
  if (!branchLists.value) return;

  let itemUlid = branchLists.value.data[itemIdx].ulid;

  deleteUlid.value = itemUlid;
  deleteModalShow.value = true;
};

const confirmDelete = async () => {
  deleteModalShow.value = false;
  emits('loading-state', true);

  let result: ServiceResponse<boolean | null> = await branchServices.delete(deleteUlid.value);

  if (result.success) {
    emits('update-profile');
    await getBranches('', true, true, 1, 10);
    showNotification(t('views.branch.alert.delete_branch.title'), t('views.branch.alert.delete_branch.content'));
  } else {
    showAlertPlaceholder('danger', '', result.errors as Record<string, Array<string>>);
  }

  emits('loading-state', false);
};

const showNotification = (pTitle: string, pContent: string) => {
  let n: NotificationData = {
    title: pTitle,
    content: pContent
  };

  emits('show-notification', n);
};

const showAlertPlaceholder = (pAlertType: 'hidden' | 'danger' | 'success' | 'warning' | 'pending' | 'dark', pTitle: string, pAlertList: Record<string, Array<string>> | null) => {
  let ap: AlertPlaceholderProps = {
    alertType: pAlertType,
    title: pTitle,
    alertList: pAlertList,
  };

  emits('show-alertplaceholder', ap);
};
// #endregion

// #region Watchers
// #endregion
</script>

<template>
  <DataList :title="t('views.company.table.title')" :enable-search="true" :can-print="true" :can-export="true"
    :pagination="branchLists ? branchLists.meta : null" @dataListChanged="onDataListChanged">
    <template #content>
      <Table class="mt-5" :hover="true">
        <Table.Thead variant="light">
          <Table.Tr>
            <Table.Th class="whitespace-nowrap">
              {{ t("views.branch.table.cols.code") }}
            </Table.Th>
            <Table.Th class="whitespace-nowrap">
              {{ t("views.branch.table.cols.name") }}
            </Table.Th>
            <Table.Th class="whitespace-nowrap">
              {{ t("views.branch.table.cols.is_main") }}
            </Table.Th>
            <Table.Th class="whitespace-nowrap">
              {{ t("views.branch.table.cols.status") }}
            </Table.Th>
            <Table.Th class="whitespace-nowrap"></Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody v-if="branchLists !== null">
          <template v-if="branchLists.data.length == 0">
            <Table.Tr class="intro-x">
              <Table.Td colspan="5">
                <div class="flex justify-center italic">{{
                  t('components.data-list.data_not_found') }}</div>
              </Table.Td>
            </Table.Tr>
          </template>
          <template v-for="(item, itemIdx) in branchLists.data" :key="item.ulid">
            <Table.Tr class="intro-x">
              <Table.Td>{{ item.code }}</Table.Td>
              <Table.Td>{{ item.name }}</Table.Td>
              <Table.Td>
                <Lucide v-if="item.is_main === true" icon="CheckCircle" />
                <Lucide v-if="item.is_main === false" icon="X" />
              </Table.Td>
              <Table.Td>
                <Lucide v-if="item.status === 'ACTIVE'" icon="CheckCircle" />
                <Lucide v-if="item.status === 'INACTIVE'" icon="X" />
              </Table.Td>
              <Table.Td>
                <div class="flex justify-end gap-1">
                  <Button variant="outline-secondary" @click="viewSelected(itemIdx)">
                    <Lucide icon="Info" class="w-4 h-4" />
                  </Button>
                  <Button variant="outline-secondary" @click="editSelected(itemIdx)">
                    <Lucide icon="Pen" class="w-4 h-4" />
                  </Button>
                  <Button variant="outline-secondary" @click="deleteSelected(itemIdx)">
                    <Lucide icon="Trash2" class="w-4 h-4 text-danger" />
                  </Button>
                </div>
              </Table.Td>
            </Table.Tr>
            <Table.Tr :class="{ 'intro-x': true, 'hidden transition-all': expandDetail !== itemIdx }">
              <Table.Td colspan="5">
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.code') }}</div>
                  <div class="flex-1">{{ item.code }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.name') }}</div>
                  <div class="flex-1">{{ item.name }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.address') }}</div>
                  <div class="flex-1">{{ item.address }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.city') }}</div>
                  <div class="flex-1">{{ item.city }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.contact') }}</div>
                  <div class="flex-1">{{ item.contact }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.is_main') }}</div>
                  <div class="flex-1">
                    <span v-if="item.is_main">{{ t('components.dropdown.values.switch.on') }}</span>
                    <span v-else>{{ t('components.dropdown.values.switch.off') }}</span>
                  </div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.remarks') }}</div>
                  <div class="flex-1">{{ item.remarks }}</div>
                </div>
                <div class="flex flex-row">
                  <div class="ml-5 w-48 text-right pr-5">{{ t('views.branch.fields.status') }}</div>
                  <div class="flex-1">
                    <span v-if="item.status === 'ACTIVE'">
                      {{ t('components.dropdown.values.statusDDL.active') }}
                    </span>
                    <span v-if="item.status === 'INACTIVE'">
                      {{ t('components.dropdown.values.statusDDL.inactive') }}
                    </span>
                  </div>
                </div>
              </Table.Td>
            </Table.Tr>
          </template>
        </Table.Tbody>
      </Table>
      <Dialog :open="deleteModalShow" @close="() => { deleteModalShow = false; }">
        <Dialog.Panel>
          <div class="p-5 text-center">
            <Lucide icon="XCircle" class="w-16 h-16 mx-auto mt-3 text-danger" />
            <div class="mt-5 text-3xl">{{ t('components.delete-modal.title') }}</div>
            <div class="mt-2 text-slate-500">
              {{ t('components.delete-modal.desc_1') }}
              <br />
              {{ t('components.delete-modal.desc_2') }}
            </div>
          </div>
          <div class="px-5 pb-8 text-center">
            <Button type="button" variant="outline-secondary" class="w-24 mr-1"
              @click="() => { deleteModalShow = false; }">
              {{ t('components.buttons.cancel') }}
            </Button>
            <Button type="button" variant="danger" class="w-24" @click="(confirmDelete)">
              {{ t('components.buttons.delete') }}
            </Button>
          </div>
        </Dialog.Panel>
      </Dialog>
    </template>
  </DataList>
</template>
