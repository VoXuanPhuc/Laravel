<template>
  <EcFlex :class="[variantCls.root, menuDirection === 'rtl' ? 'text-right' : '']" style="-webkit-overflow-scrolling: touch">
    <EcBox :class="variantCls.buttons" style="-webkit-overflow-scrolling: touch">
      <RSidebarMenuItem
        v-for="item in menuItems"
        :key="item.component"
        :item="item"
        :componentName="componentName"
        :currentRouteModule="currentRouteModule"
        :menuDirection="menuDirection"
      ></RSidebarMenuItem>
    </EcBox>
  </EcFlex>
</template>

<script>
import { useI18n } from "vue-i18n"
import { useRoute } from "vue-router"
import { computed } from "vue"
import { useGlobalStore } from "@/stores/global"
import RSidebarMenuItem from "./RSidebarMenuItem.vue"

export default {
  name: "RSidebarMenu",
  inject: ["getComponentVariants"],
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

    const route = useRoute()
    const modules = [
      // Dashboard for landlord
      {
        module: "dashboard",
        icon: "Dashboard",
        text: "core.dashboard",
        routeName: "ViewDashboard",
      },
      // Dashboard for client
      {
        module: "bright_dashboard",
        icon: "Dashboard",
        text: "core.dashboard",
        routeName: "ViewBrightDashboard",
      },

      {
        module: "organization",
        icon: "OfficeBuilding",
        text: "core.organizations",
        routeName: "ViewOrganizationList",
      },
      {
        module: "department",
        icon: "OfficeBuilding",
        text: "core.departments",
        routeName: "ViewDepartmentManagement",
      },
      {
        module: "activity",
        icon: "Activity",
        text: "core.activities",
        routeName: "ViewActivityList",
        subItems: [],
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
        module: "dependency",
        icon: "Dependency",
        text: "core.dependencies",
        routeName: "ViewDependencyScenarioList",
      },
      {
        module: "industry",
        icon: "Industry",
        text: "core.industries",
        routeName: "ViewIndustryList",
      },

      {
        module: "business_continuity_plan",
        icon: "BCP",
        text: "core.business_continuity_plan",
        routeName: "ViewBCPList",
      },
      {
        module: "assessment",
        icon: "Assessment",
        text: "core.assessment",
        routeName: "ViewBIAList",
      },
      {
        module: "report",
        icon: "Report",
        text: "core.report",
        routeName: "ViewReport",
      },
      {
        module: "user",
        icon: "User",
        text: "core.users",
        routeName: "ViewUserList",
      },
      // {
      //   module: "chatroom",
      //   icon: "chat-alt",
      //   text: "core.chatRooms",
      //   routeName: "ViewChatroomListing",
      // },
    ]
    const allowedModules = globalStore?.tenantSettings?.server?.modules || []
    const allowedModuleIds = allowedModules?.map((module) => {
      return module.value
    })
    const componentName = computed(() => route?.name)
    const menuItems = computed(() => {
      return modules
        .filter((item) => {
          return allowedModuleIds.includes(item.module)
        })
        .map((item) => ({ ...item, text: t(item.text) }))
    })
    const currentRouteModule = computed(() => route?.meta?.module)

    return {
      globalStore,
      currentRouteModule,
      componentName,
      menuItems,
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
  methods: {
    async fetchModules() {},
  },
  components: { RSidebarMenuItem },
}
</script>
