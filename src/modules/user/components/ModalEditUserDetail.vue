<template>
  <EcModalSimple :isVisible="modalEditUserDetailOpen" variant="center-md">
    <!-- Header -->
    <EcBox>
      <EcFlex class="mb-6">
        <EcHeadline as="h3" variant="h3">Edit User Detail</EcHeadline>
      </EcFlex>
    </EcBox>

    <!-- Input field -->
    <EcFlex>
      <!-- Firstname -->
      <EcBox>
        <RFormInput
          :value="firstName"
          componentName="EcInputText"
          :label="this.$t('user.label.firstName')"
          type="text"
          required="true"
          @change="
            (event) => {
              form.firstName = event.target.value
            }
          "
        />
      </EcBox>
    </EcFlex>

    <EcFlex class="mt-10">
      <!-- Last name -->
      <EcBox>
        <RFormInput
          :value="lastName"
          componentName="EcInputText"
          :label="this.$t('user.label.lastName')"
          type="text"
          required="true"
          @input="
            (event) => {
              form.lastName = event.target.value
            }
          "
        />
      </EcBox>
    </EcFlex>

    <!-- Buttons -->
    <!-- User data -->
    <EcFlex class="mt-10">
      <EcButton class="mr-4" variant="primary" @click="triggerUpdateUser">
        {{ $t("user.button.update") }}
      </EcButton>
      <EcButton variant="tertiary-outline" @click="$emit('handleClickCancelEditUserDetail')">
        {{ $t("user.button.cancel") }}
      </EcButton>
    </EcFlex>
  </EcModalSimple>
</template>

<script>
export default {
  name: "ModalEditUserDetail",
  emits: ["handleClickCancelEditUserDetail", "handleUpdateUserDetail"],
  props: {
    firstName: String,
    lastName: String,
    modalEditUserDetailOpen: Boolean,
  },

  data() {
    const form = {
      firstName: "",
      lastName: "",
    }

    return {
      form,
    }
  },

  methods: {
    triggerUpdateUser() {
      debugger
      const data = {
        firstName: this.form.firstName,
        lastName: this.form.lastName,
      }
      this.$emit("handleUpdateUserDetail", data)
    },
  },
}
</script>
