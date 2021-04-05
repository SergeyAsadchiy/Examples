<template>
  <q-card style="width: 500px; max-width: 80vw;">
    <q-card-section class="row items-center bg-primary text-white">
      <div class="text-h6">{{title}}</div>
      <q-space/>
      <q-btn dense flat icon="close" round v-close-popup/>
    </q-card-section>

    <q-card-section class="q-px-xl q-py-lg q-gutter-y-md">

      <q-select
        :options="peopleOptions"
        @filter="filterAutocompletePeoples"
        dense
        emit-value
        fill-input
        hide-selected
        input-debounce="0"
        label="Worker"
        map-options
        option-label="peopleName"
        option-value="id"
        outlined
        use-input
        v-model="formData.contractID"
      >
        <template v-slot:option="scope">
          <q-item
            v-bind="scope.itemProps"
            v-on="scope.itemEvents"
          >
            <q-item-section>
              <q-item-label>{{ scope.opt.people.name }}</q-item-label>
              <q-item-label caption>
                {{ scope.opt.project.name }} |
                {{ scope.opt.client.name }} |
                {{ scope.opt.profession.name }} |
                {{ scope.opt.certificate.name}} |
                {{ scope.opt.pm.name }} |
              </q-item-label>
            </q-item-section>
          </q-item>
        </template>
      </q-select>

      <q-select
        :options="rotationOptions"
        @filter="filterAutocompleteRotations"
        @input="onRotationSelect"
        dense
        v-if="!rotationFieldHide"
        emit-value
        fill-input
        hide-selected
        input-debounce="0"
        label="Rotation"
        map-options
        option-label="name"
        option-value="id"
        outlined
        use-input
        v-model="formData.rotationID"
      />

      <q-input dense label="From date" outlined v-model="formData.fromDate">
        <template v-slot:prepend>
          <q-icon class="cursor-pointer" name="event">
            <q-popup-proxy ref="qFromDateProxy" transition-hide="scale" transition-show="scale">
              <q-date @input="() => $refs.qFromDateProxy.hide()" mask="YYYY-MM-DD" v-model="formData.fromDate" :options="checkDateFrom"/>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>

      <div class="row items-center">
        <q-input
            @input="onDurationInput"
            dense
            label="Duration"
            outlined
            style="width: 150px"
            type="number"
            v-model.number="formData.duration"
        />
        <div class="q-px-md">Days</div>
      </div>

      <div class="row items-center" v-if="!rotationFieldHide">
        <q-input
          dense
          label="Work days"
          outlined
          style="width: 150px"
          type="number"
          v-model.number="formData.daysWork"
        />
        <div class="q-px-md"></div>
        <q-input
          dense
          label="Day-Offs"
          outlined
          style="width: 150px"
          type="number"
          v-model.number="formData.daysOff"
        />
        <div class="q-px-md">Days</div>
      </div>

      <q-input
          dense
          v-if="!hoursePerShiftFieldHide"
          label="Hours per shift"
          outlined
          style="width: 150px"
          type="number"
          v-model.number="formData.hoursPerShift"
      />

      <q-input
          dense
          label="Description"
          outlined
          v-model="formData.description"
      />

      <q-input dense disable label="To date" outlined v-model="formData.toDate">
        <template v-slot:prepend>
          <q-icon class="cursor-pointer" name="event">
            <q-popup-proxy ref="qToDateProxy" transition-hide="scale" transition-show="scale">
              <q-date @input="() => $refs.qToDateProxy.hide()" mask="YYYY-MM-DD" v-model="formData.toDate" :options="checkDateTo"/>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>

    </q-card-section>

    <q-card-actions align="right" class="q-mb-sm">
      <q-btn label="Cancel" v-close-popup/>
      <q-btn :disable="!formData.rotationID" @click="onRotationSave" label="Save"/>
    </q-card-actions>
  </q-card>
</template>

<script>
import { date } from 'quasar'
import moment from 'moment'

