<template>
  <EcBox variant="card-2" class="mb-4 mr-3 lg:inline-flex lg:flex-grow lg:w-auto" style="min-width: 12rem">
    <EcFlex class="relative justify-center items-center p-4 rounded-full w-32 h-auto overflow-hidden">
      <img :src="organization.logo_url" alt="{{ organization.name }}" />
    </EcFlex>
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <EcFlex class="items-center">
        <EcText class="font-medium text-2xl text-cBlack">
          {{ organization.name }}
        </EcText>
      </EcFlex>

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
          <EcButton
            variant="transparent-rounded"
            @click="handleClickEdit"
            v-tooltip="{ text: 'Edit Organization', position: 'bottom' }"
          >
            <EcIcon icon="Pencil" width="20" height="20" class="text-cError-500" />
          </EcButton>
        </EcBox>

        <!-- View -->

        <EcBox v-if="organization.name && !isLandlord" class="ml-2">
          <EcButton
            variant="transparent-rounded"
            @click="handleClickManageOrganization"
            v-tooltip="{ text: 'Manage Organization', position: 'bottom' }"
          >
            <EcIcon class="text-c0-500" icon="Eye" width="20" height="20" />
          </EcButton>
        </EcBox>

        <!-- End view -->

        <!-- View -->

        <!-- <EcBox v-if="organization.name && !isLandlord" class="ml-2">
          <EcButton
            variant="transparent-rounded"
            @click="handleClickViewActivities"
            v-tooltip="{ text: organization.name + '\'s activities', position: 'bottom' }"
          >
            <EcIcon class="text-c0-500" icon="Activity" width="20" height="20" />
          </EcButton>
        </EcBox> -->

        <!-- End view -->
      </EcFlex>
    </EcBox>

    <!-- Landlord indicator or link to client portal -->
    <EcBox>
      <!-- Lock Icon for landlord or go to client portal for tenant -->
      <EcIcon v-if="isLandlord" icon="LockClosed" class="-mt-4 text-cError-500" />
      <EcBox v-else>
        <!-- Open link -->
        <EcButton
          class="-mt-4 h-1 w-1"
          variant="transparent"
          :href="'https://' + organization?.domain"
          target="_blank"
          v-tooltip="{ text: 'Go to client portal' }"
        >
          <EcIcon icon="OpenUp" width="18" height="18" />
        </EcButton>

        <!-- Archived -->
        <EcButton
          v-if="organization.is_archived"
          class="mt-1 w-1 hover:cursor-auto"
          variant="transparent"
          v-tooltip="{ text: 'Organization has been archived' }"
        >
          <EcIcon icon="Archive" width="20" height="20" />
        </EcButton>
      </EcBox>
    </EcBox>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import EcIcon from "@/components/EcIcon/index.vue"
import * as helpers from "@/readybc/composables/helpers/helpers"
import EcBox from "@/components/EcBox/index.vue"

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
  computed: {
    isLandlord() {
      return this.organization?.landlord === true
    },
  },
  methods: {
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },
    /**
     * Edit organization
     */
    handleClickEdit() {
      helpers.setTenantData(this.organization)

      goto("ViewOrganizationDetail", {
        params: {
          uid: this.organization?.uid,
        },
      })
    },
    /**
     * Manage organization
     */
    handleClickManageOrganization() {
      // Set tenant data
      helpers.setTenantData(this.organization)

      goto("ViewOrganizationManagement", {
        params: {
          organizationUid: this.organization?.uid,
        },
      })
    },
    /**
     * View Activity list
     */
    handleClickViewActivities() {
      goto("ViewActivityList")
    },
  },
  components: { EcIcon, EcBox },
}
</script>
