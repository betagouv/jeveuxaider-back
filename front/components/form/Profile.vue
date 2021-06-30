<template>
  <el-form ref="profileForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-1-5xl font-boldtext-gray-800">
      Informations générales
    </div>

    <el-form-item label="Email" prop="email">
      <el-input
        v-model.trim="form.email"
        placeholder="Email"
        :disabled="disableField"
      />
    </el-form-item>
    <div class="flex">
      <el-form-item label="Prénom" prop="first_name" class="flex-1 mr-2">
        <el-input v-model="form.first_name" placeholder="Prénom" />
      </el-form-item>
      <el-form-item label="Nom" prop="last_name" class="flex-1 ml-2">
        <el-input v-model="form.last_name" placeholder="Nom" />
      </el-form-item>
    </div>

    <div class="flex">
      <el-form-item label="Téléphone mobile" prop="mobile" class="flex-1 mr-2">
        <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
      </el-form-item>
      <el-form-item label="Téléphone fixe" prop="phone" class="flex-1 ml-2">
        <el-input v-model="form.phone" placeholder="Téléphone fixe" />
      </el-form-item>
    </div>

    <div class="flex">
      <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
        <el-input v-model="form.zip" placeholder="Code postal" />
      </el-form-item>
      <el-form-item
        label="Date de naissance"
        prop="birthday"
        class="flex-1 ml-2"
      >
        <el-date-picker
          v-model="form.birthday"
          type="date"
          placeholder="Date de naissance"
          autocomplete="off"
          format="dd-MM-yyyy"
          value-format="yyyy-MM-dd"
          style="width: 100%"
          :picker-options="{ firstDayOfWeek: 1 }"
        />
      </el-form-item>
    </div>

    <template v-if="$store.getters.contextRole == 'admin'">
      <div class="mb-6 mt-12 flex text-xl text-gray-800">
        Superviseur réseau national
      </div>
      <ItemDescription container-class="mb-6">
        Si cet utilisateur est membre d'un réseau national (Les Banques
        alimentaires, Armée du Salut...), renseignez son nom. Vous permettez à
        cet utilisateur de visualiser les missions et bénévoles rattachés aux
        organisations de ce réseau national.
      </ItemDescription>
      <el-form-item label="Réseau national" prop="reseau_id" class="flex-1">
        <el-select
          v-model="form.reseau_id"
          clearable
          placeholder="Sélectionner un réseau national"
        >
          <el-option
            v-for="item in $store.getters.reseaux"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">Référent régional</div>
      <ItemDescription container-class="mb-6">
        Si cet utilisateur est référent régional, renseignez le nom de la
        région. Vous permettez à cet utilisateur de visualiser les missions et
        bénévoles rattachés aux organisations de cette région.
      </ItemDescription>
      <el-form-item label="Région" prop="referent_region" class="flex-1">
        <el-select
          v-model="form.referent_region"
          filterable
          clearable
          placeholder="Région"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.regions.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">
        Référent départemental
      </div>
      <ItemDescription container-class="mb-6">
        Si cet utilisateur est référent, renseignez le nom du département Vous
        permettez à cet utilisateur de visualiser les missions et bénévoles
        rattachés aux organisations de ce département.
      </ItemDescription>
      <el-form-item
        label="Département"
        prop="referent_department"
        class="flex-1"
      >
        <el-select
          v-model="form.referent_department"
          filterable
          clearable
          placeholder="Département"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.departments.terms"
            :key="item.value"
            :label="`${item.value} - ${item.label}`"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">Datas analyste</div>
      <ItemDescription container-class="mb-6">
        Si cet utilisateur est un datas analyste, cochez la case. Il aura accès
        au tableau de bord et à tous ses indicateurs.
      </ItemDescription>
      <el-form-item prop="is_analyste" class="flex-1">
        <el-checkbox v-model="form.is_analyste">
          Accès au tableau de bord
        </el-checkbox>
      </el-form-item>
    </template>
    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  props: {
    profile: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.profile },
      rules: {
        email: [
          {
            required: true,
            message: 'Veuillez renseigner un email',
            trigger: 'blur',
          },
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
        ],
        first_name: [
          {
            required: true,
            message: 'Veuillez renseigner un nom',
            trigger: 'blur',
          },
        ],
        last_name: [
          {
            required: true,
            message: 'Veuillez renseigner un prénom',
            trigger: 'blur',
          },
        ],
        mobile: [
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: 'Le format du numéro de téléphone est incorrect',
          trigger: 'blur',
        },
      },
    }
  },
  computed: {
    disableField() {
      return true
    },
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid, fields) => {
        if (valid) {
          this.$api
            .updateProfile(this.form.id, this.form)
            .then(() => {
              this.loading = false

              // IF CURRENT PROFILE -> UPDATE STORE
              if (this.form.id == this.$store.getters.profile.id) {
                this.$store.dispatch('user/get')
              }

              this.$router.go(-1)
              this.$message({
                message: 'Le profil a été enregistré !',
                type: 'success',
              })
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
  },
}
</script>
