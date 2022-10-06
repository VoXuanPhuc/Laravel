<template>
  <EcFlex class="lg:hidden">
    <!-- Menu Backdrop -->
    <EcBox v-show="showMenu" class="fixed top-0 left-0 w-screen h-screen bg-opacity-50 bg-cBlack" />
    <!-- Menu Wrapper -->
    <EcBox class="fixed top-0 left-0 z-40 w-screen h-screen" :class="showMenu ? '' : 'pointer-events-none'">
      <!-- Menu Background -->
      <EcBox
        class="absolute transition-all duration-200 rounded-full bg-c1-800"
        style="right: 3rem; bottom: 3.25rem; transform: translate(50%, 50%)"
        :style="menuBackgroundStyle"
      />
      <!-- Menu -->
      <EcFlex
        class="absolute bottom-0 right-0 flex-col items-end mr-8 transition-all duration-200"
        style="width: 80vh; max-width: 300px; transform-origin: bottom right"
        :style="menuStyle"
      >
        <EcFlex class="items-center justify-end w-16 mb-8 mr-4">
          <img class="w-auto flex-0" :src="logo.img" alt="Logo" />
        </EcFlex>
        <EcBox class="pr-3 overflow-auto">
          <RSidebarMenu menuDirection="rtl" :unreadNotification="unreadNotification" @click-menu-item="hideMenu" />
        </EcBox>
      </EcFlex>
    </EcBox>
    <!-- Hamburger Button -->
    <EcFlex
      class="fixed bottom-0 right-0 z-50 items-center justify-center w-16 h-16 rounded-full select-none bg-c1-800 text-cWhite"
      style="margin-right: 1rem; margin-bottom: 1.25rem"
      @click="toggleMenu"
    >
      <EcIcon v-if="!showMenu" width="34" height="34" icon="Menu" />
      <EcIcon v-else v-scroll-lock="true" width="34" height="34" icon="X" />
    </EcFlex>
  </EcFlex>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import RSidebarMenu from "./RSidebarMenu"

export default {
  name: "RSidebarMobile",
  components: {
    RSidebarMenu,
  },
  props: {
    unreadNotification: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      logo: {},
      menuHeight: 0,
      showMenu: false,
    }
  },
  setup(props) {
    const globalStore = useGlobalStore()

    return {
      globalStore,
    }
  },
  computed: {
    menuBackgroundStyle() {
      const size = this.showMenu ? "160vh" : "0"
      return {
        width: size,
        height: size,
      }
    },
    menuStyle() {
      return {
        transform: `scale(${this.showMenu ? 1 : 0})`,
        opacity: this.showMenu ? "1" : "0",
        marginBottom: this.iOS ? "12rem" : "6rem",
        height: this.iOS ? "calc(80vh - 11.25rem)" : "calc(80vh - 5.25rem)",
      }
    },
    iOS() {
      return (
        ["iPad Simulator", "iPhone Simulator", "iPod Simulator", "iPad", "iPhone", "iPod"].includes(navigator.platform) ||
        // iPad on iOS 13 detection
        navigator.userAgent.includes("Mac")
      )
    },
  },

  beforeMount() {
    this.fetchLogo()
  },

  methods: {
    async fetchLogo() {
      const { tenantId, clientId } = this.globalStore.getTenantClientId
      const tenantConfig = this.globalStore.getTenantSettings

      try {
        const logoConfig = tenantConfig?.default?.logo
        const imageUrl = tenantConfig?.server?.logo || require(`@/tenants/${tenantId}/assets/${logoConfig?.sidebar}`)

        this.logo = {
          img: imageUrl || "https://via.placeholder.com/64x78",
          class: logoConfig?.class,
          style: logoConfig?.style,
        }
      } catch (error) {
        console.groupCollapsed(
          "%c Tenant config file does not exist",
          "padding: 1px 6px 1px 0px; background: yellow; color: black"
        )
        console.log(`tenantId: ${tenantId}`)
        console.log(`clientId: ${clientId}`)
        console.log(error)
        console.groupEnd()
      }
    },
    toggleMenu() {
      this.showMenu = !this.showMenu
    },
    hideMenu() {
      this.showMenu = false
    },
  },
}
</script>
