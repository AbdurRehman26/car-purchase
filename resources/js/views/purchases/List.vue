<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="query.keyword"
        :placeholder="$t('table.keyword')"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />

      <!-- 
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>

   
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
          <span>
            {{ scope.row.vin.substr(0, scope.row.vin.length - 4) }}
            <strong>{{ scope.row.vin.substr(-4) }}</strong>
          </span>
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
          <span>{{ scope.row.user ? scope.row.user.name : '' }}</span>
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
            {{
              scope.row.user
                ? $t('purchases.update_associate')
                : $t('purchases.associate')
            }}
          </el-button>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Update" width="200">
        <template slot-scope="scope">
          <el-button
            @click.prevent="handlePurchaseUpdate(scope.row.id, scope.row)"
            type="info"
            size="small"
            icon="el-icon-edit"
          >
            Update
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

    <el-dialog :visible.sync="dialogPurchaseUpdate" title="Update">
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
            <el-form-item label="Need To Address">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.need_to_address"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Trade In">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.trade_in"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Deposit">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.deposit"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Down Payment">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.down_payment"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Local / State">
              <el-select
                v-model="purchaseItem.state_or_local"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['local', 'state']"
                  :key="item"
                  :label="item.toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Cash / Finance">
              <el-select
                v-model="purchaseItem.cash_finance"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['cash', 'finance']"
                  :key="item"
                  :label="item.toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Location">
              <el-select
                v-model="purchaseItem.location"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['gone', 'lot', 'on_lot']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Shipped">
              <el-select
                v-model="purchaseItem.shipped"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in shippedOptions"
                  :key="item.key"
                  :label="item.label.replace('_', ' ').toUpperCase()"
                  :value="item.key"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Signed Record">
              <el-select
                v-model="purchaseItem.signed_record"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['yes', 'no', 'pending']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>


            <el-form-item label="Lender">
              <el-select
                v-model="purchaseItem.lender"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in lenders"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Funding Status">
              <el-select
                v-model="purchaseItem.funding_status"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in fundingOptions"
                  :key="item.key"
                  :label="item.label.replace('_', ' ').toUpperCase()"
                  :value="item.key"
                />
              </el-select>
            </el-form-item>



            <el-form-item label="Warranty">
              <el-select
                v-model="purchaseItem.warranty"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in warranties"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>


            <el-form-item label="Parts Needed">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.parts_needed"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="State Inspection">
              <el-select
                v-model="purchaseItem.inspection"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['yes', 'no', 'n/a']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>


            <el-form-item label="Make Ready">
              <el-select
                v-model="purchaseItem.make_ready"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in makes"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Repair Status">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.repair_status"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <div class="clear-left" />
          </el-form>
        </div>

        <div style="text-align:right;">
          <el-button type="danger" @click="dialogPurchaseUpdate = false">
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
      makes: [],
      warranties: [],
      lenders: [],
      fundingOptions: [
        {
          key: 'draft',
          label: 'Funded-Draft',
        },
        {
          key: 'not',
          label: 'Not Funded',
        },
        {
          key: 'financed',
          label: 'Funded-Financed',
        },
        {
          key: 'wired',
          label: 'Funded-Wire',
        },
        {
          key: 'cashier',
          label: 'Funded-Cashier ck',
        },
        
      ],
      shippedOptions: [
        {
          key: 'pu',
          label: 'P/U',
        },
        {
          key: 'ship_cop',
          label: 'Ship COP',
        },
        {
          key: 'ship_cod',
          label: 'Ship COD',
        },
        {
          key: 'picked_up',
          label: 'Picked Up',
        },
        {
          key: 'pick_up',
          label: 'Pick Up',
        },
      ],
      users: [],
      purchaseItem: {},
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      userCreating: false,
      query: {
        pagination: true,
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
        is_sold: 0,
      },
      newItem: {
        trade_in: '',
        need_to_address: '',
        trade_in: '',
        deposit: '',
        down_payment: '',
        state_or_local: '',
        cash_finance: '',
        location: '',
        shipped: '',
        signed_record: '',
        parts_needed: '',
        inspection: '',
        make_ready: '',
        lender: '',
        funding_status: '',
        repair_status: '',
      },
      dialogFormVisible: false,
      dialogPurchase: false,
      dialogPurchaseUpdate: false,
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
    async handlePurchaseUpdate(id, purchaseItem) {
      this.purchaseItem = purchaseItem;
      this.currentItemId = id;
      this.dialogPermissionLoading = true;
      this.dialogPurchaseUpdate = true;
      this.dialogPermissionLoading = false;
    },
    updatePurchase() {
      this.$refs['userForm'].validate(valid => {
        if (valid) {
          this.purchaseItem.is_sold = 1;
          this.userCreating = true;
          purchaseResource
            .update(this.currentItemId, this.purchaseItem)
            .then(response => {
              this.$message({
                message: 'Purchase has been updated successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetCurrentItem();
              this.dialogPurchaseUpdate = false;
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
