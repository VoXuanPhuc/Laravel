<template>
  <EcBox variant="card-2" class="mb-4 mr-3 lg:inline-flex lg:flex-grow lg:w-auto" style="min-width: 12rem">
    <EcFlex class="relative justify-center items-center p-4 rounded-full w-32 h-auto overflow-hidden">
      <img :src="organization.logo_path" alt="{{ organization.name }}" />
    </EcFlex>
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <EcText class="font-medium text-2xl text-cBlack">
        {{ organization.name }}
      </EcText>

      <EcText class="font-medium text-c0-500 text-sm mt-2">
        Status:
        <span :class="statusText(organization.is_active)">{{ organization.is_active ? "Active" : "Inactive" }}</span>
      </EcText>
      <EcText class="font-medium text-c0-500 text-sm mt-2">
        Created at: {{ globalStore.formatDate(organization.created_at) }}</EcText
      >

      <!-- Actions -->
      <EcFlex class="w-full items-center mt-2">
        <!-- Edit -->
        <EcBox v-if="organization.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickEdit" title="Edit">
            <EcIcon icon="Pencil" width="20" height="20" class="text-cError-500" />
          </EcButton>
        </EcBox>

        <!-- View -->

        <EcBox v-if="organization.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickManageOrganization" title="Manage Organization">
            <EcIcon class="text-c0-500" icon="Eye" width="20" height="20" />
          </EcButton>
        </EcBox>

        <!-- End view -->
      </EcFlex>
    </EcBox>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "OrganizationListCardItem",
  props: {
    organization: {
      type: Object,
      default: () => {},
    },
  },
  setup() {
    const globalStore = useGlobalStore()

    return {
      globalStore,
    }
  },
  methods: {
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },

    handleClickEdit() {
      goto("ViewOrganizationDetail", {
        params: {
          organizationUid: this.organization?.uid,
        },
      })
    },
    handleClickManageOrganization() {
      goto("ViewOrganizationManagement", {
        params: {
          organizationUid: this.organization?.uid,
        },
      })
    },
  },
}
</script>
