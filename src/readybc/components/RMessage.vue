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
/**
 * If you want to show a message just use this 
    this.$store.dispatch("addToastMessage", {
      type: "neutral", // Available type: neutral / sucess / error / warning
      content: {
        type: "message" // Available type: message / link
        text: "Information",
      },
      cb: () => {} // The cb will execute after that message close
    })

  * If you want to show a message with custom content (routerLink for example), you can do this
      this.$store.dispatch("addToastMessage", {
        type: "warning",
        content: {
          type: "link",
          to: { name: "ViewDashboard" },
          linkText: "Click here to go to dashboard"
        },
        cb: () => {} // The cb will execute after that message close
      })
    Remember: you can put anything in the content object and use it in template v-slot,
      so it will allow you to put any kind of components that you want to the CMessage
 */
export default {
  name: "RMessage",
  props: {
    max: {
      type: Number,
      default: 5,
    },
  },
  computed: {
    computedMessages() {
      const messages = this.$store.state.messages ?? {}
      const messagesList = Object.keys(messages)?.map((key) => ({
        ...messages[key],
      }))
      return messagesList?.slice(Math.max(messagesList?.length - this?.max, 0), messagesList?.length) ?? []
    },
  },
  methods: {
    close(key) {
      this.$store.dispatch("removeToastMessage", key)
    },
  },
}
</script>
