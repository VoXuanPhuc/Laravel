<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("user.users") }}
        </EcHeadline>
      </EcFlex>

      <!-- Search box -->
      <EcFlex v-show="true" class="items-center justify-end flex-grow w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          placeholder="Search"
          class="w-full md:max-w-xs"
          @search="handleSearch()"
          @clear-search="handleClearSearch()"
          @update:modelValue="onFilter"
        />
        <EcButton style="padding: 0.75rem" class="ml-4" variant="secondary" @click="toggleFilter">
          <EcIcon icon="Adjustments" />
        </EcButton>
      </EcFlex>
    </EcFlex>
    <!-- User filter -->
    <UserFilter
      v-if="isFilter"
      v-model:createdAt="createdAt"
      @filter:apply="handleFilter"
      @filter:clear="handleClearFilter"
      :roleOptions="roleOptions"
    />
    <UserSubMenu />
    <EcFlex class="justify-end mt-2">
      <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="plus-circle" @click="handleClickAddUser()">
        {{ $t("user.button.addUser") }}
      </EcButton>
    </EcFlex>
    <!-- Table -->
    <RTable :list="users" :isLoading="isLoading" class="mt-2 lg:mt-4">
      <template #header>
        <RTableHeaderRow>
          <RTableHeaderCell v-for="(h, idx) in headerData" :key="idx" class="text-cBlack font-semibold">
            {{ h.label }}
          </RTableHeaderCell>
          <RTableHeaderCell variant="gradient" />
        </RTableHeaderRow>
      </template>
      <template v-slot="{ item, last, first }">
        <RTableRow class="hover:bg-c0-100">
          <!-- Username -->
          <RTableCell :class="{ 'rounded-tl-lg': first, 'rounded-bl-lg': last }">
            {{ formatData(item.username) }}
          </RTableCell>
          <RTableCell>
            <EcText class="w-32" :variant="getEmailConfirmationStatusType(item.isEmailConfirmed)">
              {{ getEmailConfirmationStatus(item.isEmailConfirmed) }}
            </EcText>
          </RTableCell>

          <!-- Confirmation Status -->
          <RTableCell>
            <EcText class="w-64" :variant="getStatusType(item.status)">
              {{ item.status }}
            </EcText>
          </RTableCell>

          <!-- Is Active -->
          <RTableCell>
            <EcText class="w-32" :variant="getStatusType(item.isActive)">
              {{ item.isActive ? "Enabled" : "Disabled" }}
            </EcText>
          </RTableCell>

          <!-- Datetime -->
          <RTableCell class="pr-20">
            {{ formatData(item.createdAt, dateTimeFormat) }}
          </RTableCell>

          <!-- Action -->
          <RTableCell :isTruncate="false" variant="gradient" :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }">
            <EcFlex class="items-center justify-center h-full">
              <RTableAction class="w-30">
                <!-- View action -->
                <EcFlex class="items-center px-4 py-2 cursor-pointer text-cBlack hover:bg-c0-100" @click="handleClickView(item)">
                  <EcIcon class="mr-3" icon="Eye" />
                  <EcText class="font-medium">{{ $t("user.button.view") }}</EcText>
                </EcFlex>

                <!-- Re-invite action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickReinvite(item)"
                >
                  <EcIcon class="mr-3" icon="MailOpen" />
                  <EcText class="font-medium">{{ $t("user.button.reInvite") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
    <EcFlex class="flex-col mt-8 sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus class="mb-4 sm:mb-0" :currentPage="currentPage" :totalCount="totalItems" :limit="limit" />
      <RPagination v-model="currentPage" :totalItems="totalItems" :itemPerPage="limit" @input="setPage($event)" />
    </EcFlex>
  </RLayout>
</template>

<script>
import UserSubMenu from "@/modules/user/components/UserSubMenu.vue"
import UserFilter from "@/modules/user/components/UserFilter"
import { formatData, goto } from "@/modules/core/composables"
import debounce from "lodash.debounce"
import { handleErrorForUser } from "@/modules/user/api/handleErrorForUser.js"
import { useUserList } from "@/modules/user/use/useUserList"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewUserList",
  components: { UserSubMenu, UserFilter },
  setup() {
    const globalStore = useGlobalStore()
    const limit = 2
    const totalItems = 3
    const currentPage = 1

    const { fetchUserList, searchQuery, entityName, permissionGroup } = useUserList()

    return {
      globalStore,
      fetchUserList,
      limit,
      currentPage,
      searchQuery,
      entityName,
      totalItems,
      permissionGroup,
    }
  },
  data() {
    return {
      isSearched: false,
      headerData: [
        { label: this.$t("user.label.emailOrUsername") },
        { label: this.$t("user.label.emailConfirmedStatus") },
        { label: this.$t("user.label.status") },
        { label: this.$t("user.label.active") },
        { label: this.$t("user.label.createdAt") },
      ],
      isLoading: false,
      totalPages: 1,
      roleOptions: [],
      isFilter: false,
      users: [],
    }
  },

  async mounted() {
    this.isLoading = true
    this.getPermissionGroup()
    await this.getUsers()
    this.isLoading = false
  },

  computed: {
    clientId() {
      return this.globalStore.getClientId
    },
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
    },
  },

  watch: {
    currentPage() {},
  },

  methods: {
    async getUsers() {
      this.users = await this.fetchUserList()
    },

    formatData,
    async getPermissionGroup() {
      const data = [{}]
      const error = null
      if (error) handleErrorForUser({ error, $t: this.$t })
      else {
        this.roleOptions = data.map((x) => ({
          name: x.name,
          value: x.id,
        }))
      }
    },

    // handleFilter
    handleFilter(data) {
      Object.keys(data).forEach((element) => {
        this[element] = data?.[element]
      })
    },
    onFilter: debounce(function () {
      this.currentPage = 1
    }, 400),

    // handleSearch
    handleSearch() {
      this.currentPage = 1
    },

    // handleClearSearch
    handleClearSearch() {
      this.searchQuery = ""
      this.isSearch = false

      this.currentPage = 1
    },
    handleClearFilter(data) {
      Object.keys(data).forEach((element) => {
        this[element] = data?.[element]
      })
    },

    handleClickAddUser() {
      // Go to User New Page
      goto("ViewUserNew")
    },

    toggleFilter() {
      this.isFilter = !this.isFilter
    },

    getEmailConfirmationStatus(value) {
      return value === "true" ? "Confirmed" : "UnConfirmed"
    },

    getEmailConfirmationStatusType(value) {
      return value === "true" ? "pill-cSuccess-inv" : "pill-c1"
    },

    getStatusType(value) {
      // if (value === "pending") return "pill-c1"
      if (value === "UNCONFIRMED") {
        return "pill-disabled"
      }
      if (value === "CONFIRMED") {
        return "pill-cSuccess-inv"
      }
      return value ? "pill-cSuccess-inv" : "pill-c1"
    },

    getStatusLabel(value) {
      return value ? "Active" : "Pending"
    },

    handleClickView(item) {
      // Go to User Detail Page
      goto("ViewUserDetail", {
        params: {
          userId: item.id,
        },
      })
    },

    async handleClickReinvite(item) {
      // const clientId = this.clientId
      // const variables = {
      //   clientId,
      //   loginId: item.id,
      // }
      // const { error } = await apiResendConfirmationEmail({ variables, fetcher })
      const error = null
      if (error) {
        handleErrorForUser({ error, $t: this.$t })
      } else {
        this.globalStore.addSuccessToastMessage(this.$t("user.message.reinviteSuccess"))
      }
    },
  },
}
</script>
