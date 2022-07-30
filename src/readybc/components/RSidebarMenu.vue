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
            <JIcon
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
          <JIcon
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
            class="font-medium tracking-wider leading-tight text-lg truncate"
            :class="[
              variantCls.button.text.wrapper,
              currentRouteModule === 'settings' ? variantCls.button.text.selected : variantCls.button.text.idle,
            ]"
          >
            {{ $t("core.settings") }}
          </EcText>
        </EcBox>
      </EcButton>
      <EcButton variant="wrapper" :class="[variantCls.button.for, variantCls.button.wrapper]" @click="handleClickLogout()">
        <EcBox :class="[variantCls.button.box, { 'flex-row-reverse': menuDirection === 'rtl' }]">
          <JIcon
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
import { useStore } from "vuex"

export default {
  name: "CSidebarMenu",
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
    const { t } = useI18n()
    const router = useRouter()
    const route = useRoute()
    const store = useStore()

    const defaultModules = [
      {
        module: "dashboard",
        icon: "Template",
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
        module: "case",
        icon: "ClipboardList",
        text: "core.cases",
        routeName: "ViewCaseList",
      },
      {
        module: "client",
        icon: "UserGroup",
        text: "core.clients",
        routeName: "ViewClientList",
      },
      {
        module: "policy",
        icon: "ClipboardCheck",
        text: "core.policies",
        routeName: "ViewPolicyList",
      },
      {
        module: "claim",
        icon: "ShieldDollar",
        text: "core.claims",
        routeName: "ViewClaimList",
      },
      {
        module: "transaction",
        icon: "CreditCard",
        text: "core.transactions",
        routeName: "ViewTransactionList",
      },
      {
        module: "product",
        icon: "Clipboard",
        text: "core.product",
        routeName: "ViewProductList",
      },
      {
        module: "participant",
        icon: "User",
        text: "core.participants",
        routeName: "ViewParticipantList",
      },
      {
        module: "user",
        icon: "Users",
        text: "core.users",
        routeName: "ViewUserList",
      },
      {
        module: "organization",
        icon: "OfficeBuilding",
        text: "core.organizations",
        routeName: "ViewOrganizationList",
      },
      {
        module: "report",
        icon: "ChartSquareBar",
        text: "Report",
        routeName: "ViewReport",
      },
      {
        module: "chatroom",
        icon: "chat-alt",
        text: "core.chatRooms",
        routeName: "ViewChatroomListing",
      },
    ]

    const tenantModules = computed(() => store?.state?.tenantSettings?.modules)
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
      store.dispatch("logout")
    }

    return {
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
          componentName: "CSidebarMenu",
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
