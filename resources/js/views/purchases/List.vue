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

    </div>


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
                  :key="index"
                  v-for="(user, index) in users"
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
          <el-button @click.prevent="updatePurchase('user')" type="primary">
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
            <el-form-item v-if="viewPermissions && viewPermissions.includes('need_to_address')" label="Need To Address">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.need_to_address"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('trade_in')" label="Trade In">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.trade_in"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('deposit')" label="Deposit">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.deposit"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('down_payment')" label="Down Payment">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.down_payment"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('local_or_state')" label="Local / State">
              <el-select
                v-model="purchaseItem.local_or_state"
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('cash_finance')" label="Cash / Finance">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('location')" label="Location">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('shipped')" label="Shipped">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('signed_record')" label="Signed Record">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('lender')" label="Lender">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('funding_status')" label="Funding Status">
              <el-select
                v-model="purchaseItem.funding_status"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in fundingOptions"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('warranty')" label="Warranty">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('parts_needed')" label="Parts Needed">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.parts_needed"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('inspection')" label="State Inspection">
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

            <el-form-item v-if="viewPermissions && viewPermissions.includes('make_ready')" label="Make Ready">
              <el-select
                v-model="purchaseItem.make_ready"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in make_ready"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('repair_status')" label="Repair Status">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.repair_status"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item v-if="viewPermissions && viewPermissions.includes('ship_date')" label="Ship Date">
              <el-input
                type="date"
                placeholder="Please input"
                v-model="purchaseItem.ship_date"
              >
              </el-input>
            </el-form-item>


            <el-form-item v-if="viewPermissions && viewPermissions.includes('notes')" label="Notes">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.notes"
                show-word-limit
              >
              </el-input>
            </el-form-item>


            <el-form-item v-if="viewPermissions && viewPermissions.includes('docs_needed')" label="Docs Needed">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.docs_needed"
                show-word-limit
              >
              </el-input>
            </el-form-item>


            <el-form-item v-if="viewPermissions && viewPermissions.includes('review')" label="Review">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="purchaseItem.review"
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
import {mapGetters} from 'vuex'; // Waves directive

const purchaseResource = new PurchaseResource();
const userResource = new Resource('users');
const warrantyResource = new Resource('warranty');
const fundingResource = new Resource('funding-status');
const lenderResource = new Resource('lender');
const makeReadyResource = new Resource('make-ready');
const permissionResource = new Resource('permissions');


export default {
  name: 'UserList',
  components: { Pagination },
  data() {
    return {
      make_ready: [],
      warranties: [],
      lenders: [],
      fundingOptions: [],
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
        local_or_state: '',
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
  computed: {
      ...mapGetters([
          'permissions'
      ]),
      viewPermissions(){
          var permissionValues = []
          for(var i in this.permissions){
              var permissionTitle = this.permissions[i].title.replace('View ', '');
              permissionValues.push(permissionTitle)
          }
        return permissionValues;
      }

  },
  created() {
    this.resetCurrentItem();
    this.getList();
    this.getOptions();
  },
  methods: {
    getOptions() {
      this.getWarranties();
      this.getFundings();
      this.getLenders();
      this.getMakeReady();
    },
    async getMakeReady() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await makeReadyResource.list(this.query);
      this.make_ready = response.data;

      this.make_ready.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getWarranties() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await warrantyResource.list(this.query);
      this.warranties = response.data;
      console.log(this.warranties , 222);
      this.warranties.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getFundings() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await fundingResource.list(this.query);
      this.fundingOptions = response.data;
      this.fundingOptions.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getLenders() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await lenderResource.list(this.query);
      this.lenders = response.data;
      this.lenders.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },

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
    updatePurchase(userType) {
      this.$refs['userForm'].validate(valid => {
        if (valid) {

          if(userType != 'user'){
            this.purchaseItem.is_sold = 1;
          }

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
