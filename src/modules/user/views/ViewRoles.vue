<template>
  <RLayout>
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("user.users") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>
    <UserSubMenu />
    <EcFlex class="justify-end mt-4">
      <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="plus-circle" @click="handleCreate()">
        {{ $t("user.button.createRole") }}
      </EcButton>
    </EcFlex>

    <RTable :list="permissionGroups" :isLoading="isLoading" class="mt-6 lg:mt-10">
      <template #header>
        <RTableHeaderRow>
          <RTableHeaderCell v-for="(h, idx) in headerData" :key="idx">
            {{ h.label }}
          </RTableHeaderCell>
          <RTableHeaderCell variant="gradient" />
        </RTableHeaderRow>
      </template>
      <template v-slot="{ item, last, first }">
        <RTableRow>
          <RTableCell :class="{ 'rounded-tl-lg': first, 'rounded-bl-lg': last }">
            {{ formatData(item.name) }}
          </RTableCell>
          <RTableCell>
            {{ formatData(item.description) }}
          </RTableCell>
          <RTableCell class="pr-20">
            {{ formatData(item.createdAt, dateTimeFormat) }}
          </RTableCell>
          <RTableCell variant="gradient" :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }">
            <EcFlex class="items-center justify-center h-full">
              <EcButton variant="transparent-rounded" @click="goToPermissionGroupDetail(item.id)">
                <EcIcon class="text-c0-300" icon="Eye" width="20" height="20" />
              </EcButton>
              <EcButton variant="transparent-rounded" @click="handleDelete(item.id)">
                <EcIcon class="text-cError-500" icon="trash" width="20" height="20" />
              </EcButton>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
  </RLayout>

  <!-- Modal Delete Permission Group -->
  <teleport to="#layer3">
    <EcModalSimple :isVisible="showDeleteRoleModal" variant="center-3xl">
      <EcBox class="text-center">
        <EcFlex class="justify-center">
          <EcIcon class="text-cError-500" width="64" height="64" icon="TrashAlt" />
        </EcFlex>
        <EcBox>
          <EcHeadline as="h2" variant="h2" class="text-4xl text-cError-500">
            {{ $t("user.confirmToDelete") }}
          </EcHeadline>
          <EcText class="mt-2 text-c0-500">
            {{ $t("user.cfDeleteNote2") }}
          </EcText>
        </EcBox>
        <EcFlex v-if="!isDeletePolicyLoading" class="justify-center mt-10">
          <EcButton variant="warning" @click="deletePermissionGroup">
            {{ $t("user.button.delete") }}
          </EcButton>
          <EcButton class="ml-3" variant="tertiary-outline" @click="toggleDeleteModal">
            {{ $t("user.button.cancel") }}
          </EcButton>
        </EcFlex>
        <EcFlex v-else class="justify-center h-10 mt-10">
          <EcSpinner variant="secondary-lg" />
        </EcFlex>
      </EcBox>
    </EcModalSimple>
  </teleport>
</template>

<script>
import UserSubMenu from "../components/UserSubMenu"
import { formatData } from "@/modules/core/composables"
// import { apiPermissionGroups, apiDeletePermissionGroup } from "@covergo/cover-composables"
import { handleErrorForUser } from "../api"

export default {
  name: "ViewRoles",
  components: { UserSubMenu },
  data() {
    return {
      isLoading: false,
      permissionGroups: [],
      selectedId: null,
      showDeleteRoleModal: false,
      headerData: [
        { label: this.$t("user.label.name") },
        { label: this.$t("user.label.description") },
        { label: this.$t("user.label.createdAt") },
      ],
    }
  },
  computed: {
    dateTimeFormat() {
      return this.$store.state.dateTimeFormat
    },
  },
  mounted() {
    this.fetchPermissionGroups()
  },
  methods: {
    formatData,
    async fetchPermissionGroups() {
      // this.isLoading = true
      // const { error, data } = await apiPermissionGroups({ fetcher })
      // if (error) {
      //   handleErrorForUser({ error, $t: this.$t })
      //   this.isLoading = false
      //   return
      // }

      // this.permissionGroups = data.reverse()
      this.isLoading = false
    },
    async deletePermissionGroup() {
      // const variables = {
      //   id: this.selectedId,
      // }
      // const { error } = await apiDeletePermissionGroup({ variables, fetcher })
      const error = null
      if (error) {
        handleErrorForUser({ error, $t: this.$t })
        this.isLoading = false
        return
      }

      this.toggleDeleteModal()
      this.selectedId = null
      this.fetchPermissionGroups()
    },
    goToPermissionGroupDetail(id) {
      this.$router.push({
        name: "ViewRoleDetail",
        params: { id },
      })
    },
    handleCreate() {
      this.$router.push({ name: "ViewRoleNew" })
    },
    handleDelete(id) {
      this.selectedId = id
      this.toggleDeleteModal()
    },
    toggleDeleteModal() {
      this.showDeleteRoleModal = !this.showDeleteRoleModal
    },
  },
}
</script>
