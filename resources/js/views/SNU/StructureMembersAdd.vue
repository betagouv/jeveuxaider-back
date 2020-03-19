<template>
  <div class="structure-members max-w-2xl">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ structure.name }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Inviter un nouveau membre de votre équipe
        </div>
      </div>
    </div>
    <div class="px-12">
      <el-form
        ref="structureMembersAdd"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <div class="mb-6 text-xl text-gray-800">
          Rôle de l'utilisateur
        </div>
        <el-radio-group v-model="form.role" class="flex flex-col">
          <el-radio class="mb-6 flex items-center" label="tuteur">
            <div>Tuteur</div>
            <div class="description">
              Vous pourrez ensuite assigner chaque tuteur à une ou plusieurs
              missions.
            </div>
          </el-radio>
          <el-radio class="mb-6 flex items-center" label="responsable">
            <div>Responsable</div>
            <div class="description">
              Vous pouvez partager vos droits d'administration de votre compte
              de structure d'accueil SNU avec plusieurs personnes.
            </div>
          </el-radio>
        </el-radio-group>
        <div class="mt-12 flex mb-6 text-xl text-gray-800">
          Informations générales
        </div>

        <div class="flex justify-between">
          <el-form-item label="Prénom" prop="first_name" class="flex-1 mr-1">
            <el-input v-model="form.first_name" placeholder="Prénom" />
          </el-form-item>
          <el-form-item label="Nom" prop="last_name" class="flex-1 ml-1">
            <el-input v-model="form.last_name" placeholder="Nom de famille" />
          </el-form-item>
        </div>
        <el-form-item label="Adresse email" prop="email">
          <el-input v-model="form.email" placeholder="Adresse email" />
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Ajouter un membre
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { getStructure, inviteStructureMember } from "@/api/structure";

export default {
  name: "StructureMembersAdd",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      structure: {},
      form: {
        role: "tuteur"
      },
      rules: {
        email: [
          {
            type: "email",
            message: "Le format de l'email n'est pas correct",
            trigger: "blur"
          },
          {
            required: true,
            message: "Veuillez renseigner votre email",
            trigger: "blur"
          }
        ],
        first_name: [
          {
            required: true,
            message: "Prénom obligatoire",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Nom obligatoire",
            trigger: "blur"
          }
        ]
      },
      loading: false
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getStructure(this.id)
      .then(response => {
        this.$store.commit("setLoading", false);
        this.structure = response.data;
      })
      .catch(() => {
        this.$store.commit("setLoading", false);
      });
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["structureMembersAdd"].validate(valid => {
        if (valid) {
          inviteStructureMember(this.id, this.form)
            .then(() => {
              this.loading = false;
              this.$router.push(`/structure/${this.id}/members`);
              this.$message({
                dangerouslyUseHTMLString: true,
                message: `${this.form.first_name} ${this.form.last_name} fait maintenant partie de votre équipe. <br /><br />Une notification email lui a été envoyé.`,
                type: "success"
              });
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
.el-radio__label
  @apply text-gray-800 font-medium
  .description
    @apply text-secondary font-light mt-1
</style>
