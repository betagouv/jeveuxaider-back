<template>
  <el-form ref="documentForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-xl text-gray-800">Informations générales</div>

    <el-form-item label="Nom du document" prop="title">
      <el-input v-model="form.title" placeholder="Nom du document" />
    </el-form-item>

    <el-form-item label="Description" prop="description" class="flex-1">
      <el-input
        v-model="form.description"
        name="description"
        type="textarea"
        :autosize="{ minRows: 3, maxRows: 10 }"
        placeholder="Détail du contenu du fichier"
      />
    </el-form-item>

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
          <div
            class="mr-4 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center"
            style="width: 50px; height: 50px"
          >
            <font-awesome-icon size="lg" :icon="file.type | icoFromMimeType" />
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
        <div
          class="mr-4 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center"
          style="width: 50px; height: 50px"
        >
          <fa
            :icon="[
              'fas',
              $options.filters.icoFromMimeType(form.file.mime_type),
            ]"
            class="text-xl"
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
import FileUpload from '@/mixins/FileUpload'

export default {
  mixins: [FileUpload],
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
      rules: {
        title: [
          {
            required: true,
            message: 'Veuillez renseigner un titre',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onRemoveFile() {
      this.file = null
      this.form.file = null
    },
    onSubmit() {
      this.loading = true
      this.$refs.documentForm.validate((valid) => {
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
