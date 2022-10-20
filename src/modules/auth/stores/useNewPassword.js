import { defineStore } from "pinia"

export const useNewPasswordStore = defineStore("newPassword", {
  state: () => ({
    newPasswordChallenge: {},
  }),

  getters: {
    getNewPasswordChallenge() {
      return this.newPasswordChallenge
    },
  },
  actions: {
    setNewPasswordChallenge(challengeData) {
      this.newPasswordChallenge = challengeData
    },
  },
})
