<template>
  <div>
    <AppHeader />

    <div class="bg-blue-900 pb-12">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Appel aux collectivités
          </h1>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="border-t lg:border-t-0 lg:w-1/2 px-8 py-8 md:py-16 lg:p-16">
        <div class="uppercase pb-2 mt-3 text-xl text-gray-500 sm:mt-5">
          Crise sanitaire du
          <b>COVID-19</b>
        </div>
        <div class="sm:text-center lg:text-left">
          <h2
            class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-4xl sm:leading-none lg:text-5xl"
          >
            Collectivités
            <br>engagez-vous dans la
            <span class="text-blue-500">Réserve Civique</span>
          </h2>
          <p
            class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-2xl md:max-w-3xl"
          >
            Face à l’épidémie de Covid-19
            le Gouvernement appelle à la mobilisation
            générale des solidarités.
          </p>

          <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
            <div class="rounded-full shadow-md">
              <a
                href="#form"
                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-blue-500 hover:bg-blue-900 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10"
              >Créez la page de votre collectivité</a>
            </div>
          </div>
        </div>
      </div>
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/hotel-de-ville-collectivite.jpg"
        >
      </div>
    </div>

    <div class="bg-blue-900 relative">
      <div class="container mx-auto pb-48 pt-16 px-4">
        <div class="max-w-6xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
          >
            Connectez les structures et leurs futurs bénévoles
          </h2>
          <p
            class="text-xl leading-8 text-indigo-200 mt-2"
          >
            Équilibrez l'offre et la demande sur votre territoire grâce à une plateforme ludique et gratuite
          </p>
        </div>

        <div class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
          <div>
            <p class="mt-2 text-lg leading-8 font-medium text-indigo-200">
              Déjà plus de
            </p>
            <p class="text-4xl leading-none font-extrabold text-white">
              250 000
            </p>
            <p class="mt-2 text-lg leading-6 font-medium text-indigo-200">
              volontaires déjà inscrits
            </p>
          </div>
          <div class="mt-10 sm:mt-0">
            <p class="mt-2 text-lg leading-8 font-medium text-indigo-200">
              Pas moins de
            </p>
            <p class="text-4xl leading-none font-extrabold text-white">
              3 000
            </p>
            <p class="mt-2 text-lg leading-6 font-medium text-indigo-200">
              structures et associations
            </p>
          </div>
          <div class="mt-10 sm:mt-0">
            <p class="mt-2 text-lg leading-8 font-medium text-indigo-200">
              Un taux moyen de
            </p>
            <p class="text-4xl leading-none font-extrabold text-white">
              65%
            </p>
            <p class="mt-2 text-lg leading-6 font-medium text-indigo-200">
              de remplissage de missions
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-100">
      <div class="pb-12 sm:pb-16">
        <div id="form">
          <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto z-10">
              <div class="rounded-lg bg-white shadow-lg p-8 lg:px-20 lg:py-16 relative -mt-32">
                <div
                  class="text-base leading-6 font-semibold uppercase tracking-wider text-blue-800"
                >
                  Appel aux collectivités
                </div>
                <h2
                  class="mt-2 text-black text-3xl leading-9 font-extrabold tracking-tight sm:text-4xl sm:leading-10"
                >
                  Je souhaite inscrire ma collectivité
                </h2>
                <template v-if="!submitted">
                  <el-form
                    ref="collectivityForm"
                    :model="form"
                    label-position="top"
                    :rules="rules"
                  >
                    <div>
                      <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Nom de la collectivité"
                              prop="name"
                            >
                              <el-input
                                v-model="form.name"
                                placeholder="Nom de la collectivité"
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Type"
                              prop="type"
                            >
                              <el-select
                                v-model="form.type"
                                placeholder="Selectionner le type"
                              >
                                <el-option
                                  v-for="item in $store.getters.taxonomies.collectivities_types.terms"
                                  :key="item.value"
                                  :label="item.label"
                                  :value="item.value"
                                />
                              </el-select>
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Nom du contact"
                              prop="last_name"
                            >
                              <el-input
                                v-model="form.last_name"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Prénom du contact"
                              prop="first_name"
                            >
                              <el-input
                                v-model="form.first_name"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Email du contact"
                              prop="email"
                            >
                              <el-input
                                v-model.trim="form.email"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-3">
                            <el-form-item
                              label="Téléphone"
                              prop="mobile"
                            >
                              <el-input
                                v-model="form.mobile"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-2">
                            <el-form-item
                              label="Page Instagram"
                              prop="instagram"
                            >
                              <el-input
                                v-model="form.instagram"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-2">
                            <el-form-item
                              label="Page Twitter"
                              prop="twitter"
                            >
                              <el-input
                                v-model="form.twitter"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-2">
                            <el-form-item
                              label="Page Facebook"
                              prop="facebook"
                            >
                              <el-input
                                v-model="form.facebook"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-6">
                            <el-form-item
                              label="Quelques mots à propos de votre collectivité"
                              prop="description"
                              class="flex-1"
                            >
                              <el-input
                                v-model="form.description"
                                name="description"
                                type="textarea"
                                :autosize="{ minRows: 3, maxRows: 8 }"
                                placeholder
                              />
                            </el-form-item>
                          </div>

                          <div class="col-span-6 sm:col-span-6">
                            <el-form-item
                              label="Quels sont les codes postaux concernés par votre collectivité ?"
                              prop="zips"
                              class="flex-1"
                            >
                              <item-description>Séparer les codes postaux par des virgules. Ex: 75001,75002,75003</item-description>
                              <el-input
                                v-model="form.zips"
                                name="zips"
                                type="textarea"
                                :autosize="{ minRows: 3, maxRows: 10 }"
                                placeholder="Codes postaux..."
                              />
                            </el-form-item>
                          </div>
                        </div>
                      </div>
                    </div>
                  </el-form>
                  <div class="mt-8">
                    <span class="block w-full text-center rounded-full shadow-lg">
                      <el-button
                        class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-blue-500 hover:bg-blue-900 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10"
                        @click="handleSubmit"
                      >Proposer ma collectivité</el-button>
                    </span>
                    <div
                      class="text-center mt-3 text-gray-500"
                    >
                      L'équipe de la Réserve Civique vous accompagne tout au long de votre inscription
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="text-center mt-8 border-t border-gray-200 pt-8">
                    Votre demande d'inscription de collectivité a bien été prise en compte.
                    <br>Nous allons traiter votre demande dans les plus brefs délais et vous serez notifié de l'évolution du dossier.
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "FrontCollectivitySubmit",
  components: { ItemDescription },
  data() {
    return {
      loading: false,
      submitted: false,
      form: {}
    };
  },
  computed: {
    rules() {
      let rules = {
        name: [
          {
            required: true,
            message: "Veuillez renseigner un nom de collectivité",
            trigger: "blur"
          }
        ],
        type: [
          {
            required: true,
            message: "Veuillez renseigner un type",
            trigger: "blur"
          }
        ],
        first_name: [
          {
            required: true,
            message: "Veuillez renseigner un prénom",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Veuillez renseigner un nom",
            trigger: "blur"
          }
        ],
        email: [
          {
            required: true,
            message: "Veuillez renseigner un email",
            trigger: "blur"
          }
        ],
        mobile: [
          {
            required: true,
            message: "Veuillez renseigner un téléphone",
            trigger: "blur"
          }
        ],
        zips: [
          {
            required: true,
            message: "Veuillez renseigner des codes postaux",
            trigger: "blur"
          }
        ]
      };
      return rules;
    }
  },
  methods: {
    handleSubmit() {
      this.loading = true;
      this.$refs["collectivityForm"].validate(valid => {
        if (valid) {
          submitCollectivity(this.form)
            .then(() => {
              this.loading = false;
              this.submitted = true;
              this.$message({
                message:
                  "Votre collectivité a été enregistrée et est en attente de validation !",
                type: "success"
              });
            })
            .catch(() => {
              this.loading = false;
            });
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
.el-form-item
  margin-bottom: 8px
</style>