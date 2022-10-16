export function useDependencyScenarioStatusEnum() {
  const statuses = [
    {
      name: "Created",
      value: 1,
    },
    {
      name: "In Progress",
      value: 2,
    },
    {
      name: "In Review",
      value: 3,
    },
    {
      name: "Finished",
      value: 4,
    },
  ]

  return {
    statuses,
  }
}
