<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      fab
      dark
      large
      color="blue darken-1"
    >
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            MP3 Player
          </v-list-item-title>
          <v-list-item-subtitle> Colin Ripalda </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider />

      <v-list
        dense
        nav
        subheader
      >
        <v-subheader inset>
          Songs
        </v-subheader>

        <v-list-item-group>
          <v-list-item @click="modals.addSong.status = 1">
            <v-list-item-icon>
              <v-icon> mdi-music-note-plus </v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Upload Song</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item>
            <v-list-item-icon>
              <v-icon>mdi-music-circle</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>All Song</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>

        <v-divider />

        <v-subheader inset>
          Playlist
        </v-subheader>

        <v-list-item-group>
          <v-list-item @click="modals.addPlaylist.status = 1">
            <v-list-item-icon>
              <v-icon> mdi-playlist-music </v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Create Playlist</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            v-for="(currPlaylist, playlistKey) in playlists"
            :key="playlistKey"
          >
            <v-list-item-icon class="ml-5">
              <v-icon small>
                mdi-folder-music
              </v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ currPlaylist.name }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      flat
      color="white"
    >
      <v-app-bar-nav-icon @click="drawer = !drawer" />

      <v-toolbar-title>ALL SONGS</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <div>
          <v-simple-table
            fab
            large
            color="blue darken-1"
          >
            >
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">
                    Title
                  </th>
                  <th class="text-left">
                    Artist
                  </th>
                  <th class="text-left">
                    Album
                  </th>
                  <th class="text-left">
                    Duration
                  </th>
                  <th class="text-left">
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, itemKey) in songs"
                  :key="itemKey"
                  :class="{
                    'blue lighten-4': playing && playing.index === itemKey
                  }"
                >
                  <td>{{ item.title }}</td>
                  <td>{{ item.artist }}</td>
                  <td>{{ item.album }}</td>
                  <td>{{ getAudioDuration(item.audio.duration) }}</td>
                  <td>
                    <v-btn
                      text
                      small
                      rounded
                      @click="handleClickPlay({ item, itemKey })"
                    >
                      <v-icon>mdi-play</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </div>
      </v-container>
    </v-main>

    <v-footer
      padless
      inset
      app
    >
      <v-col
        cols="12"
        class="pa-0"
      >
        <v-card
          v-if="playing"
          flat
          color="transparent"
          class="pa-0 ma-0"
        >
          <v-progress-linear
            :value="playing.time"
            class="my-0"
            height="3"
          />

          <v-list>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>{{ playing.name }}</v-list-item-title>
                <v-list-item-subtitle>
                  {{ playing.artist }} / {{ playing.album }}
                </v-list-item-subtitle>
              </v-list-item-content>

              <v-spacer />

              <v-list-item-icon>
                <v-btn icon>
                  <v-icon>mdi-rewind</v-icon>
                </v-btn>
              </v-list-item-icon>

              <v-list-item-icon
                :class="{ 'mx-5': $vuetify.breakpoint.mdAndUp }"
              >
                {{ isPlaying }}
                <v-btn
                  v-if="!playing.audio.paused"
                  icon
                  @click="playing.audio.pause()"
                >
                  <v-icon>mdi-pause</v-icon>
                </v-btn>

                <v-btn
                  v-else
                  icon
                  @click="playing.audio.play()"
                >
                  <v-icon>mdi-play</v-icon>
                </v-btn>
              </v-list-item-icon>

              <v-list-item-icon
                class="ml-0"
                :class="{ 'mr-3': $vuetify.breakpoint.mdAndUp }"
              >
                <v-btn icon>
                  <v-icon>mdi-fast-forward</v-icon>
                </v-btn>
              </v-list-item-icon>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-footer>

    <v-dialog
      v-model="modals.addSong.status"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Add Song
        </v-card-title>

        <v-card-text>
          <v-text-field
            v-model="modals.addSong.data.title"
            label="Title"
          />

          <v-text-field
            v-model="modals.addSong.data.artist"
            label="Artist"
          />

          <v-text-field
            v-model="modals.addSong.data.album"
            label="Album"
          />

          <v-file-input
            v-model="modals.addSong.data.file"
            accept="audio/*"
            label="Song"
          />
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="handleClickCreateSong"
          >
            Create
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="modals.editSong.status"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Add Song
        </v-card-title>

        <v-card-text>
          <v-text-field
            v-model="modals.editSong.data.title"
            label="Title"
          />
          <v-text-field
            v-model="modals.editSong.data.artist"
            label="Artist"
          />
          <v-text-field
            v-model="modals.editSong.data.album"
            label="Album"
          />
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="handleClickEditSong"
          >
            Edit
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="modals.addPlaylist.status"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Create Playlist
        </v-card-title>

        <v-card-text>
          <v-text-field
            v-model="modals.addPlaylist.data.name"
            label="Name"
          />
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="handleClickCreatePlaylist"
          >
            Create
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axios from '@/plugins/axios'

