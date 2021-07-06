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

          <v-list-item
            :input-value="0"
            inactive
            style="cursor: pointer"
            @click="$router.push(`/`)"
          >
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
            :input-value="Number($route.params.playlist_id) === Number(currPlaylist.id)"
          >
            <v-list-item-icon class="ml-5">
              <v-icon small>
                mdi-folder-music
              </v-icon>
            </v-list-item-icon>

            <v-list-item-content @click.stop="$router.push(`/${currPlaylist.id}`)">
              <v-list-item-title>{{ currPlaylist.name }}</v-list-item-title>
            </v-list-item-content>

            <v-btn
              text
              x-small
              fab
              @click="handleClickOpenEditPlaylist({ item: currPlaylist })"
            >
              <v-icon small>
                mdi-pencil
              </v-icon>
            </v-btn>

            <v-btn
              text
              x-small
              fab
              @click="handleClickDeletePlaylist({ item: currPlaylist })"
            >
              <v-icon
                small
                color="red"
              >
                mdi-delete
              </v-icon>
            </v-btn>
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
      <v-container
        v-if="dataStatus.isLoading"
        class="d-flex justify-center align-center display-1 font-weight-light"
        style="height: 100%"
      >
        Loading...
      </v-container>

      <v-container
        v-else-if="dataStatus.isEmpty"
        class="d-flex justify-center align-center display-1 font-weight-light"
        style="height: 100%"
      >
        No songs available 😥
      </v-container>

      <v-container
        v-else
        fluid
      >
        <div>
          <v-simple-table
            fab
            large
            color="blue darken-1"
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
                  <th
                    class="text-left"
                  >
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
                  <td>{{ item.duration }}</td>
                  <td>
                    <v-btn
                      text
                      small
                      rounded
                      @click="handleClickPlay({ item, itemKey })"
                    >
                      <v-icon>mdi-play</v-icon>
                    </v-btn>

                    <v-btn
                      v-if="!$route.params.playlist_id"
                      text
                      small
                      rounded
                      @click="handleClickOpenAddToPlaylist({ item })"
                    >
                      <v-icon>mdi-playlist-plus</v-icon>
                    </v-btn>

                    <v-btn
                      v-if="$route.params.playlist_id"
                      text
                      small
                      rounded
                      color="red"
                      @click="handleClickRemoveFromPlaylist({ item })"
                    >
                      <v-icon>mdi-playlist-remove</v-icon>
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

              <v-list-item-icon :class="{ 'mx-5': $vuetify.breakpoint.mdAndUp }">
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

    <v-dialog
      v-model="modals.editPlaylist.status"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Edit Playlist
        </v-card-title>

        <v-card-text>
          <v-text-field
            v-model="modals.editPlaylist.data.name"
            label="Name"
          />
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="handleClickEditPlaylist"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="modals.addSongToPlaylist.status"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Add Song to Playlist
        </v-card-title>

        <v-card-text>
          <v-list rounded>
            <v-subheader>Playlists</v-subheader>
            <v-list-item-group color="primary">
              <v-list-item
                v-for="(currPlaylist, playlistKey) in playlists"
                :key="playlistKey"
                inactive
                style="cursor: pointer"
                @click="handleClickAddToPlaylist({ item: currPlaylist })"
              >
                <v-list-item-content>
                  <v-list-item-title v-text="currPlaylist.name" />
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axios from '@/plugins/axios'
import _isEmpty from 'lodash/isEmpty'
import Swal from 'sweetalert2'

