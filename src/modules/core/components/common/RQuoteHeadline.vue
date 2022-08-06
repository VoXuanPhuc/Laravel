<template>
  <EcHeadline v-bind="$attrs" @mouseenter="handleMouseEnter" @mouseleave="handleMouseLeave">
    <EcBox class="relative" style="width: fit-content">
      <slot />
      <EcBox class="absolute top-0 px-2" style="right: 100%">
        <EcText class="cursor-pointer text-c0-300" :class="{ 'opacity-0': !isMouseHovering }" @click="copyLink()"> # </EcText>
      </EcBox>
    </EcBox>
  </EcHeadline>
</template>
<script>
import { computed, ref } from "vue"
import { useRoute } from "vue-router"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
export default {
  name: "CQuoteHeadline",

  setup() {
    const route = useRoute()
    const globalStore = useGlobalStore()
    const { t } = useI18n()
    const isMouseHovering = ref(false)

    const routePath = computed(() => {
      const protocol = window.location.protocol
      const hostName = window.location.hostname
      const fullPath = route.fullPath

      const tenantId = globalStore.getTenantId
      const tenantQuery = tenantId ? `?tenantId=${tenantId}` : ""

      return `${protocol}//${hostName}${fullPath}${tenantQuery}`
    })

    function handleMouseEnter() {
      isMouseHovering.value = true
    }

    function handleMouseLeave() {
      isMouseHovering.value = false
    }

    function copyLink() {
      const el = document.createElement("textarea")
      el.value = routePath.value
      el.setAttribute("readonly", "")
      el.style.position = "absolute"
      el.style.left = "-9999px"
      document.body.appendChild(el)
      const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false
      el.select()
      document.execCommand("copy")
      document.body.removeChild(el)
      if (selected) {
        document.getSelection().removeAllRanges()
        document.getSelection().addRange(selected)
      }

      globalStore.addToastMessage({
        type: "success",
        content: {
          type: "message",
          text: t("core.copyLinkSuccess"),
        },
      })
    }

    return { isMouseHovering, routePath, handleMouseEnter, handleMouseLeave, copyLink }
  },
}
</script>