export default {
  data: () => ({
    drawer: 1,

    playing: null,

    modals: {
      addSong: {
        status: 0,
        data: {
          title: null,
          artist: null,
          album: null,
          file: null
        },
        reset() {
          this.status = 0;
          this.data = {
            title: null,
            artist: null,
            album: null,
            file: null
          };
        }
      },

      editSong: {
        status: 0,
        data: {
          title: null,
          artist: null,
          album: null,
          duration: null,
          index: null
        },
        reset() {
          this.status = 0;
          this.data = {
            title: null,
            artist: null,
            album: null,
            duration: null,
            index: null
          };
        }
      },

      addPlaylist: {
        status: 0,
        data: {
          name: null
        },
        reset() {
          this.status = 0;
          this.data = {
            name: null
          };
        }
      }
    },

    songs: [],

    playlists: []
  }),

  computed: {
    isPlaying() {
      const currentPlaying = this.playing

      if (!currentPlaying) {
        return false
      }

      if (!currentPlaying.paused || currentPlaying.currentTime) {
        return true
      }

      return true
    }
  },

  async created () {
    await this.getPlaylist()
    await this.getSongs()
  },

  methods: {
    getAudioDuration(time){
      let mins = Math.floor(time / 60);
      if (mins < 10) {
        mins = '0' + String(mins);
      }
      let secs = Math.floor(time % 60);
      if (secs < 10) {
        secs = '0' + String(secs);
      }

      return mins + ':' + secs;
    },

    handleClickShowEditModal({ item, index }) {
      const modal = this.modals.editSong;

      modal.status = 1;
      modal.data = {
        ...item,
        index
      };
    },

    handleClickEditSong() {
      const modal = this.modals.editSong;
      const { index, ...data } = modal.data;
      this.songs[index] = data;

      this.songs.__ob__.dep.notify();

      modal.reset();
    },

    handleDeleteSong({ index }) {
      this.songs.splice(index, 1);
    },

    handleClickPlay ({item, itemKey}) {
      if (this.playing) {
        this.playing.audio.pause();
        this.playing = null
      }

      this.playing = {...item, index: itemKey}

      this.playing.audio.play()

      const duration = this.playing.audio.duration
      this.playing.audio.addEventListener('timeupdate',  () => {
        this.playing.time = (this.playing.audio.currentTime.toFixed() / duration) * 100
        this.playing.__ob__.dep.notify();
      })

    },

    async getPlaylist () {
      try {
        const response = await axios.get('/playlists')

        this.playlists = response.data

        return response.data
      } catch (error) {
        console.log(error)
      }
    },

    async handleClickCreatePlaylist () {
      try {
        const modal = this.modals.addPlaylist;

        await axios.post('/playlists', {
          name: modal.data.name
        })

        await this.getPlaylist()

        modal.reset()
      } catch (error) {
        console.log(error)
      }
    },

    async getSongs () {
      try {
        const response = await axios.get('/songs')

        const songs = response.data

        for (let i = 0; i < songs.length; i++) {
          const curr = songs[i];
          curr.audio = new Audio(`http://localhost:8000/storage/${curr.path}`)
        }

        this.songs = songs

        return response.data
      } catch (error) {
        console.log(error)
      }
    },

    async handleClickCreateSong() {
      try {
        const modal = this.modals.addSong;

        const formData = new FormData();

        formData.append('title', modal.data.title)
        formData.append('artist', modal.data.artist)
        formData.append('album', modal.data.album)
        formData.append('file', modal.data.file)

        const repsonse = await axios.post('/songs', formData)
        console.log(repsonse)

        await this.getSongs()

        modal.reset();
      } catch (error) {
        console.log(error)
      }
    }
  }
};
</script>
