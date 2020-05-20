<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">
        Profil
      </div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">
          {{ form.first_name }} {{ form.last_name }}
        </div>
        <profile-roles-tags
          :profile="form"
          class="ml-3 relative"
        />
      </div>
    </template>
    <div
      v-else
      class="mb-12 font-bold text-2xl text-gray-800"
    >
      Invitation d'un nouveau
      <span v-if="role == 'referent'">référent départemental</span>
      <span v-if="role == 'referent_regional'">référent régional</span>
      <span v-if="role == 'superviseur'">superviseur national</span>
      <span v-if="role == 'analyste'">datas analyste</span>
    </div>

    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <div class="mb-6 text-xl text-gray-800">
        Informations générales
      </div>

      <el-form-item
        label="Email"
        prop="email"
      >
        <el-input
          v-model.trim="form.email"
          placeholder="Email"
          :disabled="disableField"
        />
      </el-form-item>
      <div class="flex">
        <el-form-item
          label="Prénom"
          prop="first_name"
          class="flex-1 mr-2"
        >
          <el-input
            v-model="form.first_name"
            placeholder="Prénom"
          />
        </el-form-item>
        <el-form-item
          label="Nom"
          prop="last_name"
          class="flex-1 ml-2"
        >
          <el-input
            v-model="form.last_name"
            placeholder="Nom"
          />
        </el-form-item>
      </div>
      
      <div class="flex">
        <el-form-item
          label="Téléphone mobile"
          prop="mobile"
          class="flex-1 mr-2"
        >
          <el-input
            v-model="form.mobile"
            placeholder="Téléphone mobile"
          />
        </el-form-item>
        <el-form-item
          label="Téléphone fixe"
          prop="phone"
          class="flex-1 ml-2"
        >
          <el-input
            v-model="form.phone"
            placeholder="Téléphone fixe"
          />
        </el-form-item>
      </div>

      <div class="flex">
        <el-form-item
          label="Code postal"
          prop="zip"
          class="flex-1 mr-2"
        >
          <el-input
            v-model="form.zip"
            placeholder="Code postal"
          />
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
            style="width:100%;"
          />
        </el-form-item>
      </div>

      <template v-if="$store.getters.contextRole == 'admin'">
        <template v-if="mode == 'edit' || role == 'superviseur'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Superviseur réseau national
          </div>
          <item-description>
            Si cet utilisateur est membre d'un réseau national (La Croix Rouge,
            Armée du Salut...), renseignez son nom. Vous permettez à cet
            utilisateur de visualiser les missions et volontaires rattachés aux
            structures de ce réseau national.
          </item-description>
          <el-form-item
            label="Réseau national"
            prop="reseau_id"
            class="flex-1"
          >
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
        </template>
        <template v-if="mode == 'edit' || role == 'referent_regional'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Référent régional
          </div>
          <item-description>
            Si cet utilisateur est référent régional, renseignez le nom de la région.
            Vous permettez à cet utilisateur de visualiser les missions et
            volontaires rattachés aux structures de cette région.
          </item-description>
          <el-form-item
            label="Région"
            prop="referent_region"
            class="flex-1"
          >
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
        </template>
        <template v-if="mode == 'edit' || role == 'referent'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Référent départemental
          </div>
          <item-description>
            Si cet utilisateur est référent, renseignez le nom du département
            Vous permettez à cet utilisateur de visualiser les missions et
            volontaires rattachés aux structures de ce département.
          </item-description>
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
        </template>
        <template v-if="mode == 'edit' || role == 'analyste'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Datas analyste
          </div>
          <item-description>
            Si cet utilisateur est un datas analyste, cochez la case. Il aura accès au tableau de bord et à tous ses indicateurs.
          </item-description>
          <el-form-item
            prop="is_analyste"
            class="flex-1"
          >
            <el-checkbox
              v-model="form.is_analyste"
            >
              Accès au tableau de bord
            </el-checkbox>
          </el-form-item>
        </template>
      </template>
      <div class="flex pt-2">
        <el-button
          type="primary"
          :loading="loading"
          @click="onSubmit"
        >
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getProfile, updateProfile, addProfile } from "@/api/user";
import ProfileRolesTags from "@/components/ProfileRolesTags.vue";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "ProfileForm",
  components: { ProfileRolesTags, ItemDescription },
  props: {
    mode: {
      type: String,
      required: true
    },
    id: {
      type: Number,
      default: null
    },
    role: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      form: {
        role: this.role
      }
    };
  },
  computed: {
    disableField(){
      return this.mode == 'edit' ? true : false;
    },
    rules() {
      let rules = {
        email: [
          {
            required: true,
            message: "Veuillez renseigner un email",
            trigger: "blur"
          },
          {
            type: "email",
            message: "Le format de l'email n'est pas correct",
            trigger: "blur"
          }
        ],
        first_name: [
          {
            required: true,
            message: "Veuillez renseigner un nom",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Veuillez renseigner un prénom",
            trigger: "blur"
          }
        ],
        mobile: [
          {
            pattern: /^[+|\s|\d]*$/,
            message: "Le format du numéro de téléphone est incorrect",
            trigger: "blur"
          }
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: "Le format du numéro de téléphone est incorrect",
          trigger: "blur"
        }
      };

      if (this.role == "referent") {
        rules.referent_department = [
          {
            required: true,
            message: "Veuillez sélectionner un département",
            trigger: "blur"
          }
        ];
      }

      if (this.role == "referent_regional") {
        rules.referent_region = [
          {
            required: true,
            message: "Veuillez sélectionner une région",
            trigger: "blur"
          }
        ];
      }

      if (this.role == "superviseur") {
        rules.reseau_id = [
          {
            required: true,
            message: "Veuillez sélectionner un réseau national",
            trigger: "blur"
          }
        ];
      }

      return rules;
    }
  },
  created() {
    if (this.mode == "edit") {
      this.$store.commit("setLoading", true);
      getProfile(this.id)
        .then(response => {
          this.$store.commit("setLoading", false);
          this.form = response.data;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["profileForm"].validate(valid => {
        if (valid) {
          if (this.id) {
            updateProfile(this.form.id, this.form)
              .then(() => {
                this.loading = false;

                // IF CURRENT PROFILE -> UPDATE STORE
                if (this.form.id == this.$store.getters.profile.id) {
                  this.$store.dispatch("user/get");
                }

                this.$router.go(-1);
                this.$message({
                  message: "Le profil a été enregistré !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          } else {
            addProfile(this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "Le profil a été enregistré !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          }
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>
