<template>
  <v-container fluid>
    <v-card class="pa-5">
      <v-card-title class="d-flex justify-space-between">
        <div>
          {{ isEdit ? "Edit" : "Add" }} Group {{ group?.title ? `#${group.title}` : "" }}
        </div>
        <v-btn color="primary" :to="{ name: 'groups.index' }">Groups</v-btn>
      </v-card-title>

      <v-form ref="form" @submit.prevent="handleSubmit">
        <v-row>
          <!-- Group Name -->
          <v-col cols="12" md="4">
            <v-text-field
              v-model="form.name"
              label="Name"
              :rules="[rules.required]"
              required
            />
          </v-col>

          <!-- Description -->
          <v-col cols="12" md="8">
            <v-textarea
              v-model="form.description"
              label="Description"
              :rules="[rules.required]"
              required
            />
          </v-col>

          <!-- User Search and Selection -->
          <v-col cols="12" md="6">
            <v-text-field
              v-model="userSearch"
              label="Add Members"
              @input="debouncedSearch"
              append-icon="mdi-magnify"
            />

            <v-list v-if="searchResults.length">
              <v-list-item
                v-for="user in searchResults"
                :key="user.id"
                @click="selectUser(user)"
                class="hoverable"
              >
                <v-list-item-content>
                  <v-list-item-title
                    >{{ user.name }} ({{ user.email }})</v-list-item-title
                  >
                </v-list-item-content>
              </v-list-item>
            </v-list>

            <div class="mt-3">
              <v-chip
                v-for="(user, index) in selectedUsers"
                :key="user.id"
                close
                @click:close="removeUser(index)"
                class="ma-1"
                color="info"
                text-color="white"
              >
                {{ user.name }} ({{ user.email }})
              </v-chip>
            </div>
          </v-col>

          <!-- Current Members if editing -->
          <v-col cols="12" md="6" v-if="isEdit && group.groupMembers.length">
            <v-list two-line>
              <v-subheader>Current Members</v-subheader>
              <v-list-item v-for="member in group.groupMembers" :key="member.id">
                <v-list-item-content>
                  <v-list-item-title
                    >{{ member.name }} ({{ member.email }})</v-list-item-title
                  >
                </v-list-item-content>
                <v-list-item-action>
                  <v-chip
                    :color="member.pivot.status === 'accepted' ? 'success' : 'warning'"
                  >
                    {{ member.pivot.status }}
                  </v-chip>
                </v-list-item-action>
              </v-list-item>
            </v-list>
          </v-col>

          <!-- Submit Button -->
          <v-col cols="12" class="text-right">
            <v-btn color="success" type="submit">{{ isEdit ? "Update" : "Add" }}</v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import debounce from "lodash/debounce";

export default {
  props: {
    group: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      form: {
        name: this.group?.name || "",
        description: this.group?.description || "",
      },
      userSearch: "",
      searchResults: [],
      selectedUsers: [],
      rules: {
        required: (v) => !!v || "Required",
      },
    };
  },
  computed: {
    isEdit() {
      return !!this.group;
    },
  },
  methods: {
    async searchUsers() {
      if (this.userSearch.length < 2) return;
      const res = await fetch(`/users/search?q=${encodeURIComponent(this.userSearch)}`);
      this.searchResults = await res.json();
    },
    selectUser(user) {
      if (!this.selectedUsers.some((u) => u.id === user.id)) {
        this.selectedUsers.push(user);
      }
      this.searchResults = [];
      this.userSearch = "";
    },
    removeUser(index) {
      this.selectedUsers.splice(index, 1);
    },
    async handleSubmit() {
      const payload = {
        ...this.form,
        selected_user_ids: this.selectedUsers.map((u) => u.id),
      };
      const url = this.isEdit ? `/groups/${this.group.id}` : "/groups";
      const method = this.isEdit ? "PUT" : "POST";

      await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify(payload),
      }).then(() => {
        this.$router.push({ name: "groups.index" });
      });
    },
  },
  created() {
    this.debouncedSearch = debounce(this.searchUsers, 300);
  },
};
</script>

<style scoped>
.hoverable:hover {
  background: #f5f5f5;
  cursor: pointer;
}
</style>
