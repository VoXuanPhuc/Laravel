export function useBCPStatusEnum() {
  const statuses = [
    {
      name: "Todo",
      value: 1,
      tag_color: "bg-c3-100",
    },
    {
      name: "In Progress",
      value: 2,
      tag_color: "bg-c3-200",
    },
    {
      name: "Needs Attention",
      value: 3,
      tag_color: "bg-c3-300",
    },
    {
      name: "Overdue",
      value: 4,
      tag_color: "bg-c3-400",
    },
    {
      name: "Up to date",
      value: 5,
      tag_color: "bg-c3-500",
    },
  ]

  return {
    statuses,
  }
}
