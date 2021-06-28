<template>
  <el-form ref="documentForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-1-5xl font-bold text-gray-800">
      Informations générales
    </div>

    <el-form-item label="Nom du document" prop="title">
      <el-input v-model="form.title" placeholder="Nom du document" />
    </el-form-item>

    <el-form-item label="Type du document" prop="type">
      <el-select v-model="form.type" placeholder="Sélectionner un type">
        <el-option
          v-for="item in $store.getters.taxonomies.document_types.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
    </el-form-item>

    <template v-if="form.type == 'file'">
      <el-form-item label="Description" prop="description" class="flex-1">
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 3, maxRows: 10 }"
          placeholder="Détail du contenu du fichier"
        />
      </el-form-item>

      <div class="mb-6">
        <div v-if="!form.file" class>
          <el-upload
            v-if="!file"
            class="upload-demo"
            drag
            action
            accept="application/zip, application/x-rar-compressed, application/pdf, text/plain, text/csv, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation"
            :show-file-list="false"
            :auto-upload="false"
            :on-change="onSelectFile"
          >
            <i class="el-icon-upload" />
            <div class="el-upload__text">
              Glissez votre document ou
              <br />
              <em>cliquez ici pour le sélectionner</em>
            </div>
          </el-upload>
          <div v-else class="flex items-center">
            <div class="mr-4">
              <img
                :src="
                  require(`@/assets/images/dynamic/${$options.filters.icoFromMimeType(
                    file.type
                  )}.svg`)
                "
                alt="File"
                class="h-10 w-auto"
              />
            </div>
            <div class="mr-8">
              <div>{{ file.name }}</div>
              <div class="text-sm text-gray-600">
                {{ file.size | fileSizeOctets }}
              </div>
            </div>
            <div>
              <el-button
                type="danger"
                icon="el-icon-delete"
                @click.prevent="file = null"
              >
                Supprimer
              </el-button>
            </div>
          </div>
        </div>
        <div v-else class="flex items-center">
          <div class="mr-4">
            <img
              :src="
                require(`@/assets/images/dynamic/${$options.filters.icoFromMimeType(
                  form.file.mime_type
                )}.svg`)
              "
              alt="File"
              class="h-10 w-auto"
            />
          </div>
          <div class="mr-8">
            <div>{{ form.file.file_name }}</div>
            <div class="text-sm text-gray-700">
              {{ form.file.size | fileSizeOctets }}
            </div>
          </div>
          <div>
            <el-button
              type="secondary"
              @click.prevent="onDownloadFile(form.file)"
            >
              Télécharger
            </el-button>
            <el-button
              type="danger"
              icon="el-icon-delete"
              :loading="loadingDelete"
              @click.prevent="onDeleteFile('document')"
            />
          </div>
        </div>
      </div>
    </template>

    <template v-if="form.type == 'link'">
      <el-form-item label="Lien du document" prop="link">
        <el-input
          v-model="form.link"
          placeholder="https://www.notion.so/Mod-les-de-missions-845ccc32a629458fbca20290b5f1af72"
        />
      </el-form-item>
    </template>

    <el-form-item label="Accessible pour les rôles" prop="roles">
      <el-select
        v-model="form.roles"
        multiple
        placeholder="Sélectionner un rôle"
      >
        <el-option label="Référent" value="referent" />
        <el-option label="Responsable" value="responsable" />
      </el-select>
    </el-form-item>

    <div v-if="form.roles.includes('referent')" class="flex my-8 bg-gray-50">
      <el-form-item class="p-4 mb-0" prop="notification">
        <el-checkbox v-model="sendNotificationsToReferent">
          <template v-if="form.id"
            >Notifier les référents de la mise à jour de cette
            ressource</template
          >
          <template v-else
            >Notifier les référents de l'ajout de cette ressource</template
          >
        </el-checkbox>
      </el-form-item>
    </div>

    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'
import FileUpload from '@/mixins/FileUpload'

export default {
  mixins: [FormMixin, FileUpload],
  props: {
    document: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.document },
      sendNotificationsToReferent: false,
      file: null,
    }
  },
  computed: {
    rules() {
      const rules = {
        title: [
          {
            required: true,
            message: 'Veuillez renseigner un titre',
            trigger: 'blur',
          },
        ],
      }
      if (this.form.type == 'link') {
        rules.link = [
          {
            required: true,
            message: 'Veuillez renseigner un lien',
            trigger: 'blur',
          },
        ]
      }
      return rules
    },
  },
  methods: {
    onRemoveFile() {
      this.file = null
      this.form.file = null
    },
    onSubmit() {
      this.loading = true
      this.$refs.documentForm.validate((valid, fields) => {
        if (valid) {
          this.$api
            .addOrUpdateDocument(this.document.id, this.form)
            .then((response) => {
              this.form = response.data
              if (this.file) {
                this.$api
                  .uploadFile(this.form.id, 'document', this.file)
                  .then(() => {
                    this.onSubmitEnd()
                  })
              } else {
                this.onSubmitEnd()
              }
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
    onSubmitEnd() {
      this.loading = false
      this.$router.push('/dashboard/contents/documents')
      this.$message({
        message: 'Le document a été enregistrée !',
        type: 'success',
      })
      if (this.sendNotificationsToReferent) {
        this.$api
          .notifyDocument(this.form.id)
          .then((response) => {
            this.$message({
              message:
                'Une notification a été envoyée à  ' +
                response.data.notify_count +
                ' référents',
              type: 'success',
            })
          })
          .catch(() => {
            //
          })
      }
    },
  },
}
</script>
