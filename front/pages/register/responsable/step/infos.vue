<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>
    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        Racontez-nous <br />
        <span class="font-bold">{{ form.name }}</span>
      </h1>
    </div>
    <div class="rounded-lg bg-white max-w-xl mx-auto overflow-hidden">
      <div
        class="px-8 py-6 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Votre organisation en quelques mots
      </div>

      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <div class="mb-8 text-md text-gray-500">
          Cette description doit expliquer votre raison d'être et susciter le
          désir d'engagement des milliers bénévoles découvrant votre
          organisation sur la plateforme JeVeuxAider. Cette description
          apparaîtra sur votre future page vitrine et sur l'ensemble de vos
          missions de bénévolat publiées sur la plateforme.
        </div>
        <el-form
          ref="structureForm"
          :model="form"
          label-position="top"
          class="form-register-steps"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <div class="mb-4 lg:mb-6 wrapper-textarea">
            <el-form-item
              :label="`À propos de votre organisation (200 caractères min.)`"
              prop="description"
              class=""
            >
              <textarea
                v-model="form.description"
                rows="4"
                class="custom-textarea placeholder-gray-600"
                placeholder="Dites-nous tout à propos de votre organisation"
              />
            </el-form-item>
          </div>

          <el-form-item
            label="E-mail public de votre organisation"
            prop="email"
          >
            <input
              v-model="form.email"
              class="custom-input placeholder-gray-600"
              placeholder="E-mail public de votre organisation"
            />
          </el-form-item>

          <el-form-item label="Téléphone de votre organisation" prop="phone">
            <input
              v-model="form.phone"
              class="custom-input !placeholder-gray-600"
              placeholder="Téléphone de votre organisation"
            />
          </el-form-item>

          <template v-if="collectivity">
            <div
              class="mb-8 text-black text-2xl font-extrabold leading-9 text-center"
            >
              Codes postaux de votre collectivité
            </div>

            <ItemDescription container-class="mb-6">
              En tant que collectivité, vous aurez accès au statistiques des
              organisations enregistrées avec vos codes postaux. <br />Vous
              aurez aussi la possibilité de gérer la page de votre collectivité
              qui listera toutes les missions dans votre collectivité. Par
              exemple pour Bayonne :
              <a
                href="https://jeveuxaider.gouv.fr/territoires/bayonne"
                target="_blank"
                >https://jeveuxaider.gouv.fr/territoires/bayonne</a
              >
            </ItemDescription>

            <el-form-item
              label="Liste des codes postaux"
              prop="zips"
              class="!flex-1"
            >
              <el-select
                v-model="form.zips"
                multiple
                allow-create
                filterable
                default-first-option
                placeholder="Saisissez tous les codes postaux"
              >
              </el-select>
            </el-form-item>
          </template>

          <div
            class="mb-8 text-black text-2xl font-extrabold leading-9 text-center"
          >
            Votre organisation sur les réseaux
          </div>
          <el-form-item label="Site de l'organisation" prop="website">
            <input
              v-model="form.website"
              class="custom-input placeholder-gray-600 prefix prefix-website"
              placeholder="https://www.votresite.fr"
            />
          </el-form-item>
          <el-form-item label="Page Facebook" prop="facebook">
            <input
              v-model="form.facebook"
              class="custom-input placeholder-gray-600 prefix prefix-facebook"
              placeholder="https://facebook.com/votrepage"
            />
          </el-form-item>
          <el-form-item label="Page Twitter" prop="twitter">
            <input
              v-model="form.twitter"
              class="custom-input placeholder-gray-600 prefix prefix-twitter"
              placeholder="https://twitter.com/votrepage"
            />
          </el-form-item>
          <el-form-item label="Profil Instagram" prop="instagram">
            <input
              v-model="form.instagram"
              class="custom-input placeholder-gray-600 prefix prefix-instagram"
              placeholder="https://instagram.com/votrepage"
            />
          </el-form-item>
          <el-form-item label="Plateforme de don" prop="donation">
            <input
              v-model="form.donation"
              class="custom-input placeholder-gray-600 prefix prefix-donation"
              placeholder="URL de votre page (Helloasso, Microdon, Ulule, etc...)"
            />
          </el-form-item>
        </el-form>
        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="!shadow-lg !block !w-full !text-center !rounded-lg !z-10 !border !border-transparent !bg-[#16a972] !px-4 sm:!px-6 !py-4 !text-lg sm:!text-xl !leading-6 !font-bold !text-white hover:!bg-[#0e9f6e] focus:!outline-none focus:!border-indigo-700 focus:!ring-indigo !transition"
              @click="onSubmit"
              >Continuer</el-button
            >
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'register-steps',
  asyncData({ $api, store, error }) {
    if (!store.getters.structure) {
      return error({ statusCode: 403 })
    }
    return {
      structureId: store.getters.structure.id,
      form: {
        ...store.getters.structure,
        zips: store.getters.structure.collectivity
          ? store.getters.structure.collectivity.zips
          : null,
      },
      collectivity:
        store.getters.structure && store.getters.structure.collectivity
          ? { ...store.getters.structure.collectivity }
          : null,
    }
  },
  data() {
    return {
      loading: false,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/responsable/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/responsable/step/profile',
        },
        {
          name: `Informations sur l'organisation`,
          status: 'complete',
          href: '/register/responsable/step/structure',
        },
        {
          name: `Quelques mots sur l'organisation`,
          status: 'current',
          disable:
            this.$store.getters.structure.statut_juridique == 'Collectivité',
        },
        {
          name: `Votre organisation en images`,
          status: 'upcoming',
        },
      ],
    }
  },
  computed: {
    rules() {
      const rules = {
        description: [
          {
            required: true,
            message: 'Veuillez renseigner une description',
            trigger: 'blur',
          },
          {
            min: 200,
            message: 'Minimum 200 caractères',
            trigger: 'blur',
          },
        ],
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
        ],
        phone: [
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
      }

      if (this.collectivity) {
        rules.zips = [
          {
            required: true,
            message: 'Veuillez saisir les codes postaux de la commune',
            trigger: 'blur',
          },
        ]
      }

      return rules
    },
  },
  mounted() {
    document.getElementById('step-container').scrollTop = 0
  },
  methods: {
    onSubmit() {
      this.$refs.structureForm.validate(async (valid, fields) => {
        if (valid) {
          this.loading = true
          if (this.collectivity) {
            this.collectivity.zips = this.form.zips
            await this.$api.updateCollectivity(
              this.collectivity.id,
              this.collectivity
            )
          }
          this.$api
            .updateStructure(this.structureId, this.form)
            .then(async (res) => {
              console.log('res', res)
              this.loading = false
              await this.$store.dispatch('auth/fetchUser')
              window.plausible &&
                window.plausible(
                  'Inscription responsable - Étape 4 - Quelques mots sur l’organisation'
                )
              this.$router.push('/register/responsable/step/images')
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
        }
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
.wrapper-textarea {
  ::v-deep {
    .el-form-item__content {
      @apply flex;
    }
  }
}
</style>
