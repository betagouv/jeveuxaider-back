<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites-nous en plus sur votre organisation&nbsp;!
        <br />Ces
        <span class="font-bold">informations générales</span> permettront au
        service référent de mieux vous connaître.
      </p>
      <p>
        Une question? Contactez<br /><span class="font-bold"
          ><a
            target="_blank"
            href="mailto:contact@reserve-civique.on.crisp.email"
          >
            le support</a
          >
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="2" align-center class="p-4 sm:p-8 border-b-2">
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Organisation"
        description="J'enregistre mon organisation en tant que responsable"
      />
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>
    <div class="p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">Mon organisation</div>
      <el-form
        ref="structureForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-lg"
      >
        <el-form-item label="Nom de votre organisation" prop="name">
          <el-input
            v-model="form.name"
            placeholder="Nom de votre organisation"
          />
        </el-form-item>
        <el-form-item label="Statut juridique" prop="statut_juridique">
          <el-select
            v-model="form.statut_juridique"
            placeholder="Statut juridique"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_legal_status
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Association'"
          label="Disposez vous d'un agrément ?"
          prop="association_types"
        >
          <el-select
            v-model="form.association_types"
            placeholder="Choix de l'agrément"
            multiple
          >
            <el-option
              v-for="item in $store.getters.taxonomies.association_types.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Structure publique'"
          prop="structure_publique_type"
        >
          <el-select
            v-model="form.structure_publique_type"
            placeholder="Choisissez le type de votre organisation publique"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_publique_types
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="
            form.statut_juridique == 'Structure publique' &&
            (form.structure_publique_type == 'Service de l\'Etat' ||
              form.structure_publique_type == 'Etablissement public')
          "
          prop="structure_publique_etat_type"
        >
          <el-select
            v-model="form.structure_publique_etat_type"
            placeholder="Choisissez une option"
          >
            <el-option
              v-for="item in $store.getters.taxonomies
                .structure_publique_etat_types.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Structure privée'"
          prop="structure_privee_type"
        >
          <el-select
            v-model="form.structure_privee_type"
            placeholder="Choisissez le type d'organisation privée"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_privee_types
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>

        <el-form-item
          label="Présentation synthétique de l'organisation"
          prop="description"
          class="flex-1"
        >
          <el-input
            v-model="form.description"
            type="textarea"
            :autosize="{ minRows: 2, maxRows: 6 }"
            placeholder="Décrivez votre organisation, en quelques mots"
          />
        </el-form-item>
        <template v-if="form.statut_juridique != 'Collectivité'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Réseau national ou territorial
          </div>
          <item-description container-class="mb-6">
            Si votre organisation est membre d'un réseau national ou territorial
            qui figure dans le menu déroulant du champ ci-dessous,
            sélectionnez-le. Vous permettrez au superviseur de votre réseau de
            visualiser les missions et bénévoles rattachés à votre organisation.
            Vous faciliterez également la validation de votre organisation par
            les autorités territoriales lors de votre inscription.
          </item-description>
          <el-form-item label="Réseau national" prop="reseau" class="flex-1">
            <el-select
              v-model="form.reseau_id"
              clearable
              placeholder="Aucun"
              filterable
            >
              <el-option
                v-for="item in $store.getters.reseaux"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </template>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Continuer
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  data() {
    return {
      loading: false,
      structureId: this.$store.getters.structure_as_responsable.id,
      form: {},
      rules: {
        name: {
          required: true,
          message: 'Le nom de votre organisation est requis',
          trigger: 'blur',
        },
        statut_juridique: {
          required: true,
          message:
            'Veuillez renseigner la forme juridique de votre organisation',
          trigger: 'blur',
        },
        mobile: [
          {
            required: true,
            message: 'Un numéro de téléphone est obligatoire',
            trigger: 'blur',
          },
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
  created() {
    this.form = { ...this.$store.getters.structure_as_responsable } || null
  },
  methods: {
    onSubmit() {
      this.$refs.structureForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$api
            .updateStructure(this.structureId, this.form)
            .then(async () => {
              // Get profile to get new role
              await this.$store.dispatch('auth/fetchUser')
              this.loading = false
              this.$router.push('/register/responsable/step/address')
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
  @apply hidden
    @screen sm
      @apply block
::v-deep .el-upload-list__item-name
  @apply hidden
</style>
