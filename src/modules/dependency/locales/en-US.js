export default {
  dependencyScenario: {
    dependencies: "Dependency Scenarios",

    search: "Search",

    deleteFullNote: "",

    confirmToDelete: "Confirm to Delete",
    cfDeleteNote: "You are going to delete ",
    cfDeleteNote2: "This action cannot be reverted. The record will be deleted permanently.",
    optional: "Optional",
    filter: "Filter",

    labels: {
      yes: "Yes",
      no: "No",
      name: "Dependency Scenario name",
      description: "Description",
      entityName: "Entity Name",
      dependencyName: "Dependency Name",
      category: "Category",
      owners: "Dependency owners",
      status: "Status",
      createdAt: "Created At",
      deleteDependency: "Delete dependency",
      confirmToDelete: "Confirm to Delete",
      confirmDeleteQuestion: "Are you sure to delete this dependency scenario?",
      noteDeleteDependency:
        "You can permanently delete the dependency from database. This action cannot be reverted and you cannot restore the record anymore.",
      targets: "Targets",
      upstream: "Upstream",
      downstream: "Downstream",
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
      exportDependencies: "Export Dependencies",
      viewdependencyCategories: "Dependency Categories",
      viewdependencyOwners: "Dependency Owners",
      addDependency: "Add dependency ",
      edit: "Edit",
      view: "View",
      delete: "Delete",
      deleteDependency: "Delete dependency",
    },

    title: {
      newDependency: "New Dependencies Scenario",
      editDependency: "Update Dependency Scenario",
      dependencyDetail: "Dependency Scenario Detail",
      remote: "Remote access",
      software: "Software / Technology",
      confirmToCancel: "Confirm to cancel",
      cancelQuestion: "Do you wish to cancel doing dependency?",
      linksDependencies: "Links Dependencies",
      upstreamDependencies: "Upstream Dependencies",
      downstreamDependencies: "Downstream Dependencies",
    },

    target: {
      label: "Target dependencies",
    },

    messages: {
      updatedDependency: "Updated dependency, redirect to dependency list...",
      createSuccess: "Create dependency successfully!",
      deleteSuccess: "Dependency has been delete successfully!",
      code: "Code",
    },
    // Errors
    errors: {
      updateDependency: "Error to update dependency, please retry",
      listDependency: "Unable to get list of dependencys",
      listCategory: "Unable to get list of dependency Categories",
      listOwner: "Unable to get list of dependency Owners",
      download: "Error occured, please try again",
    },

    placeholders: {
      category: "--- dependency Category ---",
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
      label: "Dependency Owners",
      uid: "uid",
      name: "Dependency Owners",
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
        createOwner: "Create dependency Owner",
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
        createdOwner: "Dependency owner create successfully",
        updateOwner: "Dependency owner has been update successfully",
        deleteOwner: "Deleted",
      },
      errors: {
        createOwner: "Oops! Unable to create dependency owner",
        fetchOwner: "Error occured, unable to get owner detail",
        updateOwner: "Error occured, unable to update owner data",
        deleteOwner: "Error occured, can not delete dependency owner",
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
