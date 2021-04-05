<template>
  <div class="float-left mr-2">
    <a data-target="#channelModal" data-toggle="modal" href="#">
      <i aria-hidden="true" class="far fa-edit fa-2x"></i>
    </a>
    <!-- Logout Modal-->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="channelModal" role="dialog"
         tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload images for a channel</h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mx-2">Channel's avatar</div>
            <div class="row m-3">
              <div class="row col-lg-4 mr-4">
                <img :src="user.avatar_file_name" alt="" class="img-avatar" v-if="!avatarFile">
                <img :src="avatarFileName" alt="" class="img-avatar" v-if="avatarFile">
              </div>
              <div class="row col-lg-8 my-auto">
                <div class="mb-2">The required size for the avatar image is 130x130 px</div>
                <input @change="avatarFileInputChange" name="avatarFile" type="file">
              </div>
            </div>
            <div class="mx-3" v-if="avatarFile">{{avatarFileName}}</div>
          </div>
          <hr style="margin:0">
          <div class="modal-body">
            <div class="ml-3">Channel's banner</div>
            <div class="row m-3">
              <div class="row col-lg-4 mr-4">
                <img :src="user.banner_file_name" alt="" class="img-file-place" v-if="!bannerFile">
                <img :src="bannerFileName" alt="" class="img-file-place" v-if="bannerFile">
              </div>
              <div class="row col-lg-8 my-auto">
                <div class="mb-2">The required size for the banner image is 1500x386 px</div>
                <input @change="bannerFileInputChange" name="bannerFile" type="file">
              </div>
            </div>
            <div class="mx-3" v-if="bannerFile">{{bannerFileName}}</div>
          </div>
          <hr style="margin:0">
          <div class="modal-body">
            <div class="ml-3">Channel's photo</div>
            <div class="row m-3">
              <div class="row col-lg-4 mr-4 ">
                <img :src="user.photo_file_name" alt="" class="img-file-place" v-if="!photoFile">
                <img :src="photoFileName" alt="" class="img-file-place" v-if="photoFile">
              </div>
              <div class="row col-lg-8 my-auto">
                <input @change="photoFileInputChange" name="bannerFile" type="file">
              </div>
            </div>
            <div class="mx-3" v-if="photoFile">{{photoFileName}}</div>
          </div>
          <div class="modal-body">
            <div class="ml-3">Channel's title</div>
            <div class="row m-3">
              <div class="row col-lg-12 my-auto">
                <input
                    @change="inputChange"
                    class="form-control"
                    id="channel-title"
                    name="title"
                    type="text"
                    v-model="user.title"
                >
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="ml-3">Channel's description</div>
            <div class="row m-3">
              <div class="row col-lg-12 my-auto">
                <textarea @change="inputChange"
                          class="form-control"
                          id="description"
                          name="description"
                          rows="4"
                          v-model="user.description"
                ></textarea>
              </div>
            </div>
          </div>
          <div class="row mx-3 my-1">
            <div class="col-lg-2">
              <!--<select class="custom-select" v-model="social">
                <option :value="option" v-for="option in options">{{option}}</option>
              </select>-->
              Facebook
            </div>
            <div class="col-lg-9">
              <input
                  @change="inputChange"
                  class="form-control"
                  type="text"
                  v-model="user.social_facebook"
              >
            </div>
          </div>
          <div class="row mx-3 my-1">
            <div class="col-lg-2">Twitter</div>
            <div class="col-lg-9">
              <input
                  @change="inputChange"
                  class="form-control"
                  type="text"
                  v-model="user.social_twitter"
              >
            </div>
          </div>
          <div class="row mx-3 my-1">
            <div class="col-lg-2">LinkedIn</div>
            <div class="col-lg-9">
              <input
                  @change="inputChange"
                  class="form-control"
                  type="text"
                  v-model="user.social_linkedin"
              >
            </div>
          </div>
          <div class="row mx-3 my-1">
            <div class="col-lg-2">Pinterest</div>
            <div class="col-lg-9">
              <input
                  @change="inputChange"
                  class="form-control"
                  type="text"
                  v-model="user.social_pinterest"
              >
            </div>
          </div>
          <div class="row mx-3 my-1">
            <div class="col-lg-2">Instagram</div>
            <div class="col-lg-9">
              <input
                  @change="inputChange"
                  class="form-control"
                  type="text"
                  v-model="user.social_instagram"
              >
            </div>
          </div>

          <div class="modal-footer mt-3">
            <button @click="redirectPage" class="btn btn-secondary" data-dismiss="modal" type="button">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {}
  },
  data () {
    return {
      avatarFile: null,
      bannerFile: null,
      photoFile: null,
      avatarFileName: null,
      bannerFileName: null,
      photoFileName: null
    }
  },
  methods: {
    avatarFileInputChange (e) {
      this.avatarFile = e.target.files[0]
      this.fileUpload()
    },
    bannerFileInputChange (e) {
      this.bannerFile = e.target.files[0]
      this.fileUpload()
    },
    photoFileInputChange (e) {
      this.photoFile = e.target.files[0]
      this.fileUpload()
    },
    inputChange () {this.fileUpload()},

    fileUpload () {
      const formData = new FormData()
      formData.append('avatarFile', this.avatarFile)
      formData.append('bannerFile', this.bannerFile)
      formData.append('photoFile', this.photoFile)
      formData.append('title', this.user.title)
      formData.append('description', this.user.description)
      formData.append('social_facebook', this.user.social_facebook)
      formData.append('social_twitter', this.user.social_twitter)
      formData.append('social_linkedin', this.user.social_linkedin)
      formData.append('social_pinterest', this.user.social_pinterest)
      formData.append('social_instagram', this.user.social_instagram)
      formData.append('user_id', this.user.id)
      axios.post('/channel-store', formData).then(response => {
        this.avatarFileName = response.data?.avatarFileName
        this.bannerFileName = response.data?.bannerFileName
        this.photoFileName = response.data?.photoFileName
      }).catch(error => console.log(error))
    },

    redirectPage () {
      window.location.href = '/single-channel/' + this.user.id
    }
  }
}
</script>
<style scoped>
  .img-avatar-place {
    background: #8781bd;
    border-radius: 50px;
    height: 90px;
    width: 90px;
  }

  .img-avatar {
    border-radius: 50px;
    height: 90px;
    width: 90px;
  }

  .img-file-place {
    max-width: 100%;
    height: auto;
  }
</style>