export default {
  data: () => ({
    drawer: 1,

    playing: null,

    dataStatus: {
      isLoading: true,
      isEmpty: false
    },

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
      },

      editPlaylist: {
        status: 0,
        data: {
          id: null,
          name: null
        },
        reset() {
          this.status = 0;
          this.data = {
            id: null,
            name: null
          };
        }
      },

      addSongToPlaylist: {
        status: 0,
        data: {
          song_id: null,
          playlist_id: null
        },
        reset() {
          this.status = 0;
          this.data = {
            song_id: null,
            playlist_id: null
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

      if (currentPlaying.paused) {
        return false
      }

      if (!currentPlaying.currentTime) {
        return false
      }

      return true
    }
  },

  watch: {
    '$route': {
      async handler (val) {
        await this.getSongs({
          playlist_id: val.params.playlist_id
        })
      },

      immediate: true
    }
  },

  async created () {
    await this.getPlaylist()
  },

  methods: {
    swalSuccess () {
      return Swal.fire({
        title: 'Success!',
        text: 'Your request has been processed successfully!',
        icon: 'success'
      })
    },

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

        return this.swalSuccess()
      } catch (error) {
        console.log(error)
      }
    },

    async getSongs (payload) {
      try {
        this.dataStatus.isLoading = 1

        const apiUrl = process.env.VUE_APP_API_URL
        const params = {
          ...payload,
          playlist_id: this.$route.params.playlist_id
        }

        const response = await axios.get('/songs', {params})

        const songs = response.data
        const songsLength = songs.length

        for (let i = 0; i < songsLength; i++) {
          const curr = songs[i];
          curr.audio = new Audio()
          // curr.audio.canplaythrough = loadedAudio(i)
          curr.audio.src = `${apiUrl}/storage/${curr.path}`
        }

        this.dataStatus.isLoading = 0
        this.dataStatus.isEmpty = _isEmpty(songs)
        this.songs = songs

        return response.data
      } catch (error) {
        console.log(error)
      }
    },

    handleClickCreateSong() {
      try {
        const modal = this.modals.addSong;

        const formData = new FormData();

        formData.append('title', modal.data.title)
        formData.append('artist', modal.data.artist)
        formData.append('album', modal.data.album)
        formData.append('file', modal.data.file)

        const audio = document.createElement('audio');
        const reader = new FileReader();

        const vInstance = this

        if (modal.data.file) {
          reader.onload = function (e) {
            audio.src = e.target.result;
            audio.addEventListener('loadedmetadata', async () => {
              const durationArr = String(audio.duration).split('.');
              durationArr[1] = durationArr[1].substr(0, 2)

              formData.append('duration', durationArr.join(':'))

              await axios.post('/songs', formData)

              await vInstance.getSongs()

              modal.reset();

              return vInstance.swalSuccess()
            },false);
          };

          reader.readAsDataURL(modal.data.file);
        }

      } catch (error) {
        console.log(error)
      }
    },

    handleClickOpenAddToPlaylist ({item}) {
      const modal = this.modals.addSongToPlaylist
      modal.data.song_id = item.id
      modal.status = true
    },

    async handleClickAddToPlaylist ({item}) {
      try {
        const modal = this.modals.addSongToPlaylist
        modal.data.playlist_id = item.id

        await axios.post('/song-playlists', modal.data)

        modal.status = 0

        return this.swalSuccess()
      } catch (error) {
        console.log(error)
      }
    },

    handleClickOpenEditPlaylist ({item}) {
      const modal = this.modals.editPlaylist

      modal.data.id = item.id
      modal.data.name = item.name

      modal.status = 1
    },

    async handleClickEditPlaylist () {
      const modal = this.modals.editPlaylist

      try {
        const data = {
          playlist_id: modal.data.id,
          name: modal.data.name
        }

        await axios.patch('/playlists', data)

        await this.getPlaylist()

        modal.status = 0

        return this.swalSuccess()
      } catch (error) {
        console.log(error)
      }
    },

    async handleClickDeletePlaylist ({item}) {
      try {
        const data = {
          playlist_id: item.id
        }

        await axios.delete('/playlists', {data})

        await this.getPlaylist()

        return this.swalSuccess()
      } catch (error) {
        console.log(error)
      }
    },

    async handleClickRemoveFromPlaylist ({item}) {
      try {
        const data = {
          song_id: item.id,
          playlist_id: this.$route.params.playlist_id
        }

        await axios.delete('/song-playlists', {data})

        await this.getSongs()

        return this.swalSuccess()

      } catch (error) {
        console.log(error)
      }
    }
  }
};
</script>
