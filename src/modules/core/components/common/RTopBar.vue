<template>
  <EcFlex :class="variantCls.root">
    <!-- Breadcrum -->
    <EcFlex class="w-1/2 ml-2">
      <RBreadcrumb :items="breadcrumbItems" />
    </EcFlex>

    <!-- Notification and beyond -->
    <EcFlex class="w-1/2 justify-end mr-4">
      <!-- Notifications -->
      <EcFlex :class="variantCls.notifications">
        <EcIcon icon="Bell" />
      </EcFlex>

      <!-- Settings -->
      <EcFlex :class="variantCls.settings" @click="handleClickSettings">
        <EcIcon icon="Cog" />
      </EcFlex>

      <!-- Avartar -->
      <EcFlex :class="variantCls.avatar_box" @click="handleClickAvatarIcon">
        <EcText class="text-cWhite font-semibold">{{ userAvatarLetters }}</EcText>
      </EcFlex>

      <EcFlex :class="variantCls.logout" @click="handleClickLogout">
        <EcIcon icon="Logout" />
        <EcText class="ml-2 text-base">Logout</EcText>
      </EcFlex>

      <!-- account tool box -->
      <Transition @leave="handleLeaveMenu">
        <EcBox v-if="isAccountBoxOpen" :class="variantCls.menu_box">
          <!-- Menu item -->
          <EcFlex :class="variantCls.menu_item">
            <EcText class="text-base font-semibold">Version: 1.0</EcText>
          </EcFlex>

          <!-- Menu item -->
          <EcFlex :class="variantCls.menu_item" @click="handleClickMyAccount">
            <EcIcon icon="User" width="20" height="20" class="text-cSuccess-600" />
            <EcText class="text-base ml-2">My account</EcText>
          </EcFlex>
        </EcBox>
      </Transition>
    </EcFlex>
  </EcFlex>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import EcFlex from "@/components/EcFlex/index.vue"
import { goto } from "../../composables"
import * as helpers from "@/readybc/composables/helpers/helpers"
import EcText from "@/components/EcText/index.vue"
import EcBox from "@/components/EcBox/index.vue"
import EcIcon from "@/components/EcIcon/index.vue"
import RBreadcrumb from "./RBreadcrumb.vue"
import { useRoute } from "vue-router"

export default {
  name: "RTopBar",
  inject: ["getComponentVariants"],
  props: {
    unreadNotification: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      isAccountBoxOpen: false,
    }
  },
  /* eslint-disable */
  setup() {
    const globalStore = useGlobalStore()

    const route = useRoute()

    const breadcrumbItems = route?.meta?.breadcrumbs || []

    return {
      globalStore,
      breadcrumbItems,
    }
  },
  beforeMount() {},
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "RTopBar",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },

    userFullName() {
      return helpers.getUserFullName() || "N/A"
    },

    userAvatarLetters() {
      return this.userFullName
        .split(" ")
        .map((i) => i.charAt(0))
        .join("")
        .toUpperCase()
    },
  },
  methods: {
    handleClickAvatarIcon() {
      this.isAccountBoxOpen = !this.isAccountBoxOpen
    },
    handleLeaveMenu() {
      this.isAccountBoxOpen = false
    },

    /**
     * View Setting
     */
    handleClickSettings() {
      goto("ViewSettingList")
    },

    /**
     * View Profile
     */
    handleClickMyAccount() {
      goto("ViewProfile")
    },

    /**
     * Logout
     */
    handleClickLogout() {
      this.globalStore.logout()
    },
  },
  components: { EcFlex, EcText, EcBox, EcIcon, RBreadcrumb },
}
</script>
