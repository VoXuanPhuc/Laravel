<template>
  <RLayout :title="organizationName">
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <REntityCard :entity="organizationInfo" typename="organization" @update:credentials="fetchOrganization()" />
      </template>
      <template #right>
        <!-- Delete organization -->
        <EcBox variant="card-1" class="mb-8">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("organization.deleteOrganization") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("organization.noteDeleteOrganization") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("organization.deleteOrganization") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modals -->
    <teleport to="#layer2">
      <!-- Modal Delete -->
      <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
        <EcBox class="text-center">
          <EcFlex class="justify-center">
            <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
          </EcFlex>
          <EcBox>
            <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
              {{ $t("organization.confirmToDelete") }}
            </EcHeadline>
            <EcText class="text-c0-500 mt-4">
              {{ $t("organization.youAreGoingToDelete") }}
              {{ $t("organization.organization") }}
              <EcText class="inline text-c1-500">{{ organizationName }}</EcText>
            </EcText>
            <EcText class="text-c0-500 mt-2">
              {{ $t("organization.actionCannotBeRevert") }}
            </EcText>
          </EcBox>
          <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
            <EcButton variant="warning" @click="handleDeleteOrganization">
              {{ $t("organization.delete") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
              {{ $t("organization.cancel") }}
            </EcButton>
          </EcFlex>
          <EcFlex v-else class="items-center justify-center mt-10 h-10">
            <EcSpinner type="dots" />
          </EcFlex>
        </EcBox>
      </EcModalSimple>
    </teleport>
  </RLayout>
</template>

<script>
import { useOrganizationDetail } from "./../use/useOrganizationDetail"

export default {
  name: "ViewOrganizationDetail",
  setup() {
    const { state, send, organizationName, organizationInfo } = useOrganizationDetail()
    return {
      state,
      send,
      organizationName,
      organizationInfo,
    }
  },
  computed: {
    isLoading() {
      return this.state.matches("reading.fetching")
    },
    isModalDeleteOpen() {
      return this.state.matches("deleting")
    },
    isDeleteLoading() {
      return this.state.matches("deleting.fetching")
    },
  },
  methods: {
    fetchOrganization() {},
    handleOpenDeleteModal() {},
    handleCloseDeleteModal() {},
    handleDeleteOrganization() {},
  },
}
</script>
