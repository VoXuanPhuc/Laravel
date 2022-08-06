<template>
  <EcBox
    :class="isShowMore ? 'bg-c0-100' : ''"
    class="relative px-5 py-4 leading-tight rounded-lg group text-c0-500 hover:bg-c0-100"
  >
    <EcFlex class="items-center">
      <EcBox class="w-6/12 pr-2 md:w-4/12">
        <slot name="label">
          <EcText>{{ label ? label : "-" }}</EcText>
        </slot>
      </EcBox>
      <EcBox class="w-6/12 px-2" :class="isReadOnly ? 'md:w-8/12' : 'md:w-7/12'">
        <slot name="value">
          <EcText v-if="!isList" class="font-medium break-all" :class="inputCustomClass" @click="handleClickValue">
            {{ formattedValue }}
          </EcText>
          <EcBox v-if="isList" class="pl-4">
            <ul style="list-style-type: disc">
              <li v-for="(item, idx) in value" :key="idx">
                {{ item }}
              </li>
            </ul>
          </EcBox>
        </slot>
      </EcBox>
      <EcBox v-if="!isReadOnly" class="ml-auto group-hover:visible">
        <!-- Edit Only -->
        <EcButton v-if="canEdit && !canDelete" variant="transparent-rounded" style="padding: 0.25rem" @click="handleClickEdit">
          <EcIcon icon="Pencil" />
        </EcButton>
        <!-- Delete Only -->
        <EcButton v-if="canDelete && !canEdit" variant="transparent-rounded" style="padding: 0.25rem" @click="handleClickDelete">
          <EcIcon class="text-cError-500" icon="Trash" />
        </EcButton>
        <!-- Edit & Delete -->
        <EcButton
          v-if="canDelete && canEdit"
          v-click-outside="hideShowMore"
          variant="transparent-rounded"
          style="padding: 0.25rem; min-height: 28px"
          @click="handleClickMore"
        >
          <EcIcon icon="DotsHorizontal" />
        </EcButton>
      </EcBox>
      <EcBox v-if="isShowMore" class="absolute right-0 z-10 py-2 rounded-lg shadow bg-cWhite" style="top: 2rem">
        <EcFlex class="items-center px-4 py-2 cursor-pointer text-c0-500 hover:bg-c0-100" @click="handleClickEdit">
          <EcIcon class="mr-3" icon="Pencil" />
          <EcText class="font-medium"> {{ $t("core.edit") }} </EcText>
        </EcFlex>
        <EcFlex class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100" @click="handleClickDelete">
          <EcIcon class="mr-3" icon="Trash" />
          <EcText class="font-medium"> {{ $t("core.delete") }} </EcText>
        </EcFlex>
      </EcBox>
    </EcFlex>
  </EcBox>
</template>

<script>
import dayjs from "dayjs"

export default {
  name: "REditableField",
  emits: ["edit", "delete", "value-click"],
  props: {
    /**
     * @description Field Label
     */
    label: {
      type: String,
      required: false,
      default: "",
    },
    /**
     * @description Field value
     */
    value: {
      required: true,
    },
    /**
     * @description Field id to call functionalities
     */
    itemId: {
      type: String,
      default: "",
    },
    /**
     * @description Field type
     */
    type: {
      type: String,
      default: "string",
    },
    /**
     * @description Date time format
     */
    dateFormat: {
      type: String,
      default: "DD/MM/YYYY",
    },
    /**
     * @description Hide/Show showMore button
     */
    isReadOnly: {
      type: Boolean,
      default: false,
    },
    /**
     * @description Render value in a list
     */
    isList: {
      type: Boolean,
      default: false,
    },
    /**
     * @description Enable/Disable Edit function
     */
    canEdit: {
      type: Boolean,
      default: true,
    },
    /**
     * @description Enable/Disable Delete function
     */
    canDelete: {
      type: Boolean,
      default: true,
    },
    /**
     * @description Custom label class
     */
    inputCustomClass: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isShowMore: false,
    }
  },
  computed: {
    formattedValue() {
      if (this.type !== "boolean" && this.type !== "booleanValue") {
        if (!this.value) return "-"
        if (dayjs.isDayjs(this.value) || this.type === "dateValue") {
          return dayjs(this.value).format(this.dateFormat)
        } else {
          return this.value
        }
      } else {
        return this.value ? this.$t("core.yes") : this.$t("core.no")
      }
    },
  },
  methods: {
    handleClickMore() {
      this.isShowMore = !this.isShowMore
    },
    hideShowMore() {
      this.isShowMore = false
    },
    handleClickEdit() {
      this.$emit("edit", {
        id: this.itemId,
        type: this.type,
        label: this.label,
        value: this.value,
      })
    },
    handleClickDelete() {
      this.$emit("delete", this.itemId)
    },
    handleClickValue() {
      this.$emit("value-click")
    },
  },
}
</script>
