<template>
  <RLayout :title="$t('setting.settings')">
    <EcFlex class="mt-8 grid grild-cols-1 md:grid-cols-4 gap-3">
      <!-- Setting item -->
      <EcBox
        variant="card-2"
        v-for="settingItem in settingItems"
        :key="settingItem.key"
        class="hover:cursor-pointer hover:shadow-3"
        @click="handleClickSettingItem(settingItem)"
      >
        <EcFlex>
          <EcIcon :icon="settingItem.icon" />
          <EcLabel class="ml-2 hover:cursor-pointer">{{ $t(settingItem.label) }}</EcLabel>
        </EcFlex>
      </EcBox>
      <!-- End item -->
    </EcFlex>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useSetting } from "../use/useSetting"

export default {
  name: "ViewSettingList",

  setup() {
    const { settingItems } = useSetting()
    console.log(settingItems)
    return {
      settingItems,
    }
  },

  methods: {
    handleClickSettingItem(settingItem) {
      goto(settingItem.routeName)
    },
  },
}
</script>
