<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- 
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>

      <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
   
      <el-select v-model="query.role" :placeholder="$t('table.role')" clearable style="width: 90px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in roles" :key="item" :label="item | uppercaseFirst" :value="item" />
      </el-select>
   
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
   

      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    --></div>

    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="VIN No.">
        <template slot-scope="scope">
          <span>{{ scope.row.vin }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Model No.">
        <template slot-scope="scope">
          <span>{{ scope.row.model }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Year">
        <template slot-scope="scope">
          <span>{{ scope.row.year }}</span>
        </template>
      </el-table-column>



      <el-table-column align="center" label="User">
        <template slot-scope="scope">
          <span>{{ scope.row.user ? scope.row.user.name : "" }}</span>
        </template>
      </el-table-column>


      <el-table-column align="center" label="Actions" width="200">
        <template slot-scope="scope">
          <el-button
            @click.prevent="handlePurchaseEdit(scope.row.id)"
            type="primary"
            size="small"
            icon="el-icon-edit"
          >
            {{ scope.row.user ? $t('purchases.update_associate')  : $t('purchases.associate') }}
          </el-button>
        </template>
      </el-table-column>



    </el-table>

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="getList"
    />

    <el-dialog :visible.sync="dialogPurchase" title="Associate User">
      <div v-loading="dialogPermissionLoading" class="form-container">
        <div class="permissions-container">
          <el-form
            ref="userForm"
            :rules="rules"
            :model="purchaseItem"
            label-position="left"
            label-width="150px"
            style="max-width: 500px;"
          >
            <el-form-item label="User">
              <el-select
                v-model="purchaseItem.user_id"
                placeholder="please select a user"
              >
                <el-option
                  v-for="user in users"
                  :label="user.name"
                  :value="user.id"
                />
              </el-select>
            </el-form-item>

            <div class="clear-left" />
          </el-form>
        </div>

        <div style="text-align:right;">
          <el-button type="danger" @click="dialogPurchase = false">
            {{ $t('permission.cancel') }}
          </el-button>
          <el-button @click.prevent="updatePurchase" type="primary">
            {{ $t('permission.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import PurchaseResource from '@/api/purchase';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive

const purchaseResource = new PurchaseResource();
const userResource = new Resource('users');

export default {
  name: 'UserList',
  components: { Pagination },
  data() {
    return {
      users: [],
      purchaseItem: {},
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      userCreating: false,
      query: {
        pagination : true,
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
      },
      newUser: {},
      dialogFormVisible: false,
      dialogPurchase: false,
      dialogPermissionLoading: false,
      currentItemId: 0,
      rules: {},
    };
  },
  computed: {},
  created() {
    this.resetCurrentItem();
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await purchaseResource.list(this.query);

      console.log(response, 111111);

      this.list = response.data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetCurrentItem();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    async handlePurchaseEdit(id) {
      this.currentItemId = id;
      this.dialogPermissionLoading = true;
      this.dialogPurchase = true;

      if (!this.users.length) {
        const response = await userResource.list({});

        this.users = response.data;
      }

      this.dialogPermissionLoading = false;
    },
    updatePurchase() {
      
      this.$refs['userForm'].validate(valid => {
        if (valid) {
          this.userCreating = true;
          purchaseResource
            .update(this.currentItemId, this.purchaseItem)
            .then(response => {
              this.$message({
                message:
                  'Purchase has been updated successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetCurrentItem();
              this.dialogPurchase = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.userCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetCurrentItem() {
      this.purchaseItem = {};
      this.currentItemId = 0;
      this.dialogPurchase = false;
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
  },
};
</script>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
.app-container {
  flex: 1;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  .block {
    float: left;
    min-width: 250px;
  }
  .clear-left {
    clear: left;
  }
}
</style>
