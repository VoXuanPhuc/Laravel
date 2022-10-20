<template>
  <EcBox :class="variantCls.button.for" @click="handleClickMenuItem(item)">
    <EcButton
      variant="wrapper"
      :class="[
        variantCls.button.wrapper,
        item?.module === currentRouteModule ? variantCls.button.selected : variantCls.button.idle,
        { 'flex-row-reverse': menuDirection === 'rtl' },
      ]"
    >
      <EcBox :class="variantCls.button.box">
        <!-- Menu Icon -->
        <EcIcon
          :icon="item.icon"
          width="20"
          height="20"
          :class="[
            variantCls.button.icon.wrapper,
            item?.module === currentRouteModule ? variantCls.button.icon.selected : variantCls.button.icon.idle,
            { 'mr-4': menuDirection === 'ltr' },
            { 'ml-4': menuDirection === 'rtl' },
          ]"
        />
        <!-- Menu text -->
        <EcText
          :class="[
            variantCls.button.text.wrapper,
            item?.module === currentRouteModule ? variantCls.button.text.selected : variantCls.button.text.idle,
          ]"
        >
          {{ item.text }}
        </EcText>

        <!-- Menu data -->
        <EcBox
          v-if="item?.data"
          :class="[
            variantCls.data.wrapper,
            item?.module === currentRouteModule ? variantCls.data.text.selected : variantCls.data.text.idle,
          ]"
        >
          {{ item?.data }}</EcBox
        >

        <!-- Menu sub item -->
        <EcIcon
          @click="handleExpandMenu(item)"
          v-if="item?.subItems?.length > 0"
          :icon="subItemExpansion[item.module] ? 'ChevronDown' : 'ChevronRight'"
          :class="[
            variantCls.subMenu.wrapper,
            item?.module === currentRouteModule ? variantCls.subMenu.text.selected : variantCls.subMenu.text.idle,
          ]"
          width="14"
        />
      </EcBox>
    </EcButton>

    <EcBox v-if="item?.subItems?.length > 0 && subItemExpansion[item.module]">
      <RSidebarMenuItem
        class="ml-4 border-l border-c3-50"
        v-for="(subItem, idx) in item.subItems"
        :key="idx"
        :item="subItem"
        :componentName="componentName"
        :currentRouteModule="currentRouteModule"
        :menuDirection="menuDirection"
      />
    </EcBox>
  </EcBox>
</template>

<script>
import { useRouter } from "vue-router"
import EcBox from "@/components/EcBox/index.vue"

export default {
  name: "RSidebarMenuItem",
  inject: ["getComponentVariants"],
  emits: ["click-menu-item"],
  props: {
    item: {
      type: Object,
      required: true,
      default: () => {},
    },
    componentName: {
      type: String,
    },
    currentRouteModule: {
      type: String,
    },
    menuDirection: {
      type: String,
    },
  },

  data() {
    return {
      subItemExpansion: {},
    }
  },
  setup() {
    const router = useRouter()

    return {
      router,
    }
  },
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "RSidebarMenuItem",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },
  },
  methods: {
    /**
     *
     * @param {*} item
     */
    handleClickMenuItem(item) {
      this.subItemExpansion[item.module] = !this.subItemExpansion[item.module]

      this.router.push({
        name: item.routeName,
      })
      this.$emit("click-menu-item")
    },

    handleExpandMenu(item) {
      console.log(this.subItemExpansion)
    },

    isSubMenuExpanded(item) {
      return this.subItemExpansion[item.module]
    },
  },
  components: { EcBox },
}
</script>
