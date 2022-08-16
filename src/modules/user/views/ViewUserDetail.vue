<template>
  <RLayout :title="computedUsername">
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <!-- Role -->
        <EcBox variant="card-1" class="mb-8">
          <EcHeadline as="h3" variant="h3" class="px-3 text-c1-800">
            {{ $t("user.role") }}
          </EcHeadline>

          <REditableField
            :canDelete="false"
            :label="$t('user.label.role')"
            :value="currentRole?.label"
            :isReadOnly="!canEditRole"
            @edit="handleClickEditRole()"
          />
        </EcBox>

        <!-- User Entity -->
        <EcBox variant="card-1" class="mb-4">
          <EcHeadline as="h3" variant="h3" class="px-3 mb-4 text-c1-800">
            {{ $t("user.associatedEntity") }}
          </EcHeadline>

          <REditableField
            v-for="(item, index) in associatedUser"
            :key="item.key"
            :label="item.label"
            :value="item.value"
            :inputCustomClass="this.isEditableField(item.key) ? 'text-c1-800 cursor-pointer' : ''"
            :canEdit="this.isEditableField(item.key)"
            :canDelete="false"
            :class="{ 'mb-6': index !== associatedUser.length - 1 }"
            @value-click="handleClickAssociatedUser(item.key)"
            @click="handleClickAssociatedUser(item.key)"
          />
        </EcBox>
      </template>
      <template #right>
        <!-- Delete User -->
        <EcBox variant="card-1" class="mb-8">
          <EcHeadline as="h3" variant="h3" class="px-5 text-c1-800">
            {{ $t("user.deleteUser") }}
          </EcHeadline>
          <EcText class="px-5 my-6 leading-snug text-c1-800">
            {{ $t("user.deleteFullNote") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="trash" @click="handleClickDelete()">
            {{ $t("user.button.deleteUser") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modal Delete User -->
    <teleport to="#layer2">
      <ModalDeleteUser
        :username="computedUsername"
        :modalDeleteOpen="this.modalDeleteOpen"
        @handleClickCancelDelete="handleClickCancelDelete"
        @handleDeleteUser="handleDeleteUser"
      />
    </teleport>

    <!-- Modal Edit Role -->
    <teleport to="#layer2">
      <ModalEditUserRole
        :username="this.computedUsername"
        :modalEditRoleOpen="this.modalEditRoleOpen"
        :roleOptions="this.roleOptions"
        :currentRole="this.currentRole"
        @handleClickCancelEditRole="handleClickCancelEditRole"
        @handleUpdateRole="handleUpdateRole"
      />
    </teleport>

    <!-- Modal Edit User detail -->
    <teleport to="#layer2">
      <ModalEditUserDetail
        v-model:firstName="this.user.firstName"
        v-model:lastName="this.user.lastName"
        :modalEditUserDetailOpen="modalEditUserDetailOpen"
        @handleClickCancelEditUserDetail="handleClickCancelEditUserDetail"
        @handleUpdateUserDetail="handleUpdateUserDetail"
      />
    </teleport>
  </RLayout>
</template>

<script>
import dayjs from "dayjs"
import { ref } from "vue"
import { useGlobalStore } from "@/stores/global"
import { useUserDetail } from "../use/useUserDetail"
import ModalDeleteUser from "../components/ModalDeleteUser.vue"
import ModalEditUserRole from "../components/ModalEditUserRole.vue"
import ModalEditUserDetail from "../components/ModalEditUserDetail.vue"
import EcText from "@/components/EcText/index.vue"
import EcBox from "@/components/EcBox/index.vue"
import { useRoleList } from "../use/useRoleList"

export default {
  name: "ViewUserDetail",

  components: {
    ModalDeleteUser,
    ModalEditUserRole,
    ModalEditUserDetail,
    EcText,
    EcBox,
  },

  data() {
    const associatedUser = [
      { key: "status", label: this.$t("user.label.status"), value: null },
      { key: "createdAt", label: this.$t("user.label.createdAt"), value: null },
      { key: "username", label: this.$t("user.label.username"), value: "" },
      { key: "name", label: this.$t("user.label.name"), value: "" },
      { key: "firstName", label: this.$t("user.label.firstName"), value: "" },
      { key: "lastName", label: this.$t("user.label.lastName"), value: "" },
      { key: "isActive", label: this.$t("user.label.active"), value: "" },
    ]

    return {
      currentRole: {},
      associatedUser,
      isLoading: true,

      // Modal edit user detail
      modalEditUserDetailOpen: false,

      // Modal delete user
      modalDeleteOpen: false,

      // Modal edit role
      modalEditRoleOpen: false,

      roleOptions: [],
    }
  },
  setup(props) {
    const globalStore = useGlobalStore()
    const { getUserDetail, updateUser, deleteUser, assignRole } = useUserDetail()
    const { getRoles } = useRoleList()

    const user = ref({})
    return {
      user,
      globalStore,
      getUserDetail,
      updateUser,
      deleteUser,
      getRoles,
      assignRole,
    }
  },

  computed: {
    /** Computed username */
    computedUsername() {
      if (!this.user.firstName || !this.user.lastName) {
        return "-"
      }

      return `${this.user.firstName} ${this.user.lastName}`
    },

    /** Editable field */
    editableFields() {
      return ["name"]
    },
  },
  mounted() {
    this.getUser()
    this.fetchRoles()
  },

  methods: {
    /**
     * Get user detail
     */
    async getUser() {
      this.isLoading = true

      const { userId } = this.$route.params
      this.user = await this.getUserDetail(userId)
      if (this.user) {
        this.currentRole = this.user.role
        this.mapUserToAssociatedUser(this.user)
      }

      this.isLoading = false
    },

    /**
     * Map object to array fields list
     * @param {Map object to array fields list} user
     */
    mapUserToAssociatedUser(user) {
      this.associatedUser = [
        { key: "isActive", label: this.$t("user.label.active"), value: user.isActive ? "Enabled" : "Disabled" },
        { key: "status", label: this.$t("user.label.status"), value: user.status },
        { key: "createdAt", label: this.$t("user.label.createdAt"), value: dayjs(user.createdAt) },
        { key: "username", label: this.$t("user.label.username"), value: user.username },
        { key: "name", label: this.$t("user.label.name"), value: this.computedUsername },
        { key: "firstName", label: this.$t("user.label.name"), value: user.firstName },
        { key: "lastName", label: this.$t("user.label.lastName"), value: user.lastName },
      ]
    },

    /**
     * Check is editable field
     * @param {*} field
     */
    isEditableField(field) {
      return this.editableFields.includes(field)
    },

    // ======== Edit user role ===

    /**
     * Check is user able to edit role
     */
    canEditRole() {
      return true
    },

    /**
     * Click edit permimssion button
     */
    async handleClickEditRole() {
      // await this.getrole()
      this.modalEditRoleOpen = true
    },

    /**
     * Click cancel edit role on modal
     */
    handleClickCancelEditRole() {
      this.modalEditRoleOpen = false
    },

    /**
     *
     * @param {*} selectedRole
     */
    async handleUpdateRole(selectedRoleUid) {
      this.isLoading = true

      await this.assignRole(this.user.id, selectedRoleUid)
      await this.getUser()
      this.modalEditRoleOpen = false
      this.isLoading = false
    },

    // ======== Edit user data =====
    handleClickAssociatedUser(key) {
      if (!this.isEditableField(key)) {
        return
      }

      this.modalEditUserDetailOpen = true
    },

    // Update user detail
    async handleUpdateUserDetail(data) {
      this.modalEditUserDetailOpen = false
      this.isLoading = true
      await this.updateUser(this.user.id, data)
      await this.getUser()
    },

    // Cancel edit user data
    handleClickCancelEditUserDetail() {
      this.modalEditUserDetailOpen = false
    },

    // ======== Open modal delete delete
    handleClickDelete() {
      this.modalDeleteOpen = true
    },

    // Cancel deletet user
    handleClickCancelDelete() {
      this.modalDeleteOpen = false
    },

    // User confirmed delete
    async handleDeleteUser() {
      this.modalDeleteOpen = false
      this.isLoading = true
      await this.deleteUser(this.user.id)

      this.isLoading = false
    },

    /**
     * Fetch roles
     */
    async fetchRoles() {
      this.roleOptions = await this.getRoles()
    },
  },
}
</script>
