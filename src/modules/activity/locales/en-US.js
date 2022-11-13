export default {
  activity: {
    activities: "Activities",

    search: "Search",
    associatedEntity: "Activity Detail",
    deleteFullNote:
      "You can permanently delete the activity from database. This action cannot be reverted and you cannot restore the record anymore.",

    confirmToDelete: "Confirm to Delete",
    cfDeleteNote: "You are going to delete ",
    cfDeleteNote2: "This action cannot be reverted. The record will be deleted permanently.",
    optional: "Optional",
    context: "Context",
    // Delete
    actionCannotBeRevert: "This action cannot be reverted. The record will be deleted permanently.",
    confirmDeleteQuestion: "This action cannot be reverted. Are you sure you want to delete this activity?",
    confirmDeleteAction: "If so, type the activity name into the box below to confirm",

    labels: {
      businessUnit: "Business Unit",
      entityName: "Entity Name",
      activityName: "Activity Name",
      createdAt: "Created At",
      step: "Step",
      yes: "Yes",
      no: "No",
      name: "Name of activity",
      description: "Description",
      roles: "Who is required to deliver this activity?",
      alternative_roles: "Alternate roles that could deliver this activity?",
      min_people: "Minimum number of people that could support this function?",
      wfh: "Can this be delivered remotely?",
      utilities: "Utilities required to support function",
      enableRemote: "What enables remote access?",
      unableEnableRemote: "If unable to deliver remotely, what sites / buildings are required?",
      software: "What applications or software are required?",
      dataStorage: "IT Data Storage",
      data: "Data",
      storageLocation: "Storage location",
      equipments: "Equipment requirements",
      status: "Status",
    },
    buttons: {
      add: "Add",
      create: "Create",
      update: "Update",
      next: "Next",
      finish: "Finish",
      back: "Back",
      cancel: "Cancel",
      close: "Close",
      exportActivities: "Export Activities",
      timeImpactAnalysis: "Time Impact Analysis",
      addActivity: "Add activity ",
      edit: "Edit",
      view: "View",
      confirm: "Confirm",
      delete: "Delete",
      deleteActivity: "Delete Activity",
    },

    title: {
      newActivity: "New Activity",
      editActivity: "Update Activity",
      activityDetail: "Activity Detail",
      remote: "Remote access",
      software: "Software / Technology",
      tolerant: "Maximum Tolerable Period of Disruption (MTPD)",
      rto: "Recovery Time Objective (RTO)",
      confirmToCancel: "Confirm to cancel",
      cancelQuestion: "Do you wish to cancel doing activity?",
    },

    messages: {
      updatedActivity: "Updated activity, redirect to activity list...",
      toNextStep: "Updated activity, redirect to next step...",
      createSuccess: "Create activity successfully!",
      deleteSuccess: "Activity has been delete successfully!",
      code: "Code",
    },
    // Errors
    errors: {
      listDivision: "Unable to retrive corresponding division",
      updateActivity: "Error to update activity, please retry",
      listActivity: "Unable get list of Activities",
      download: "Error occured, please try again",
    },

    placeholders: {
      loading: "Loading...",
      division: "--- Division ---",
      bu: "--- Business Unit ---",
    },

    assignee: {
      label: "Assignee",
      uid: "uid",
      name: "Assignee",
    },

    division: {
      label: "Division",
      uid: "uid",
      name: "Division",
    },
    bu: {
      label: "Business Unit",
      uid: "uid",
      name: "Business Unit",
    },

    tooltips: {
      cancel: "Cancel doing activity",
      tolerantTime:
        "If the disruption impact is dependent on the time of the week/month/year that the disruption occurs, please use ‘worst case’ scenario to assess MTPD and explain below",
      rtoPeriod: "If you are unsure, select the timeframe that is half the MTPD.",
    },

    tolerant: {
      time: "How long could the business operate without this function before it becomes a ‘moderate’ impact according to your risk scales?",
      timeOf: "If dependent on time of the week/month/year, please outline here:",
      reason: "Justification for MTPD - why have you chosen this time?",
    },

    rto: {
      period:
        "Period of time within which minimum levels of services and/or products and the supporting systems, applications, or functions are to be recovered after a disruption has occurred.",
      testedRealTime: "Has the RTO been tested in real-time?",
      scenario: "What scenario/s would disrupt this activity the most?",
      existingWorkaround: "What is the existing workarounds for {disruptionName} for this activity?",
      workaroundSolution: "Please enter your solution for {disruptionName}",
      workaroundEnacted: "How long could this workaround feasibly be enacted?",
    },
  },
}
