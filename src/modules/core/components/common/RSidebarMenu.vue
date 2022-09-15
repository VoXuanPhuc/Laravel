<template>
  <EcFlex :class="[variantCls.root, menuDirection === 'rtl' ? 'text-right' : '']" style="-webkit-overflow-scrolling: touch">
    <EcBox :class="variantCls.buttons" style="-webkit-overflow-scrolling: touch">
      <EcBox v-for="item in menuItems" :key="item.component" :class="variantCls.button.for" @click="handleClickMenuItem(item)">
        <EcButton
          variant="wrapper"
          :class="[
            variantCls.button.wrapper,
            item?.module === currentRouteModule ? variantCls.button.selected : variantCls.button.idle,
            { 'flex-row-reverse': menuDirection === 'rtl' },
          ]"
          @click="handleClickMenuItem(item)"
        >
          <EcBox :class="variantCls.button.box">
            <EcIcon
              :icon="item.icon"
              width="1.25rem"
              height="1.25rem"
              :class="[
                variantCls.button.icon.wrapper,
                item?.module === currentRouteModule ? variantCls.button.icon.selected : variantCls.button.icon.idle,
                { 'mr-4': menuDirection === 'ltr' },
                { 'ml-4': menuDirection === 'rtl' },
              ]"
            />
            <EcText
              :class="[
                variantCls.button.text.wrapper,
                item?.module === currentRouteModule ? variantCls.button.text.selected : variantCls.button.text.idle,
              ]"
            >
              {{ item.text }}
            </EcText>
          </EcBox>
        </EcButton>
        <EcBox
          v-if="item?.module === 'notification' && unreadNotification"
          class="absolute bg-cError-600 inline-block px-2 rounded-xl text-cWhite text-sm"
          style="top: 2px; right: 0px"
          >{{ unreadNotification }}</EcBox
        >
      </EcBox>
    </EcBox>
    <EcBox class="mb-0 mt-auto relative">
      <!-- Settings button -->
      <EcButton
        variant="wrapper"
        :class="[
          variantCls.button.for,
          variantCls.button.wrapper,
          currentRouteModule === 'settings' ? variantCls.button.selected : variantCls.button.idle,
          { 'flex-row-reverse': menuDirection === 'rtl' },
        ]"
        @click="handleClickSettings()"
      >
        <EcBox :class="variantCls.button.box">
          <EcIcon
            icon="Cog"
            width="1.25rem"
            height="1.25rem"
            :class="[
              variantCls.button.icon.wrapper,
              currentRouteModule === 'settings' ? variantCls.button.icon.selected : variantCls.button.icon.idle,
              { 'mr-4': menuDirection === 'ltr' },
              { 'ml-4': menuDirection === 'rtl' },
            ]"
          />
          <EcText
            class="font-medium tracking-wider leading-tight truncate"
            :class="[
              variantCls.button.text.wrapper,
              currentRouteModule === 'settings' ? variantCls.button.text.selected : variantCls.button.text.idle,
            ]"
          >
            {{ $t("core.settings") }}
          </EcText>
        </EcBox>
      </EcButton>

      <!-- Logout button -->
      <EcButton variant="wrapper" :class="[variantCls.button.for, variantCls.button.wrapper]" @click="handleClickLogout()">
        <EcBox :class="[variantCls.button.box, { 'flex-row-reverse': menuDirection === 'rtl' }]">
          <EcIcon
            icon="Logout"
            width="1.25rem"
            height="1.25rem"
            :class="[
              variantCls.button.icon.wrapper,
              variantCls.button.icon.idle,
              { 'mr-4': menuDirection === 'ltr' },
              { 'ml-4': menuDirection === 'rtl' },
            ]"
          />
          <EcText :class="[variantCls.button.text.wrapper, variantCls.button.text.idle]">
            {{ $t("core.logout") }}
          </EcText>
        </EcBox>
      </EcButton>
    </EcBox>
  </EcFlex>
</template>

<script>
import { useI18n } from "vue-i18n"
import { useRouter, useRoute } from "vue-router"
import { computed } from "vue"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "RSidebarMenu",
  inject: ["getComponentVariants"],
  emits: ["click-menu-item"],
  props: {
    /**
     * @description: Menu direction
     * Supported: "ltr", "rtl"
     */
    menuDirection: {
      type: String,
      default: "ltr",
    },
    unreadNotification: {
      type: Number,
      default: 0,
    },
  },
  setup(props, { emit }) {
    const globalStore = useGlobalStore()
    const { t } = useI18n()
    const router = useRouter()
    const route = useRoute()

    const defaultModules = [
      {
        module: "dashboard",
        icon: "Dashboard",
        text: "core.dashboard",
        routeName: "ViewDashboard",
      },
      {
        module: "notification",
        icon: "Bell",
        text: "core.notifications",
        routeName: "ViewNotificationsHome",
      },
      {
        module: "organization",
        icon: "OfficeBuilding",
        text: "core.organizations",
        routeName: "ViewOrganizationList",
      },
      {
        module: "activity",
        icon: "Activity",
        text: "core.activities",
        routeName: "ViewActivityList",
      },
      {
        module: "resource",
        icon: "Resource",
        text: "core.resources",
        routeName: "ViewResourceList",
      },
      {
        module: "supplier",
        icon: "Supplier",
        text: "core.suppliers",
        routeName: "ViewSupplierList",
      },
      {
        module: "industry",
        icon: "Industry",
        text: "core.industries",
        routeName: "ViewIndustryList",
      },

      {
        module: "user",
        icon: "Users",
        text: "core.users",
        routeName: "ViewUserList",
      },

      {
        module: "report",
        icon: "ChartSquareBar",
        text: "core.report",
        routeName: "ViewReport",
      },
      // {
      //   module: "chatroom",
      //   icon: "chat-alt",
      //   text: "core.chatRooms",
      //   routeName: "ViewChatroomListing",
      // },
    ]

    const tenantModules = computed(() => globalStore?.tenantSettings?.modules)
    const componentName = computed(() => route?.name)
    const menuItems = computed(() => {
      // return avaiableModules?.filter((item) => tenantSettings?.value?.includes(item.module))
      const formattedOutput = (array) =>
        array?.map((item) => ({
          ...item,
          text: t(item.text),
        }))

      return tenantModules.value === "default"
        ? formattedOutput(defaultModules)
        : Array.isArray(tenantModules.value)
        ? formattedOutput(tenantModules.value)
        : formattedOutput(defaultModules)
    })

    const currentRouteModule = computed(() => route?.meta?.module)
    const unreadNotificationCount = computed(() => {
      return props.unreadNotification < 15 ? props.unreadNotification : "*"
    })

    /**
     *
     * @param {*} item
     */
    const handleClickMenuItem = (item) => {
      router.push({
        name: item.routeName,
      })
      emit("click-menu-item")
    }

    const handleClickSettings = () => {
      router.push({ name: "ViewSettingList" })
      emit("click-menu-item")
    }

    const handleClickLogout = () => {
      globalStore.logout()
    }

    return {
      globalStore,
      currentRouteModule,
      componentName,
      menuItems,
      handleClickMenuItem,
      handleClickSettings,
      handleClickLogout,
      unreadNotificationCount,
    }
  },

  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "RSidebarMenu",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },
  },
}
</script>
