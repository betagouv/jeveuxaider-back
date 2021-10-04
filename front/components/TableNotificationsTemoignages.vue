<template>
  <el-table
    v-loading="loading"
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" align="center">
      <template slot-scope="scope">
        <Avatar
          class="m-auto"
          :fallback="scope.row.participation.profile.short_name"
        />
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Bénévole" width="250">
      <template slot-scope="scope">
        <div class="text-gray-900">
          {{ scope.row.participation.profile.full_name }}
        </div>
        <div class="font-light text-gray-600">
          <div class="text-xs">
            {{ scope.row.participation.profile.email }}
          </div>
          <div class="text-xs">
            {{ scope.row.participation.profile.mobile }} -
            {{ scope.row.participation.profile.zip }}
          </div>
        </div>
      </template>
    </el-table-column>

    <el-table-column prop="mission" label="# Mission" width="100">
      <template slot-scope="scope">
        {{ scope.row.participation.mission_id }}
      </template>
    </el-table-column>

    <el-table-column prop="reminders_sent" label="Nb de relances" width="150">
      <template slot-scope="scope">
        {{ scope.row.reminders_sent }}
      </template>
    </el-table-column>

    <el-table-column
      prop="last_sent_at"
      label="Dernière relance"
      min-width="200"
    >
      <template slot-scope="scope">
        {{ scope.row.last_sent_at | fromNow }}
      </template>
    </el-table-column>

    <el-table-column label="Actions" width="250" class-name="dropdown-wrapper">
      <template slot-scope="scope">
        <el-dropdown
          split-button
          size="small"
          @command="handleCommand"
          @click.native.stop
        >
          Choisissez une action
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item :command="{ action: 'copy', row: scope.row }">
              Copier le lien vers le formulaire
            </el-dropdown-item>
            <el-dropdown-item :command="{ action: 'resend', row: scope.row }">
              Renvoyer le mail
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    tableData: {
      type: Array,
      default: null,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    onUpdatedRow: {
      type: Function,
      default: () => {},
    },
    onClickedRow: {
      type: Function,
      default: () => {},
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'copy') {
        this.onCopyLink(command.row)
      }
      if (command.action == 'resend') {
        this.onSendReminderTestimony(command.row)
      }
    },
    onCopyLink(row) {
      this.$copyText(`${this.$config.appUrl}/temoignages/${row.token}`).then(
        () => {
          this.$message({
            message: 'Le lien a été copié dans votre presse papier (CTRL+V)',
            type: 'success',
          })
        }
      )
    },
    onSendReminderTestimony(row) {
      this.$confirm(
        `Vous êtes sur le point de relancer le bénévole. Êtes vous sur de vouloir renvoyer l'email ?`,
        'Relancer le bénévole',
        {
          confirmButtonText: 'Renvoyer',
          cancelButtonText: 'Annuler',
          center: true,
        }
      ).then(async () => {
        const { data: notificationTemoignage } = await this.$axios.get(
          `/notification-temoignage/${row.id}/resend`
        )
        this.$store.commit('volet/setRow', notificationTemoignage)
        this.onUpdatedRow(notificationTemoignage)
      })
    },
  },
}
</script>
