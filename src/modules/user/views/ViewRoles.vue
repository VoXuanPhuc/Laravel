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

    <RTable :list="roles" :isLoading="isLoading" class="mt-6 lg:mt-10">
      <template #header>
        <RTableHeaderRow>
          <RTableHeaderCell v-for="(h, idx) in headerData" :key="idx" class="font-semibold">
            {{ h.label }}
          </RTableHeaderCell>
          <RTableHeaderCell variant="gradient" />
        </RTableHeaderRow>
      </template>
      <template v-slot="{ item, last, first }">
        <RTableRow>
          <RTableCell :class="{ 'rounded-tl-lg': first, 'rounded-bl-lg': last }">
            {{ formatData(item.label) }}
          </RTableCell>
          <RTableCell>
            {{ formatData(item.description) }}
          </RTableCell>
          <RTableCell class="pr-20">
            {{ formatData(item.created_at, dateTimeFormat) }}
          </RTableCell>
          <RTableCell variant="gradient" :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }">
            <EcFlex class="items-center justify-center h-full">
              <EcButton variant="transparent-rounded" @click="goToRoleDetail(item.uid)">
                <EcIcon class="text-c0-300" icon="Eye" width="20" height="20" />
              </EcButton>
              <EcButton variant="transparent-rounded" @click="handleClickIconDelete(item.uid)">
                <EcIcon class="text-cError-500" icon="trash" width="20" height="20" />
              </EcButton>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
  </RLayout>

  <!-- Modal Delete Role -->
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
        <EcFlex v-if="!isDeleteRoleLoading" class="justify-center mt-10">
          <EcButton variant="warning" @click="handleDeleteRole">
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
import { useRoleList } from "../use/useRoleList"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewRoles",
  components: { UserSubMenu },
  data() {
    return {
      isLoading: false,
      isDeleteRoleLoading: false,
      roles: [],
      selectedId: null,
      showDeleteRoleModal: false,
      headerData: [
        { label: this.$t("role.label.name") },
        { label: this.$t("role.label.description") },
        { label: this.$t("role.label.createdAt") },
      ],
    }
  },

  setup() {
    const globalStore = useGlobalStore()
    const { getRoles, deleteRole } = useRoleList()

    return {
      globalStore,
      getRoles,
      deleteRole,
    }
  },
  computed: {
    dateTimeFormat() {
      return this.globalStore.getDateTimeFormat
    },
  },
  mounted() {
    this.fetchRoles()
  },
  methods: {
    formatData,
    async fetchRoles() {
      this.isLoading = true

      const data = await this.getRoles()

      this.roles = data
      this.isLoading = false
    },

    /**
     * Handle the event when user click delete on modal
     */
    async handleDeleteRole() {
      await this.deleteRole(this.selectedId)
      this.toggleDeleteModal()
      this.selectedId = null
      this.fetchRoles()
    },

    /**
     * Go to role detail
     * @param {*} uid
     */
    goToRoleDetail(uid) {
      this.$router.push({
        name: "ViewRoleDetail",
        params: { uid },
      })
    },
    handleCreate() {
      this.$router.push({ name: "ViewRoleNew" })
    },
    handleClickIconDelete(uid) {
      this.selectedId = uid
      this.toggleDeleteModal()
    },
    toggleDeleteModal() {
      this.showDeleteRoleModal = !this.showDeleteRoleModal
    },
  },
}
</script>
