import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faFilePowerpoint,
  faFileWord,
  faFileExcel,
  faFilePdf,
  faFile,
  faCloudUploadAlt,
  faUserAstronaut,
  faPaperPlane,
  faTimes,
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faFilePowerpoint)
library.add(faFileWord)
library.add(faFileExcel)
library.add(faFilePdf)
library.add(faFile)
library.add(faCloudUploadAlt)
library.add(faUserAstronaut)
library.add(faPaperPlane)
library.add(faTimes)

Vue.component('font-awesome-icon', FontAwesomeIcon)
