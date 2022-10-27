<template>
  <!-- Log summary -->
  <EcFlex v-for="(change, changeIdx) in log?.change_summary" :key="changeIdx" class="items-center mb-5 text-sm">
    <EcLabel class="p-1 rounded-full text-cWhite" :class="[log?.causer_summary?.color]">{{ log?.causer_summary?.short }}</EcLabel>

    <!-- Factor performs action -->
    <EcBox class="text-sm ml-3">
      <!-- Factors -->
      <EcFlex>
        <!-- Causer -->
        <EcLabel class="font-semibold mr-1">{{ log?.causer_summary?.name }}</EcLabel>
        <!-- Event -->
        <EcLabel class="font-thin">{{ log?.event }}</EcLabel>
        <!-- Subject type -->
        <EcLabel class="font-semibold ml-1">{{ change?.subject }}</EcLabel>
        <EcLabel class="font-thin ml-2" v-tooltip="{ text: log?.created_at, position: 'right' }">{{ log?.date }}</EcLabel>
      </EcFlex>

      <!-- Data which  changed -->
      <EcFlex v-if="change?.old?.length > 0 && change?.new?.length > 0">
        <EcLabel :class="getFromFormat(change?.old)">{{ change?.old }}</EcLabel>
        <EcLabel class="ml-2 mr-2">→</EcLabel>
        <EcLabel>{{ change?.new }}</EcLabel>
      </EcFlex>
    </EcBox>
    <!-- End factor and actions -->
  </EcFlex>
</template>

<script>
export default {
  name: "RActivityLogRow",

  props: {
    log: {
      type: Object,
      default: () => {},
    },
  },

  methods: {
    getFromFormat(value) {
      return value === "None" ? "text-c0-400" : ""
    },
  },
}
</script>
