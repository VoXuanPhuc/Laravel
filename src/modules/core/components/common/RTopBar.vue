<template>
  <EcFlex :class="variantCls.root">
    <EcFlex class="w-1/2"></EcFlex>
    <EcFlex class="w-1/2 justify-end mr-4">
      <EcFlex class="items-center mr-4 text-sm"> Hi, {{ userFullName }} </EcFlex>
      <!-- Avartar -->
      <EcFlex :class="variantCls.avatar_box" @click="handleClickAvatarIcon">
        <EcText class="text-c1-800 font-semibold">{{ userAvatarLetters }}</EcText>
      </EcFlex>

      <!-- account tool box -->
      <Transition @leave="handleLeaveMenu">
        <EcBox v-if="isAccountBoxOpen" :class="variantCls.menu_box">
          <!-- Menu item -->
          <EcFlex :class="variantCls.menu_item">
            <EcText class="font-semibold">Version: 1.0</EcText>
          </EcFlex>

          <!-- Menu item -->
          <EcFlex :class="variantCls.menu_item" @click="handleClickMyAccount">
            <EcIcon icon="User" width="20" height="20" class="text-cSuccess-600" />
            <EcText class="ml-2">My account</EcText>
          </EcFlex>

          <!-- Log out -->
          <EcFlex :class="variantCls.menu_item" @click="handleClickLogout">
            <EcIcon icon="Logout" width="20" height="20" class="text-cError-600" />
            <EcText class="ml-2">Logout</EcText>
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
    return {
      globalStore,
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
    handleClickMyAccount() {
      goto("ViewSettingList")
    },

    /**
     * Logout
     */
    handleClickLogout() {
      this.globalStore.logout()
    },
  },
  components: { EcFlex, EcText },
}
</script>
