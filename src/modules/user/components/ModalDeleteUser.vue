<template>
  <EcModalSimple :isVisible="modalDeleteOpen" variant="center-3xl" @overlay-click="$emit('handleClickCancelDelete')">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>
      <EcBox class="mb-6">
        <EcHeadline as="h2" variant="h2" class="text-4xl text-cError-500">
          {{ $t("user.confirmToDelete") }}
        </EcHeadline>
        <EcText class="mt-4 text-c0-500">
          {{ $t("user.cfDeleteNote") }}
          <EcText class="inline text-c1-800 font-semibold">{{ username }}</EcText>
        </EcText>
        <EcText class="mt-2 text-c0-500">
          {{ $t("user.cfDeleteNote2") }}
        </EcText>
      </EcBox>
      <EcFlex class="justify-center mt-10">
        <EcButton variant="warning" @click="$emit('handleDeleteUser')">
          {{ $t("user.button.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="$emit('handleClickCancelDelete')">
          {{ $t("user.button.cancel") }}
        </EcButton>
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>

<script>
export default {
  emits: ["handleDeleteUser", "handleClickCancelDelete"],
  props: {
    modalDeleteOpen: Boolean,
    username: String,
  },
}
</script>
