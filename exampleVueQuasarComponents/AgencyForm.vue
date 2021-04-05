<template>
  <q-page>
    <div class="q-pt-xl q-mx-lg">
      <q-card class="q-mx-auto" style="max-width: 1200px">
        <div class="doc-example q-mb-lg q-card q-card--bordered q-card--flat no-shadow">
          <div class="q-toolbar row no-wrap items-center doc-example__toolbar bg-grey-1">
            <div class="doc-card-title">Agency data</div>
            <div class="doc-card-title q-mx-md">agency ID: {{ agency.id}}</div>
            <q-space></q-space>
            <q-btn @click="onSubmit" flat>
              <q-icon class="text-grey" name="far fa-save"/>
            </q-btn>
          </div>
        </div>
        <div class="row q-pa-md q-ma-md">
          <div class="column col-12 col-md-5 col-lg-4 col-xl-4 q-pa-sm items-center">
            <div class="img-delete-btn-container">
              <q-img :src="agency.imageUrl ? agency.imageUrl : 'statics/avatars/agency_avatar_default.png'"
                     class="q-mb-md img-responsive" style="max-height: 300px; width: 300px; border-radius: 50%"/>
              <q-btn @click="showImageDeleteDialog=true" class="img-delete-btn" color="primary" icon="delete" outline
                     round v-if="agency.id"/>
            </div>
            <q-uploader
                :factory="onUploadImage"
                accept=".jpg,.jpeg,.png,.gif,.webp"
                ref="imageUploader"
                stack-label="upload image"
            />
          </div>
          <div class="column col-12 col-md-7 col-lg-8 col-xl-8 q-mt-sm">
            <q-input
                :rules="[val => !!val || 'Field is required']"
                label="Name *"
                outlined
                ref="name"
                v-model="agency.name"
            />
            <div v-if="!agency.city" class="q-gutter-y-md">
              <q-select
                  :options="cityOptions"
                  :rules="[val => !!val || 'Field is required']"
                  @input="panel=agency.city"
                  @filter="filterAutocompleteCity"
                  emit-value
                  fill-input
                  hide-selected
                  input-debounce="0"
                  label="City *"
                  map-options
                  option-label="city"
                  option-value="city"
                  outlined
                  ref="city"
                  use-input
                  v-model="agency.city"
              >
                <template v-slot:after>
                  <q-btn @click="showAddNewCityToListDialog = true" flat icon="add" round>
                    <q-tooltip>Add new city to list</q-tooltip>
                  </q-btn>
                </template>

                <template v-slot:option="scope">
                  <q-item
                      v-bind="scope.itemProps"
                      v-on="scope.itemEvents"
                  >
                    <q-item-section>
                      <q-item-label> <div class="text-bold">{{ scope.opt.city }}</div></q-item-label>
                      <q-item-label caption>{{ scope.opt.country }}</q-item-label>
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
              <q-input label="Address" outlined v-model="agency.address"/>
              <q-input
                  :rules="[val => !!val || 'Field is required']"
                  label="Email *"
                  outlined
                  ref="email"
                  v-model="agency.email"
              />
              <q-input
                  :rules="[val => !!val || 'Field is required']"
                  label="Phone *"
                  outlined
                  ref="phone"
                  v-model="agency.phone"
              />
              <q-input label="Fax" outlined v-model="agency.fax"/>
              <q-input
                  :rules="[val => !!val || 'Field is required']"
                  label="Website *" outlined
                  ref="website"
                  v-model="agency.website"
              />
            </div>
            <div v-if="agency.city">
              <q-tabs
                  active-color="primary"
                  class="bg-grey-3"
                  no-caps
                  v-model="panel"
              >
                <q-tab :label="agency.city" :name="agency.city"/>
                <q-tab :key="city.city + '_' + index" :label="index + 1 + '. ' + city.city" :name="city.city + '_' + index"
                       v-for="(city, index) in agency.cities"/>
                <q-tab label="+" name="addNew"/>
              </q-tabs>
              <q-tab-panels animated class="" v-model="panel">

                <q-tab-panel :name="agency.city" class="q-gutter-y-md">
                  <q-select
                      :options="cityOptions"
                      :rules="[val => !!val || 'Field is required']"
                      @filter="filterAutocompleteCity"
                      @input="panel=agency.city"
                      emit-value
                      fill-input
                      hide-selected
                      input-debounce="0"
                      label="City *"
                      map-options
                      option-label="city"
                      option-value="city"
                      outlined
                      ref="city"
                      use-input
                      v-model="agency.city"
                  >
                    <template v-slot:after>
                      <q-btn @click="showAddNewCityToListDialog = true" flat icon="add" round>
                        <q-tooltip>Add new city to list</q-tooltip>
                      </q-btn>
                    </template>

                    <template v-slot:option="scope">
                      <q-item
                          v-bind="scope.itemProps"
                          v-on="scope.itemEvents"
                      >
                        <q-item-section>
                          <q-item-label> <div class="text-bold">{{ scope.opt.city }}</div></q-item-label>
                          <q-item-label caption>{{ scope.opt.country }}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                  <q-input label="Address" outlined v-model="agency.address"/>
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Email *"
                      outlined
                      ref="email"
                      v-model="agency.email"
                  />
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Phone *"
                      outlined
                      ref="phone"
                      v-model="agency.phone"
                  />
                  <q-input label="Fax" outlined v-model="agency.fax"/>
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Website *" outlined
                      ref="website"
                      v-model="agency.website"
                  />
                </q-tab-panel>

                <q-tab-panel
                    :key="city.city + '_' + index"
                    :name="city.city + '_' + index"
                    class="q-gutter-y-md"
                    v-for="(city, index) in agency.cities">
                  <q-select
                      :options="cityOptions"
                      :rules="[val => !!val || 'Field is required']"
                      @filter="filterAutocompleteCity"
                      @input="[city.city_id = cityOptions.find(e => e.city === city.city).id, panel=city.city + '_' + index]"
                      emit-value
                      fill-input
                      hide-selected
                      input-debounce="0"
                      label="City *"
                      map-options
                      option-label="city"
                      option-value="city"
                      outlined
                      use-input
                      v-model="city.city"
                  >
                    <template v-slot:after>
                      <q-btn @click="showAddNewCityToListDialog = true" flat icon="add" round>
                        <q-tooltip>Add new city to list</q-tooltip>
                      </q-btn>
                    </template>

                    <template v-slot:option="scope">
                      <q-item
                          v-bind="scope.itemProps"
                          v-on="scope.itemEvents"
                      >
                        <q-item-section>
                          <q-item-label> <div class="text-bold">{{ scope.opt.city }}</div></q-item-label>
                          <q-item-label caption>{{ scope.opt.country }}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                  <q-input label="Address" outlined v-model="city.address"/>
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Email *"
                      outlined
                      ref="email"
                      v-model="city.email"
                  />
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Phone *"
                      outlined
                      ref="phone"
                      v-model="city.phone"
                  />
                  <q-input label="Fax" outlined
                           v-model="city.fax"
                  />
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Website *" outlined
                      ref="website"
                      v-model="city.website"
                  />
                </q-tab-panel>

                <q-tab-panel class="q-gutter-y-md" name="addNew">
                  <q-select
                      :options="cityOptions"
                      :rules="[val => !!val || 'Field is required']"
                      @filter="filterAutocompleteCity"
                      @input="newCity.city_id = cityOptions.find(e => e.city === newCity.city).id"
                      emit-value
                      fill-input
                      hide-selected
                      input-debounce="0"
                      label="City *"
                      map-options
                      option-label="city"
                      option-value="city"
                      outlined
                      use-input
                      v-model="newCity.city"
                    >
                      <template v-slot:after>
                        <q-btn @click="showAddNewCityToListDialog = true" flat icon="add" round>
                          <q-tooltip>Add new city to list</q-tooltip>
                        </q-btn>
                      </template>

                    <template v-slot:option="scope">
                      <q-item
                          v-bind="scope.itemProps"
                          v-on="scope.itemEvents"
                      >
                        <q-item-section>
                          <q-item-label> <div class="text-bold">{{ scope.opt.city }}</div></q-item-label>
                          <q-item-label caption>{{ scope.opt.country }}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </template>
                    </q-select>
                  <q-input label="Address" outlined v-model="newCity.address"/>
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Email *"
                      outlined
                      ref="email"
                      v-model="newCity.email"
                  />
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Phone *"
                      outlined
                      ref="phone"
                      v-model="newCity.phone"
                  />
                  <q-input label="Fax" outlined
                           v-model="newCity.fax"
                  />
                  <q-input
                      :rules="[val => !!val || 'Field is required']"
                      label="Website *" outlined
                      ref="website"
                      v-model="newCity.website"
                  />
                </q-tab-panel>
              </q-tab-panels>
            </div>

            <q-input label="Instagram" outlined v-model="agency.instagram"/>
            <div class="row q-py-lg q-gutter-md ">
              <q-space></q-space>
              <q-btn @click="onSubmit" label="Save"/>
              <q-btn @click="onCancelForm" label="Cancel"/>
            </div>
          </div>
          <q-space></q-space>
        </div>

      </q-card>
    </div>
    <!--ImageDelete-->
    <q-dialog v-model="showImageDeleteDialog">
      <q-card style="width: 500px; max-width: 80vw;">
        <q-card-section class="row items-center ">
          <div class="text-h6">Confirm image delete</div>
          <q-space/>
          <q-btn dense flat icon="close" round v-close-popup/>
        </q-card-section>
        <q-separator/>
        <q-card-section>
          <div class="q-pa-md q-gutter-y-md text-center">
            <div class="text-h6">Are you shure?</div>
            <div class="text-h6">This will be delete image</div>
          </div>
        </q-card-section>
        <q-separator/>
        <q-card-actions align="right" class="q-my-sm">
          <q-btn label="Cancel" v-close-popup/>
          <q-btn @click="onDeleteImage" label="Delete" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--addNewCityToList-->
    <q-dialog v-model="showAddNewCityToListDialog">
      <q-card style="width: 500px; max-width: 80vw;">
        <q-card-section class="row items-center text-white bg-blue-grey-5">
          <div class="text-h6">Add new city to cities list</div>
          <q-space/>
          <q-btn dense flat icon="close" round v-close-popup/>
        </q-card-section>
        <q-separator/>
        <q-card-section>
          <q-select
              :options="countryOptions"
              :rules="[val => !!val || 'Field is required']"
              @filter="filterAutocompleteCountry"
              emit-value
              fill-input
              hide-selected
              input-debounce="0"
              label="Select country"
              map-options
              option-label="country"
              option-value="code"
              outlined
              ref="country"
              use-input
              v-model="newCityCountryCode"
          />
          <q-input
              class="q-my-md"
              label="Type city"
              outlined
              v-model="newCityToListName"
          />
        </q-card-section>
        <q-checkbox class="q-mx-md q-mb-md" label="Top City" v-model="newCityToListTopCity"/>
        <q-separator/>
        <q-card-actions align="right" class="q-my-sm">
          <q-btn label="Cancel" v-close-popup/>
          <q-btn v-if="!newCityToListName" disable label="Add" v-close-popup/>
          <q-btn v-else @click="onAddNewCityToList" label="Add" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'AgencyForm',
  data () {
    return {
      routeAgencyID: Number(this.$router.currentRoute.params.id),
      agency: { id: '' },
      showImageDeleteDialog: false,
      cityOptions: [],
      panel: '',
      newCity: { id: '', city_id: '', city: '', website: '', address: '', email: '', phone: '', fax: '' },
      newCityToListName: '',
      newCityCountryCode: '',
      newCityToListTopCity: false,
      showAddNewCityToListDialog: false,
      countryOptions: []
    }
  },
  methods: {
    ...mapActions(['saveAgencyForm']),
    ...mapActions(['imageUpload', 'imageDelete']),
    ...mapActions(['getAgency']),
    ...mapActions(['addNewCityToList']),
    ...mapActions(['GET_CITIES']),

    async onUploadImage (file) {
      if (!this.agency.id) this.agency = await this.onSaveForm()

      const formData = new FormData()
      formData.append('file', file[0])
      formData.append('id', this.agency.id)
      formData.append('image', this.agency.image)

      const params = {
        data: formData,
        url: '/api/agency-image-upload'
      }
      this.imageUpload(params)
        .then((resp) => {
          if (resp.data && resp.data.agency) {
            this.agency.imageUrl = resp.data.agency.imageUrl
            this.agency.image = resp.data.agency.image
            history.pushState(null, null, '/#/agencies/agency-form/' + this.agency.id)
            this.$q.notify({ message: 'Image was successfully saved', color: 'primary', icon: 'check' })
            this.$refs.imageUploader.reset()
          } else {
            this.$q.notify({ message: 'Something went wrong1...', color: 'negative', icon: 'warning' })
          }
        })
        .catch((err) => {
          this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
          console.log(err)
        })
    },

    onSubmit () {
      this.$refs.name.validate()
      // TODO:: validate
      // this.$refs.city.validate()
      // this.$refs.email.validate()
      // this.$refs.phone.validate()
      // this.$refs.website.validate()
      if (this.$refs.name.hasError) {
        this.$q.notify({ message: 'Form not valid', color: 'negative', icon: 'warning' })
      } else {
        this.onSaveForm()
      }
    },
    onSaveForm () {
      if (this.newCity.city) this.agency.cities.push(this.newCity)
      const params = {
        data: this.agency
      }
      return new Promise((resolve, reject) => {
        this.saveAgencyForm(params)
          .then((resp) => {
            if (resp.data && resp.data.agency) {
              this.agency = resp.data.agency
              this.newCity = { id: '', city_id: '', city: '', website: '', address: '', email: '', phone: '', fax: '' }
              this.panel = this.agency.city
              resolve(this.agency)
              history.pushState(null, null, '/#/agencies/agency-form/' + this.agency.id)
              this.$q.notify({ message: 'Agency was successfully saved', color: 'primary', icon: 'check' })
            } else {
              this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
            }
          })
          .catch((err) => {
            this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
            console.log(err)
          })
      })
    },
    onCancelForm () {
      this.$router.go(-1)
    },
    onDeleteImage () {
      const params = {
        data: this.agency,
        url: '/api/agency-image-delete'
      }
      this.imageDelete(params)
        .then((resp) => {
          if (resp.data && resp.data.agency) {
            this.agency.imageUrl = resp.data.agency.imageUrl
            this.agency.image = resp.data.agency.image
            this.$q.notify({ message: 'Image was successfully deleted', color: 'primary', icon: 'check' })
            this.$refs.imageUploader.reset()
          } else {
            this.$q.notify({ message: 'Something went wrong1...', color: 'negative', icon: 'warning' })
          }
        })
        .catch((err) => {
          this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
          console.log(err)
        })
    },
    filterAutocompleteCity (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.cityOptions = this.cities.filter(
          (v) => v.city && v.city.toLocaleLowerCase().indexOf(needle) > -1
        )
      })
    },
    onAddNewCityToList () {
      const params = {
        data: {
          cityName: this.newCityToListName,
          countryCode: this.newCityCountryCode,
          topCity: this.newCityToListTopCity
        }
      }
      this.showAddNewCityToListDialog = false
      this.newCityToListTopCity = false
      this.newCityToListName = ''

      this.addNewCityToList(params)
        .then((resp) => {
          if (resp.data && resp.data.cities) {
            this.GET_CITIES()
            this.$q.notify({ message: 'New city was added to cities list', color: 'primary', icon: 'check' })
          } else {
            this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
          }
        })
        .catch((err) => {
          this.$q.notify({ message: 'Something went wrong...', color: 'negative', icon: 'warning' })
          console.log(err)
        })
    },
    filterAutocompleteCountry (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.countryOptions = this.listData.countries.filter(
          (v) => v && v.country && v.country.toLocaleLowerCase().indexOf(needle) > -1
        )
      })
    }
  },
  computed: {
    ...mapState(['cities'])
  },
  async beforeMount () {
    if (this.routeAgencyID) {
      const resp = await this.getAgency(this.routeAgencyID)
      this.agency = resp.data && resp.data.agency ? resp.data.agency : { id: '' }
      this.listData = resp.data && resp.data.listData ? resp.data.listData : []
    } else {
      const resp = await this.getAgency()
      this.listData = resp.data && resp.data.listData ? resp.data.listData : []
    }
    this.panel = this.agency.city
    this.GET_CITIES()
  }
}
</script>

<style scoped>

</style>
