<template>
  <v-app>
    <v-card-title>
      <!-- add button on the right -->
      <v-spacer></v-spacer>

      <v-btn color="success" @click="downloadSampleExcel">Download Sample Excel </v-btn>
      <v-btn color="primary" @click="createTask">Add Task </v-btn>
    </v-card-title>
    <v-card-text>
      <v-sheet elevation="1" class="pa-4 mb-4" color="white" rounded>
        <v-row dense>
          <!-- Assignee Filter -->
          <v-col cols="12" md="4">
            <v-autocomplete
              v-model="filters.assignees"
              :items="allAssignees"
              item-value="id"
              item-text="name"
              label="Assignees"
              multiple
              chips
              dense
              clearable
              hide-details
              class="custom-input"
              @change="applyFilters"
            />
          </v-col>
          <!-- Group Filter -->

          <v-col cols="12" md="4" class="d-flex align-center">
            <v-switch
              v-model="filters.created_by_me"
              label="Created by Me"
              inset
              hide-details
              class="mt-2"
              @change="applyFilters"
            />
          </v-col>

          <v-col cols="12" md="4">
            <v-row dense class="d-flex align-center justify-end">
              <v-col cols="8">
                <v-file-input
                  v-model="importFile"
                  label="Import Tasks (Excel)"
                  accept=".xls,.xlsx"
                  dense
                  hide-details
                  @change="onFileSelected"
                ></v-file-input>
              </v-col>
              <v-col cols="3" class="d-flex align-center">
                <v-btn
                  color="success"
                  :disabled="!importFile"
                  @click="uploadImportFile"
                  block
                  small
                >
                  Import
                </v-btn>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-sheet>

      <v-tabs
        center-active
        v-model="selectedTab"
        class="flex-grow-1"
        next-icon="mdi-arrow-right-bold-box-outline"
        prev-icon="mdi-arrow-left-bold-box-outline"
        show-arrows
        @change="onTabChange"
      >
        <v-tab v-for="(tab, ind) in statuses" :key="ind">
          {{ tab.name }} ({{ tab.total_tasks || 0 }})
        </v-tab>
      </v-tabs>
      <v-data-table
        :headers="headers"
        :items="tasks"
        :loading="loading"
        class="elevation-1"
        item-key="id"
      >
        <template v-slot:item.index="{ index }">
          {{ index + 1 }}
        </template>

        <template v-slot:item.assignies="{ item }">
          <v-chip
            v-for="user in item.assignies"
            :key="user.id"
            color="primary"
            small
            class="ma-1"
            dark
          >
            {{ user.name }}
          </v-chip>
        </template>

        <template v-slot:item.status="{ item }">
          <v-select
            :items="statuses"
            item-value="id"
            item-text="name"
            v-model="item.status.id"
            x-small
            chips
            @change="updateTaskStatus(item)"
          />
        </template>

        <template v-slot:item.priority="{ item }">
          <v-select
            :items="priorities"
            item-value="id"
            item-text="name"
            v-model="item.priority.id"
            x-small
            chips
            color="primary"
            @change="updateTaskPriority(item)"
          />
        </template>

        <template v-slot:item.due_datetime="{ item }">
          <v-chip color="info" small class="ma-1" dark>
            {{ item.due_datetime }}
          </v-chip>
        </template>

        <!-- <template v-slot:item.group="{ item }">
          <v-chip color="success" small class="ma-1" dark>
            {{ item.group ? item.group.name : "No Group" }}
          </v-chip>
        </template> -->

        <template v-slot:item.actions="{ item }">
          <v-btn
            x-small
            fab
            dark
            color="primary"
            v-if="item.can_view"
            @click="viewTask(item.id)"
          >
            <v-icon>mdi-eye</v-icon>
          </v-btn>
          <v-btn
            x-small
            fab
            dark
            color="secondary"
            v-if="item.can_edit"
            @click="editTask(item.id)"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn
            x-small
            fab
            dark
            color="error"
            v-if="item.can_delete"
            @click="deleteTask(item.id)"
          >
            <v-icon>mdi-delete</v-icon>
          </v-btn>
          <v-btn
            x-small
            fab
            dark
            color="success"
            v-if="item.can_delete"
            @click="exportTask(item.id)"
          >
            <v-icon>mdi-export</v-icon>
          </v-btn>
        </template>
      </v-data-table>
    </v-card-text>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      statuses: [],
      tasks: [],
      selectedTab: 0,
      loading: false,
      priorities: [],
      headers: [
        { text: "#", value: "index", sortable: true },
        { text: "Title", value: "title", sortable: false },
        { text: "Description", value: "description", sortable: false },
        { text: "Created By", value: "created_by", sortable: false },
        { text: "Assignies", value: "assignies", sortable: false },
        { text: "Priority", value: "priority", sortable: false },
        { text: "Status", value: "status", sortable: false },
        { text: "Due Date", value: "due_datetime", sortable: true },
        { text: "Created_at", value: "created_at", sortable: true },
        { text: "Actions", value: "actions", sortable: false, align: "end" },
      ],
      filters: {
        assignees: [],
        groups: [],
        created_by_me: false,
      },
      allAssignees: [],
      allGroups: [],
      importFile: null,
    };
  },
  mounted() {
    this.fetchStatuses();
  },
  methods: {
    downloadSampleExcel() {
      window.open("/sample-excel/task-create-sample.xlsx", "_blank");
    },
    onFileSelected(file) {
      this.importFile = file;
    },
    uploadImportFile() {
      if (!this.importFile) return;

      const formData = new FormData();
      formData.append("file", this.importFile);

      this.loading = true;
      axios
        .post("/tasks/import", formData)
        .then(() => {
          this.fetchStatuses(); // Refresh tasks
          alert("Tasks imported successfully.");
        })
        .catch((error) => {
          console.error(error);
          alert("Failed to import tasks.");
        })
        .finally(() => {
          this.loading = false;
          this.importFile = null;
        });
    },

    applyFilters() {
      const statusId = this.statuses[this.selectedTab].id;
      this.fetchTasks(statusId);
    },

    fetchStatuses() {
      this.loading = true;
      axios
        .get("/task-statuses")
        .then((res) => {
          this.statuses = res.data.statuses;
          this.priorities = res.data.priorities || [];
          this.allAssignees = res.data.users || [];
          this.allGroups = res.data.groups || [];
          if (this.statuses.length) {
            this.fetchTasks(
              this.selectedTab ? this.statuses[this.selectedTab].id : this.statuses[0].id
            );
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchTasks(statusId) {
      this.loading = true;
      const params = new URLSearchParams();
      params.append("status_id", statusId);
      if (this.filters.created_by_me) {
        params.append("created_by_me", 1);
      }
      if (this.filters.assignees.length) {
        this.filters.assignees.forEach((id) => params.append("assignees[]", id));
      }
      if (this.filters.groups.length) {
        this.filters.groups.forEach((id) => params.append("groups[]", id));
      }
      axios
        .get(`get-tasks?${params.toString()}`)
        .then((res) => {
          this.tasks = res.data.tasks;
        })
        .catch((error) => {
          console.error("Error fetching tasks:", error.response);
          alert("Failed to fetch tasks.");
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onTabChange(newTabIndex) {
      const statusId = this.statuses[newTabIndex].id;
      this.fetchTasks(statusId);
    },
    createTask() {
      window.location.href = "/tasks/create";
    },
    viewTask(id) {
      window.location.href = `/tasks/${id}`;
    },
    editTask(id) {
      window.location.href = `/tasks/${id}/edit`;
    },
    deleteTask(id) {
      if (confirm("Are you sure you want to delete this task?")) {
        axios
          .delete(`/tasks/${id}`)
          .then(() => {
            this.fetchTasks(this.statuses[this.selectedTab].id);
          })
          .catch((error) => {
            console.error("Error deleting task:", error);
            alert("Failed to delete task.");
          });
      }
    },
    updateTaskStatus(item) {
      this.loading = true;
      axios
        .put(`/tasks/${item.id}/update-status`, { status_id: item.status.id })
        .then(() => {
          this.fetchStatuses();
          this.loading = false;
        })
        .catch((error) => {
          console.error("Error updating task status:", error);
          alert("Failed to update task status.");
        })
        .finally(() => {
          this.loading = false;
        });
    },
    updateTaskPriority(item) {
      this.loading = true;
      axios
        .put(`/tasks/${item.id}/update-priority`, { priority_id: item.priority.id })
        .then(() => {
          this.fetchStatuses();
          this.loading = false;
        })
        .catch((error) => {
          console.error("Error updating task priority:", error);
          alert("Failed to update task priority.");
        })
        .finally(() => {
          this.loading = false;
        });
    },
    exportTask(id) {
      window.open(`/tasks/${id}/export`, "_blank");
    },
  },
};
</script>
