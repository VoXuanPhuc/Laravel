<template>
  <EcMessage :messages="computedMessages" @close="close">
    <template v-slot="{ content }">
      <!-- Content is a string -->
      <EcBox v-if="content.type === 'message'">
        {{ content.text }}
      </EcBox>

      <!-- The code here will contain every cases supported to show by CMessage -->
      <router-link v-else-if="content.type === 'link'" :to="content.to">
        {{ content.linkText }}
      </router-link>
    </template>
  </EcMessage>
</template>

<script>
import { useGlobalStore } from "@/stores/global"

export default {
  name: "RMessage",
  props: {
    max: {
      type: Number,
      default: 5,
    },
  },
  setup(props) {
    const globalStore = useGlobalStore()

    return {
      globalStore,
    }
  },
  computed: {
    computedMessages() {
      const messages = this.globalStore.getMessages ?? {}
      const messagesList = Object.keys(messages)?.map((key) => ({
        ...messages[key],
      }))
      return messagesList?.slice(Math.max(messagesList?.length - this?.max, 0), messagesList?.length) ?? []
    },
  },
  methods: {
    close(key) {
      this.globalStore.removeToastMessage(key)
    },
  },
}
</script>
