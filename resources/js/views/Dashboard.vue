<template>
  <div>
    <el-row :gutter="12">
      <el-col :span="6">
        <el-card shadow="hover">
          <router-link :to="{ name: 'activities' }">
            <div class="flex flex-row items-center">
              <div class="flex-1">
                <div class="text-black text-xl font-bold">{{ $store.getters['app/activities'].count }}</div>
                <div class="text-gray-700">Activités</div>
              </div>
              <div class="bg-blue-100 rounded-full w-16 h-16 flex justify-center items-center">
                <font-awesome-icon size="2x" class="fa-fw text-primary" icon="folder" />
              </div>
            </div>
          </router-link>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card shadow="hover">
          <router-link :to="{ name: 'collaborators' }">
            <div class="flex flex-row items-center">
              <div class="flex-1">
                <div class="text-black text-xl font-bold">{{ $store.getters['app/collaborators'].count }}</div>
                <div class="text-gray-700">Collaborateurs</div>
              </div>
              <div class="bg-blue-100 rounded-full w-16 h-16 flex justify-center items-center">
                <font-awesome-icon size="2x" class="fa-fw text-primary" icon="user-friends" />
              </div>
            </div>
          </router-link>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script type="text/babel">
export default {
  methods: {

  }
};
</script>
