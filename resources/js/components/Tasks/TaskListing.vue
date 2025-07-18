<template>
  <v-app>
    <!-- header -->
    <v-card-title>
      <!-- add button on the right -->
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="createTask">Add Task </v-btn>
    </v-card-title>
    <v-card-text>
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
          {{ tab.name }}
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

        <!-- show priority in badges -->
        <template v-slot:item.priority="{ item }">
          <v-chip color="warning" small class="ma-1" dark>
            {{ item.priority }}
          </v-chip>
        </template>

        <template v-slot:item.due_datetime="{ item }">
          <v-chip color="info" small class="ma-1" dark>
            {{ item.due_datetime }}
          </v-chip>
        </template>

        <template v-slot:item.status="{ item }">
          <v-chip color="danger" small class="ma-1" dark>
            {{ item.status }}
          </v-chip>
        </template>

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
      selectedTab: null,
      loading: false,
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
    };
  },
  mounted() {
    this.fetchStatuses();
  },
  methods: {
    fetchStatuses() {
      this.loading = true;
      axios
        .get("/task-statuses")
        .then((res) => {
          this.statuses = res.data;
          if (this.statuses.length) {
            this.selectedTab = 0;
            this.fetchTasks(this.statuses[0].id);
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchTasks(statusId) {
      this.loading = true;
      axios
        .get(`get-tasks?status_id=${statusId}`)
        .then((res) => {
          this.tasks = res.data.tasks;
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
  },
};
</script>
