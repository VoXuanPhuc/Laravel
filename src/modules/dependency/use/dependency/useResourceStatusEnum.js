export function useResourceStatusEnum() {
  const statuses = [
    {
      name: "Free",
      value: 1,
    },
    {
      name: "Allocated",
      value: 2,
    },
    {
      name: "Destroyed",
      value: 3,
    },
  ]

  return {
    statuses,
  }
}
