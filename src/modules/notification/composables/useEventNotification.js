import { computed, onMounted } from "vue"

export default function useEventNotification() {
  onMounted(() => {
    console.log("we mou - mounted")
  })

  const methods = computed(() => {
    return this.configs?.method?.map((item) => {
      return {
        name: this.ucFirst(item),
        value: item,
      }
    })
  })

  return {
    methods,
  }
}
