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
            :value="role.name"
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
        :modalEditRoleOpen="this.modalEditRoleOpen"
        :isChooseNewRole="this.isChooseNewRole"
        :newRole="this.newRole"
        :roleOptions="this.roleOptions"
        @handleClickCancelEditRole="handleClickCancelEditRole"
        @handleUpdateRole="handleUpdateRole"
        @handleChangeNewRoleInModal="handleChangeNewRoleInModal"
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
// import { goto } from "@/modules/core/composables"
// import { handleErrorForUser } from "@/modules/user/api/handleErrorForUser.js"
import { ref } from "vue"
import { useGlobalStore } from "@/stores/global"
import { useUserDetail } from "../use/useUserDetail"
import ModalDeleteUser from "../components/ModalDeleteUser.vue"
import ModalEditUserRole from "../components/ModalEditUserRole.vue"
import ModalEditUserDetail from "../components/ModalEditUserDetail.vue"

export default {
  name: "ViewUserDetail",

  components: {
    ModalDeleteUser,
    ModalEditUserRole,
    ModalEditUserDetail,
  },

  data() {
    const associatedUser = [
      { key: "createdAt", label: this.$t("user.label.createdAt"), value: null },
      { key: "username", label: this.$t("user.label.username"), value: "" },
      { key: "name", label: this.$t("user.label.name"), value: "" },
      { key: "firstName", label: this.$t("user.label.firstName"), value: "" },
      { key: "lastName", label: this.$t("user.label.lastName"), value: "" },
    ]

    const newRole = ref("")

    return {
      role: {},
      associatedUser,
      isLoading: true,

      // Modal edit user detail
      modalEditUserDetailOpen: false,

      // Modal delete user
      modalDeleteOpen: false,

      // Modal edit role
      modalEditRoleOpen: false,

      newRole,
      roleOptions: [
        { value: "abc-xys", name: "Admmin" },
        { value: "abc-ahihi", name: "Manager" },
      ],
    }
  },
  setup(props) {
    const globalStore = useGlobalStore()
    const { getUserDetail, updateUser } = useUserDetail()

    const user = ref({})
    return {
      user,
      globalStore,
      getUserDetail,
      updateUser,
    }
  },

  computed: {
    computedUsername() {
      if (!this.user.firstName && !this.user.lastName) {
        return "-"
      }

      return `${this.user.firstName} ${this.user.lastName}`
    },

    isChooseNewRole() {
      return this.newRole && this.role.id !== this.newRole
    },

    editableFields() {
      return ["name"]
    },
  },
  mounted() {
    this.getUser()
  },

  methods: {
    async getUser() {
      this.isLoading = true

      const { userId } = this.$route.params
      this.user = await this.getUserDetail(userId)
      this.role = this.user.role
      this.mapUserToAssociatedUser(this.user)
      this.isLoading = false
    },

    // Map object to array fields list
    mapUserToAssociatedUser(user) {
      this.associatedUser = [
        { key: "createdAt", label: this.$t("user.label.createdAt"), value: dayjs(user.createdAt) },
        { key: "username", label: this.$t("user.label.username"), value: user.username },
        { key: "name", label: this.$t("user.label.name"), value: `${user.firstName} ${user.lastName}` },
        { key: "firstName", label: this.$t("user.label.name"), value: user.firstName },
        { key: "lastName", label: this.$t("user.label.lastName"), value: user.lastName },
      ]
    },

    // Check is editable field
    isEditableField(field) {
      return this.editableFields.includes(field)
    },

    // -======== Edit user role ===

    // Check is user able to edit role
    canEditRole() {
      return true
    },

    // Click edit permimssion button
    async handleClickEditRole() {
      // await this.getrole()
      this.modalEditRoleOpen = true
    },

    // Click cancel edit role on modal
    handleClickCancelEditRole() {
      this.modalEditRoleOpen = false
    },

    handleChangeNewRoleInModal(newRole) {
      this.newRole = newRole
    },

    // Finially, handle edit Role click
    handleUpdateRole() {},

    // ======== Edit user data =====
    handleClickAssociatedUser(key) {
      if (!this.isEditableField(key)) {
        return
      }

      this.modalEditUserDetailOpen = true
    },

    // Update user detail
    handleUpdateUserDetail(data) {
      this.modalEditUserDetailOpen = false
      this.updateUser(this.user.id, data)
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
    handleDeleteUser() {
      alert("hi")
    },
  },
}
</script>
