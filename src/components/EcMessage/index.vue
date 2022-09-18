<template>
  <transition
    :enterFromClass="variantCls.enterFrom"
    :enterActiveClass="variantCls.enterActive"
    :leaveActiveClass="variantCls.leaveActive"
    :leaveToClass="variantCls.leaveTo"
  >
    <div v-if="messages && messages.length > 0" :class="variantCls.root" :style="computedStyle">
      <transition-group
        :enterFromClass="variantCls.enterFrom"
        :enterActiveClass="variantCls.enterActive"
        :leaveActiveClass="variantCls.leaveActive"
        :leaveToClass="variantCls.leaveTo"
      >
        <div v-for="item in computedMessages" :key="item.key">
          <div
            :key="item.key"
            :class="item.appliedCls"
            tabindex="0"
            @keypress.enter="$emit('click', item)"
            @blur="$emit('blur')"
            @focus="$emit('focus')"
          >
            <slot :content="item.content">
              <!-- Default to show the content text -->
              {{ item.content }}
            </slot>
            <div :class="variantCls.closeIcon" @click="$emit('close', item.key)">
              <EcIcon width="20" height="20" icon="X" />
            </div>
          </div>
        </div>
      </transition-group>
    </div>
  </transition>
</template>

<script>
export default {
  name: "EcMessage",
  emits: ["click", "blur", "focus", "close"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    messages: {
      validator(val) {
        return val === null || Array.isArray(val)
      },
      default: () => [],
      required: false,
      // example: [{ id: "unique_id", type: "neutral", text: "I am a message" }]
      // type can be: neutral, error, success, warning
    },
    max: {
      type: Number,
      default: 5,
      required: false,
    },
    zIndex: {
      type: Number,
      required: false,
    },
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcMessage",
          variant: this.variant,
        })?.el ?? {}
      )
    },

    computedMessages() {
      return this.messages.map((item) => {
        let cls = "messageNeutral"
        if (item.type === "warning") cls = "messageWarning"
        if (item.type === "error") cls = "messageError"
        if (item.type === "success") cls = "messageSuccess"

        return {
          ...item,
          appliedCls: this.variantCls[cls],
        }
      })
    },

    computedStyle() {
      return this.zIndex ? `z-index: ${this.zIndex}` : ""
    },
  },
}
</script>
