export default {
  bcp: {
    businessContinuity: "Business Continuity Plan",

    search: "Search",

    deleteFullNote: "",

    confirmToDelete: "Confirm to Delete",
    cfDeleteNote: "You are going to delete ",
    cfDeleteNote2: "This action cannot be reverted. The record will be deleted permanently.",
    optional: "Optional",
    filter: "Filter",

    plans: {
      title: "BCP Report Files",
      reports: "BCP Reports",
    },
    labels: {
      yes: "Yes",
      no: "No",
      from: "From",
      to: "To",
      name: "Plan name",
      description: "Description",
      entityName: "Entity Name",
      bcpName: "BCP Name",
      dueDate: "Due Date",
      category: "Category",
      owners: "Resource owners",
      status: "Status",
      report_num: "# Report",
      createdAt: "Created At",
      deleteBCP: "Delete Resource",
      confirmToDelete: "Confirm to Delete",
      confirmDeleteQuestion: "Are you sure to delete this BCP?",
      noteDeleteBCP:
        "You can permanently delete the Resource from database. This action cannot be reverted and you cannot restore the record anymore.",
    },
    buttons: {
      add: "Add",
      create: "Create",
      update: "Update",
      next: "Next",
      confirm: "Confirm",
      finish: "Finish",
      back: "Back",
      cancel: "Cancel",
      close: "Close",
      exportBCPs: "Export BCPs",
      viewResourceCategories: "Resource Categories",
      viewResourceOwners: "Resource Owners",
      addBCP: "Add BCP",
      edit: "Edit",
      view: "View",
      delete: "Delete",
      deleteBCP: "Delete BCP",
    },

    title: {
      newBCP: "New BCP",
      editBCP: "Update BCP",
      bcpDetail: "BCP Detail",
      remote: "Remote access",
      software: "Software / Technology",
      confirmToCancel: "Confirm to cancel",
      cancelQuestion: "Do you wish to cancel doing BCP?",
    },

    messages: {
      updatedBCP: "Updated BCP, redirect to BCP list...",
      createSuccess: "Create BCP successfully!",
      deleteSuccess: "BCP has been delete successfully!",
      code: "Code",
    },
    // Errors
    errors: {
      updateBCP: "Error to update Resource, please retry",
      listBCPs: "Unable to get list of Resources",
      listCategory: "Unable to get list of Resource Categories",
      listOwner: "Unable to get list of Resource Owners",
      download: "Error occured, please try again",
    },

    placeholders: {
      category: "--- BCPs Category ---",
      loading: "     Loading...   ",
      bu: "--- Business Unit ---",
    },

    category: {
      label: "Category",
      uid: "uid",
      name: "Category",
      title: {
        newCategory: "New Category",
        editCategory: "Edit Category",
        categoryDetail: "Category detail",
      },
      labels: {
        name: "Name",
        description: "Description",

        createdAt: "Created At",
        deleteCategory: "Delete Category",
        confirmDeleteQuestion: "Are you sure to delete this category",
        noteDeleteCategory:
          "You can permanently delete the category from database. This action cannot be reverted and you cannot restore the record anymore.",
      },
      buttons: {
        addCategory: "Add Category",
        delete: "Delete",
        back: "Back",
        cancel: "Cancel",
      },
      messages: {
        createCategory: "Created category",
      },
      errors: {
        createCategory: "Error! Create category failed",
      },
    },
    owner: {
      label: "Resource Owners",
      uid: "uid",
      name: "Resource Owners",
      title: {
        newOwner: "New Owner",
        editOwner: "Edit Owner",
        ownerDetail: "Owner detail",
      },
      labels: {
        name: "Owner name",
        first_name: "Owner name",
        description: "Description",
        invitation_status: "Invite to RBC?",
        createdAt: "Create At",
        createOwner: "Create Resource Owner",
        firstName: "First name",
        lastName: "Last name",
        email: "Email",
        inviteToUser: "Invite user to ReadyBC?",
        deleteOwner: "Delete Owner",
        confirmToDelete: "Confirm to Delete",
        confirmDeleteQuestion: "Are you sure to delete this owner?",
        noteDeleteOwner:
          "You can permanently delete the Owner from database. This action cannot be reverted and you cannot restore the record anymore.",
      },
      buttons: {
        addOwner: "Add Owner",
        create: "Create",
        confirm: "Confirm",
        delete: "Delete",
        cancel: "Cancel",
        back: "Back",
      },
      messages: {
        createdOwner: "Resource owner create successfully",
        updateOwner: "Resource owner has been update successfully",
        deleteOwner: "Deleted",
      },
      errors: {
        createOwner: "Oops! Unable to create resource owner",
        fetchOwner: "Error occured, unable to get owner detail",
        updateOwner: "Error occured, unable to update owner data",
        deleteOwner: "Error occured, can not delete resource owner",
      },
    },
    modal: {
      labels: {},
      buttons: {
        create: "Create",
        cancel: "Cancel",
      },
    },
  },
}