export default {
  name: 'EventForm',
  props: {
    cellProps: null,
    resources: null,
    rotations: null,
    callbackCheckRotationDate: null,
    eventType: null
  },
  data () {
    return {
      formData: {
        type: this.eventType,
        period: '',
        fromDate: '',
        toDate: '',
        duration: '',
        daysWork: 0,
        daysOff: 0,
        contractID: '',
        rotationID: '',
        times: {},
        description: '',
        hoursPerShift: 0
      },
      peopleOptions: [],
      rotationOptions: [],
      title: '',
      rotationFieldHide: false,
      hoursePerShiftFieldHide: false,
      nonRotationType: '',
      nonRotationID: ''
    }
  },
  methods: {
    checkDateFrom (date) {
      let selectedPeriod = new Date(date)
      let currentPeriod = new Date(this.formData.fromDate)
      // console.log(this.fromDate, currentPeriod)
      selectedPeriod = selectedPeriod.getFullYear() + '_' + selectedPeriod.getMonth()
      currentPeriod = currentPeriod.getFullYear() + '_' + currentPeriod.getMonth()
      return selectedPeriod === currentPeriod
    },
    checkDateTo () {
      // not editable, only by duration
      return false
    },
    getToDate () {
      const fromDate = new Date(this.formData.fromDate)
      const toDate = date.addToDate(fromDate, { days: this.formData.duration - 1 })
      this.formData.toDate = date.formatDate(toDate, 'YYYY-MM-DD')
    },
    onRotationSelect (val) {
      // console.log(val)
      const rotat = this.rotations.find(e => e.id === val)
      const days = (rotat.name.split(' ').find(r => r.includes('/')) || '1/1').split('/')
      this.formData.duration = rotat.duration
      this.formData.hoursPerShift = rotat.perDay
      this.formData.daysWork = days[0]
      this.formData.daysOff = days[1]
      // TODO set To date
      this.getToDate()
    },
    onDurationInput () {
      this.getToDate()
    },
    async onRotationSave () {
      this.formData.times = {}
      // TODO save sick of the rotation when it will be done
      // console.log('this.formData', this.formData)
      let daysWork = this.formData.daysWork
      let daysOff = this.formData.daysOff
      let cday = moment(this.formData.fromDate)

      for (let i = 1; i <= this.formData.duration; i++) {
        if (!daysWork && !daysOff) {
          daysWork = this.formData.daysWork
          daysOff = this.formData.daysOff
        }

        this.formData.times[cday.format('YYYY_MM_DD')] = {
          date: cday.format('YYYY-MM-DD'),
          period: cday.format('YYYY_MM'),
          hours: daysWork ? this.formData.hoursPerShift : 0,
          type: daysWork ? this.formData.type : this.nonRotationType,
          rotationID: daysWork ? this.formData.rotationID : this.nonRotationID
        }

        if (daysWork) {
          daysWork--
        } else {
          if (!daysWork && daysOff) {
            daysOff--
          }
        }
        cday = cday.add(1, 'days')
      }

      const cres = await this.callbackCheckRotationDate(this.formData)
      if (cres) {
        console.log(this.formData)
        this.$emit('onEventSave', this.formData)
      } else {
        if (cres === 0) {
          this.$q.notify({
            message: 'Max duration is 500 days',
            type: 'negative'
          })
        } else {
          this.$q.notify({
            message: 'The date is already used',
            type: 'warning'
          })
        }
      }
    },
    filterAutocompletePeoples (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.peopleOptions = this.resources.filter(
          (v) => v && v.people && v.people.name && v.people.name.toLocaleLowerCase().indexOf(needle) > -1
        )
      })
    },
    filterAutocompleteRotations (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.rotationOptions = this.rotations.filter(
          (v) => v && v.name && v.name.toLocaleLowerCase().indexOf(needle) > -1 &&
            (this.eventType !== 'rotation' || (v.name !== 'Sick' && v.name !== 'Vocation'))
        )
      })
    },
    getEventToFormData () {
      const event = this.cellProps.shift
      const formData = JSON.parse(JSON.stringify(event))
      // console.log('formData', formData)
      return formData
    },
    initForm () {
      const sID = this.cellProps && this.cellProps.shift ? this.cellProps.shift.id : ''
      this.formData = this.getEventToFormData()
      switch (this.eventType) {
        case 'rotation':
          if (sID) {
            this.title = 'Edit Rotation'
          } else {
            this.title = 'Add Rotation'
          }
          this.nonRotationType = 'day-offs'
          this.nonRotationID = this.rotations.find(r => r.name === 'Day-Offs') ? this.rotations.find(r => r.name === 'Day-Offs').id : ''
          break
        case 'sick':
          if (sID) {
            this.title = 'Edit Sick'
          } else {
            this.formData.rotationID = this.rotations.find(r => r.name === 'Sick') ? this.rotations.find(r => r.name === 'Sick').id : ''
            this.title = 'Set As Sick'
          }
          this.rotationFieldHide = true
          this.hoursePerShiftFieldHide = true
          this.nonRotationType = 'sick'
          this.nonRotationID = this.formData.rotationID
          break
        case 'vocation':
          if (sID) {
            this.title = 'Edit Vocation'
          } else {
            this.formData.rotationID = this.rotations.find(r => r.name === 'Vocation') ? this.rotations.find(r => r.name === 'Vocation').id : ''
            this.title = 'Add Vocation'
          }
          this.rotationFieldHide = true
          this.hoursePerShiftFieldHide = true
          this.nonRotationType = 'vocation'
          this.nonRotationID = this.formData.rotationID
          break
      }
    }
  },
  mounted () {
    // console.log(this.cellProps)
    this.initForm()
    this.peopleOptions = this.resources
    this.rotationOptions = this.rotations.filter(r => this.eventType !== 'rotation' || (r.name !== 'Sick' && r.name !== 'Vocation'))
  }
}
</script>

<style scoped>

</style>
