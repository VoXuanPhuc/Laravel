<template>
  <EcBox variant="card-5" class="mt-5">
    <!-- Filter Form -->
    <EcBox class="grid lg:grid-cols-2 grid-cols-1 gap-4">
      <RFormInput v-model="form.entityName" componentName="EcInputText" :label="$t('user.filter.name')" />
      <RFormInput v-model="form.status" componentName="EcRadios" :label="$t('user.filter.status')" :options="status" />
      <RFormInput
        v-model="form.email"
        class="mb-5"
        componentName="EcInputText"
        :label="$t('user.filter.email')"
        type="email"
        variant="primary-lg"
        data-test="inputEmail"
      />
      <RFormInput
        v-model="form.permissionGroup"
        componentName="EcSelect"
        :label="$t('user.filter.role')"
        :options="roleOptions"
      />
      <RFormInput v-model="form.internalCode" componentName="EcInputText" :label="$t('user.filter.code')" />
      <RDatePickerAdvanced
        :modelValue="form.createdAt"
        :label="$t('user.filter.createdAt')"
        :clearLabel="$t('user.filter.clear')"
        :dateTypeOptions="dateTypeOptions"
        :quickOptions="quickOptions"
        @update:modelValue="handleCreatedAt"
      />
    </EcBox>
    <!-- Filter Actions -->
    <EcFlex class="mt-4">
      <EcButton @click="handleClickApplyFilter">
        {{ $t("case.apply") }}
      </EcButton>
      <EcButton variant="primary-outline" class="ml-3" @click="handleClickClearFilter">
        {{ $t("case.clear") }}
      </EcButton>
    </EcFlex>
  </EcBox>
</template>

<script>
export default {
  name: "UserFilter",
  emits: ["filter:apply", "filter:clear"],
  props: {
    createdAt: {
      type: Object,
      default: () => {},
    },
    internalCode: {
      type: String,
      default: "",
    },
    email: {
      type: String,
      default: "",
    },
    roleOptions: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      quickOptions: [
        { label: this.$t("core.today"), value: "today" },
        { label: this.$t("core.lastWeek"), value: "lastWeek" },
        { label: this.$t("core.lastMonth"), value: "lastMonth" },
        { label: this.$t("core.lastThreeMonths"), value: "last3Months" },
      ],
      dateTypeOptions: [
        { name: this.$t("core.between"), value: "between" },
        { name: this.$t("core.before"), value: "before" },
        { name: this.$t("core.after"), value: "after" },
      ],
      status: [
        { name: "Active", value: true },
        { name: "Pending", value: false },
      ],
      form: {
        email: "",
        status: "",
        entityName: "",
        permissionGroup: "",
        createdAt: this.createdAt,
        internalCode: "",
      },
    }
  },
  methods: {
    handleCreatedAt(data) {
      this.form.createdAt = data
    },
    handleClickApplyFilter() {
      this.$emit("filter:apply", this.form)
    },
    handleClickClearFilter() {
      Object.keys(this.form).forEach((element) => {
        this.form[element] = ""
      })
      this.form.createdAt = { type: "between", value: [], quickOptionValue: "" }
      this.$emit("filter:clear", this.form)
    },
  },
}
</script>
