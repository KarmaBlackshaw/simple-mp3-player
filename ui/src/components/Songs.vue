<template>
  <v-container>
    <div>
      <v-btn outlined color="success" @click="modals.add.status = 1">Add</v-btn>
    </div>
    <div>
      <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Title</th>
              <th class="text-left">Artist</th>
              <th class="text-left">Album</th>
              <th class="text-left">Duration</th>
              <th class="text-left" width="250">#</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, itemKey) in songs" :key="item.name">
              <td>{{ item.title }}</td>
              <td>{{ item.artist }}</td>
              <td>{{ item.album }}</td>
              <td>{{ item.duration }}</td>
              <td>
                <v-btn
                  class="mr-2"
                  color="primary"
                  outlined
                  @click="handleClickShowEditModal({ item, index: itemKey })"
                  >Edit</v-btn
                >
                <v-btn
                  class="mr-2"
                  color="red"
                  outlined
                  @click="handleDeleteSong({ index: itemKey })"
                  >Delete</v-btn
                >
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>

      <v-dialog v-model="modals.add.status" width="500">
        <v-card>
          <v-card-title class="text-h5 grey lighten-2"> Add Song </v-card-title>

          <v-card-text>
            <v-text-field
              v-model="modals.add.data.title"
              label="Title"
            ></v-text-field>
            <v-text-field
              v-model="modals.add.data.artist"
              label="Artist"
            ></v-text-field>
            <v-text-field
              v-model="modals.add.data.album"
              label="Album"
            ></v-text-field>
            <v-text-field
              v-model="modals.add.data.duration"
              label="Duration"
            ></v-text-field>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="handleClickCreateSong">
              Create
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="modals.edit.status" width="500">
        <v-card>
          <v-card-title class="text-h5 grey lighten-2"> Add Song </v-card-title>

          <v-card-text>
            <v-text-field
              v-model="modals.edit.data.title"
              label="Title"
            ></v-text-field>
            <v-text-field
              v-model="modals.edit.data.artist"
              label="Artist"
            ></v-text-field>
            <v-text-field
              v-model="modals.edit.data.album"
              label="Album"
            ></v-text-field>
            <v-text-field
              v-model="modals.edit.data.duration"
              label="Duration"
            ></v-text-field>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="handleClickEditSong">
              Edit
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </v-container>
</template>

<script>
export default {
  name: "Songs",

  data: () => ({
    modals: {
      add: {
        status: 0,
        data: {
          title: null,
          artist: null,
          album: null,
          duration: null,
        },
        reset() {
          this.status = 0;
          this.data = {
            title: null,
            artist: null,
            album: null,
            duration: null,
          };
        },
      },
      edit: {
        status: 0,
        data: {
          title: null,
          artist: null,
          album: null,
          duration: null,
          index: null,
        },
        reset() {
          this.status = 0;
          this.data = {
            title: null,
            artist: null,
            album: null,
            duration: null,
            index: null,
          };
        },
      },
    },
    songs: [],
  }),

  methods: {
    handleClickCreateSong() {
      const modal = this.modals.add;

      this.songs.push(modal.data);

      modal.reset();
    },

    handleClickShowEditModal({ item, index }) {
      const modal = this.modals.edit;

      modal.status = 1;
      modal.data = {
        ...item,
        index,
      };
    },

    handleClickEditSong() {
      const modal = this.modals.edit;
      const { index, ...data } = modal.data;
      this.songs[index] = data;

      this.songs.__ob__.dep.notify();

      modal.reset();
    },

    handleDeleteSong({ item, index }) {
      this.songs.splice(index, 1);
    },
  },
};
</script>
