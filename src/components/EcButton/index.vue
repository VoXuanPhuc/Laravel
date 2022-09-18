<template>
  <component
    :is="decideComponent"
    :class="variantCls.root"
    :href="href"
    :to="to"
    :target="target"
    :disabled="disabled"
    tabindex="0"
  >
    <!-- Prefix Slot -->
    <slot name="prefix">
      <div v-if="iconPrefixName" :class="variantCls.left">
        <EcIcon :icon="iconPrefixName" :class="variantCls.iconPrefix" :width="iconPrefixWidth" :height="iconPrefixHeight" />
      </div>
    </slot>
    <!-- Default Slot -->
    <div :class="variantCls.middle">
      <slot />
    </div>
    <!-- Suffix Slot -->
    <slot name="suffix">
      <div v-if="iconSuffixName" :class="variantCls.right">
        <EcIcon :icon="iconSuffixName" :class="variantCls.iconSuffix" :width="iconSuffixWidth" :height="iconSuffixHeight" />
      </div>
    </slot>
  </component>
</template>
<script>
export default {
  props: {
    variant: {
      type: String,
      default: "default",
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
    to: {
      type: [Object, String],
      required: false,
    },
    href: {
      type: String,
      required: false,
    },
    target: {
      type: String,
      default: "_self",
      required: false,
    },
    routerTag: {
      type: String,
      required: false,
      default: "router-link",
    },
    iconPrefix: {
      type: String,
      default: "",
    },
    iconSuffix: {
      type: String,
      default: "",
    },
  },
  computed: {
    variants() {
      return (
        this.getComponentVariants({
          componentName: "EcButton",
          variant: this.variant,
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },
    // When passed via prop, it overrides variants definition
    iconPrefixName() {
      return this.iconPrefix ? this.iconPrefix : this.variants?.assets?.iconPrefixName
    },
    iconPrefixWidth() {
      return this.variants?.assets?.iconPrefixWidth
    },
    iconPrefixHeight() {
      return this.variants?.assets?.iconPrefixHeight
    },
    // When passed via prop, it overrides variants definition
    iconSuffixName() {
      return this.iconSuffix ? this.iconSuffix : this.variants?.assets?.iconSuffixName
    },
    iconSuffixWidth() {
      return this.variants?.assets?.iconSuffixWidth
    },
    iconSuffixHeight() {
      return this.variants?.assets?.iconSuffixHeight
    },
    decideComponent() {
      if (this.href) return "a"
      if (this.to) return this.routerTag
      return "button"
    },
  },
}
</script>
